@extends('layouts.department')

@section('content')
@php
    $budgetAllocated = (float) ($stats['budget_allocated'] ?? 0);
    $budgetDisplay = $budgetAllocated >= 1000000000
        ? '₱' . number_format($budgetAllocated / 1000000000, 1) . 'B'
        : ($budgetAllocated >= 1000000 ? '₱' . number_format($budgetAllocated / 1000000, 1) . 'M' : '₱' . number_format($budgetAllocated, 0));
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
    :root {
        --dept-bg: #f8f7f5;
        --dept-surface: #ffffff;
        --dept-ink: #1e1b4b;
        --dept-muted: #9ca3af;
        --dept-line: rgba(0,0,0,0.06);
        --dept-accent: #f59e0b;
        --dept-accent-dark: #d97706;
        --dept-blue: #d97706;
        --dept-emerald: #10b981;
        --dept-rose: #f43f5e;
    }

    .dept-dashboard { max-width: 1400px; margin: 0 auto; padding: 24px; }
    @media (min-width: 640px) { .dept-dashboard { padding: 32px; } }
    @media (min-width: 1024px) { .dept-dashboard { padding: 40px; } }

    /* Hero */
    .dept-hero {
        position: relative;
        border-radius: 20px;
        padding: 36px 40px;
        background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        margin-bottom: 24px;
        overflow: hidden;
    }
    @media (min-width: 640px) { .dept-hero { padding: 44px 48px; } }
    .dept-hero::before {
        content: ""; position: absolute; inset: 0;
        background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.5;
        pointer-events: none;
    }
    .dept-hero::after {
        content: ""; position: absolute; top: -50%; right: -10%;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 60%);
        pointer-events: none;
    }
    html.dark-mode .dept-hero {
        background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%) !important;
    }
    .dept-hero-content { position: relative; z-index: 1; }
    .dept-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.15em; color: #fbbf24; margin-bottom: 12px;
    }
    .dept-hero-eyebrow::before {
        content: ""; display: block; width: 8px; height: 8px;
        border-radius: 50%; background: #fbbf24;
        box-shadow: 0 0 0 4px rgba(251,191,36,0.25);
    }
    .dept-hero-title {
        font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 800;
        color: white; line-height: 1.15; letter-spacing: -0.03em; margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .dept-hero-subtitle {
        font-size: 1rem;
        color: rgba(255,255,255,0.65); max-width: 620px; line-height: 1.6;
    }
    .dept-hero-meta { display: flex; align-items: center; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
    .dept-hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; background: rgba(255,255,255,0.1);
        backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);
        border-radius: 100px; font-size: 0.75rem; font-weight: 600;
        color: rgba(255,255,255,0.9);
    }

    /* Quick Actions */
    .dept-quick-actions { display: flex; gap: 12px; margin-bottom: 24px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .dept-quick-actions::-webkit-scrollbar { display: none; }
    .dept-quick-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; background: var(--dept-surface);
        border: 1px solid var(--dept-line); border-radius: 100px;
        font-size: 0.8125rem; font-weight: 600; color: var(--dept-ink);
        white-space: nowrap; transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .dept-quick-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border-color: rgba(0,0,0,0.1); }

    /* Stats */
    .dept-stats { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
    @media (min-width: 640px) { .dept-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .dept-stats { grid-template-columns: repeat(4, 1fr); } }

    .dept-stat {
        position: relative; background: var(--dept-surface);
        border-radius: 12px; padding: 24px; border: 1px solid var(--dept-line);
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        transition: all 0.2s ease; overflow: hidden;
    }
    .dept-stat::before {
        content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: var(--stat-accent, var(--dept-accent)); opacity: 0; transition: opacity 0.2s;
    }
    .dept-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); }
    .dept-stat:hover::before { opacity: 1; }
    .dept-stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
    .dept-stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: var(--stat-icon-bg, #fef3c7); color: var(--stat-icon-color, #d97706);
    }
    .dept-stat-trend {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 8px; border-radius: 100px; font-size: 0.6875rem; font-weight: 700;
        background: var(--trend-bg, #d1fae5); color: var(--trend-color, #047857);
    }
    .dept-stat-trend.negative { --trend-bg: #ffe4e6; --trend-color: #be123c; }
    .dept-stat-label { font-size: 0.8125rem; font-weight: 500; color: var(--dept-muted); margin-bottom: 8px; }
    .dept-stat-value { font-size: 1.875rem; font-weight: 800; color: var(--dept-ink); letter-spacing: -0.02em; line-height: 1; }
    .dept-stat-footer { margin-top: 12px; font-size: 0.75rem; color: var(--dept-muted); }

    /* Main Grid */
    .dept-main { display: grid; grid-template-columns: 1fr; gap: 24px; margin-bottom: 24px; }
    @media (min-width: 1024px) { .dept-main { grid-template-columns: 1.2fr 0.8fr; } }

    /* Card */
    .dept-card { background: var(--dept-surface); border-radius: 12px; border: 1px solid var(--dept-line); box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); overflow: hidden; }
    .dept-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid var(--dept-line); }
    .dept-card-title-wrap { display: flex; align-items: center; gap: 12px; }
    .dept-card-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: #fef3c7; color: #d97706;
    }
    .dept-card-icon.green { background: #d1fae5; color: #047857; }
    .dept-card-title { font-size: 1rem; font-weight: 700; color: var(--dept-ink); line-height: 1.3; }
    .dept-card-subtitle { font-size: 0.75rem; color: var(--dept-muted); margin-top: 2px; }
    .dept-card-action { font-size: 0.8125rem; font-weight: 600; color: var(--dept-blue); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: gap 0.2s; }
    .dept-card-action:hover { gap: 8px; }
    .dept-card-body { padding: 20px 24px; }

    /* Map */
    .dept-map-wrap { position: relative; border-radius: 8px; overflow: hidden; border: 1px solid var(--dept-line); }
    #department-map { height: 420px; width: 100%; background: #e5e7eb; }
    .dept-map-legend {
        position: absolute; bottom: 16px; right: 16px;
        background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
        padding: 12px 16px; border-radius: 8px;
        border: 1px solid var(--dept-line); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); z-index: 400;
    }
    .dept-map-legend-title { font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #4b5563; margin-bottom: 8px; }
    .dept-map-legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #4b5563; margin-bottom: 6px; }
    .dept-map-legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    /* Status Badges */
    .dept-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; }
    .dept-status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .dept-status-planning { background: #fef3c7; color: #b45309; }
    .dept-status-ongoing { background: #dbeafe; color: #1d4ed8; }
    .dept-status-on-hold { background: #fee2e2; color: #b91c1c; }
    .dept-status-completed { background: #d1fae5; color: #047857; }
    .dept-status-cancelled { background: #f3f4f6; color: #4b5563; }

    /* Project List */
    .dept-projects { display: flex; flex-direction: column; gap: 12px; }
    .dept-project {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 16px; border-radius: 8px; border: 1px solid var(--dept-line);
        background: #fafaf9; transition: all 0.2s ease;
    }
    .dept-project:hover { background: var(--dept-surface); border-color: rgba(0,0,0,0.12); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); transform: translateY(-1px); }
    .dept-project-avatar {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.875rem; font-weight: 800; color: white; flex-shrink: 0;
    }
    .dept-project-info { flex: 1; min-width: 0; }
    .dept-project-title { font-size: 0.9375rem; font-weight: 700; color: var(--dept-ink); margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dept-project-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .dept-project-meta-item { display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; color: var(--dept-muted); }
    .dept-project-progress { margin-top: 10px; }
    .dept-progress-bg { height: 6px; background: #e5e7eb; border-radius: 100px; overflow: hidden; }
    .dept-progress-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--dept-accent) 0%, var(--dept-accent-dark) 100%); }
    .dept-project-action {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 10px;
        border: 1px solid var(--dept-line); background: var(--dept-surface);
        color: #4b5563; transition: all 0.2s ease; flex-shrink: 0;
    }
    .dept-project-action:hover { background: var(--dept-blue); color: white; border-color: var(--dept-blue); }

    /* Empty State */
    .dept-empty { text-align: center; padding: 48px 24px; }
    .dept-empty-icon { width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 16px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; }
    .dept-empty h4 { font-size: 1rem; font-weight: 700; color: var(--dept-ink); margin-bottom: 4px; }
    .dept-empty p { font-size: 0.875rem; color: var(--dept-muted); }

    /* Animations */
    @keyframes deptFadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .dept-animate { animation: deptFadeUp 0.5s ease forwards; opacity: 0; }
    .dept-animate:nth-child(1) { animation-delay: 0.05s; }
    .dept-animate:nth-child(2) { animation-delay: 0.1s; }
    .dept-animate:nth-child(3) { animation-delay: 0.15s; }
    .dept-animate:nth-child(4) { animation-delay: 0.2s; }
    @media (prefers-reduced-motion: reduce) { .dept-animate { animation: none; opacity: 1; } }
</style>

<div class="dept-dashboard">
    <!-- HERO -->
    <div class="dept-hero dept-animate">
        <div class="dept-hero-content">
            <div class="dept-hero-eyebrow">Department Workspace</div>
            <h1 class="dept-hero-title">Department Dashboard</h1>
            <p class="dept-hero-subtitle">A focused view of your projects, locations, and current delivery progress across the City of Cabuyao.</p>
            <div class="dept-hero-meta">
                <span class="dept-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Last updated: Just now
                </span>
                <span class="dept-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    3 Projects need attention
                </span>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="dept-quick-actions dept-animate">
        <a href="{{ route('department.projects.create') }}" class="dept-quick-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Project
        </a>
        <a href="{{ route('department.reports.index') }}" class="dept-quick-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            Generate Report
        </a>
        <a href="{{ route('department.map.index') }}" class="dept-quick-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            Open Map View
        </a>
        <button type="button" onclick="window.print()" class="dept-quick-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M3 16.5l4.5-4.5M3 16.5l3.75-8.25M21 16.5l-4.5-4.5M21 16.5l-3.75-8.25m-15 0l7.5-4.5m0 0l7.5 4.5m-7.5-4.5v9"/></svg>
            Export Data
        </button>
    </div>

    <!-- STATS -->
    <div class="dept-stats">
        <!-- Total Projects -->
        <div class="dept-stat dept-animate" style="--stat-accent: #d97706; --stat-icon-bg: #fef3c7; --stat-icon-color: #d97706;">
            <div class="dept-stat-header">
                <div class="dept-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
                <span class="dept-stat-trend">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                    +12%
                </span>
            </div>
            <div class="dept-stat-label">Total Projects</div>
            <div class="dept-stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
            <div class="dept-stat-footer">Across all barangays</div>
        </div>

        <!-- Ongoing -->
        <div class="dept-stat dept-animate" style="--stat-accent: #f59e0b; --stat-icon-bg: #fef3c7; --stat-icon-color: #d97706;">
            <div class="dept-stat-header">
                <div class="dept-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L6.75 3.75l8.586 8.986M12.75 3.75h5.695l-2.659 2.849m-2.048 2.194L17.25 12.75l-4.518 4.518"/></svg>
                </div>
                <span class="dept-stat-trend">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                    +5%
                </span>
            </div>
            <div class="dept-stat-label">Ongoing Projects</div>
            <div class="dept-stat-value">{{ $stats['ongoing'] ?? 0 }}</div>
            <div class="dept-stat-footer">Active this quarter</div>
        </div>

        <!-- Completed -->
        <div class="dept-stat dept-animate" style="--stat-accent: #10b981; --stat-icon-bg: #d1fae5; --stat-icon-color: #047857;">
            <div class="dept-stat-header">
                <div class="dept-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="dept-stat-trend">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"/></svg>
                    +8%
                </span>
            </div>
            <div class="dept-stat-label">Completed Projects</div>
            <div class="dept-stat-value">{{ $stats['completed'] ?? 0 }}</div>
            <div class="dept-stat-footer">Successfully delivered</div>
        </div>

        <!-- Budget -->
        <div class="dept-stat dept-animate" style="--stat-accent: #f43f5e; --stat-icon-bg: #ffe4e6; --stat-icon-color: #be123c;">
            <div class="dept-stat-header">
                <div class="dept-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="dept-stat-trend negative">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"/></svg>
                    -2%
                </span>
            </div>
            <div class="dept-stat-label">Budget Allocated</div>
            <div class="dept-stat-value budget" title="₱{{ number_format($budgetAllocated, 0) }}">{{ $budgetDisplay }}</div>
            <div class="dept-stat-footer">Department budget</div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="dept-main">
        <!-- MAP -->
        <div class="dept-card dept-animate">
            <div class="dept-card-header">
                <div class="dept-card-title-wrap">
                    <div class="dept-card-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <div class="dept-card-title">Project Locations</div>
                        <div class="dept-card-subtitle">Geographic distribution across Cabuyao</div>
                    </div>
                </div>
                <a href="{{ route('department.map.index') }}" class="dept-card-action">
                    Full Map
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="dept-card-body">
                <div class="dept-map-wrap">
                    <div id="department-map"></div>
                    <div class="dept-map-legend">
                        <div class="dept-map-legend-title">Project Status</div>
                        <div class="dept-map-legend-item"><span class="dept-map-legend-dot" style="background:#fbbf24"></span> Planning</div>
                        <div class="dept-map-legend-item"><span class="dept-map-legend-dot" style="background:#3b82f6"></span> On Going</div>
                        <div class="dept-map-legend-item"><span class="dept-map-legend-dot" style="background:#ef4444"></span> On Hold</div>
                        <div class="dept-map-legend-item"><span class="dept-map-legend-dot" style="background:#10b981"></span> Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT PROJECTS -->
        <div class="dept-card dept-animate">
            <div class="dept-card-header">
                <div class="dept-card-title-wrap">
                    <div class="dept-card-icon green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    </div>
                    <div>
                        <div class="dept-card-title">Recent Projects</div>
                        <div class="dept-card-subtitle">Latest department project activity</div>
                    </div>
                </div>
                <a href="{{ route('department.projects.index') }}" class="dept-card-action">
                    View All
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="dept-card-body">
                @if ($recentProjects->isEmpty())
                    <div class="dept-empty">
                        <div class="dept-empty-icon">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <h4>No projects yet</h4>
                        <p>Get started by creating your first department project.</p>
                    </div>
                @else
                    <div class="dept-projects">
                        @foreach ($recentProjects as $project)
                            @php
                                $statusClass = match($project->current_status) {
                                    'Planning' => 'dept-status-planning',
                                    'On Going' => 'dept-status-ongoing',
                                    'On Hold' => 'dept-status-on-hold',
                                    'Completed' => 'dept-status-completed',
                                    'Cancelled' => 'dept-status-cancelled',
                                    default => 'dept-status-planning',
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
                                    0 => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                    1 => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                    2 => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                                    3 => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
                                };
                            @endphp
                            <div class="dept-project">
                                <div class="dept-project-avatar" style="background: {{ $avatarGradient }}">{{ $initials }}</div>
                                <div class="dept-project-info">
                                    <div class="dept-project-title">{{ $project->project_name }}</div>
                                    <div class="dept-project-meta">
                                        <span class="dept-status {{ $statusClass }}">{{ $project->current_status }}</span>
                                        <span class="dept-project-meta-item">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            {{ $project->barangay?->name ?? 'N/A' }}
                                        </span>
                                        <span class="dept-project-meta-item">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            ₱{{ number_format($project->budget ?? 0) }}
                                        </span>
                                    </div>
                                    <div class="dept-project-progress">
                                        <div class="dept-progress-bg">
                                            <div class="dept-progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('department.projects.show', $project->project_id) }}" class="dept-project-action" title="View project">
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

            const map = L.map('department-map', {
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
                    color: '#d97706',
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
                                'Proposed': '#fbbf24',
                                'Planning': '#fbbf24',
                                'For bidding': '#3b82f6',
                                'Bidding ongoing': '#3b82f6',
                                'Bidding - Success': '#3b82f6',
                                'Award of contract': '#3b82f6',
                                'Implementation': '#3b82f6',
                                'On Going': '#3b82f6',
                                'On Hold': '#ef4444',
                                'Completed': '#10b981',
                                'Cancelled': '#6b7280',
                                'Bidding - Failed': '#ef4444',
                                'Procurement': '#3b82f6'
                            };
                            return L.circleMarker(latlng, {
                                radius: 8,
                                fillColor: statusColor[feature.properties.status] || '#9CA3AF',
                                color: '#fff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.85
                            });
                        },
                        onEachFeature: function(feature, layer) {
                            const props = feature.properties;
                            layer.bindPopup(`
                                <div style="font-family: Inter, sans-serif; min-width: 180px;">
                                    <h4 style="margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #1e1b4b;">${props.name}</h4>
                                    <p style="margin: 0 0 4px; font-size: 12px; color: #6b7280;"><strong>Status:</strong> ${props.status}</p>
                                    <p style="margin: 0 0 4px; font-size: 12px; color: #6b7280;"><strong>Barangay:</strong> ${props.barangay}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;"><strong>Budget:</strong> ₱${parseInt(props.budget).toLocaleString()}</p>
                                    <a href="${props.url}" style="display:inline-block;margin-top:8px;font-size:12px;color:#3b82f6;font-weight:600;text-decoration:none;">View Details →</a>
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