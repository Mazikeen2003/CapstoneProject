@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: #000000;">Add New User</h1>
        <p style="color: #171716;">Create a new system user</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #ffffff; border: 2px solid #9CA3AF;">
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium" style="color: #0f1e3d;">Name</label>
                    <input type="text" class="mt-1 block w-full rounded-md border shadow-sm" style="background-color: #ffffff; border-color: #9CA3AF; color: #0f1e3d; border-width: 1px;">
                </div>
                <div>
                    <label class="block text-sm font-medium" style="color: #0f1e3d;">Email</label>
                    <input type="email" class="mt-1 block w-full rounded-md border shadow-sm" style="background-color: #ffffff; border-color: #9CA3AF; color: #0f1e3d; border-width: 1px;">
                </div>
                <div>
                    <label class="block text-sm font-medium" style="color: #0f1e3d;">Role</label>
                    <select class="mt-1 block w-full rounded-md border shadow-sm" style="background-color: #ffffff; border-color: #9CA3AF; color: #0f1e3d; border-width: 1px;">
                        <option>Admin</option>
                        <option>Department</option>
                        <option>City Official</option>
                        <option>Barangay Official</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium" style="color: #0f1e3d;">Password</label>
                    <input type="password" class="mt-1 block w-full rounded-md border shadow-sm" style="background-color: #ffffff; border-color: #9CA3AF; color: #0f1e3d; border-width: 1px;">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ url('/admin/users') }}" class="px-4 py-2 rounded" style="color: #6B7280;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded text-white font-semibold" style="background-color: #000000; color: #ffffff;">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
