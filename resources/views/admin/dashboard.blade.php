@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
    <div class="admin-dashboard-hero rounded-3xl px-6 py-7 shadow-lg sm:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-amber-300">System command center</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Admin Dashboard</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">A focused view of access, projects, reports, and system activity.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="admin-dashboard-stat admin-dashboard-stat-blue rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-600">Users</p>
                <span class="admin-dashboard-stat-icon rounded-xl p-2 text-blue-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" /></svg>
                </span>
            </div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $users ?? 0 }}</p>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-emerald rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-600">Projects</p>
                <span class="admin-dashboard-stat-icon rounded-xl p-2 text-emerald-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L12 3l9 4.5v9L12 21l-9-4.5v-9zM3 7.5l9 4.5m9-4.5l-9 4.5m0 0V21" /></svg>
                </span>
            </div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $projects ?? 0 }}</p>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs font-medium text-slate-500">
                <span class="mr-4">Published: {{ $projects_published ?? 0 }}</span>
                <span>Drafts: {{ $projects_draft ?? 0 }}</span>
            </div>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-amber rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-600">Reports</p>
                <span class="admin-dashboard-stat-icon rounded-xl p-2 text-amber-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h9l4 4v16H6V2zm9 0v5h4M9 13h6m-6 4h6M9 9h2" /></svg>
                </span>
            </div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $reports ?? 0 }}</p>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-rose rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-slate-600">Audit Logs</p>
                <span class="admin-dashboard-stat-icon rounded-xl p-2 text-rose-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 4.5-3 7.8-7 9-4-1.2-7-4.5-7-9V7l7-4zm-3 9l2 2 4-4" /></svg>
                </span>
            </div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $auditLogs ?? 0 }}</p>
        </div>
    </div>

    <div class="admin-dashboard-activity overflow-hidden rounded-2xl shadow-sm">
        <div class="admin-card-header flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Recent Activity</h2>
                <p class="mt-1 text-sm text-slate-500">Latest system actions and changes.</p>
            </div>
            <span class="admin-live-feed hidden rounded-full bg-slate-200 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-600 sm:inline-flex">Live feed</span>
        </div>
        <div class="p-6">
            @if(!empty($recentActivity) && $recentActivity->count())
                <ul class="space-y-3">
                    @foreach($recentActivity as $activity)
                        <li class="recent-activity-item rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm transition hover:border-blue-200 hover:bg-white">
                            <div class="flex items-start gap-3">
                                <div class="recent-activity-icon mt-0.5 flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="text-sm font-semibold text-slate-900">{{ optional($activity->user)->username ?? optional($activity->user)->user_email ?? 'System' }}</div>
                                        <div class="text-xs font-medium text-slate-500">{{ optional($activity->created_at)->diffForHumans() }}</div>
                                    </div>
                                    <div class="mt-2 text-sm leading-6 text-slate-600">{{ $activity->action }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="recent-activity-empty rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">No recent activity.</div>
            @endif
        </div>
    </div>
</div>
@endsection
