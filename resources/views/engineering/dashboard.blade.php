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
/* ===== ENGINEERING DASHBOARD - DEPT STYLE ===== */
.ed-wrap {
    --ed-bg: #f8f7f5;
    --ed-surface: #ffffff;
    --ed-raised: #fafaf9;
    --ed-hero-start: #0a4353;
    --ed-hero-mid: #11788a;
    --ed-hero-end: #22a6b8;
    --ed-action: #0f6a7c;
    --ed-action-hover: #084c5b;
    --ed-ink: #1e1b4b;
    --ed-ink-secondary: #374151;
    --ed-muted: #9ca3af;
    --ed-line: rgba(0,0,0,0.06);
    --ed-line-strong: rgba(0,0,0,0.12);
    --ed-shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    --ed-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --ed-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --ed-radius-sm: 12px;
    --ed-radius: 16px;
    --ed-radius-xl: 20px;
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: var(--ed-bg);
    color: var(--ed-ink);
    transition: background 0.3s, color 0.3s;
}
@media (min-width: 640px) { .ed-wrap { padding: 32px; } }
@media (min-width: 1024px) { .ed-wrap { padding: 40px; } }

html:not(.dark-mode) body:has(.ed-wrap) { background: #f8f7f5 !important; }
html.dark-mode body:has(.ed-wrap) { background: #0f172a !important; }

.dark .ed-wrap,
html.dark-mode .ed-wrap {
    --ed-bg: #0f172a;
    --ed-surface: #1e293b;
    --ed-raised: #243247;
    --ed-ink: #f8fafc;
    --ed-ink-secondary: #cbd5e1;
    --ed-muted: #64748b;
    --ed-action: #9ee6f7;
    --ed-action-hover: #d8f5ff;
    --ed-line: rgba(148,163,184,0.2);
    --ed-line-strong: rgba(148,163,184,0.35);
    --ed-shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.3);
    --ed-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --ed-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
}

/* ===== HERO ===== */
.ed-hero {
    position: relative;
    border-radius: var(--ed-radius-xl);
    padding: 36px 40px;
    margin-bottom: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--ed-hero-start) 0%, #0c5c70 26%, var(--ed-hero-mid) 62%, var(--ed-hero-end) 100%);
    box-shadow: var(--ed-shadow-lg);
}
@media (min-width: 640px) { .ed-hero { padding: 44px 48px; } }
.ed-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.5;
    pointer-events: none;
}
.ed-hero::after {
    content: "";
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(125,211,252,0.22) 0%, transparent 60%);
    pointer-events: none;
}
.ed-hero-content { position: relative; z-index: 1; }
.ed-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #d8f5ff;
    margin-bottom: 12px;
}
.ed-hero-eyebrow::before {
    content: "";
    display: block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #9ee6f7;
    box-shadow: 0 0 0 4px rgba(158,230,247,0.22);
}
.ed-hero-title {
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: white;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.ed-hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.65);
    max-width: 620px;
    line-height: 1.6;
}
.ed-hero-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 24px;
    flex-wrap: wrap;
}
.ed-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
}
.ed-hero-badge svg { width: 14px; height: 14px; }

/* ===== STATS ===== */
.ed-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
@media (min-width: 640px) { .ed-stats { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .ed-stats { grid-template-columns: repeat(4, 1fr); } }

.ed-stat {
    position: relative;
    background: var(--ed-surface);
    border-radius: var(--ed-radius-sm);
    padding: 24px;
    border: 1px solid var(--ed-line);
    box-shadow: var(--ed-shadow-sm);
    transition: all 0.2s ease;
    overflow: hidden;
}
.ed-stat::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--stat-accent, #d97706);
    opacity: 0;
    transition: opacity 0.2s;
}
.ed-stat:hover { transform: translateY(-2px); box-shadow: var(--ed-shadow-md); }
.ed-stat:hover::before { opacity: 1; }
.ed-stat-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
}
.ed-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--stat-icon-bg, #fef3c7);
    color: var(--stat-icon-color, #d97706);
}
.ed-stat-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--ed-muted);
    margin-bottom: 8px;
}
.ed-stat-value {
    font-size: 1.875rem;
    font-weight: 800;
    color: var(--ed-ink);
    letter-spacing: -0.02em;
    line-height: 1;
}
.ed-stat-footer {
    margin-top: 12px;
    font-size: 0.75rem;
    color: var(--ed-muted);
}

/* ===== MAIN GRID ===== */
.ed-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 24px;
}
@media (min-width: 1024px) { .ed-main { grid-template-columns: 1.2fr 0.8fr; } }

/* ===== CARD ===== */
.ed-card {
    background: var(--ed-surface);
    border-radius: var(--ed-radius-sm);
    border: 1px solid var(--ed-line);
    box-shadow: var(--ed-shadow-sm);
    overflow: hidden;
    transition: background 0.3s, border-color 0.3s;
}
.ed-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--ed-line);
}
.ed-card-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ed-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dff9ff;
    color: #0f6a7c;
}
.ed-card-icon.green { background: #dff9ff; color: #0f6a7c; }
.ed-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ed-ink);
    line-height: 1.3;
}
.ed-card-subtitle {
    font-size: 0.75rem;
    color: var(--ed-muted);
    margin-top: 2px;
}
.ed-card-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--ed-action);
    font-size: 0.8125rem;
    font-weight: 600;
    text-decoration: none;
    transition: gap 0.2s;
}
.ed-card-action:hover { gap: 8px; color: var(--ed-action-hover); }
.ed-card-body { padding: 20px 24px; }

/* ===== MAP ===== */
.ed-map-wrap {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--ed-line);
}
#engineering-map {
    height: 420px;
    width: 100%;
    background: #e5e7eb;
}
.ed-map-legend {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    padding: 12px 16px;
    border-radius: 8px;
    border: 1px solid var(--ed-line);
    box-shadow: var(--ed-shadow-md);
    z-index: 400;
}
.ed-map-legend-title {
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #4b5563;
    margin-bottom: 8px;
}
.ed-map-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    color: #4b5563;
    margin-bottom: 6px;
}
.ed-map-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ===== STATUS BADGES ===== */
.ed-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: capitalize;
}
.ed-status::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}
.ed-status-planning,
.ed-status-proposed { background: rgba(37,99,235,0.10); color: #2563eb; }
.ed-status-bidding { background: rgba(245,158,11,0.10); color: #f59e0b; }
.ed-status-bidding-ongoing { background: rgba(6,182,212,0.10); color: #06b6d4; }
.ed-status-award { background: rgba(139,92,246,0.10); color: #8b5cf6; }
.ed-status-ongoing,
.ed-status-implementation { background: rgba(15,118,110,0.10); color: #0f766e; }
.ed-status-hold { background: rgba(220,38,38,0.10); color: #dc2626; }
.ed-status-completed { background: rgba(22,163,74,0.10); color: #16a34a; }
.ed-status-cancelled { background: rgba(100,116,139,0.10); color: #64748b; }

/* ===== PROJECT LIST ===== */
.ed-projects { display: flex; flex-direction: column; gap: 12px; }
.ed-project {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--ed-line);
    background: var(--ed-raised);
    transition: all 0.2s ease;
}
.ed-project:hover {
    background: var(--ed-surface);
    border-color: var(--ed-line-strong);
    box-shadow: var(--ed-shadow-md);
    transform: translateY(-1px);
}
.ed-project-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 800;
    color: white;
    flex-shrink: 0;
}
.ed-project-info { flex: 1; min-width: 0; }
.ed-project-title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--ed-ink);
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ed-project-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.ed-project-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--ed-muted);
}
.ed-project-meta-item svg { width: 13px; height: 13px; }
.ed-project-progress { margin-top: 10px; }
.ed-progress-bg {
    height: 6px;
    background: #e5e7eb;
    border-radius: 100px;
    overflow: hidden;
}
.ed-progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}
.ed-project-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid var(--ed-line);
    background: var(--ed-surface);
    color: #4b5563;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.ed-project-action:hover {
    background: #d97706;
    color: white;
    border-color: #d97706;
}

/* ===== EMPTY STATE ===== */
.ed-empty {
    text-align: center;
    padding: 48px 24px;
}
.ed-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 16px;
    background: #fef3c7;
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ed-empty h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ed-ink);
    margin-bottom: 4px;
}
.ed-empty p {
    font-size: 0.875rem;
    color: var(--ed-muted);
}

/* ===== PAGINATION ===== */
.ed-pagination {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--ed-line);
}

/* ===== DARK MODE OVERRIDES ===== */
html.dark-mode .ed-status-planning,
html.dark-mode .ed-status-proposed,
.dark .ed-status-planning,
.dark .ed-status-proposed { background: rgba(37,99,235,0.12); color: #60a5fa; }
html.dark-mode .ed-status-bidding,
.dark .ed-status-bidding { background: rgba(245,158,11,0.12); color: #fbbf24; }
html.dark-mode .ed-status-bidding-ongoing,
.dark .ed-status-bidding-ongoing { background: rgba(6,182,212,0.12); color: #67e8f9; }
html.dark-mode .ed-status-award,
.dark .ed-status-award { background: rgba(139,92,246,0.12); color: #a78bfa; }
html.dark-mode .ed-status-ongoing,
html.dark-mode .ed-status-implementation,
.dark .ed-status-ongoing,
.dark .ed-status-implementation { background: rgba(15,118,110,0.12); color: #5eead4; }
html.dark-mode .ed-status-hold,
.dark .ed-status-hold { background: rgba(220,38,38,0.12); color: #f87171; }
html.dark-mode .ed-status-completed,
.dark .ed-status-completed { background: rgba(22,163,74,0.12); color: #4ade80; }
html.dark-mode .ed-status-cancelled,
.dark .ed-status-cancelled { background: rgba(100,116,139,0.12); color: #cbd5e1; }

html.dark-mode .ed-map-legend,
.dark .ed-map-legend {
    background: rgba(15,23,42,0.95);
    border-color: #475569;
}
html.dark-mode .ed-map-legend-title,
html.dark-mode .ed-map-legend-item,
.dark .ed-map-legend-title,
.dark .ed-map-legend-item { color: #cbd5e1; }

html.dark-mode .ed-progress-bg,
.dark .ed-progress-bg { background: #334155; }

/* ===== ANIMATIONS ===== */
@keyframes edFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.ed-animate {
    animation: edFadeUp 0.5s ease forwards;
    opacity: 0;
}
.ed-animate:nth-child(1) { animation-delay: 0.05s; }
.ed-animate:nth-child(2) { animation-delay: 0.1s; }
.ed-animate:nth-child(3) { animation-delay: 0.15s; }
.ed-animate:nth-child(4) { animation-delay: 0.2s; }

@media (prefers-reduced-motion: reduce) {
    .ed-animate { animation: none; opacity: 1; }
}
</style>

<div class="ed-wrap">
    <!-- HERO -->
    <div class="ed-hero ed-animate">
        <div class="ed-hero-content">
            <div class="ed-hero-eyebrow">Engineering Workspace</div>
            <h1 class="ed-hero-title">Engineering Dashboard</h1>
            <p class="ed-hero-subtitle">Review project delivery, track field progress, and monitor citywide implementation health.</p>
            <div class="ed-hero-meta">
                <span class="ed-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Cabuyao City
                </span>
                <span class="ed-hero-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $stats['ongoing'] ?? 0 }} Active
                </span>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="ed-stats">
        <div class="ed-stat ed-animate" style="--stat-accent: #3b82f6; --stat-icon-bg: #dbeafe; --stat-icon-color: #2563eb;">
            <div class="ed-stat-header">
                <div class="ed-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
            </div>
            <div class="ed-stat-label">Total Projects</div>
            <div class="ed-stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
            <div class="ed-stat-footer">Across all barangays</div>
        </div>

        <div class="ed-stat ed-animate" style="--stat-accent: #f59e0b; --stat-icon-bg: #fef3c7; --stat-icon-color: #d97706;">
            <div class="ed-stat-header">
                <div class="ed-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L6.75 3.75l8.586 8.986M12.75 3.75h5.695l-2.659 2.849m-2.048 2.194L17.25 12.75l-4.518 4.518"/></svg>
                </div>
            </div>
            <div class="ed-stat-label">Ongoing Projects</div>
            <div class="ed-stat-value">{{ $stats['ongoing'] ?? 0 }}</div>
            <div class="ed-stat-footer">Currently active</div>
        </div>

        <div class="ed-stat ed-animate" style="--stat-accent: #10b981; --stat-icon-bg: #d1fae5; --stat-icon-color: #047857;">
            <div class="ed-stat-header">
                <div class="ed-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="ed-stat-label">Completed Projects</div>
            <div class="ed-stat-value">{{ $stats['completed'] ?? 0 }}</div>
            <div class="ed-stat-footer">Successfully delivered</div>
        </div>

        <div class="ed-stat ed-animate" style="--stat-accent: #f43f5e; --stat-icon-bg: #ffe4e6; --stat-icon-color: #be123c;">
            <div class="ed-stat-header">
                <div class="ed-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="ed-stat-label">Budget Allocated</div>
            <div class="ed-stat-value" title="₱{{ number_format($budgetAllocated, 0) }}">{{ $budgetDisplay }}</div>
            <div class="ed-stat-footer">Engineering budget</div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="ed-main">
        <!-- MAP -->
        <div class="ed-card ed-animate">
            <div class="ed-card-header">
                <div class="ed-card-title-wrap">
                    <div class="ed-card-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <div class="ed-card-title">Project Locations</div>
                        <div class="ed-card-subtitle">Geographic distribution across Cabuyao</div>
                    </div>
                </div>
                <a href="{{ route('engineering.map.index') }}" class="ed-card-action">
                    Full Map
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="ed-card-body">
                <div class="ed-map-wrap">
                    <div id="engineering-map"></div>
                    <div class="ed-map-legend">
                        <div class="ed-map-legend-title">Project Status</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#2563eb"></span> Proposed</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#f59e0b"></span> For bidding</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#06b6d4"></span> Bidding ongoing</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#8b5cf6"></span> Award of contract</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#0f766e"></span> Implementation</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#16a34a"></span> Completed</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#dc2626"></span> On Hold</div>
                        <div class="ed-map-legend-item"><span class="ed-map-legend-dot" style="background:#64748b"></span> Cancelled</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT PROJECTS -->
        <div class="ed-card ed-animate">
            <div class="ed-card-header">
                <div class="ed-card-title-wrap">
                    <div class="ed-card-icon green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    </div>
                    <div>
                        <div class="ed-card-title">Recent Projects</div>
                        <div class="ed-card-subtitle">Latest engineering project activity</div>
                    </div>
                </div>
                <a href="{{ route('engineering.projects.index') }}" class="ed-card-action">
                    View All
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <div class="ed-card-body">
                @if ($recentProjects->isEmpty())
                    <div class="ed-empty">
                        <div class="ed-empty-icon">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <h4>No projects yet</h4>
                        <p>No engineering projects have been recorded.</p>
                    </div>
                @else
                    <div class="ed-projects">
                        @foreach ($recentProjects as $project)
                            @php
                                $statusClass = match($project->current_status) {
                                    'Planning', 'Proposed' => 'ed-status-planning',
                                    'For bidding', 'Procurement' => 'ed-status-bidding',
                                    'Bidding ongoing' => 'ed-status-bidding-ongoing',
                                    'Award of contract', 'Bidding - Success' => 'ed-status-award',
                                    'On Going', 'Implementation' => 'ed-status-implementation',
                                    'On Hold' => 'ed-status-hold',
                                    'Completed' => 'ed-status-completed',
                                    'Cancelled' => 'ed-status-cancelled',
                                    default => 'ed-status-planning',
                                };
                                $progress = $project->latestUpdate?->progress_percentage ?? 0;
                                $initials = collect(explode(' ', $project->project_name))->map(fn($word) => strtoupper($word[0] ?? ''))->take(2)->implode('');
                                $avatarGradient = match($loop->index % 4) {
                                    0 => 'linear-gradient(135deg, #0a4353 0%, #0c5c70 30%, #11788a 70%, #22a6b8 100%)',
                                    1 => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                                    2 => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                                    3 => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
                                };
                            @endphp
                            <div class="ed-project">
                                <div class="ed-project-avatar" style="background: {{ $avatarGradient }}">{{ $initials }}</div>
                                <div class="ed-project-info">
                                    <div class="ed-project-title">{{ $project->project_name }}</div>
                                    <div class="ed-project-meta">
                                        <span class="ed-status {{ $statusClass }}">{{ $project->current_status }}</span>
                                        <span class="ed-project-meta-item">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            {{ $project->barangay?->barangay_name ?? 'N/A' }}
                                        </span>
                                        <span class="ed-project-meta-item">₱{{ number_format($project->approved_budget ?? 0) }}</span>
                                    </div>
                                    <div class="ed-project-progress">
                                        <div class="ed-progress-bg">
                                            <div class="ed-progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="ed-project-action" title="View project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if ($recentProjects->hasPages())
                        <div class="ed-pagination">{{ $recentProjects->links() }}</div>
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

                fetch('{{ route('api.projects.geojson') }}')
                    .then(r => r.json())
                    .then(function(data) {
                        L.geoJSON(data, {
                            pointToLayer: function(feature, latlng) {
                                const statusColor = {
                                    proposed: '#2563eb',
                                    forbidding: '#f59e0b',
                                    biddingongoing: '#06b6d4',
                                    awardofcontract: '#8b5cf6',
                                    implementation: '#0f766e',
                                    completed: '#16a34a',
                                    onhold: '#dc2626',
                                    cancelled: '#64748b'
                                };
                                const normalizedStatus = String(feature.properties.status || '')
                                    .trim()
                                    .toLowerCase()
                                    .replace(/[\s_-]+/g, '');

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: statusColor[normalizedStatus] || '#64748b',
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
