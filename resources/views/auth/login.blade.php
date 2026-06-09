<x-guest-layout>
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900">

        <!-- Main Content -->
        <div class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl">
                
                <!-- Login Card -->
                <div class="mx-auto w-full max-w-md rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_25px_90px_rgba(15,23,42,0.12)] backdrop-blur-xl px-6 py-8 sm:px-10">
                    
                    <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-3 text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Secure Administrative Access
                        </span>

                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                            City Transparency Portal
                        </h1>

                        <p class="text-sm text-slate-500">
                            Management Console
                        </p>
                    </div>

                    <div class="mt-10 space-y-6">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Username -->
                            <div class="space-y-2">
                                <label class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                    Username
                                </label>
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 4a4 4 0 100 8 4 4 0 000-8z" />
                                            <path fill-rule="evenodd" d="M2 16.5A6.5 6.5 0 0110 10a6.5 6.5 0 018 6.5v.5H2v-.5z" clip-rule="evenodd" />
                                        </svg>
                                        <input type="email" name="email" required autofocus
                                            placeholder="Enter administrator ID"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                                        Password
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-600">
                                            Forgot?
                                        </a>
                                    @endif
                                </div>

                                <div class="rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                                        </svg>
                                        <input type="password" id="password" name="password" required
                                            placeholder="•••••••••••••"
                                            class="w-full bg-transparent text-sm border-none outline-none focus:ring-0" />
                                        <button type="button" id="togglePassword" class="text-slate-400 hover:text-slate-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.getElementById('togglePassword').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const input = document.getElementById('password');
                                    const btn = this;
                                    if (input.type === 'password') {
                                        input.type = 'text';
                                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" /><path d="M15.171 13.576l1.472 1.473a1 1 0 001.414-1.414l-14-14a1 1 0 00-1.414 1.414l1.473 1.474A10.014 10.014 0 00.458 10c1.274 4.057 5.064 7 9.542 7 2.412 0 4.7-.862 6.47-2.286l1.472 1.473a1 1 0 001.414-1.414z" /></svg>';
                                    } else {
                                        input.type = 'password';
                                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>';
                                    }
                                });
                            </script>

                            <!-- Button -->
                            <button type="submit"
                                class="w-full rounded-full bg-gradient-to-r from-slate-950 to-slate-800 px-5 py-3 text-sm font-semibold text-white shadow-lg">
                                Sign In
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 text-center text-sm text-slate-500">
                        <p>Unauthorized access is strictly prohibited.</p>
                        <a href="#" class="mt-2 inline-block font-semibold text-emerald-600">
                            Contact Technical Support
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