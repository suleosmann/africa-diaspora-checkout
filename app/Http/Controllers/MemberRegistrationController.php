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
    /**
     * ✅ Handle new member registration and initiate payment
     */
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

        // Hardcoded Premier Membership
        $membershipAmount = 1;
        $membershipName = 'Premier Membership';

        // ✅ Find or create user
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

        // ✅ Create transaction record
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

        // ✅ Return data to frontend
        return response()->json([
            'reference' => $reference,
            'email' => $member->email,
            'amount' => $membershipAmount,
            'membership_name' => $membershipName,
            'message' => 'Ready to initialize Paystack inline checkout.'
        ]);
    }



    /**
     * ✅ Handle Paystack callback
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (! $reference) {
            return Inertia::render('PaymentFailed', [
                'message' => 'No transaction reference provided.',
            ]);
        }

        $paystackSecret = config('services.paystack.secret_key');

        // Verify transaction with Paystack
        $verify = Http::withToken($paystackSecret)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (! $verify->successful()) {
            return Inertia::render('PaymentFailed', [
                'message' => 'Unable to verify transaction. Please try again later.',
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
                'verified_via'  => 'callback',
                'error'         => 'Transaction failed or was cancelled',
            ],
        ]);

        return Inertia::render('PaymentFailed', [
            'message' => 'Payment was not successful.',
        ]);
    }

    /**
     * ✅ Handle Paystack Webhook (optional)
     */
    public function handleWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret    = config('services.paystack.secret_key');

        // Verify webhook authenticity
        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            abort(403, 'Invalid signature');
        }

        $data = json_decode($payload, true)['data'] ?? null;

        if (! $data) {
            return response()->json(['status' => 'no data']);
        }

        $reference = $data['reference'] ?? null;
        $status    = $data['status'] ?? null;

        if (! $reference || ! $status) {
            return response()->json(['status' => 'missing reference']);
        }

        // Update transaction
        Transaction::where('referenceId', $reference)->update([
            'status'      => $status === 'success'
                ? TransactionStatus::SUCCESS
                : TransactionStatus::FAILED,
            'verified_at' => now(),
            'remarks'     => $data,
        ]);

        return response()->json(['status' => 'ok']);
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

}
