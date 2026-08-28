<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Your Password | City Transparency Portal</title>
    @include('components.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-lg object-contain" width="40" height="40" />
                <div class="flex flex-col">
                    <span class="text-base font-bold tracking-tight text-slate-900 sm:text-xl">City Transparency Portal</span>
                    <span class="text-[9px] uppercase tracking-[0.25em] text-slate-500 sm:text-[10px]">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @include('components.public-theme-toggle')
            </div>
        </nav>
    </header>

    <main class="min-h-screen bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center">
            <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-gradient-to-r from-[#0f1e3d] to-[#162347] px-6 py-5 text-white sm:px-8">
                    <div class="text-xs font-semibold uppercase tracking-[0.28em] text-[#c9a84c]">ProjectTracker</div>
                    <h1 class="mt-1 text-xl font-bold">Change Your Password</h1>
                </div>

                <div class="p-6 sm:p-8">
                    @if (session('status'))
                        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mb-6 text-sm text-slate-600">This password was generated for you and must be changed before you can continue to the dashboard.</p>

                    <form id="forcedPasswordForm" action="{{ route('password.change.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">New Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required minlength="12" class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Enter a new password" />
                                <button type="button" data-toggle-target="password" class="absolute inset-y-0 right-3 flex items-center text-sm font-semibold text-slate-500">Show</button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirm New Password</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Re-enter the new password" />
                                <button type="button" data-toggle-target="password_confirmation" class="absolute inset-y-0 right-3 flex items-center text-sm font-semibold text-slate-500">Show</button>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                <span>Password strength</span>
                                <span id="passwordStrengthText" class="text-slate-600">Weak</span>
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

                        <button id="forcedPasswordSubmit" type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70">
                            <span class="password-submit-spinner hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-r-transparent" aria-hidden="true"></span>
                            <span class="password-submit-label">Update Password</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');
            const passwordForm = document.getElementById('forcedPasswordForm');
            const passwordSubmit = document.getElementById('forcedPasswordSubmit');

            function calculateStrength(value) {
                let score = 0;
                if (value.length >= 12) score += 1;
                if (/[a-z]/.test(value)) score += 1;
                if (/[A-Z]/.test(value)) score += 1;
                if (/[0-9]/.test(value)) score += 1;
                if (/[^A-Za-z0-9]/.test(value)) score += 1;

                return score;
            }

            function applyStrength(value) {
                const score = calculateStrength(value);
                const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
                const colors = ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#22c55e', '#16a34a'];
                const labels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong', 'Excellent'];

                const index = Math.min(score, 5);
                strengthBar.style.width = widths[index];
                strengthBar.style.backgroundColor = colors[index];
                strengthText.textContent = labels[index];
                strengthText.style.color = colors[index];
            }

            passwordInput.addEventListener('input', function () {
                applyStrength(this.value);
            });

            document.querySelectorAll('[data-toggle-target]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.toggleTarget;
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    this.textContent = isPassword ? 'Hide' : 'Show';
                });
            });

            confirmInput.addEventListener('input', function () {
                if (!passwordInput.value || !this.value) {
                    return;
                }
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Passwords do not match.');
                } else {
                    this.setCustomValidity('');
                }
            });

            if (passwordForm && passwordSubmit) {
                passwordForm.addEventListener('submit', function () {
                    passwordSubmit.disabled = true;
                    passwordSubmit.querySelector('.password-submit-spinner').classList.remove('hidden');
                    passwordSubmit.querySelector('.password-submit-label').textContent = 'Updating...';
                });
            }

            applyStrength(passwordInput.value);
        });
    </script>
</body>
</html>
