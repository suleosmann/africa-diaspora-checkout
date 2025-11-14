<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
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
                'gateway' => 'paystack',
            ],
        ]);

        return response()->json([
            'reference' => $reference,
            'email' => $member->email,
            'amount' => $membershipAmount,
            'membership_name' => $membershipName,
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
            'amount'    => $request->amount, // Already in cents from frontend
            'currency'  => 'USD',
            'reference' => $request->reference,
            'card'      => [
                'number'        => str_replace(' ', '', $request->card['number']),
                'cvv'           => $request->card['cvv'],
                'expiry_month'  => $request->card['month'],
                'expiry_year'   => '20' . $request->card['year'], // Convert YY to YYYY
            ]
        ];

        Log::info('Paystack charge request', ['payload' => $payload]);

        try {
            $response = Http::withToken($paystackSecret)
                ->post("https://api.paystack.co/charge", $payload);

            $data = $response->json();
            Log::info('Paystack charge response', $data);

            // Store reference for OTP/3DS follow-up
            if (isset($data['data']['reference'])) {
                session(['paystack_charge_reference' => $data['data']['reference']]);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Paystack charge error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitOtp(Request $request)
    {
        $paystackSecret = config('services.paystack.secret_key');

        $reference = session('paystack_charge_reference');

        if (!$reference) {
            return response()->json([
                'status' => false,
                'message' => 'Session expired. Please try again.'
            ], 400);
        }

        $payload = [
            'otp' => $request->otp,
            'reference' => $reference
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

            if (!$response->successful()) {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Unable to verify'
                ]);
            }

            $data = $response->json();
            
            if ($data['status'] && $data['data']['status'] === 'success') {
                // Find original transaction by metadata or amount
                $transaction = Transaction::where('email', $data['data']['customer']['email'])
                    ->where('status', TransactionStatus::PENDING)
                    ->where('amount', $data['data']['amount'] / 100)
                    ->latest()
                    ->first();

                if ($transaction) {
                    $transaction->update([
                        'status' => TransactionStatus::SUCCESS,
                        'verified_at' => now(),
                        'remarks' => array_merge($transaction->remarks ?? [], [
                            'paystack_reference' => $reference,
                            'verified_at' => now()->toDateTimeString(),
                        ])
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment successful',
                    'original_reference' => $transaction->referenceId ?? null
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
            // Find transaction by our reference OR by email/amount
            $transaction = Transaction::where('referenceId', $reference)->first();
            
            if (!$transaction) {
                // Fallback: find by email and amount
                $transaction = Transaction::where('email', $data['customer']['email'])
                    ->where('status', TransactionStatus::PENDING)
                    ->where('amount', $data['amount'] / 100)
                    ->latest()
                    ->first();
            }

            if ($transaction) {
                $transaction->update([
                    'status'      => TransactionStatus::SUCCESS,
                    'verified_at' => Carbon::now(),
                    'remarks'     => array_merge($transaction->remarks ?? [], [
                        'gateway'          => 'Paystack',
                        'verified_via'     => 'callback',
                        'amount_confirmed' => $data['amount'] / 100,
                        'email'            => $data['customer']['email'] ?? null,
                        'paystack_reference' => $data['reference'],
                    ]),
                ]);
            }

            return Inertia::render('PaymentSuccess', [
                'data' => $data,
            ]);
        }

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
            Log::warning('Invalid Paystack webhook signature');
            abort(403, 'Invalid signature');
        }

        $event = json_decode($payload, true);
        $data = $event['data'] ?? null;

        if (!$data) {
            return response()->json(['status' => 'no data']);
        }

        Log::info('Paystack webhook received', ['event' => $event['event'], 'reference' => $data['reference'] ?? 'N/A']);

        if ($event['event'] === 'charge.success') {
            $reference = $data['reference'] ?? null;
            $status    = $data['status'] ?? null;

            if (!$reference) {
                return response()->json(['status' => 'missing reference']);
            }

            // Find transaction
            $transaction = Transaction::where('referenceId', $reference)->first();
            
            if (!$transaction) {
                // Fallback
                $transaction = Transaction::where('email', $data['customer']['email'])
                    ->where('status', TransactionStatus::PENDING)
                    ->where('amount', $data['amount'] / 100)
                    ->latest()
                    ->first();
            }

            if ($transaction && $status === 'success') {
                $transaction->update([
                    'status'      => TransactionStatus::SUCCESS,
                    'verified_at' => now(),
                    'remarks'     => array_merge($transaction->remarks ?? [], [
                        'gateway' => 'Paystack',
                        'verified_via' => 'webhook',
                        'paystack_data' => $data,
                    ]),
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}