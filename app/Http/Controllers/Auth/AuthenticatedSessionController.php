<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Services\AuditLogService;

class AuthenticatedSessionController extends Controller
{
    private const SHORT_WINDOW_SECONDS = 900;
    private const DAILY_WINDOW_SECONDS = 86400;
    private const SHORT_ATTEMPT_LIMIT = 5;
    private const DAILY_ATTEMPT_LIMIT = 10;
    private const SHORT_LOCKOUT_SECONDS = 900;
    private const DAILY_LOCKOUT_SECONDS = 3600;

    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $email = (string) $request->old('email', '');
        $lockoutSeconds = 0;

        if ($email !== '') {
            $lockoutSeconds = $this->loginLockoutSeconds($email, $request);
        }

        return view('auth.login', compact('lockoutSeconds'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureLoginRateLimited($request);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with('role')
            ->where('user_email', $credentials['email'])
            ->first();

        if (! $user) {
            // Record the failed login attempt for auditing purposes.
            try {
                AuditLogService::logFailedLogin($credentials['email'] ?? '', $request->ip());
            } catch (\Throwable $e) {
                // Do not let logging errors affect authentication flow.
            }
            $this->recordFailedLoginAttempt($request);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if ($user->is_disabled) {
            $this->recordFailedLoginAttempt($request);

            throw ValidationException::withMessages([
                'email' => 'This account has been disabled. Please contact the administrator.',
            ]);
        }

        if (! Hash::check($credentials['password'], $user->password_hash)) {
            // Record the failed login attempt for auditing purposes.
            try {
                AuditLogService::logFailedLogin($credentials['email'] ?? '', $request->ip());
            } catch (\Throwable $e) {
                // Do not let logging errors affect authentication flow.
            }
            $this->recordFailedLoginAttempt($request);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Successful login: clear the rate limit
        foreach ($this->loginRateLimitKeys($credentials['email'], $request) as $key) {
            RateLimiter::clear($key);
        }

        // OTP remains valid for its full window rather than being single-use,
        // functioning similarly to a temporary passphrase — this reduces repeated
        // email sends during normal login/logout cycles while still requiring
        // correct email+password credentials before the OTP step is ever reached,
        // and the window is capped at 10 minutes.
        $hasValidUnexpiredOtp = ! empty($user->otp_code)
            && ! empty($user->otp_expires_at)
            && now()->lessThan($user->otp_expires_at);

        if (! $hasValidUnexpiredOtp) {
            $code = (string) random_int(100000, 999999);
            $user->forceFill([
                'otp_code' => $code,
                'otp_expires_at' => now()->addMinutes(10),
            ])->save();

            Mail::to($user->user_email)->send(new OtpMail($code, $user->first_name ?: $user->username));
        }

        $request->session()->put('pending_otp_user_id', $user->user_id);
        $request->session()->put('pending_otp_remember', $request->boolean('remember'));

        return redirect()->route('otp.verify.form');
    }
    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    protected function ensureLoginRateLimited(Request $request): void
    {
        $seconds = $this->loginLockoutSeconds((string) $request->input('email', ''), $request);

        if ($seconds === 0) {
            return;
        }

        $this->throwLoginThrottled($seconds);
    }

    /** Record account and account/IP failures, then apply progressive cooldowns. */
    protected function recordFailedLoginAttempt(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $accountKey = $this->accountThrottleKey($email);
        $dailyKey = $this->dailyThrottleKey($email);
        $pairKey = $this->loginThrottleKey($request);

        RateLimiter::hit($accountKey, self::SHORT_WINDOW_SECONDS);
        RateLimiter::hit($dailyKey, self::DAILY_WINDOW_SECONDS);
        RateLimiter::hit($pairKey, self::SHORT_WINDOW_SECONDS);

        if (RateLimiter::tooManyAttempts($accountKey, self::SHORT_ATTEMPT_LIMIT)) {
            $this->startLockout($this->accountLockKey($email), self::SHORT_LOCKOUT_SECONDS);
        }

        if (RateLimiter::tooManyAttempts($dailyKey, self::DAILY_ATTEMPT_LIMIT)) {
            $this->startLockout($this->dailyLockKey($email), self::DAILY_LOCKOUT_SECONDS);
        }

        if (RateLimiter::tooManyAttempts($pairKey, self::SHORT_ATTEMPT_LIMIT)) {
            $this->startLockout($this->pairLockKey($email, $request), self::SHORT_LOCKOUT_SECONDS);
        }

        $seconds = $this->loginLockoutSeconds($email, $request);

        if ($seconds > 0) {
            $this->throwLoginThrottled($seconds);
        }
    }

    protected function loginThrottleKey(Request $request): string
    {
        return $this->loginThrottleKeyForEmail((string) $request->input('email', ''), $request);
    }

    protected function loginThrottleKeyForEmail(string $email, Request $request): string
    {
        return 'login:pair:' . hash('sha256', Str::lower(trim($email)) . '|' . ($request->ip() ?? ''));
    }

    protected function accountThrottleKey(string $email): string
    {
        return 'login:account:' . hash('sha256', Str::lower(trim($email)));
    }

    protected function dailyThrottleKey(string $email): string
    {
        return 'login:daily:' . hash('sha256', Str::lower(trim($email)));
    }

    protected function accountLockKey(string $email): string
    {
        return 'login:lock:account:' . hash('sha256', Str::lower(trim($email)));
    }

    protected function dailyLockKey(string $email): string
    {
        return 'login:lock:daily:' . hash('sha256', Str::lower(trim($email)));
    }

    protected function pairLockKey(string $email, Request $request): string
    {
        return 'login:lock:pair:' . hash('sha256', Str::lower(trim($email)) . '|' . ($request->ip() ?? ''));
    }

    /** @return array<string> */
    protected function loginRateLimitKeys(string $email, Request $request): array
    {
        return [
            $this->accountThrottleKey($email),
            $this->dailyThrottleKey($email),
            $this->loginThrottleKeyForEmail($email, $request),
            $this->accountLockKey($email),
            $this->dailyLockKey($email),
            $this->pairLockKey($email, $request),
        ];
    }

    protected function loginLockoutSeconds(string $email, Request $request): int
    {
        return max(array_map(
            fn (string $key): int => RateLimiter::availableIn($key),
            [
                $this->accountLockKey($email),
                $this->dailyLockKey($email),
                $this->pairLockKey($email, $request),
            ],
        ));
    }

    protected function startLockout(string $key, int $seconds): void
    {
        if (! RateLimiter::tooManyAttempts($key, 1)) {
            RateLimiter::hit($key, $seconds);
        }
    }

    protected function throwLoginThrottled(int $seconds): void
    {
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
