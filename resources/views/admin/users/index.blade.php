@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">User Access</h1>
                <p class="mt-2 text-sm text-slate-600">Manage system users, assign roles, and keep your access controls up to date.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Add New User
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-3xl mb-4 shadow-sm">
            {{ $message }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Username</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Barangay</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->user_email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-sky-700">
                                {{ $user->role?->role_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->barangay?->barangay_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.users.edit', $user->user_id) }}" class="text-slate-700 hover:text-slate-900 font-semibold">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
