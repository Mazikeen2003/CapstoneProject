@extends('layouts.city')

@section('content')
@php
    $cityName = $cityName ?? 'City';
    $budgetAllocated = (float) ($stats['budget_allocated'] ?? 0);
    $budgetDisplay = $budgetAllocated >= 1000000000
        ? '₱' . number_format($budgetAllocated / 1000000000, 1) . 'B'
        : ($budgetAllocated >= 1000000 ? '₱' . number_format($budgetAllocated / 1000000, 1) . 'M' : '₱' . number_format($budgetAllocated, 0));
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
/* ===== CITY DASHBOARD - DEPT STYLE ===== */
.cd-dashboard { max-width: 1400px; margin: 0 auto; padding: 24px; }
@media (min-width: 640px) { .cd-dashboard { padding: 32px; } }
@media (min-width: 1024px) { .cd-dashboard { padding: 40px; } }

/* Hero */
.cd-hero {
    position: relative;
    border-radius: 20px;
    padding: 36px 40px;
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    margin-bottom: 24px;
    overflow: hidden;
}
@media (min-width: 640px) { .cd-hero { padding: 44px 48px; } }
.cd-hero::before {
    content: ""; position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.5;
    pointer-events: none;
}
.cd-hero::after {
    content: ""; position: absolute; top: -50%; right: -10%;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(168,85,247,0.24) 0%, transparent 60%);
    pointer-events: none;
}
html.dark-mode .cd-hero {
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%) !important;
}
.cd-hero-content { position: relative; z-index: 1; }
.cd-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.15em; color: #d8b4fe; margin-bottom: 12px;
}
.cd-hero-eyebrow::before {
    content: ""; display: block; width: 8px; height: 8px;
    border-radius: 50%; background: #818cf8;
    box-shadow: 0 0 0 4px rgba(129,140,248,0.25);
}
.cd-hero-title {
    font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 800;
    color: white; line-height: 1.15; letter-spacing: -0.03em; margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.cd-hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.65); max-width: 620px; line-height: 1.6;
}
.cd-hero-meta { display: flex; align-items: center; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
.cd-hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; background: rgba(255,255,255,0.1);
    backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);
    border-radius: 100px; font-size: 0.75rem; font-weight: 600;
    color: rgba(255,255,255,0.9);
}

/* Stats */
.cd-stats { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
@media (min-width: 640px) { .cd-stats { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .cd-stats { grid-template-columns: repeat(4, 1fr); } }

.cd-stat {
    position: relative; background: #ffffff;
    border-radius: 12px; padding: 24px; border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    transition: all 0.2s ease; overflow: hidden;
}
.cd-stat::before {
    content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--stat-accent, #4f46e5); opacity: 0; transition: opacity 0.2s;
}
.cd-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); }
.cd-stat:hover::before { opacity: 1; }
.cd-stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
.cd-stat-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: var(--stat-icon-bg, #e0e7ff); color: var(--stat-icon-color, #4f46e5);
}
.cd-stat-label { font-size: 0.8125rem; font-weight: 500; color: #9ca3af; margin-bottom: 8px; }
.cd-stat-value { font-size: 1.875rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; line-height: 1; }
.cd-stat-footer { margin-top: 12px; font-size: 0.75rem; color: #9ca3af; }

/* Main Grid */
.cd-main { display: grid; grid-template-columns: 1fr; gap: 24px; }
@media (min-width: 1024px) { .cd-main { grid-template-columns: 1.2fr 0.8fr; } }

/* Card */
.cd-card { background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); overflow: hidden; }
.cd-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid rgba(0,0,0,0.06); }
.cd-card-title-wrap { display: flex; align-items: center; gap: 12px; }
.cd-card-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: #e0e7ff; color: #4f46e5;
}
.cd-card-icon.teal { background: #ccfbf1; color: #0f766e; }
.cd-card-title { font-size: 1rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
.cd-card-subtitle { font-size: 0.75rem; color: #9ca3af; margin-top: 2px; }
.cd-card-action { display: inline-flex; align-items: center; gap: 4px; color: #4f46e5; font-size: 0.8125rem; font-weight: 600; text-decoration: none; transition: gap 0.2s; }
.cd-card-action:hover { gap: 8px; }
.cd-card-body { padding: 20px 24px; }

html.dark-mode .cd-stat,
html.dark-mode .cd-card {
    background: #0f172a;
    border-color: #334155;
    color: #f8fafc;
}
html.dark-mode .cd-stat-value,
html.dark-mode .cd-card-title,
html.dark-mode .cd-project-title,
html.dark-mode .cd-empty h4 {
    color: #f8fafc;
}
html.dark-mode .cd-card-header { border-bottom-color: #334155; }
html.dark-mode .cd-card-subtitle,
html.dark-mode .cd-stat-label,
html.dark-mode .cd-stat-footer,
html.dark-mode .cd-empty p { color: #94a3b8; }
html.dark-mode .cd-project {
    background: #1e293b;
    border-color: #475569;
}
html.dark-mode .cd-project:hover { background: #334155; border-color: #64748b; }
html.dark-mode .cd-project-action {
    background: #1e293b;
    border-color: #475569;
    color: #cbd5e1;
}
html.dark-mode .cd-project-action:hover { background: #4f46e5; border-color: #4f46e5; color: #fff; }
html.dark-mode .cd-map-wrap { border-color: #475569; }
html.dark-mode .cd-map-legend {
    background: rgba(15,23,42,0.95);
    border-color: #475569;
}
html.dark-mode .cd-map-legend-title,
html.dark-mode .cd-map-legend-item { color: #cbd5e1; }
html.dark-mode .cd-progress-bg { background: #334155; }

/* Map */
.cd-map-wrap { position: relative; border-radius: 8px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); }
#city-map { height: 420px; width: 100%; background: #e5e7eb; }
.cd-map-legend {
    position: absolute; bottom: 16px; right: 16px;
    background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
    padding: 12px 16px; border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); z-index: 400;
}
.cd-map-legend-title { font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #4b5563; margin-bottom: 8px; }
.cd-map-legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #4b5563; margin-bottom: 6px; }
.cd-map-legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* Status Badges */
.cd-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; }
.cd-status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.cd-status-planning { background: #fef3c7; color: #b45309; }
.cd-status-ongoing { background: #dbeafe; color: #1d4ed8; }
.cd-status-on-hold { background: #fee2e2; color: #b91c1c; }
.cd-status-completed { background: #d1fae5; color: #047857; }
.cd-status-cancelled { background: #f3f4f6; color: #4b5563; }

/* Project List */
.cd-projects { display: flex; flex-direction: column; gap: 12px; }
.cd-project {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 16px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.06);
    background: #f8fafc; transition: all 0.2s ease;
}
.cd-project:hover { background: #ffffff; border-color: rgba(0,0,0,0.12); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); transform: translateY(-1px); }
.cd-project-avatar {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.875rem; font-weight: 800; color: white; flex-shrink: 0;
}
.cd-project-info { flex: 1; min-width: 0; }
.cd-project-title { font-size: 0.9375rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cd-project-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.cd-project-progress { margin-top: 10px; }
.cd-progress-bg { height: 6px; background: #e5e7eb; border-radius: 100px; overflow: hidden; }
.cd-progress-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%); }
.cd-project-action {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.06); background: #ffffff;
    color: #4b5563; transition: all 0.2s ease; flex-shrink: 0;
}
.cd-project-action:hover { background: #4f46e5; color: white; border-color: #4f46e5; }

/* Empty State */
.cd-empty { text-align: center; padding: 48px 24px; }
.cd-empty-icon { width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 16px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; }
.cd-empty h4 { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
.cd-empty p { font-size: 0.875rem; color: #9ca3af; }

/* Animations */
@keyframes cdFadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.cd-animate { animation: cdFadeUp 0.5s ease forwards; opacity: 0; }
.cd-animate:nth-child(1) { animation-delay: 0.05s; }
.cd-animate:nth-child(2) { animation-delay: 0.1s; }
.cd-animate:nth-child(3) { animation-delay: 0.15s; }
.cd-animate:nth-child(4) { animation-delay: 0.2s; }
@media (prefers-reduced-motion: reduce) { .cd-animate { animation: none; opacity: 1; } }
</style>

<div class="cd-dashboard">
    <!-- HERO -->
    <div class="cd-hero cd-animate">
        <div class="cd-hero-content">
            <div class="cd-hero-eyebrow">City Operations</div>
            <h1 class="cd-hero-title">City Official Dashboard</h1>
            <p class="cd-hero-subtitle">A focused view of {{ $cityName }} projects, locations, and current delivery progress.</p>
            <div class="cd-hero-meta">
                <span class="cd-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    {{ $cityName }}
                </span>
                <span class="cd-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $stats['ongoing'] ?? 0 }} Active
                </span>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="cd-stats">
        <div class="cd-stat cd-animate" style="--stat-accent: #3b82f6; --stat-icon-bg: #dbeafe; --stat-icon-color: #2563eb;">
            <div class="cd-stat-header">
                <div class="cd-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
            </div>
            <div class="cd-stat-label">Total Projects</div>
            <div class="cd-stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
            <div class="cd-stat-footer">In {{ $cityName }}</div>
        </div>

        <div class="cd-stat cd-animate" style="--stat-accent: #f59e0b; --stat-icon-bg: #fef3c7; --stat-icon-color: #d97706;">
            <div class="cd-stat-header">
                <div class="cd-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L6.75 3.75l8.586 8.986M12.75 3.75h5.695l-2.659 2.849m-2.048 2.194L17.25 12.75l-4.518 4.518"/></svg>
                </div>
            </div>
            <div class="cd-stat-label">Ongoing Projects</div>
            <div class="cd-stat-value">{{ $stats['ongoing'] ?? 0 }}</div>
            <div class="cd-stat-footer">Currently active</div>
        </div>

        <div class="cd-stat cd-animate" style="--stat-accent: #10b981; --stat-icon-bg: #d1fae5; --stat-icon-color: #047857;">
            <div class="cd-stat-header">
                <div class="cd-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="cd-stat-label">Completed Projects</div>
            <div class="cd-stat-value">{{ $stats['completed'] ?? 0 }}</div>
            <div class="cd-stat-footer">Successfully delivered</div>
        </div>

        <div class="cd-stat cd-animate" style="--stat-accent: #f43f5e; --stat-icon-bg: #ffe4e6; --stat-icon-color: #be123c;">
            <div class="cd-stat-header">
                <div class="cd-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="cd-stat-label">Budget Allocated</div>
            <div class="cd-stat-value" title="₱{{ number_format($budgetAllocated, 0) }}">{{ $budgetDisplay }}</div>
            <div class="cd-stat-footer">City budget</div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="cd-main">
        <!-- MAP -->
        <div class="cd-card cd-animate">
            <div class="cd-card-header">
                <div class="cd-card-title-wrap">
                    <div class="cd-card-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <div class="cd-card-title">{{ $cityName }} Project Locations</div>
                        <div class="cd-card-subtitle">Geographic distribution of projects</div>
                    </div>
                </div>
                <a href="{{ route('city.map.index') }}" class="cd-card-action">
                    Full Map
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="cd-card-body">
                <div class="cd-map-wrap">
                    <div id="city-map"></div>
                    <div class="cd-map-legend">
                        <div class="cd-map-legend-title">Project Status</div>
                        <div class="cd-map-legend-item"><span class="cd-map-legend-dot" style="background:#fbbf24"></span> Planning</div>
                        <div class="cd-map-legend-item"><span class="cd-map-legend-dot" style="background:#3b82f6"></span> On Going</div>
                        <div class="cd-map-legend-item"><span class="cd-map-legend-dot" style="background:#ef4444"></span> On Hold</div>
                        <div class="cd-map-legend-item"><span class="cd-map-legend-dot" style="background:#10b981"></span> Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT PROJECTS -->
        <div class="cd-card cd-animate">
            <div class="cd-card-header">
                <div class="cd-card-title-wrap">
                    <div class="cd-card-icon teal">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    </div>
                    <div>
                        <div class="cd-card-title">Recent Projects</div>
                        <div class="cd-card-subtitle">Latest city project activity</div>
                    </div>
                </div>
                <a href="{{ route('city.projects.index') }}" class="cd-card-action">
                    View All
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="cd-card-body">
                @if ($recentProjects->isEmpty())
                    <div class="cd-empty">
                        <div class="cd-empty-icon">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <h4>No projects yet</h4>
                        <p>No projects have been recorded for this city.</p>
                    </div>
                @else
                    <div class="cd-projects">
                        @foreach ($recentProjects as $project)
                            @php
                                $statusClass = match($project->current_status) {
                                    'Planning' => 'cd-status-planning',
                                    'On Going' => 'cd-status-ongoing',
                                    'On Hold' => 'cd-status-on-hold',
                                    'Completed' => 'cd-status-completed',
                                    'Cancelled' => 'cd-status-cancelled',
                                    default => 'cd-status-planning',
                                };
                                $progress = match($project->current_status) {
                                    'Planning' => 10,
                                    'On Going' => 45,
                                    'On Hold' => 30,
                                    'Completed' => 100,
                                    'Cancelled' => 0,
                                    default => 0,
                                };
                                $initials = collect(explode(' ', $project->project_name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                                $avatarGradient = match($loop->index % 4) {
                                    0 => 'linear-gradient(135deg, #0f172a 0%, #1e293b 30%, #3730a3 70%, #6366f1 100%)',
                                    1 => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                    2 => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                                    3 => 'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)',
                                };
                            @endphp
                            <div class="cd-project">
                                <div class="cd-project-avatar" style="background: {{ $avatarGradient }}">{{ $initials }}</div>
                                <div class="cd-project-info">
                                    <div class="cd-project-title">{{ $project->project_name }}</div>
                                    <div class="cd-project-meta">
                                        <span class="cd-status {{ $statusClass }}">{{ $project->current_status }}</span>
                                    </div>
                                    <div class="cd-project-progress">
                                        <div class="cd-progress-bg">
                                            <div class="cd-progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('city.projects.show', $project->project_id) }}" class="cd-project-action" title="View project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
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

                const map = L.map('city-map', {
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
