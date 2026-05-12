@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-white">Edit User</h1>
        <p style="color: #c9a84c;">Update user details and role</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #162347; border: 1px solid #c9a84c;">
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-white">Name</label>
                    <input type="text" value="Juan Dela Cruz" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #0f1e3d; border-color: #c9a84c; color: white;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Email</label>
                    <input type="email" value="department@cabuyao.gov.ph" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #0f1e3d; border-color: #c9a84c; color: white;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Role</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #0f1e3d; border-color: #c9a84c; color: white;">
                        <option>Admin</option>
                        <option selected>Department</option>
                        <option>City Official</option>
                        <option>Barangay Official</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Status</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #0f1e3d; border-color: #c9a84c; color: white;">
                        <option selected>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ url('/admin/users') }}" class="px-4 py-2 rounded" style="color: #c9a84c;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection
