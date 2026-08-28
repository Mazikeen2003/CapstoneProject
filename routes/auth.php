<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AccountSetupController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password as PasswordRule;

Route::middleware('guest')->group(function () {
    Route::get('account/setup/{token}', [AccountSetupController::class, 'create'])
        ->name('account.setup');

    Route::post('account/setup/{token}', [AccountSetupController::class, 'store'])
        ->name('account.setup.store');

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('otp/verify', [OtpController::class, 'showForm'])->name('otp.verify.form');
    Route::post('otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('password/change', function () {
        return view('auth.change-password');
    })->name('password.change');

    Route::post('password/change', function (Request $request) {
        $validated = $request->validate([
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
        ]);

        $user = $request->user();
        $user->forceFill([
            'password_hash' => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        Auth::login($user, true);

        return redirect()->route('dashboard')->with('status', 'Password updated successfully.');
    })->name('password.change.submit');

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
