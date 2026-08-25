@extends('layouts.city')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">City Projects</h1>
        <p class="mt-1 text-sm text-slate-500">Browse all city projects across Cabuyao.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($projects->isEmpty())
            <div class="text-sm text-slate-500">No projects have been added yet.</div>
        @else
            <div class="space-y-4 lg:hidden">
                @foreach ($projects as $project)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $project->current_status }}</p>
                            </div>
                            <a href="{{ route('city.projects.show', $project->project_id) }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">View</a>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 text-sm text-slate-600">
                            <div><span class="block text-xs text-slate-500">Budget</span><span class="font-semibold text-slate-900">₱{{ number_format($project->approved_budget ?? 0, 2) }}</span></div>
                            <div><span class="block text-xs text-slate-500">Barangay</span><span class="font-semibold text-slate-900">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="hidden lg:block overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200">
                <table class="w-full min-w-[720px] border-collapse text-sm">
                    <thead class="admin-card-header bg-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Project Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Barangay</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Budget</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($projects as $project)
                            <tr class="department-project-table-row hover:bg-slate-50">
                                <td class="py-3 px-4 text-slate-900 font-medium">{{ $project->project_name }}</td>
                                <td class="py-3 px-4 text-slate-900">{{ $project->current_status }}</td>
                                <td class="py-3 px-4 text-slate-900">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</td>
                                <td class="py-3 px-4 text-slate-900">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('city.projects.show', $project->project_id) }}" class="text-blue-600 font-semibold hover:text-blue-800">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
