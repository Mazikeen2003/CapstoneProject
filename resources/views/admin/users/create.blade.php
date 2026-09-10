@extends('layouts.admin')

@section('content')
<style>
.admin-create-user {
    --create-bg: #f4f4f5;
    --create-surface: #ffffff;
    --create-ink: #0f0d1f;
    --create-muted: #6b7280;
    --create-line: rgba(0, 0, 0, 0.06);
    --create-line-strong: rgba(0, 0, 0, 0.1);
    --create-card-border: #dbe3ee;
    --create-indigo: #4338ca;
    --create-gold: #f59e0b;
    --create-warning: #92400e;
    --create-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04);
    --create-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
html.dark-mode .admin-create-user {
    --create-bg: #0f0e1a;
    --create-surface: #141321;
    --create-ink: #f8f7f5;
    --create-muted: #94a3b8;
    --create-line: rgba(255, 255, 255, 0.06);
    --create-line-strong: rgba(255, 255, 255, 0.1);
    --create-card-border: #020617;
    --create-warning: #fbbf24;
}
.admin-create-user > * { opacity: 0; animation: createUserFadeUp 0.5s ease forwards; }
.admin-create-user > *:nth-child(1) { animation-delay: 0.05s; }
.admin-create-user > *:nth-child(2) { animation-delay: 0.1s; }
@keyframes createUserFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.admin-create-user .admin-create-card {
    position: relative;
    overflow: hidden;
    background: var(--create-surface) !important;
    border: 1px solid var(--create-card-border) !important;
    border-radius: 20px !important;
    box-shadow: var(--create-shadow) !important;
}
html.dark-mode .admin-create-user .admin-create-card {
    background: #141321 !important;
    border: 1px solid #020617 !important;
    box-shadow: inset 0 0 0 1px #1e293b, var(--create-shadow) !important;
}
.admin-create-user .admin-create-card::before {
    content: '';
    position: absolute;
    inset: 0 0 auto;
    height: 3px;
    background: linear-gradient(90deg, var(--create-gold), var(--create-indigo));
}
.admin-create-user h1,
.admin-create-user h3,
.admin-create-user label,
.admin-create-user .text-gray-700,
.admin-create-user .text-gray-800 { color: var(--create-ink) !important; }
.admin-create-user .text-gray-500 { color: var(--create-muted) !important; }
.admin-create-user .admin-create-disable-label {
    background: rgba(245, 158, 11, 0.08) !important;
    border-color: rgba(245, 158, 11, 0.2) !important;
    color: var(--create-warning) !important;
}
.admin-create-user .admin-create-disable-label .admin-create-disable-text {
    color: var(--create-warning) !important;
    font-weight: 700;
}
.admin-create-user input:not([type='checkbox']),
.admin-create-user select {
    background: var(--create-bg) !important;
    color: var(--create-ink) !important;
    border-color: var(--create-line-strong) !important;
    transition: var(--create-transition);
}
.admin-create-user input:not([type='checkbox']):focus,
.admin-create-user select:focus {
    border-color: var(--create-gold) !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1) !important;
    outline: none;
}
.admin-create-user #departmentPermissionsSection,
.admin-create-user #adminPermissionsSection {
    background: var(--create-bg) !important;
    border-color: var(--create-line) !important;
}
.admin-create-user .admin-create-primary {
    background: linear-gradient(135deg, #1e1b4b, var(--create-indigo)) !important;
    color: #ffffff !important;
    transition: var(--create-transition);
}
.admin-create-user .admin-create-primary:hover {
    transform: translateY(-2px);
    filter: brightness(1.08);
    box-shadow: 0 8px 24px -4px rgba(67, 56, 202, 0.4);
}
.admin-create-user .admin-create-cancel {
    background: var(--create-bg) !important;
    color: var(--create-ink) !important;
    transition: var(--create-transition);
}
</style>

<div class="admin-create-user space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Create New User</h1>
        <p class="text-sm text-gray-500 mt-1">Add a new user account to the system.</p>
    </div>

    <div class="admin-create-card rounded-xl shadow-sm border p-8">
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
                    <label class="admin-create-disable-label flex items-center gap-3 rounded-xl border px-4 py-3 text-sm">
                        <input type="checkbox" name="is_disabled" value="1" {{ old('is_disabled') ? 'checked' : '' }} class="h-4 w-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                        <span class="admin-create-disable-text">Disable login access for this user</span>
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
                    <label for="barangay_id" class="block text-sm font-semibold text-gray-700 mb-2">Barangay <span id="barangayRequiredMarker" class="hidden text-red-600">*</span></label>
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

            <!-- Row 4: Planning Permissions (only shown when Planning role selected) -->
            <div id="departmentPermissionsSection" class="border border-gray-200 rounded-lg p-5 bg-gray-50" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Planning Permissions</h3>
                <p class="text-xs text-gray-500 mb-4">Choose which actions this Planning user is allowed to perform.</p>
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
                        <input type="checkbox" name="is_department_head" value="1" {{ old('is_department_head') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Approve/Reject Project Edit Requests
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
                <button type="submit" data-loading-text="Creating..." class="admin-create-primary px-6 py-2.5 rounded-lg font-semibold inline-flex items-center justify-center gap-2">
                    <span class="loading-spinner hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                    <span class="loading-label">Create User</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="admin-create-cancel px-6 py-2.5 rounded-lg font-semibold transition">
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
            const requiredMarker = document.getElementById('barangayRequiredMarker');

            barangaySelect.disabled = !isBarangayRole;
            barangaySelect.required = isBarangayRole;
            barangaySelect.style.opacity = isBarangayRole ? '1' : '0.6';
            barangaySelect.style.cursor = isBarangayRole ? 'pointer' : 'not-allowed';

            if (requiredMarker) {
                requiredMarker.classList.toggle('hidden', !isBarangayRole);
            }

            if (!isBarangayRole) {
                barangaySelect.value = '';
            }
        }

        function updateDepartmentPermissionsVisibility() {
            if (!roleSelect) return;

            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = (selectedOption?.text || '').toLowerCase();
            const isPlanningRole = selectedOption?.value === '3'
                || selectedRoleText.includes('planning')
                || selectedRoleText.includes('department');

            if (departmentPermissionsSection) {
                departmentPermissionsSection.style.display = isPlanningRole ? 'block' : 'none';
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
