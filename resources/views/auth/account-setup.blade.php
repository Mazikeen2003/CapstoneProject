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
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 rounded-lg object-contain" width="40" height="40">
                <div>
                    <div class="text-base font-bold tracking-tight text-slate-900 sm:text-xl">City Transparency Portal</div>
                    <div class="text-[9px] uppercase tracking-[0.25em] text-slate-500 sm:text-[10px]">Cabuyao Municipal Office</div>
                </div>
            </div>
            @include('components.public-theme-toggle')
        </nav>
    </header>

    <main class="min-h-[calc(100vh-5rem)] bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex min-h-[calc(100vh-10rem)] items-center justify-center">
            <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-gradient-to-r from-[#0f1e3d] to-[#162347] px-6 py-5 text-white sm:px-8">
                    <div class="text-xs font-semibold uppercase tracking-[0.28em] text-[#c9a84c]">ProjectTracker</div>
                    <h1 class="mt-1 text-xl font-bold">Set Up Your Password</h1>
                </div>
                <div class="p-6 sm:p-8">
                    <p class="mb-6 text-sm text-slate-600">Create a strong password for <strong>{{ $user->username }}</strong> to activate your account.</p>

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
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">New Password</label>
                            <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-600">
                            Use at least 12 characters with uppercase and lowercase letters, numbers, and symbols.
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-70">
                            <span class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-r-transparent" data-spinner aria-hidden="true"></span>
                            <span data-label>Create Password</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.querySelector('form').addEventListener('submit', function () {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.querySelector('[data-spinner]').classList.remove('hidden');
            button.querySelector('[data-label]').textContent = 'Creating...';
        });
    </script>
</body>
</html>
