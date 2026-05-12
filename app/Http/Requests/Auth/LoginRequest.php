<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if ($mockUser = $this->mockCredentialsMatch()) {
            $this->session()->put('mock_user', $mockUser);
            RateLimiter::clear($this->throttleKey());

            return;
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function mockCredentialsMatch(): ?array
    {
        $mockUsers = [
            'barangay@example.com' => [
                'password' => 'barangay123',
                'role' => 'barangay',
                'name' => 'Barangay Official',
            ],
            'city@example.com' => [
                'password' => 'city123',
                'role' => 'city',
                'name' => 'City Official',
            ],
            'admin@example.com' => [
                'password' => 'admin123',
                'role' => 'admin',
                'name' => 'Administrator',
            ],
            'department@example.com' => [
                'password' => 'department123',
                'role' => 'department',
                'name' => 'Department Staff',
            ],
        ];

        $email = $this->input('email');
        $password = $this->input('password');

        if (isset($mockUsers[$email]) && $mockUsers[$email]['password'] === $password) {
            return array_merge(['email' => $email], $mockUsers[$email]);
        }

        return null;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
