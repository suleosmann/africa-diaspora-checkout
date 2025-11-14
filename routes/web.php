<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\ContributionController;
use App\Models\MembershipType;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you register web routes for your application.
| Each route returns an Inertia view or calls a controller method.
|
*/


/**
 * ------------------------------------------------------------
 * 🌍 PUBLIC LANDING / HOMEPAGE
 * ------------------------------------------------------------
 * You can keep this simple or redirect to registration.
 */
Route::get('/', fn () => Inertia::render('LandingPage'))
    ->name('home');
Route::get('/register', fn () => Inertia::render('RegisterMember'))
    ->name('register.member.home');



/**
 * ------------------------------------------------------------
 * 🧾 MEMBER REGISTRATION FLOW
 * ------------------------------------------------------------
 * New members can register and immediately pay via Paystack.
 */
Route::post('/register-member', [MemberRegistrationController::class, 'store'])
    ->name('register.member');

Route::get('/payment/callback', [MemberRegistrationController::class, 'callback'])
    ->name('member.payment.callback');

Route::post('/payment/webhook', [MemberRegistrationController::class, 'handleWebhook'])
    ->name('member.payment.webhook');



/**
 * ------------------------------------------------------------
 * 🎁 OPTIONAL: GENERAL CONTRIBUTION FLOW
 * ------------------------------------------------------------
 * Keep this if you also accept one-time donations
 * without requiring registration.
 */
Route::get('/contribute', [ContributionController::class, 'index'])
    ->name('contribute.page');

Route::post('/contribute', [ContributionController::class, 'store'])
    ->name('contribute.store');

Route::get('/payment/{reference}', [MemberRegistrationController::class, 'showPaymentPage'])
    ->name('payment.custom');

Route::post('/paystack/charge', [MemberRegistrationController::class, 'charge'])
    ->name('paystack.charge');



/**
 * ------------------------------------------------------------
 * 💳 MEMBERSHIP TYPES ENDPOINT
 * ------------------------------------------------------------
 * Used by your Vue form to populate the membership dropdown.
 * Returns all active membership plans (Basic, Premium, Gold, etc.).
 */
Route::get('/membership-types', function () {
    return response()->json([
        'data' => MembershipType::select('id', 'name', 'amount')->orderBy('amount')->get(),
    ]);
})->name('membership.types');

Route::get('/download-thesis', function () {
    $filePath = public_path('thesis.pdf');
    
    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }
    
    return response()->download($filePath, 'ADEN-Thesis.pdf', [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="ADEN-Thesis.pdf"'
    ]);
});

/**
 * ------------------------------------------------------------
 * ⚙️ SYSTEM / TEST ROUTES (optional)
 * ------------------------------------------------------------
 * For example, a simple health check route for server testing.
 */
Route::get('/up', fn () => ['status' => 'ok']);
