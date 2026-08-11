@extends('layouts.department')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Department Projects</h1>
        <p class="mt-1 text-sm text-slate-500">Manage and track department projects.</p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">All Projects</h2>
                <p class="mt-1 text-sm text-slate-500">Browse all department projects and actions.</p>
            </div>
            <a href="{{ route('department.projects.create') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-amber-500">Add New Project</a>
        </div>

        @if ($projects->isEmpty())
            <div class="text-sm text-slate-500">
                No projects have been added yet.
            </div>
        @else
            <div class="space-y-4 md:hidden">
                @foreach ($projects as $project)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">{{ $project->project_code }}</p>
                            </div>
                            <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">{{ $project->current_status }}</span>
                        </div>
                        <div class="mt-4 grid gap-2 text-sm text-slate-700">
                            <div><span class="font-semibold">Budget:</span> ₱{{ number_format($project->approved_budget ?? 0, 2) }}</div>
                            <div><span class="font-semibold">Barangay:</span> {{ $project->barangay?->barangay_name ?? 'N/A' }}</div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('department.projects.show', $project->project_id) }}" class="inline-flex rounded-full border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">View</a>
                            <a href="{{ route('department.projects.edit', $project->project_id) }}" class="inline-flex rounded-full border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">Edit</a>
                            <form action="{{ route('department.projects.destroy', $project->project_id) }}" method="POST" class="inline delete-project-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-project-button inline-flex rounded-full border border-red-300 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100" data-name="{{ $project->project_name }}" data-code="{{ $project->project_code }}">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="hidden md:block overflow-x-auto rounded-3xl border border-slate-200 shadow-sm">
                <table class="w-full min-w-[720px] text-sm border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-slate-900">Code</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-900">Project Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-900">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-900">Budget</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="py-3 px-4 text-slate-800">{{ $project->project_code }}</td>
                                <td class="py-3 px-4 text-slate-800">{{ $project->project_name }}</td>
                                <td class="py-3 px-4 text-slate-800">{{ $project->current_status }}</td>
                                <td class="py-3 px-4 text-slate-800">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td class="py-3 px-4 space-x-2 text-slate-800">
                                    <a href="{{ route('department.projects.show', $project->project_id) }}" class="text-blue-600 font-semibold">View</a>
                                    <a href="{{ route('department.projects.edit', $project->project_id) }}" class="text-amber-600 font-semibold">Edit</a>
                                    <form action="{{ route('department.projects.destroy', $project->project_id) }}" method="POST" class="inline delete-project-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-project-button text-red-600 font-semibold" data-name="{{ $project->project_name }}" data-code="{{ $project->project_code }}">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="deleteConfirmModal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-slate-950/60 p-4" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; min-width: 100vw !important; height: 100vh !important; min-height: 100vh !important;">
        <div class="w-full max-w-lg rounded-[2rem] bg-white p-6 shadow-2xl ring-1 ring-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Confirm Project Deletion</h2>
            <p id="deleteModalDescription" class="mt-4 text-sm text-slate-600">Are you sure you want to delete this project? This action cannot be undone.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button id="cancelDeleteBtn" type="button" class="rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button id="confirmDeleteBtn" type="button" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <span class="delete-button-label">Delete Project</span>
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
        var modal = document.getElementById('deleteConfirmModal');
        var modalDescription = document.getElementById('deleteModalDescription');
        var cancelBtn = document.getElementById('cancelDeleteBtn');
        var confirmBtn = document.getElementById('confirmDeleteBtn');
        var confirmLabel = confirmBtn.querySelector('.delete-button-label');
        var loadingIndicator = confirmBtn.querySelector('.delete-loading-indicator');
        var selectedForm = null;

        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        document.querySelectorAll('.delete-project-button').forEach(function (button) {
            button.addEventListener('click', function () {
                selectedForm = button.closest('form');
                var projectName = button.dataset.name || 'this project';
                var projectCode = button.dataset.code ? ' (Code: ' + button.dataset.code + ')' : '';
                modalDescription.textContent = 'Delete "' + projectName + '"' + projectCode + '? This action cannot be undone.';
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
