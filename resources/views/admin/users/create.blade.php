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

            <!-- Row 0: First Name and Last Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror" placeholder="Enter first name" required>
                    @error('first_name')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror" placeholder="Enter last name" required>
                    @error('last_name')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

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

            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                A random password will be generated and emailed to this user upon creation.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        <input type="checkbox" name="is_disabled" value="1" {{ old('is_disabled') ? 'checked' : '' }} class="h-4 w-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                        Disable login access for this user
                    </label>
                    <p class="mt-2 text-xs text-gray-500">Enable this if the user should not be able to sign in until their account is reactivated.</p>
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

            <!-- Row 4: Department Permissions (only shown when Department role selected) -->
            <div id="departmentPermissionsSection" class="border border-gray-200 rounded-lg p-5 bg-gray-50" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Department Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Choose which actions this Department user is allowed to perform. All are enabled by default (full access).</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_create_project]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Create Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_edit_project]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Edit Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_delete_project]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Delete Project
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_generate_reports]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Generate/Export Reports
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 md:col-span-2">
                        <input type="checkbox" name="is_department_head" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Department Head — can approve/reject edit permission requests from department personnel
                    </label>
                </div>
            </div>

            <div id="adminPermissionsSection" class="border border-gray-200 rounded-lg p-5 bg-gray-50" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Admin Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Choose the areas this additional Admin can access. The original Admin account always has full access.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_users]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        User Access Management
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_view_reports]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Reports
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_audit_logs]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Audit Logs
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="permissions[can_manage_backups]" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Database Backups
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" data-loading-text="Creating..." class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-semibold transition inline-flex items-center justify-center gap-2">
                    <span class="loading-spinner hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                    <span class="loading-label">Create User</span>
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
        const roleSelect = document.getElementById('role_id');
        const barangaySelect = document.getElementById('barangay_id');
        const departmentPermissionsSection = document.getElementById('departmentPermissionsSection');
        const adminPermissionsSection = document.getElementById('adminPermissionsSection');

        function updateBarangaySelectState() {
            if (!roleSelect || !barangaySelect) return;

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = (selectedOption?.text || '').toLowerCase();
            const isBarangayRole = selectedRoleText.includes('barangay');

            barangaySelect.disabled = !isBarangayRole;
            barangaySelect.style.opacity = isBarangayRole ? '1' : '0.6';
            barangaySelect.style.cursor = isBarangayRole ? 'pointer' : 'not-allowed';

            if (!isBarangayRole) {
                barangaySelect.value = '';
            }
        }

        function updateDepartmentPermissionsVisibility() {
            if (!roleSelect) return;

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = (selectedOption?.text || '').toLowerCase();

            if (departmentPermissionsSection) {
                departmentPermissionsSection.style.display = selectedRoleText.includes('department') ? 'block' : 'none';
            }

            if (adminPermissionsSection) {
                adminPermissionsSection.style.display = selectedRoleText.includes('admin') ? 'block' : 'none';
            }
        }

        roleSelect.addEventListener('change', function() {
            updateBarangaySelectState();
            updateDepartmentPermissionsVisibility();
        });

        updateBarangaySelectState();
        updateDepartmentPermissionsVisibility();
    });
</script>
@endsection
