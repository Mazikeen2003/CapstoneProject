<section>
    <form id="profilePasswordForm" method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label for="update_password_current_password" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                Current Password
            </label>
            <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                    </svg>
                    <input id="update_password_current_password" name="current_password" type="password" required autocomplete="current-password" placeholder="Enter your current password" class="w-full bg-transparent text-sm border-none outline-none focus:ring-0 text-slate-900 placeholder:text-slate-400" />
                </div>
            </div>
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="update_password_password" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                New Password
            </label>
            <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                    </svg>
                    <input id="update_password_password" name="password" type="password" required autocomplete="new-password" minlength="12" placeholder="Create a new password" class="w-full bg-transparent border-none text-sm text-slate-900 outline-none focus:ring-0 placeholder:text-slate-400" />
                    <button type="button" data-password-toggle="update_password_password" class="login-password-toggle text-sm font-semibold text-slate-400 hover:text-slate-600 transition">Show</button>
                </div>
            </div>
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                <span>Password strength</span>
                <span id="profilePasswordStrengthText" class="text-red-500">Weak</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                <div id="profilePasswordStrengthBar" class="h-full w-0 rounded-full bg-red-500 transition-all duration-200"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">At least 12 characters, mixed case, a number, and a symbol.</p>
        </div>

        <div class="space-y-2">
            <label for="update_password_password_confirmation" class="block text-[0.65rem] font-semibold tracking-[0.22em] text-slate-500 uppercase">
                Confirm Password
            </label>
            <div class="login-field rounded-2xl bg-slate-100 px-4 py-3 shadow-inner">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1110 0v2h1a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h1zm2-2a3 3 0 116 0v2H7V6z" clip-rule="evenodd" />
                    </svg>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" minlength="12" placeholder="Re-enter your new password" class="w-full bg-transparent border-none text-sm text-slate-900 outline-none focus:ring-0 placeholder:text-slate-400" />
                    <button type="button" data-password-toggle="update_password_password_confirmation" class="login-password-toggle text-sm font-semibold text-slate-400 hover:text-slate-600 transition">Show</button>
                </div>
            </div>
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button id="profilePasswordSubmit" type="submit" class="login-submit-button inline-flex items-center justify-center gap-2 rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70">
                <span class="password-submit-spinner hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-r-transparent" aria-hidden="true"></span>
                <span class="password-submit-label">{{ __('Save') }}</span>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('update_password_password');
            const strengthBar = document.getElementById('profilePasswordStrengthBar');
            const strengthText = document.getElementById('profilePasswordStrengthText');
            const passwordForm = document.getElementById('profilePasswordForm');
            const passwordSubmit = document.getElementById('profilePasswordSubmit');

            if (passwordInput && strengthBar && strengthText) {
                passwordInput.addEventListener('input', function () {
                    let score = 0;
                    if (this.value.length >= 12) score++;
                    if (/[a-z]/.test(this.value)) score++;
                    if (/[A-Z]/.test(this.value)) score++;
                    if (/[0-9]/.test(this.value)) score++;
                    if (/[^A-Za-z0-9]/.test(this.value)) score++;

                    const colors = ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#22c55e', '#16a34a'];
                    const labels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong', 'Excellent'];
                    const index = Math.min(score, 5);
                    strengthBar.style.width = `${index * 20}%`;
                    strengthBar.style.backgroundColor = colors[index];
                    strengthText.textContent = labels[index];
                    strengthText.style.color = colors[index];
                });
            }

            document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(this.dataset.passwordToggle);
                    if (!input) return;
                    const showing = input.type === 'password';
                    input.type = showing ? 'text' : 'password';
                    this.textContent = showing ? 'Hide' : 'Show';
                });
            });

            if (passwordForm && passwordSubmit) {
                passwordForm.addEventListener('submit', function () {
                    passwordSubmit.disabled = true;
                    const spinner = passwordSubmit.querySelector('.password-submit-spinner');
                    const label = passwordSubmit.querySelector('.password-submit-label');
                    if (spinner) spinner.classList.remove('hidden');
                    if (label) label.textContent = 'Saving...';
                });
            }
        });
    </script>
</section>
