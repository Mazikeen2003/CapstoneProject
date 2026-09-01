<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Change Password | City Transparency Portal</title>
        @include('layouts.favicon')
        @include('components.theme-init')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Public+Sans:wght@400;600;700&display=swap">
        <style>
            .guest-glass-nav {
                backdrop-filter: blur(16px);
                background-color: rgba(248, 249, 255, 0.8);
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
        <header class="guest-glass-nav sticky top-0 z-50 w-full border-b border-slate-200/50">
            <nav class="relative mx-auto flex w-full items-center justify-between px-4 py-4 sm:px-8 lg:px-12">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
                    <div class="flex flex-col">
                        <span class="text-base font-bold tracking-tight text-slate-900 sm:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                        <span class="text-[9px] uppercase tracking-widest text-slate-500 opacity-70 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @include('components.public-theme-toggle')
                </div>
            </nav>
        </header>
        <div class="login-page min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-emerald-50/50 to-slate-100 text-slate-900">

        <!-- Main Content -->
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl">
                
                <!-- Change Password Card -->
                <div class="login-card mx-auto w-full max-w-md rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                    
                    <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                        <span class="login-badge inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Secure Profile Access
                        </span>

                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                            Change Password
                        </h1>

                        <p class="text-sm text-slate-500">
                            Keep your account protected with a strong password.
                        </p>
                    </div>

                    <div class="mt-10 space-y-6">
                        @if (session('status'))
                            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="forcedPasswordForm" action="{{ route('password.change.submit') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label for="password" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    New Password
                                </label>
                                <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                        </svg>
                                        <input id="password" name="password" type="password" required minlength="12"
                                            placeholder="•••••••••••••"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0"
                                            aria-label="New password field" />
                                        <button type="button" data-toggle-target="password" class="login-password-toggle text-slate-400 hover:text-slate-600 transition text-sm font-semibold" aria-label="Toggle password visibility">
                                            Show
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    Confirm Password
                                </label>
                                <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                        </svg>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12"
                                            placeholder="•••••••••••••"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0"
                                            aria-label="Confirm password field" />
                                        <button type="button" data-toggle-target="password_confirmation" class="login-password-toggle text-slate-400 hover:text-slate-600 transition text-sm font-semibold" aria-label="Toggle password visibility">
                                            Show
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Strength -->
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                    <span>Password Strength</span>
                                    <span id="passwordStrengthText" class="text-red-600">Weak</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div id="passwordStrengthBar" class="h-full w-0 rounded-full bg-red-500 transition-all duration-200"></div>
                                </div>
                                <ul class="mt-3 space-y-1 text-xs text-slate-600">
                                    <li>• At least 12 characters</li>
                                    <li>• Includes uppercase and lowercase letters</li>
                                    <li>• Includes numbers and symbols</li>
                                </ul>
                            </div>

                            <!-- Button -->
                            <button id="forcedPasswordSubmit" type="submit"
                                class="login-submit-button w-full rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg transition-colors">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Password visibility toggle
        document.querySelectorAll('[data-toggle-target]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-toggle-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    this.textContent = 'Hide';
                } else {
                    input.type = 'password';
                    this.textContent = 'Show';
                }
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrengthText = document.getElementById('passwordStrengthText');

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let strengthText = 'Weak';

                if (password.length >= 12) strength += 25;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
                if (/\d/.test(password)) strength += 25;
                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 25;

                if (strength <= 25) {
                    strengthText = 'Weak';
                    passwordStrengthBar.style.backgroundColor = '#ef4444';
                } else if (strength <= 50) {
                    strengthText = 'Fair';
                    passwordStrengthBar.style.backgroundColor = '#f97316';
                } else if (strength <= 75) {
                    strengthText = 'Good';
                    passwordStrengthBar.style.backgroundColor = '#eab308';
                } else {
                    strengthText = 'Strong';
                    passwordStrengthBar.style.backgroundColor = '#22c55e';
                }

                passwordStrengthBar.style.width = strength + '%';
                passwordStrengthText.textContent = strengthText;
            });
        }
    });
</script>
    </body>
</html>
