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
                    <input type="password" id="password_hash" name="password_hash" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_hash') border-red-500 @enderror" placeholder="Minimum 8 characters" required>
                    @error('password_hash')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_hash_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" id="password_hash_confirmation" name="password_hash_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Re-enter password" required>
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
                    <select id="barangay_id" name="barangay_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Select Barangay --</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
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
@endsection
