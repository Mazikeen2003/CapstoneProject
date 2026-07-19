@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Edit User</h1>
        <p class="text-sm text-gray-500 mt-1">Update user account information.</p>
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

        <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 0: First Name and Last Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror" placeholder="Enter first name" required>
                    @error('first_name')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror" placeholder="Enter last name" required>
                    @error('last_name')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 1: Username and Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror" placeholder="Enter username" required>
                    @error('username')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="user_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="user_email" name="user_email" value="{{ old('user_email', $user->user_email) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user_email') border-red-500 @enderror" placeholder="user@example.com" required>
                    @error('user_email')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Password and Confirm Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password_hash" class="block text-sm font-semibold text-gray-700 mb-2">Password (Leave blank to keep current)</label>
                    <div class="relative">
                        <input type="password" id="password_hash" name="password_hash" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_hash') border-red-500 @enderror" placeholder="Leave blank to keep current password">
                        <button type="button" id="togglePasswordEdit" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            Show
                        </button>
                    </div>
                    @error('password_hash')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_hash_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password_hash_confirmation" name="password_hash_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Confirm new password if changing">
                        <button type="button" id="togglePasswordConfirmationEdit" class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                            Show
                        </button>
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
                            <option value="{{ $role->role_id }}" {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="barangay_id" class="block text-sm font-semibold text-gray-700 mb-2">Barangay (Optional)</label>
                    <select id="barangay_id" name="barangay_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Select Barangay --</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id', $user->barangay_id) == $barangay->barangay_id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                    @error('barangay_id')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Department Permissions (only shown when Department role selected) -->
            @php
                $userPermissions = old('permissions', $user->permissions ?? []);
            @endphp
            <div id="departmentPermissionsSection" class="border border-gray-200 rounded-lg p-5 bg-gray-50" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Department Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Choose which actions this Department user is allowed to perform. All are enabled by default (full access) unless unchecked.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_create_project]" value="1" {{ ($userPermissions['can_create_project'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Create Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_edit_project]" value="1" {{ ($userPermissions['can_edit_project'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Edit Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_delete_project]" value="1" {{ ($userPermissions['can_delete_project'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Delete Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_generate_reports]" value="1" {{ ($userPermissions['can_generate_reports'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Generate/Export Reports
                    </label>
                </div>
            </div>

            <div id="adminPermissionsSection" class="border border-gray-200 rounded-lg p-5 bg-gray-50" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Admin Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Choose the areas this additional Admin can access. The original Admin account always has full access.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_users]" value="1" {{ ($userPermissions['can_manage_users'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        User Access Management
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_project_permissions]" value="1" {{ ($userPermissions['can_manage_project_permissions'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Project Edit Permissions
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_view_reports]" value="1" {{ ($userPermissions['can_view_reports'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Reports
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_audit_logs]" value="1" {{ ($userPermissions['can_manage_audit_logs'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Audit Logs
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition">
                    Update User
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

        bindToggle('togglePasswordEdit', 'password_hash');
        bindToggle('togglePasswordConfirmationEdit', 'password_hash_confirmation');

        // Department permissions section show/hide logic
        const roleSelect = document.getElementById('role_id');
        const departmentPermissionsSection = document.getElementById('departmentPermissionsSection');
        const adminPermissionsSection = document.getElementById('adminPermissionsSection');

        function updateDepartmentPermissionsVisibility() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = selectedOption.text.toLowerCase();

            if (selectedRoleText.includes('department')) {
                departmentPermissionsSection.style.display = 'block';
            } else {
                departmentPermissionsSection.style.display = 'none';
            }

            adminPermissionsSection.style.display = selectedRoleText.includes('admin') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', updateDepartmentPermissionsVisibility);

        // Initialize on page load
        updateDepartmentPermissionsVisibility();
    });
</script>
@endsection
