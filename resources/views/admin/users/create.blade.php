@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Create New User</h1>
        <p class="text-sm text-gray-500 mt-1">Add a new user account to the system.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800 font-semibold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Row 1: Username and Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror" placeholder="Enter username" required>
                    @error('username')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="user_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="user_email" name="user_email" value="{{ old('user_email') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_email') border-red-500 @enderror" placeholder="user@example.com" required>
                    @error('user_email')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Password and Confirm Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password_hash" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                    <div class="relative">
                        <input type="password" id="password_hash" name="password_hash" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_hash') border-red-500 @enderror" placeholder="Minimum 8 characters" required aria-label="Password field">
                        <button type="button" id="togglePasswordCreate" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                            Show
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="h-2 bg-gray-300 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full w-0 transition-all duration-300" style="background-color: #ccc;"></div>
                                </div>
                            </div>
                            <span id="strengthText" class="text-xs font-semibold text-gray-600 ml-2 min-w-fit">-</span>
                        </div>
                        <p class="text-xs text-gray-500">At least 8 characters with mixed case, numbers, and symbols recommended</p>
                    </div>
                    
                    @error('password_hash')
                        <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_hash_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                    <div class="relative">
                        <input type="password" id="password_hash_confirmation" name="password_hash_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Re-enter password" required aria-label="Confirm password field">
                        <button type="button" id="togglePasswordConfirmationCreate" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700" aria-label="Toggle confirm password visibility">
                            Show
                        </button>
                    </div>
                    
                    <!-- Password Match Indicator -->
                    <div id="passwordMatchContainer" class="mt-3 px-3 py-2 rounded-lg bg-gray-100 border-2 border-gray-300 transition-all" style="display: none;">
                        <div class="flex items-center gap-2">
                            <svg id="matchIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span id="matchText" class="text-sm font-semibold">Passwords match</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Role and Barangay -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-2">Role *</label>
                    <select id="role_id" name="role_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_id') border-red-500 @enderror" required>
                        <option value="">-- Select Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}" {{ old('role_id') == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="barangay_id" class="block text-sm font-semibold text-gray-700 mb-2">Barangay (Optional)</label>
                    <select id="barangay_id" name="barangay_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
                        <option value="">-- Select Barangay --</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Only available for Barangay Official roles</p>
                    @error('barangay_id')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2.5 rounded-lg hover:bg-gray-400 font-semibold transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function bindToggle(buttonId, inputId) {
            const button = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            if (!button || !input) return;
            button.addEventListener('click', function() {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.textContent = isPassword ? 'Hide' : 'Show';
            });
        }

        bindToggle('togglePasswordCreate', 'password_hash');
        bindToggle('togglePasswordConfirmationCreate', 'password_hash_confirmation');

        // Password strength calculator
        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*]/.test(password)) strength++;
            return strength;
        }

        const passwordInput = document.getElementById('password_hash');
        const confirmPasswordInput = document.getElementById('password_hash_confirmation');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const matchContainer = document.getElementById('passwordMatchContainer');
        const matchIcon = document.getElementById('matchIcon');
        const matchText = document.getElementById('matchText');

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            let color, text, width;
            
            switch(strength) {
                case 0: color = '#d1d5db'; text = 'Very Weak'; width = '0%'; break;
                case 1: color = '#ef4444'; text = 'Weak'; width = '20%'; break;
                case 2: color = '#f97316'; text = 'Fair'; width = '40%'; break;
                case 3: color = '#eab308'; text = 'Good'; width = '60%'; break;
                case 4: color = '#84cc16'; text = 'Strong'; width = '80%'; break;
                default: color = '#22c55e'; text = 'Very Strong'; width = '100%';
            }
            
            strengthBar.style.backgroundColor = color;
            strengthBar.style.width = width;
            strengthText.textContent = text;
            strengthText.style.color = color;

            // Check password match
            checkPasswordMatch();
        });

        // Password match checker
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword.length === 0) {
                matchContainer.style.display = 'none';
                return;
            }

            if (password === confirmPassword && password.length > 0) {
                matchContainer.style.display = 'block';
                matchContainer.style.backgroundColor = '#dcfce7';
                matchContainer.style.borderColor = '#22c55e';
                matchIcon.style.color = '#22c55e';
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#22c55e';
                confirmPasswordInput.style.borderColor = '#22c55e';
                confirmPasswordInput.style.boxShadow = '0 0 0 2px rgba(34, 197, 94, 0.1)';
            } else if (confirmPassword.length > 0) {
                matchContainer.style.display = 'block';
                matchContainer.style.backgroundColor = '#fee2e2';
                matchContainer.style.borderColor = '#ef4444';
                matchIcon.style.color = '#ef4444';
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
                confirmPasswordInput.style.borderColor = '#ef4444';
                confirmPasswordInput.style.boxShadow = '0 0 0 2px rgba(239, 68, 68, 0.1)';
            } else {
                matchContainer.style.display = 'none';
                confirmPasswordInput.style.borderColor = '#d1d5db';
                confirmPasswordInput.style.boxShadow = 'none';
            }
        }

        // Listen to confirm password input
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Barangay dropdown enable/disable logic
        const roleSelect = document.getElementById('role_id');
        const barangaySelect = document.getElementById('barangay_id');

        function updateBarangaySelectState() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = selectedOption.text.toLowerCase();
            
            // Enable barangay dropdown only if "barangay" role is selected
            if (selectedRoleText.includes('barangay')) {
                barangaySelect.disabled = false;
                barangaySelect.style.opacity = '1';
                barangaySelect.style.cursor = 'pointer';
            } else {
                barangaySelect.disabled = true;
                barangaySelect.value = '';
                barangaySelect.style.opacity = '0.6';
                barangaySelect.style.cursor = 'not-allowed';
            }
        }

        roleSelect.addEventListener('change', updateBarangaySelectState);

        // Initialize on page load
        updateBarangaySelectState();
    });
</script>
@endsection
