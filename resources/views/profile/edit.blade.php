<x-app-layout>
    <div class="login-page min-h-screen flex flex-col bg-slate-100 text-slate-900">
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-xl">
                <div class="login-card mx-auto w-full rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                    <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                        <span class="login-badge inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Secure Profile Access
                        </span>

                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                            Update Password
                        </h1>

                        <p class="text-sm text-slate-500">
                            Keep your account protected with a strong password.
                        </p>
                    </div>

                    <div class="mt-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
