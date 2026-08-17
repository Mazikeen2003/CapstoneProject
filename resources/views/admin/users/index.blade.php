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

    <div class="space-y-4 md:hidden">
        @forelse ($users as $user)
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $user->username }}</p>
                        <p class="text-sm text-slate-500">{{ $user->user_email }}</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $user->role?->role_name ?? 'N/A' }}</span>
                </div>
                <div class="mt-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-700">Barangay:</span> {{ $user->barangay?->barangay_name ?? 'N/A' }}</p>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline delete-user-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-user-button rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100" data-username="{{ $user->username }}" data-email="{{ $user->user_email }}">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">No users found</div>
        @endforelse
    </div>

    <div class="hidden md:block overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200">
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
                                @if ($user->is_disabled)
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-rose-700">Disabled</span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">Active</span>
                                @endif
                                <a href="{{ route('admin.users.edit', $user->user_id) }}" class="text-slate-700 hover:text-slate-900 font-semibold">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-user-button text-rose-600 hover:text-rose-800 font-semibold" data-username="{{ $user->username }}" data-email="{{ $user->user_email }}">Delete</button>
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

    <div id="adminDeleteConfirmModal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-slate-950/60 p-4" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; min-width: 100vw !important; height: 100vh !important; min-height: 100vh !important;">
        <div class="w-full max-w-lg rounded-[2rem] bg-white p-6 shadow-2xl ring-1 ring-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Confirm User Deletion</h2>
            <p id="adminDeleteModalDescription" class="mt-4 text-sm text-slate-600">Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button id="cancelAdminDeleteBtn" type="button" class="rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button id="confirmAdminDeleteBtn" type="button" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <span class="delete-button-label">Delete User</span>
                    <span class="delete-loading-indicator hidden items-center gap-2" role="status">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        Deleting…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('adminDeleteConfirmModal');
        var modalDescription = document.getElementById('adminDeleteModalDescription');
        var cancelBtn = document.getElementById('cancelAdminDeleteBtn');
        var confirmBtn = document.getElementById('confirmAdminDeleteBtn');
        var confirmLabel = confirmBtn.querySelector('.delete-button-label');
        var loadingIndicator = confirmBtn.querySelector('.delete-loading-indicator');
        var selectedForm = null;

        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        document.querySelectorAll('.delete-user-button').forEach(function (button) {
            button.addEventListener('click', function () {
                selectedForm = button.closest('form');
                var username = button.dataset.username || 'this user';
                var email = button.dataset.email ? ' (' + button.dataset.email + ')' : '';
                modalDescription.textContent = 'Delete user "' + username + '"' + email + '? This action cannot be undone.';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                confirmLabel.classList.remove('hidden');
                loadingIndicator.classList.add('hidden');
                loadingIndicator.classList.remove('inline-flex');
                confirmBtn.removeAttribute('aria-busy');
            }
            cancelBtn.disabled = false;
            cancelBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            selectedForm = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (selectedForm) {
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-60', 'cursor-not-allowed');
                confirmBtn.setAttribute('aria-busy', 'true');
                confirmLabel.classList.add('hidden');
                loadingIndicator.classList.remove('hidden');
                loadingIndicator.classList.add('inline-flex');
                cancelBtn.disabled = true;
                cancelBtn.classList.add('opacity-60', 'cursor-not-allowed');
                selectedForm.submit();
            }
        });
    });
</script>
@endsection
