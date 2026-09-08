@extends('layouts.department')

@section('content')
<style>
    .engineering-projects-page .engineering-projects-card,
    .engineering-projects-page .engineering-projects-table {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-radius: 16px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .engineering-projects-page .engineering-project-mobile-card,
    .engineering-projects-page .engineering-projects-table thead {
        background: #f4f4f5 !important;
    }

    .engineering-projects-page .engineering-project-mobile-card {
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-radius: 16px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .engineering-projects-page .engineering-project-mobile-card:hover,
    .engineering-projects-page .engineering-projects-table:hover {
        border-color: rgba(0, 0, 0, 0.1) !important;
        box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.08), 0 4px 8px -4px rgba(0, 0, 0, 0.04) !important;
    }

    html.dark-mode .engineering-projects-page .engineering-projects-card,
    html.dark-mode .engineering-projects-page .engineering-projects-table,
    .dark .engineering-projects-page .engineering-projects-card,
    .dark .engineering-projects-page .engineering-projects-table {
        background: #141321 !important;
    }

    html.dark-mode .engineering-projects-page .engineering-project-mobile-card,
    html.dark-mode .engineering-projects-page .engineering-projects-table thead,
    .dark .engineering-projects-page .engineering-project-mobile-card,
    .dark .engineering-projects-page .engineering-projects-table thead {
        background: #0f0e1a !important;
    }

    html.dark-mode .engineering-projects-page .engineering-projects-card,
    html.dark-mode .engineering-projects-page .engineering-projects-table,
    html.dark-mode .engineering-projects-page .engineering-project-mobile-card,
    .dark .engineering-projects-page .engineering-projects-card,
    .dark .engineering-projects-page .engineering-projects-table,
    .dark .engineering-projects-page .engineering-project-mobile-card {
        border: 1px solid #020617 !important;
        box-shadow: inset 0 0 0 1px #1e293b, 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    html.dark-mode .engineering-projects-page .engineering-project-mobile-card:hover,
    html.dark-mode .engineering-projects-page .engineering-projects-table:hover,
    .dark .engineering-projects-page .engineering-project-mobile-card:hover,
    .dark .engineering-projects-page .engineering-projects-table:hover {
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.2), 0 4px 8px -4px rgba(0, 0, 0, 0.15) !important;
    }
</style>
<div class="engineering-projects-page max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Engineering Projects</h1>
        <p class="mt-1 text-sm text-slate-500">View all active projects and monitor implementation progress.</p>
    </div>

    <div class="engineering-projects-card rounded-3xl border border-slate-200 p-6 shadow-sm">
        @if ($projects->isEmpty())
            <div class="text-sm text-slate-500">No projects have been added yet.</div>
        @else
            <div class="space-y-4 lg:hidden">
                @foreach ($projects as $project)
                    <div class="engineering-project-mobile-card rounded-3xl border border-slate-200 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $project->current_status }}</p>
                            </div>
                            <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">View</a>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 text-sm text-slate-600">
                            <div><span class="block text-xs text-slate-500">Budget</span><span class="font-semibold text-slate-900">₱{{ number_format($project->approved_budget ?? 0, 2) }}</span></div>
                            <div><span class="block text-xs text-slate-500">Barangay</span><span class="font-semibold text-slate-900">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="engineering-projects-table hidden lg:block overflow-x-auto rounded-3xl border border-slate-200 shadow-sm ring-1 ring-slate-200">
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
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($projects as $project)
                            <tr class="department-project-table-row hover:bg-slate-50">
                                <td class="py-3 px-4 text-slate-900 font-medium">{{ $project->project_name }}</td>
                                <td class="py-3 px-4 text-slate-900">{{ $project->current_status }}</td>
                                <td class="py-3 px-4 text-slate-900">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</td>
                                <td class="py-3 px-4 text-slate-900">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="text-blue-600 font-semibold hover:text-blue-800">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
