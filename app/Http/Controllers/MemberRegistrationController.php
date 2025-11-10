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
        // 1️⃣ Validate input (no 'unique' rule anymore)
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'industry' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'membership_type_id' => ['required', 'exists:membership_types,id'],
            'agree' => ['accepted'],
        ]);

        // 2️⃣ Get the membership plan
        $membership = MembershipType::findOrFail($data['membership_type_id']);

        // 3️⃣ Check if user already exists
        $member = User::where('email', $data['email'])->first();

        if ($member) {
            // ✅ Existing user – update optional info if missing
            $member->update([
                'phone' => $data['phone'] ?? $member->phone,
                'industry' => $data['industry'] ?? $member->industry,
                'region' => $data['region'] ?? $member->region,
                'membership_type_id' => $membership->id,
            ]);
        } else {
            // ✅ New user
            $member = User::create([
                'member_uuid' => Str::uuid(),
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'industry' => $data['industry'] ?? null,
                'region' => $data['region'] ?? null,
                'membership_type_id' => $membership->id,
                'password' => bcrypt(Str::random(12)),
            ]);
        }

        // 4️⃣ Create new transaction for membership
        $reference = 'MBR_' . strtoupper(Str::random(10));

        Transaction::create([
            'referenceId' => $reference,
            'name' => $member->name,
            'email' => $member->email,
            'amount' => $membership->amount,
            'status' => TransactionStatus::PENDING,
            'remarks' => [
                'type' => 'membership_fee',
                'membership' => $membership->name,
            ],
        ]);

        // 5️⃣ Initialize Paystack
        $paystackSecret = config('services.paystack.secret_key');
        $response = Http::withToken($paystackSecret)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $member->email,
                'amount'       => $membership->amount * 100, // USD cents
                'currency'     => 'USD',
                'reference'    => $reference,
                'callback_url' => route('member.payment.callback'),
                'metadata'     => [
                    'member_uuid'       => $member->member_uuid,
                    'member_name'       => $member->name,
                    'membership_type'   => $membership->name,
                    'membership_amount' => $membership->amount,
                ],
                'channels'     => ['card', 'mobile_money', 'bank', 'ussd', 'apple_pay'],
            ]);

        $body = $response->json();

        // 6️⃣ Handle failures
        if (! $response->successful() || empty($body['data']['authorization_url'])) {
            return response()->json([
                'error' => 'Failed to initialize payment. Please try again.',
                'details' => $body,
            ], 422);
        }

        // ✅ Return redirect URL to frontend
        return response()->json([
            'redirect_url' => $body['data']['authorization_url'],
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
}
