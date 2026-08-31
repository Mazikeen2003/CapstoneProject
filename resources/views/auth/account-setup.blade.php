<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Set Up Your Password | City Transparency Portal</title>
    @include('components.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-emerald-50/50 to-slate-100 font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 rounded-lg object-contain" width="40" height="40">
                <div class="flex flex-col">
                    <span class="text-base font-bold tracking-tight text-slate-900 sm:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[9px] uppercase tracking-[0.25em] text-slate-500 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>
            @include('components.public-theme-toggle')
        </nav>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <div class="login-card mx-auto w-full rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                    <span class="login-badge inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Secure Account Setup
                    </span>

                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Create Your Password
                    </h1>

                    <p class="text-sm text-slate-500">
                        Set a secure password for <strong>{{ $user->username }}</strong> to activate your account.
                    </p>
                </div>

                <div class="mt-8">
                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.setup.store', $token) }}" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="password" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                New Password
                            </label>
                            <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                    </svg>
                                    <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" placeholder="Create a strong password" class="w-full bg-transparent border-none text-sm text-slate-900 outline-none focus:ring-0 placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                Confirm Password
                            </label>
                            <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                    </svg>
                                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" placeholder="Re-enter your password" class="w-full bg-transparent border-none text-sm text-slate-900 outline-none focus:ring-0 placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Use at least 12 characters with uppercase and lowercase letters, numbers, and symbols.</p>
                        </div>

                        <button type="submit" class="login-submit-button inline-flex w-full items-center justify-center gap-2 rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70">
                            <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-r-transparent" data-spinner aria-hidden="true"></span>
                            <span data-label>Create Password</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('form')?.addEventListener('submit', function () {
            const button = this.querySelector('button[type="submit"]');
            if (!button) return;
            button.disabled = true;
            const spinner = button.querySelector('[data-spinner]');
            const label = button.querySelector('[data-label]');
            if (spinner) spinner.classList.remove('hidden');
            if (label) label.textContent = 'Creating...';
        });
    </script>
</body>
</html>
