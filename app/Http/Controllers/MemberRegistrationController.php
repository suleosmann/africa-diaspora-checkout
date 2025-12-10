<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Enums\TransactionStatus;
use App\Models\Enums\RegisterType;
use App\Mail\MemberRegistered;
use App\Mail\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MemberRegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email'],
                'phone' => ['required', 'string', 'max:20'],
                'country_code' => ['required', 'string', 'max:10'],
                'industry' => ['required', 'string', 'max:255'],
                'region' => ['required', 'string', 'max:255'],
                'register_type' => ['required', 'integer', 'in:0,1,2'],
                'agree' => ['accepted'],
            ], [
                'agree.accepted' => 'You must agree to the Terms & Conditions and Privacy Policy.',
                'register_type.required' => 'Please select a membership type.',
                'country_code.required' => 'Country code is required.',
                'region.required' => 'Please select your country.',
                'industry.required' => 'Industry affiliation is required.',
            ]);

            $fullPhone = $data['country_code'] . $data['phone'];

            Log::info('Registration data:', [
                'email' => $data['email'],
                'phone' => $fullPhone,
                'register_type' => $data['register_type'],
            ]);

            $member = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'member_uuid' => Str::uuid(),
                    'name' => $data['name'],
                    'phone' => $fullPhone,
                    'industry_affiliation' => $data['industry'],
                    'region' => $data['region'],
                    'register_type' => $data['register_type'],
                    'agreed_to_terms' => true,
                    'password' => bcrypt(Str::random(12)),
                ]
            );

            if (!$member->wasRecentlyCreated) {
                $member->update([
                    'register_type' => $data['register_type'],
                    'name' => $data['name'],
                    'phone' => $fullPhone,
                    'industry_affiliation' => $data['industry'],
                    'region' => $data['region'],
                    'agreed_to_terms' => true,
                ]);
            }

            // Send welcome email for all registration types
            // try {
            //     Mail::to($member->email)->send(new MemberRegistered($member));
            //     Log::info('Welcome email sent', ['email' => $member->email]);
            // } catch (\Exception $e) {
            //     Log::error('Failed to send welcome email', [
            //         'email' => $member->email,
            //         'error' => $e->getMessage()
            //     ]);
            //     // Don't break registration if email fails
            // }

            // Free membership (0)
            if ((int) $data['register_type'] === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Free membership registered successfully',
                    'email' => $member->email,
                ]);
            }

            // Download membership (1)
            if ((int) $data['register_type'] === 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'Download membership registered successfully',
                    'email' => $member->email,
                ]);
            }

            // Premium membership (2) - create transaction
            $membershipDetails = $this->getMembershipDetails($data['register_type']);
            $reference = 'MBR_' . strtoupper(Str::random(10));

            Transaction::create([
                'referenceId' => $reference,
                'name'        => $member->name,
                'email'       => $member->email,
                'amount'      => $membershipDetails['amount'],
                'status'      => TransactionStatus::PENDING,
                'remarks'     => [
                    'type' => 'membership_fee',
                    'membership' => $membershipDetails['name'],
                    'register_type' => $data['register_type'],
                    'gateway' => 'paystack',
                ],
            ]);

            return response()->json([
                'reference' => $reference,
                'email' => $member->email,
                'amount' => $membershipDetails['amount'],
                'membership_name' => $membershipDetails['name'],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Registration error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Registration failed. Please check the logs.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getMembershipDetails(int $registerType): array
    {
        return match ($registerType) {
            0 => [
                'name' => 'Free Membership',
                'amount' => 0,
            ],
            1 => [
                'name' => 'Download Membership',
                'amount' => 150,
            ],
            2 => [
                'name' => 'Premier Membership',
                'amount' => 350,
            ],
            default => [
                'name' => 'Unknown',
                'amount' => 0,
            ],
        };
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

                // Send payment success email
                try {
                    $member = User::where('email', $transaction->email)->first();
                    if ($member) {
                        Mail::to($member->email)->send(new PaymentSuccessful($member, $transaction));
                        Log::info('Payment success email sent', ['email' => $member->email]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email', [
                        'email' => $transaction->email,
                        'error' => $e->getMessage()
                    ]);
                }
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

                // Send payment success email via webhook as well
                try {
                    $member = User::where('email', $transaction->email)->first();
                    if ($member) {
                        Mail::to($member->email)->send(new PaymentSuccessful($member, $transaction));
                        Log::info('Payment success email sent via webhook', ['email' => $member->email]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email via webhook', [
                        'email' => $transaction->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}