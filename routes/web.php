<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\ContributionController;
use App\Models\MembershipType;

Route::get('/', fn () => Inertia::render('LandingPage'))
    ->name('home');

Route::get('/register', fn () => Inertia::render('RegisterMember'))
    ->name('register.member.home');

Route::post('/register-member', [MemberRegistrationController::class, 'store'])
    ->name('register.member');

Route::get('/payment/callback', [MemberRegistrationController::class, 'callback'])
    ->name('member.payment.callback');

Route::post('/payment/webhook', [MemberRegistrationController::class, 'handleWebhook'])
    ->name('member.payment.webhook');

Route::get('/contribute', [ContributionController::class, 'index'])
    ->name('contribute.page');

Route::post('/contribute', [ContributionController::class, 'store'])
    ->name('contribute.store');

Route::get('/payment/{reference}', [MemberRegistrationController::class, 'showPaymentPage'])
    ->name('payment.custom');

Route::post('/paystack/charge', [MemberRegistrationController::class, 'charge'])
    ->name('paystack.charge');

Route::get('/membership-types', function () {
    return response()->json([
        'data' => MembershipType::select('id', 'name', 'amount')->orderBy('amount')->get(),
    ]);
})->name('membership.types');

// Download thesis route (keeps existing functionality)
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

Route::post('/paystack/submit-otp', [MemberRegistrationController::class, 'submitOtp'])
    ->name('paystack.submit.otp');

Route::get('/paystack/check-status/{reference}', [MemberRegistrationController::class, 'checkStatus'])
    ->name('paystack.check.status');

// NEW SUCCESS PAGES
Route::get('/join-network-success', function () {
    return inertia('JoinNetworkSuccessPage');
})->name('join.network.success');

Route::get('/download-success', function () {
    return inertia('DownloadSuccessPage');
})->name('download.success');


Route::get('/up', fn () => ['status' => 'ok']);