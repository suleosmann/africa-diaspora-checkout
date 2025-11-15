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

        $membershipAmount = 350;
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
            // Find transaction by email and amount (since Paystack generates its own reference)
            $transaction = Transaction::where('email', $data['customer']['email'])
                ->where('status', TransactionStatus::PENDING)
                ->where('amount', $data['amount'] / 100)
                ->latest()
                ->first();

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

            // Find transaction by email and amount
            $transaction = Transaction::where('email', $data['customer']['email'])
                ->where('status', TransactionStatus::PENDING)
                ->where('amount', $data['amount'] / 100)
                ->latest()
                ->first();

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