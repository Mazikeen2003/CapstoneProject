<section>
    <form id="profilePasswordForm" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pr-16" autocomplete="new-password" minlength="12" />
                <button type="button" data-password-toggle="update_password_password" class="absolute inset-y-0 right-3 text-sm font-semibold text-gray-500 hover:text-gray-800">Show</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
            <div class="mb-2 flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-gray-500">
                <span>Password strength</span>
                <span id="profilePasswordStrengthText">Weak</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-gray-200">
                <div id="profilePasswordStrengthBar" class="h-full w-0 rounded-full bg-red-500 transition-all duration-200"></div>
            </div>
            <p class="mt-2 text-xs text-gray-500">At least 12 characters, mixed case, a number, and a symbol.</p>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pr-16" autocomplete="new-password" minlength="12" />
                <button type="button" data-password-toggle="update_password_password_confirmation" class="absolute inset-y-0 right-3 text-sm font-semibold text-gray-500 hover:text-gray-800">Show</button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button id="profilePasswordSubmit" type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70">
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
                    passwordSubmit.querySelector('.password-submit-spinner').classList.remove('hidden');
                    passwordSubmit.querySelector('.password-submit-label').textContent = 'Saving...';
                });
            }
        });
    </script>
</section>
