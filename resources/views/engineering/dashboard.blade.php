@extends('layouts.department')

@section('content')
@php
    $budgetAllocated = (float) ($stats['budget_allocated'] ?? 0);
    $budgetDisplay = $budgetAllocated >= 1000000000
        ? '₱' . number_format($budgetAllocated / 1000000000, 1) . 'B'
        : ($budgetAllocated >= 1000000 ? '₱' . number_format($budgetAllocated / 1000000, 1) . 'M' : '₱' . number_format($budgetAllocated, 0));
@endphp
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" />
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .engineering-summary-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        flex-shrink: 0;
    }
    .engineering-summary-icon.blue { background: #dbeafe; color: #2563eb; }
    .engineering-summary-icon.amber { background: #fef3c7; color: #d97706; }
    .engineering-summary-icon.emerald { background: #d1fae5; color: #059669; }
    .engineering-summary-icon.rose { background: #ffe4e6; color: #e11d48; }
    html.dark-mode .engineering-summary-icon.blue,
    .dark .engineering-summary-icon.blue { background: rgba(59, 130, 246, 0.08); color: #60a5fa; }
    html.dark-mode .engineering-summary-icon.amber,
    .dark .engineering-summary-icon.amber { background: rgba(245, 158, 11, 0.08); color: #fbbf24; }
    html.dark-mode .engineering-summary-icon.emerald,
    .dark .engineering-summary-icon.emerald { background: rgba(16, 185, 129, 0.08); color: #34d399; }
    html.dark-mode .engineering-summary-icon.rose,
    .dark .engineering-summary-icon.rose { background: rgba(244, 63, 94, 0.08); color: #fb7185; }
</style>
<style>
    .engineering-dashboard .admin-dashboard-stat,
    .engineering-dashboard .admin-dashboard-activity {
        background: #ffffff !important;
    }

    .engineering-dashboard .engineering-recent-card {
        background: #f4f4f5 !important;
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .engineering-dashboard .engineering-recent-card:hover {
        transform: translateY(-1px);
        border-color: rgba(245,158,11,.35) !important;
        box-shadow: 0 4px 6px -1px rgba(245,158,11,.12), 0 2px 4px -2px rgba(15,23,42,.12);
    }

    .engineering-recent-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .engineering-recent-pagination-info {
        color: #64748b;
        font-size: .8125rem;
    }

    .engineering-dashboard .admin-dashboard-stat {
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .engineering-dashboard .admin-dashboard-stat::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .engineering-dashboard .admin-dashboard-stat:hover {
        transform: translateY(-2px);
    }

    .engineering-dashboard .admin-dashboard-stat:hover::before { opacity: 1; }
    .engineering-dashboard .admin-dashboard-stat-blue::before { background: #2563eb; }
    .engineering-dashboard .admin-dashboard-stat-amber::before { background: #d97706; }
    .engineering-dashboard .admin-dashboard-stat-emerald::before { background: #059669; }
    .engineering-dashboard .admin-dashboard-stat-rose::before { background: #e11d48; }
    .engineering-dashboard .admin-dashboard-stat-blue:hover { border-color: #93c5fd; box-shadow: 0 10px 15px -3px rgba(37,99,235,.16); }
    .engineering-dashboard .admin-dashboard-stat-amber:hover { border-color: #fcd34d; box-shadow: 0 10px 15px -3px rgba(217,119,6,.16); }
    .engineering-dashboard .admin-dashboard-stat-emerald:hover { border-color: #6ee7b7; box-shadow: 0 10px 15px -3px rgba(5,150,105,.16); }
    .engineering-dashboard .admin-dashboard-stat-rose:hover { border-color: #fda4af; box-shadow: 0 10px 15px -3px rgba(225,29,72,.16); }

    @media (prefers-reduced-motion: reduce) {
        .engineering-dashboard .admin-dashboard-stat { transition: none; }
        .engineering-dashboard .admin-dashboard-stat:hover { transform: none; }
    }

    html.dark-mode .engineering-dashboard .admin-dashboard-stat,
    html.dark-mode .engineering-dashboard .admin-dashboard-activity,
    .dark .engineering-dashboard .admin-dashboard-stat,
    .dark .engineering-dashboard .admin-dashboard-activity {
        background: #141321 !important;
        border: 1px solid #020617 !important;
        box-shadow: inset 0 0 0 1px #1e293b, 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    html.dark-mode .engineering-dashboard .engineering-recent-card,
    .dark .engineering-dashboard .engineering-recent-card {
        background: #0f0e1a !important;
    }

    .engineering-dashboard-main { display: grid; grid-template-columns: 1fr; gap: 24px; margin-bottom: 24px; }
    @media (min-width: 1024px) { .engineering-dashboard-main { grid-template-columns: 1.2fr 0.8fr; } }
    .engineering-dashboard-panel { background: #ffffff !important; border: 1px solid rgba(0,0,0,0.06); border-radius: 12px; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); overflow: hidden; }
    .engineering-dashboard-panel-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid rgba(0,0,0,.06); }
    .engineering-dashboard-panel-title-wrap { display: flex; align-items: center; gap: 12px; }
    .engineering-dashboard-panel-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #fef3c7; color: #d97706; }
    .engineering-dashboard-panel-icon.green { background: #d1fae5; color: #047857; }
    .engineering-dashboard-panel-title { font-size: 1rem; font-weight: 700; line-height: 1.3; color: #1e1b4b; }
    .engineering-dashboard-panel-subtitle { margin-top: 2px; font-size: .75rem; color: #9ca3af; }
    .engineering-dashboard-panel-link { display: inline-flex; align-items: center; gap: 4px; color: #2563eb; font-size: .8125rem; font-weight: 600; text-decoration: none; transition: gap .2s; }
    .engineering-dashboard-panel-link:hover { gap: 8px; }
    .engineering-dashboard-panel-body { padding: 20px 24px; }
    .engineering-dashboard-map-wrap { position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,.06); border-radius: 8px; }
    .engineering-dashboard-map-legend { position: absolute; right: 16px; bottom: 16px; z-index: 400; padding: 12px 16px; border: 1px solid rgba(0,0,0,.06); border-radius: 8px; background: rgba(255,255,255,.95); box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1); }
    .engineering-dashboard-map-legend-title { margin-bottom: 8px; color: #4b5563; font-size: .6875rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
    .engineering-dashboard-map-legend-item { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; color: #4b5563; font-size: .75rem; }
    .engineering-dashboard-map-legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .engineering-dashboard-projects { display: flex; flex-direction: column; gap: 12px; }
    .engineering-dashboard-project { display: flex; align-items: flex-start; gap: 14px; padding: 16px; border: 1px solid rgba(0,0,0,0.06); border-radius: 8px; background: #fafaf9 !important; transition: all .2s ease; }
    .engineering-dashboard-project:hover { transform: translateY(-1px); border-color: rgba(0,0,0,0.12); background: #ffffff !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .engineering-dashboard-project-avatar { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .875rem; font-weight: 800; flex-shrink: 0; }
    .engineering-dashboard-project-info { flex: 1; min-width: 0; }
    .engineering-dashboard-project-title { margin-bottom: 6px; overflow: hidden; color: #1e1b4b; font-size: .9375rem; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
    .engineering-dashboard-project-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .engineering-dashboard-project-meta-item { display: inline-flex; align-items: center; gap: 4px; color: #9ca3af; font-size: .75rem; }
    .engineering-project-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: .75rem; font-weight: 700; text-transform: capitalize; }
    .engineering-project-status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-planning { background: #fef3c7; color: #b45309; }
    .status-ongoing { background: #dbeafe; color: #1d4ed8; }
    .status-hold { background: #fee2e2; color: #b91c1c; }
    .status-completed { background: #d1fae5; color: #047857; }
    .status-cancelled { background: #f3f4f6; color: #4b5563; }
    html.dark-mode .engineering-dashboard .status-planning, .dark .engineering-dashboard .status-planning { background: rgba(251,191,36,.15); color: #fbbf24; }
    html.dark-mode .engineering-dashboard .status-ongoing, .dark .engineering-dashboard .status-ongoing { background: rgba(59,130,246,.15); color: #60a5fa; }
    html.dark-mode .engineering-dashboard .status-hold, .dark .engineering-dashboard .status-hold { background: rgba(239,68,68,.15); color: #f87171; }
    html.dark-mode .engineering-dashboard .status-completed, .dark .engineering-dashboard .status-completed { background: rgba(16,185,129,.15); color: #34d399; }
    html.dark-mode .engineering-dashboard .status-cancelled, .dark .engineering-dashboard .status-cancelled { background: rgba(107,114,128,.15); color: #9ca3af; }
    .engineering-dashboard-project-progress { margin-top: 10px; }
    .engineering-dashboard-progress-bg { height: 6px; overflow: hidden; border-radius: 100px; background: #e5e7eb; }
    .engineering-dashboard-progress-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #f59e0b, #d97706); }
    .engineering-dashboard-project-action { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex-shrink: 0; border: 1px solid rgba(0,0,0,.06); border-radius: 10px; background: #fff; color: #4b5563; transition: all .2s ease; }
    .engineering-dashboard-project-action:hover { border-color: #2563eb; background: #2563eb; color: #fff; }
    .engineering-dashboard-pagination { margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,.06); }
    .engineering-dashboard-recent-panel { --engineering-recent-holder: #ffffff; --engineering-recent-project: #fafaf9; background: #ffffff !important; }
    .engineering-dashboard-map-panel { background: #ffffff !important; }
    html.dark-mode .engineering-dashboard .engineering-dashboard-map-panel, html.dark .engineering-dashboard .engineering-dashboard-map-panel, .dark .engineering-dashboard .engineering-dashboard-map-panel { background: #141321 !important; }
    html.dark-mode .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-header,
    html.dark-mode .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-body,
    html.dark .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-header,
    html.dark .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-body,
    .dark .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-header,
    .dark .engineering-dashboard .engineering-dashboard-map-panel .engineering-dashboard-panel-body { background: #141321 !important; }
    html.dark-mode .engineering-dashboard-panel, .dark .engineering-dashboard-panel { background: #172033 !important; border-color: #334155; box-shadow: 0 8px 20px rgba(2,6,23,.25); }
    html.dark-mode .engineering-dashboard .engineering-dashboard-recent-panel, html.dark .engineering-dashboard .engineering-dashboard-recent-panel, .dark .engineering-dashboard .engineering-dashboard-recent-panel { --engineering-recent-holder: #141321; --engineering-recent-project: #172033; background: #141321 !important; }
    html.dark-mode .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-header,
    html.dark-mode .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-body,
    html.dark .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-header,
    html.dark .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-body,
    .dark .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-header,
    .dark .engineering-dashboard .engineering-dashboard-recent-panel .engineering-dashboard-panel-body { background: #141321 !important; }
    html.dark-mode .engineering-dashboard-panel-header, .dark .engineering-dashboard-panel-header, html.dark-mode .engineering-dashboard-pagination, .dark .engineering-dashboard-pagination { border-color: #334155; }
    html.dark-mode .engineering-dashboard-panel-title, .dark .engineering-dashboard-panel-title, html.dark-mode .engineering-dashboard-project-title, .dark .engineering-dashboard-project-title { color: #f8fafc; }
    html.dark-mode .engineering-dashboard .engineering-dashboard-project, html.dark .engineering-dashboard .engineering-dashboard-project, .dark .engineering-dashboard .engineering-dashboard-project { background: #172033 !important; border-color: #334155 !important; }
    html.dark-mode .engineering-dashboard .engineering-dashboard-project:hover, html.dark .engineering-dashboard .engineering-dashboard-project:hover, .dark .engineering-dashboard .engineering-dashboard-project:hover { background: #243247 !important; border-color: #64748b !important; }
    html.dark-mode .engineering-dashboard-project-action, .dark .engineering-dashboard-project-action { background: #243247; color: #cbd5e1; border-color: #475569; }
    html.dark-mode .engineering-dashboard-map-legend, .dark .engineering-dashboard-map-legend { background: rgba(15,14,26,.95); border-color: rgba(255,255,255,.06); }
    html.dark-mode .engineering-dashboard-map-legend-title, html.dark-mode .engineering-dashboard-map-legend-item, .dark .engineering-dashboard-map-legend-title, .dark .engineering-dashboard-map-legend-item { color: #cbd5e1; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="engineering-dashboard engineering-page-enter max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="admin-dashboard-hero rounded-3xl px-6 py-7 shadow-lg sm:px-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-amber-300">Engineering workspace</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Engineering Dashboard</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">Review project delivery, track field progress, and monitor citywide implementation health.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="admin-dashboard-stat admin-dashboard-stat-blue rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Total Projects</p><span class="engineering-summary-icon blue material-symbols-outlined">folder_open</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['total_projects'] }}</p>
        </div>
        <div class="admin-dashboard-stat admin-dashboard-stat-amber rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Ongoing Projects</p><span class="engineering-summary-icon amber material-symbols-outlined">pending_actions</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['ongoing'] }}</p>
        </div>
        <div class="admin-dashboard-stat admin-dashboard-stat-emerald rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Completed Projects</p><span class="engineering-summary-icon emerald material-symbols-outlined">task_alt</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['completed'] }}</p>
        </div>
        <div class="admin-dashboard-stat admin-dashboard-stat-rose rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Budget Allocated</p><span class="engineering-summary-icon rose material-symbols-outlined">account_balance_wallet</span></div>
            <p class="dashboard-budget-value mt-4 font-bold text-slate-950" title="₱{{ number_format($budgetAllocated, 0) }}">{{ $budgetDisplay }}</p>
        </div>
    </div>

    <div class="engineering-dashboard-main">
        <div class="engineering-dashboard-panel engineering-dashboard-map-panel engineering-page-enter">
            <div class="engineering-dashboard-panel-header">
                <div class="engineering-dashboard-panel-title-wrap">
                    <div class="engineering-dashboard-panel-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></div>
                    <div><div class="engineering-dashboard-panel-title">Project Locations</div><div class="engineering-dashboard-panel-subtitle">Geographic distribution across Cabuyao</div></div>
                </div>
                <a href="{{ route('engineering.map.index') }}" class="engineering-dashboard-panel-link">Full Map <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
            </div>
            <div class="engineering-dashboard-panel-body">
                <div class="engineering-dashboard-map-wrap">
                    <div id="engineering-map" class="relative z-0 h-[420px] w-full bg-gray-200"></div>
                    <div class="engineering-dashboard-map-legend"><div class="engineering-dashboard-map-legend-title">Project Status</div><div class="engineering-dashboard-map-legend-item"><span class="engineering-dashboard-map-legend-dot" style="background:#fbbf24"></span> Planning</div><div class="engineering-dashboard-map-legend-item"><span class="engineering-dashboard-map-legend-dot" style="background:#3b82f6"></span> On Going</div><div class="engineering-dashboard-map-legend-item"><span class="engineering-dashboard-map-legend-dot" style="background:#ef4444"></span> On Hold</div><div class="engineering-dashboard-map-legend-item"><span class="engineering-dashboard-map-legend-dot" style="background:#10b981"></span> Completed</div></div>
                </div>
            </div>
        </div>

        <div class="engineering-dashboard-panel engineering-dashboard-recent-panel engineering-page-enter">
            <div class="engineering-dashboard-panel-header" style="background-color: #141321 !important;">
                <div class="engineering-dashboard-panel-title-wrap"><div class="engineering-dashboard-panel-icon green"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg></div><div><div class="engineering-dashboard-panel-title">Recent Projects</div><div class="engineering-dashboard-panel-subtitle">Latest engineering project activity</div></div></div>
                <a href="{{ route('engineering.projects.index') }}" class="engineering-dashboard-panel-link">View All <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
            </div>
            <div class="engineering-dashboard-panel-body" style="background: #141321 !important;">
                @if ($recentProjects->isEmpty())
                    <p class="text-sm text-slate-500">No projects yet.</p>
                @else
                    <div class="engineering-dashboard-projects">
                        @foreach ($recentProjects as $project)
                            @php
                                $statusClass = match($project->current_status) { 'Planning' => 'status-planning', 'On Going' => 'status-ongoing', 'On Hold' => 'status-hold', 'Completed' => 'status-completed', 'Cancelled' => 'status-cancelled', default => 'status-planning' };
                                $progress = $project->latestUpdate?->progress_percentage ?? 0;
                                $initials = collect(explode(' ', $project->project_name))->map(fn($word) => strtoupper($word[0] ?? ''))->take(2)->implode('');
                                $avatarGradient = ['linear-gradient(135deg,#f59e0b,#d97706)','linear-gradient(135deg,#3b82f6,#1d4ed8)','linear-gradient(135deg,#10b981,#047857)','linear-gradient(135deg,#8b5cf6,#6d28d9)'][$loop->index % 4];
                            @endphp
                            <div class="engineering-dashboard-project" style="background-color: var(--engineering-recent-project) !important;">
                                <div class="engineering-dashboard-project-avatar" style="background:{{ $avatarGradient }}">{{ $initials }}</div>
                                <div class="engineering-dashboard-project-info"><div class="engineering-dashboard-project-title">{{ $project->project_name }}</div><div class="engineering-dashboard-project-meta"><span class="engineering-project-status {{ $statusClass }}">{{ $project->current_status }}</span><span class="engineering-dashboard-project-meta-item"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>{{ $project->barangay?->barangay_name ?? 'N/A' }}</span><span class="engineering-dashboard-project-meta-item">₱{{ number_format($project->approved_budget ?? 0) }}</span></div><div class="engineering-dashboard-project-progress"><div class="engineering-dashboard-progress-bg"><div class="engineering-dashboard-progress-fill" style="width:{{ $progress }}%"></div></div></div></div>
                                <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="engineering-dashboard-project-action" title="View project"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
                            </div>
                        @endforeach
                    </div>
                    @if ($recentProjects->hasPages())
                        <div class="engineering-dashboard-pagination">{{ $recentProjects->links('vendor.pagination.custom') }}</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features?.find(f => f.properties?.kind === 'boundary');
                const geoJsonBoundary = boundaryFeature ? boundaryFeature : (geojson.features?.length ? geojson : null);

                if (!geoJsonBoundary) {
                    console.error('GeoJSON boundary is missing or malformed:', geojson);
                    return;
                }

                const cabuyaoBounds = L.geoJSON(geoJsonBoundary).getBounds();
                const map = L.map('engineering-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(geoJsonBoundary, {
                    style: {
                        color: '#3b82f6',
                        weight: 2,
                        opacity: 0.6,
                        fillOpacity: 0.1
                    }
                }).addTo(map);

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());

                fetch('{{ route("api.projects.geojson") }}')
                    .then(r => r.json())
                    .then(function(data) {
                        L.geoJSON(data, {
                            pointToLayer: function(feature, latlng) {
                                const statusColor = {
                                    'Planning': '#fbbf24',
                                    'On Going': '#3b82f6',
                                    'On Hold': '#ef4444',
                                    'Completed': '#10b981',
                                    'Cancelled': '#6b7280'
                                };

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: statusColor[feature.properties.status] || '#9CA3AF',
                                    color: '#000',
                                    weight: 2,
                                    opacity: 0.8,
                                    fillOpacity: 0.7
                                });
                            },
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                layer.bindPopup(`
                                    <div class="text-sm">
                                        <h4 class="font-bold">${props.name}</h4>
                                        <p class="text-xs text-gray-600">${props.code}</p>
                                        <p class="text-xs"><strong>Status:</strong> ${props.status}</p>
                                        <p class="text-xs"><strong>Barangay:</strong> ${props.barangay}</p>
                                        <p class="text-xs"><strong>Budget:</strong> ₱${parseInt(props.budget).toLocaleString()}</p>
                                        <a href="${props.url}" class="text-blue-600 text-xs">View Details</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    })
                    .catch(err => console.error('Failed to load projects:', err));

                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(err => console.error('Failed to load map:', err));
    });
</script>
@endsection
