<x-guest-layout>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900">

        <!-- Main Content -->
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl">
                
                <!-- Reset Password Card -->
                <div class="mx-auto w-full max-w-md rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                    
                    <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Secure Administrative Access
                        </span>

                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                            Reset Password
                        </h1>

                        <p class="text-sm text-slate-500">
                            Create a new password for your account
                        </p>
                    </div>

                    <div class="mt-10 space-y-6">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <label for="email" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    Email Address
                                </label>
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                                            placeholder="Enter your email"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    New Password
                                </label>
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                        </svg>
                                        <input id="password" type="password" name="password" required autocomplete="new-password"
                                            placeholder="•••••••••••••"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    Confirm Password
                                </label>
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                        </svg>
                                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                            placeholder="•••••••••••••"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Button -->
                            <button type="submit"
                                class="w-full rounded-full bg-gradient-to-r from-slate-950 to-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-lg">
                                Reset Password
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 text-center text-sm text-slate-500">
                        <p>Remember your password?</p>
                        <a href="{{ route('login') }}" class="mt-2 inline-block font-semibold text-emerald-600 hover:text-emerald-700">
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ FULL WIDTH FOOTER -->
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
