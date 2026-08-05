<x-guest-layout>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900">
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl">
                <div class="mx-auto w-full max-w-md rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                    <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Secure Verification
                        </span>

                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                            Verify Your Login
                        </h1>

                        <p class="text-sm text-slate-500">
                            Enter the 6-digit code sent to your email.
                        </p>
                    </div>

                    <div class="mt-10 space-y-6">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
                            @csrf

                            <div class="space-y-2">
                                <label class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    Verification Code
                                </label>
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <input type="text" name="otp_code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
                                        placeholder="123456"
                                        class="w-full bg-transparent text-center text-lg font-semibold tracking-[0.4em] border-none outline-none focus:ring-0" />
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full rounded-full bg-gradient-to-r from-slate-950 to-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-lg">
                                Verify & Log In
                            </button>
                        </form>

                        <div class="flex flex-col gap-3 text-center text-sm text-slate-500">
                            <form method="POST" action="{{ route('otp.resend') }}">
                                @csrf
                                <button type="submit" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                    Resend code
                                </button>
                            </form>
                            <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-slate-700">
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="w-full border-t border-slate-200 bg-slate-100 px-6 py-5 text-xs text-slate-500">
            <div class="mx-auto w-full max-w-7xl grid items-center gap-3 md:grid-cols-[auto_1fr_auto]">
                <div class="text-slate-700 font-semibold">
                    City Transparency Portal
                </div>
                <div class="text-center">
                    © 2024 Municipal Transparency Initiative. All data is public record.
                </div>
                <div class="flex flex-wrap items-center justify-end gap-4">
                    <a href="#" class="hover:text-slate-700">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-700">Accessibility</a>
                    <a href="#" class="hover:text-slate-700">API Docs</a>
                </div>
            </div>
        </footer>
    </div>
</x-guest-layout>
