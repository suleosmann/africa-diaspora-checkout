<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Enums\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Container\Attributes\Log;

class ContributionController extends Controller
{
    /**
     * ✅ Show the contribution form (Vue page)
     */
    public function index()
    {
        return Inertia::render('Contribute');
    }

    /**
     * ✅ Initialize a Paystack transaction
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        // Generate a unique transaction reference
        $reference = 'TXN_' . strtoupper(Str::random(10));

        // Record in DB as pending
        Transaction::create([
            'referenceId' => $reference,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'amount'      => $data['amount'],
            'status'      => TransactionStatus::PENDING,
        ]);

        // Initialize payment with Paystack
        $paystackSecret = config('services.paystack.secret_key');

        
   
        $response = Http::withToken($paystackSecret)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $data['email'],
                'amount'       => $data['amount'] * 100, // amount in kobo
                'reference'    => $reference,
                'callback_url' => route('contribute.callback'),
                'metadata'     => [
                    'contributor_name' => $data['name'],
                ],
                // 👇 Add this to explicitly allow all active channels
                'channels'     => ['card', 'mobile_money', 'bank', 'ussd', 'apple_pay'],
            ]);


        $body = $response->json();


        if (! $response->successful() || empty($body['data']['authorization_url'])) {
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }

        // Redirect user directly to Paystack checkout
        return Inertia::location($body['data']['authorization_url']);
    }

    /**
     * ✅ Handle Paystack callback and verify payment
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
                    'gateway'        => 'Paystack',
                    'verified_via'   => 'callback',
                    'amount_confirmed' => $data['amount'] / 100,
                    'email'          => $data['customer']['email'] ?? null,
                ],
            ]);

            return Inertia::render('PaymentSuccess', [
                'data' => $data,
            ]);
        }

        Transaction::where('referenceId', $reference)->update([
            'status'  => TransactionStatus::FAILED,
            'remarks' => [
                'gateway' => 'Paystack',
                'verified_via' => 'callback',
                'error' => 'Transaction failed or was cancelled',
            ],
        ]);

        return Inertia::render('PaymentFailed', [
            'message' => 'Payment was not successful.',
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        $secret = config('services.paystack.secret_key');

        // Verify webhook authenticity
        if (hash_hmac('sha512', $payload, $secret) !== $signature) {
            abort(403, 'Invalid signature');
        }

        $data = json_decode($payload, true)['data'] ?? null;

        if (! $data) {
            return response()->json(['status' => 'no data']);
        }

        $reference = $data['reference'] ?? null;
        $status = $data['status'] ?? null;

        if (! $reference || ! $status) {
            return response()->json(['status' => 'missing reference']);
        }

        // Update transaction
        Transaction::where('referenceId', $reference)->update([
            'status' => $status === 'success'
                ? TransactionStatus::SUCCESS
                : TransactionStatus::FAILED,
            'verified_at' => now(),
            'remarks' => $data,
        ]);

        return response()->json(['status' => 'ok']);
    }

}
