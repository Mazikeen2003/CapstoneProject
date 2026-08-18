<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function showForm(): View|RedirectResponse
    {
        if (! session()->has('pending_otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        $this->ensureOtpRateLimited($request);

        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $userId = session('pending_otp_user_id');
        $remember = session('pending_otp_remember', false);

        if (! $userId) {
            throw ValidationException::withMessages([
                'otp_code' => 'Your verification session has expired. Please sign in again.',
            ]);
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['pending_otp_user_id', 'pending_otp_remember']);

            throw ValidationException::withMessages([
                'otp_code' => 'Your verification session has expired. Please sign in again.',
            ]);
        }

        if ($user->otp_code !== $request->otp_code || now()->greaterThan($user->otp_expires_at)) {
            RateLimiter::hit($this->otpThrottleKey($request));

            throw ValidationException::withMessages([
                'otp_code' => 'The verification code is invalid or has expired.',
            ]);
        }

        RateLimiter::clear($this->otpThrottleKey($request));

        // Intentionally keep the OTP in place until it naturally expires. The same
        // valid code may be used again within the 10-minute window after a normal
        // login/logout cycle, while still requiring valid email/password before the
        // OTP step is ever reached. This is analogous to a temporary passphrase.
        session()->forget(['pending_otp_user_id', 'pending_otp_remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->route("{$user->role_slug}.dashboard");
    }

    protected function ensureOtpRateLimited(Request $request): void
    {
        $key = $this->otpThrottleKey($request);

        if (! RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'otp_code' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]),
        ]);
    }

    protected function otpThrottleKey(Request $request): string
    {
        $userId = session('pending_otp_user_id');

        return 'otp-verify:' . ($userId ? $userId : 'unknown') . '|' . $request->ip();
    }

    protected function resendThrottleKey(Request $request): string
    {
        $userId = session('pending_otp_user_id');

        return 'otp-resend:' . ($userId ? $userId : 'unknown') . '|' . $request->ip();
    }

    protected function ensureResendRateLimited(Request $request): void
    {
        $key = $this->resendThrottleKey($request);

        if (! RateLimiter::tooManyAttempts($key, 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'otp_code' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]),
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = session('pending_otp_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            session()->forget(['pending_otp_user_id', 'pending_otp_remember']);

            return redirect()->route('login');
        }

        $this->ensureResendRateLimited($request);

        $code = (string) random_int(100000, 999999);
        $user->forceFill([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->user_email)->send(new OtpMail($code, $user->first_name ?: $user->username));

        RateLimiter::hit($this->resendThrottleKey($request), 600);

        return back()->with('status', 'A new verification code has been sent.');
    }
}
