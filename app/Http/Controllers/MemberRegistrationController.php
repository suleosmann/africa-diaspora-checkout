<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\MembershipType;
use App\Models\Enums\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MemberRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'industry' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'agree' => ['accepted'],
        ]);

        $membershipAmount = 1;
        $membershipName = 'Premier Membership';

        $member = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'member_uuid' => Str::uuid(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'industry' => $data['industry'] ?? null,
                'region' => $data['region'] ?? null,
                'password' => bcrypt(Str::random(12)),
            ]
        );

        $reference = 'MBR_' . strtoupper(Str::random(10));

        Transaction::create([
            'referenceId' => $reference,
            'name'        => $member->name,
            'email'       => $member->email,
            'amount'      => $membershipAmount,
            'status'      => TransactionStatus::PENDING,
            'remarks'     => [
                'type' => 'membership_fee',
                'membership' => $membershipName,
            ],
        ]);

        return response()->json([
            'reference' => $reference,
            'email' => $member->email,
            'amount' => $membershipAmount,
            'membership_name' => $membershipName,
            'message' => 'Ready to initialize payment.'
        ]);
    }

    public function showPaymentPage($reference)
    {
        $transaction = Transaction::where('referenceId', $reference)->firstOrFail();

        return Inertia::render('Payment/Checkout', [
            'reference' => $reference,
            'email' => $transaction->email,
            'amount' => $transaction->amount,
            'membership' => $transaction->remarks['membership'] ?? 'Membership Fee',
        ]);
    }

    public function charge(Request $request)
    {
        $paystackSecret = config('services.paystack.secret_key');

        $payload = [
            'email'     => $request->email,
            'amount'    => $request->amount,
            'reference' => $request->reference,
            'card'      => [
                'number'        => $request->card['number'],
                'cvv'           => $request->card['cvv'],
                'expiry_month'  => $request->card['month'],
                'expiry_year'   => $request->card['year'],
            ]
        ];

        Log::info('Paystack charge request', ['payload' => $payload]);

        try {
            $response = Http::withToken($paystackSecret)
                ->post("https://api.paystack.co/charge", $payload);

            $data = $response->json();
            Log::info('Paystack charge response', $data);

            // Store the reference for OTP/3DS follow-up
            if (isset($data['data']['reference'])) {
                session(['paystack_reference' => $data['data']['reference']]);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Paystack error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Payment processing failed'
            ], 500);
        }
    }

    public function submitOtp(Request $request)
    {
        $paystackSecret = config('services.paystack.secret_key');

        $payload = [
            'otp' => $request->otp,
            'reference' => session('paystack_reference') // Get from session
        ];

        Log::info('OTP submission', $payload);

        try {
            $response = Http::withToken($paystackSecret)
                ->post("https://api.paystack.co/charge/submit_otp", $payload);

            $data = $response->json();
            Log::info('OTP response', $data);

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('OTP error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'OTP verification failed'
            ], 500);
        }
    }

    public function checkStatus($reference)
    {
        $paystackSecret = config('services.paystack.secret_key');

        try {
            $response = Http::withToken($paystackSecret)
                ->get("https://api.paystack.co/transaction/verify/{$reference}");

            $data = $response->json();
            
            if ($data['status'] && $data['data']['status'] === 'success') {
                // Update transaction
                Transaction::where('referenceId', $reference)->update([
                    'status' => TransactionStatus::SUCCESS,
                    'verified_at' => now(),
                ]);
            }

            return response()->json([
                'status' => $data['data']['status'] ?? 'pending',
                'message' => $data['message'] ?? ''
            ]);

        } catch (\Exception $e) {
            Log::error('Status check error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'pending',
                'message' => 'Unable to verify status'
            ]);
        }
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return Inertia::render('PaymentFailed', [
                'message' => 'No transaction reference provided.',
            ]);
        }

        $paystackSecret = config('services.paystack.secret_key');

        $verify = Http::withToken($paystackSecret)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$verify->successful()) {
            return Inertia::render('PaymentFailed', [
                'message' => 'Unable to verify transaction.',
            ]);
        }

        $data = $verify->json('data');

        if ($data && $data['status'] === 'success') {
            Transaction::where('referenceId', $reference)->update([
                'status'      => TransactionStatus::SUCCESS,
                'verified_at' => Carbon::now(),
                'remarks'     => [
                    'gateway'          => 'Paystack',
                    'verified_via'     => 'callback',
                    'amount_confirmed' => $data['amount'] / 100,
                    'email'            => $data['customer']['email'] ?? null,
                ],
            ]);

            return Inertia::render('PaymentSuccess', [
                'data' => $data,
            ]);
        }

        Transaction::where('referenceId', $reference)->update([
            'status'  => TransactionStatus::FAILED,
            'remarks' => [
                'gateway'       => 'Paystack',
                'error'         => 'Transaction failed',
            ],
        ]);

        return Inertia::render('PaymentFailed', [
            'message' => 'Payment was not successful.',
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret    = config('services.paystack.secret_key');

        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            abort(403, 'Invalid signature');
        }

        $data = json_decode($payload, true)['data'] ?? null;

        if (!$data) {
            return response()->json(['status' => 'no data']);
        }

        $reference = $data['reference'] ?? null;
        $status    = $data['status'] ?? null;

        if (!$reference || !$status) {
            return response()->json(['status' => 'missing reference']);
        }

        Transaction::where('referenceId', $reference)->update([
            'status'      => $status === 'success'
                ? TransactionStatus::SUCCESS
                : TransactionStatus::FAILED,
            'verified_at' => now(),
            'remarks'     => $data,
        ]);

        return response()->json(['status' => 'ok']);
    }
}