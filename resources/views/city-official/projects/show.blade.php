@extends('layouts.city')

@section('content')
@php
    $startDate = \Carbon\Carbon::parse($project->start_date);
    $endDate = \Carbon\Carbon::parse($project->target_end_date);
    $today = \Carbon\Carbon::today();
    $totalDays = $startDate->diffInDays($endDate);
    $daysElapsed = $startDate->diffInDays($today);
    $timelineProgress = ($totalDays > 0) ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
    $reportedProgress = $project->latestUpdate?->progress_percentage;
    $progress = $reportedProgress !== null ? $reportedProgress : $timelineProgress;
@endphp

<style>
/* ===== CITY PROJECT SHOW - RICH REDESIGN ===== */
.cs-wrap {
    --cs-bg: #f8f7f5;
    --cs-surface: #ffffff;
    --cs-raised: #fafaf9;
    --cs-ink: #0f172a;
    --cs-ink-secondary: #374151;
    --cs-muted: #6b7280;
    --cs-line: rgba(0,0,0,0.06);
    --cs-line-strong: rgba(0,0,0,0.12);
    --cs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --cs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --cs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --cs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --cs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --cs-radius: 16px;
    --cs-radius-sm: 12px;
    --cs-radius-xs: 10px;
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .cs-wrap {
    --cs-bg: #0f0e1a;
    --cs-surface: #1a1929;
    --cs-raised: #222136;
    --cs-ink: #f8fafc;
    --cs-ink-secondary: #cbd5e1;
    --cs-muted: #64748b;
    --cs-line: rgba(255,255,255,0.06);
    --cs-line-strong: rgba(255,255,255,0.12);
    --cs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --cs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
    --cs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --cs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    --cs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.6), 0 8px 10px -6px rgb(0 0 0 / 0.5);
}
html.dark-mode .cs-wrap {
    --cs-bg: #0f0e1a;
    --cs-surface: #1a1929;
    --cs-raised: #222136;
    --cs-ink: #f8fafc;
    --cs-ink-secondary: #cbd5e1;
    --cs-muted: #64748b;
    --cs-line: rgba(255,255,255,0.06);
    --cs-line-strong: rgba(255,255,255,0.12);
    --cs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --cs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
    --cs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --cs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    --cs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.6), 0 8px 10px -6px rgb(0 0 0 / 0.5);
}

.cs-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
    background: var(--cs-bg);
    color: var(--cs-ink);
    transition: background 0.3s, color 0.3s;
}
html:not(.dark-mode) body:has(.cs-wrap) { background: #f8f7f5 !important; }
html:not(.dark-mode) .cs-wrap { background: #f8f7f5; }
html.dark-mode body:has(.cs-wrap) { background: #0f0e1a !important; }
@media (min-width: 640px) { .cs-wrap { padding: 32px; } }
@media (min-width: 1024px) { .cs-wrap { padding: 40px; } }

/* ===== HERO ===== */
.cs-hero {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 30%, #3730a3 70%, #6366f1 100%);
    border-radius: var(--cs-radius);
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--cs-shadow-xl);
    overflow: hidden;
}
@media (min-width: 640px) { .cs-hero { padding: 40px; } }
.cs-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(129,140,248,0.16) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(199,210,254,0.12) 0%, transparent 40%);
    pointer-events: none;
}
.cs-hero::after {
    content: "";
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(129,140,248,0.24) 0%, transparent 60%);
    pointer-events: none;
}
.cs-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.cs-hero-content { position: relative; z-index: 1; }
.cs-hero-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.cs-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 100px;
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: rgba(255,255,255,0.9);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.cs-hero-badge svg { width: 14px; height: 14px; }
.cs-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    letter-spacing: -0.02em;
    margin-bottom: 8px;
}
.cs-hero-subtitle {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.cs-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.cs-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.7; }
.cs-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 100px;
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 700;
    background: rgba(59, 130, 246, 0.25);
    color: #bfdbfe;
    border: 1px solid rgba(59, 130, 246, 0.3);
    backdrop-filter: blur(8px);
}
.cs-status::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #60a5fa;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3);
}

/* ===== MAIN GRID ===== */
.cs-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 24px;
}
@media (min-width: 1024px) {
    .cs-grid { grid-template-columns: 1fr 360px; align-items: start; }
}

/* ===== CARD ===== */
.cs-card {
    background: var(--cs-surface);
    border-radius: var(--cs-radius-sm);
    border: 1px solid var(--cs-line);
    box-shadow: var(--cs-shadow-sm);
    overflow: hidden;
    margin-bottom: 24px;
    transition: background 0.3s, border-color 0.3s;
}
html:not(.dark-mode) .cs-card { background: #fff; border-color: rgba(0,0,0,0.06); box-shadow: 0 1px 2px rgb(0 0 0 / 0.05); }
html:not(.dark-mode) .cs-detail-item,
html:not(.dark-mode) .cs-timeline-content { background: #fafaf9; border-color: rgba(0,0,0,0.06); }
html:not(.dark-mode) .cs-detail-item:hover,
html:not(.dark-mode) .cs-timeline-content:hover { background: #fff; border-color: rgba(0,0,0,0.12); }
html.dark-mode .cs-card { background: #1a1929; border-color: rgba(255,255,255,0.06); }
html.dark-mode .cs-detail-item,
html.dark-mode .cs-timeline-content { background: #222136; border-color: rgba(255,255,255,0.06); }
html.dark-mode .cs-detail-item:hover,
html.dark-mode .cs-timeline-content:hover { background: #29283b; border-color: rgba(255,255,255,0.12); }
.cs-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--cs-line);
}
.cs-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cs-card-icon.blue { background: #dbeafe; color: #2563eb; }
.cs-card-icon.emerald { background: #d1fae5; color: #059669; }
.cs-card-icon.purple { background: #ede9fe; color: #7c3aed; }
.cs-card-icon.amber { background: #fef3c7; color: #b45309; }
.cs-card-icon.rose { background: #ffe4e6; color: #e11d48; }
.cs-card-icon.gray { background: #f3f4f6; color: #4b5563; }
.dark .cs-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .cs-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .cs-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
.dark .cs-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .cs-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
.dark .cs-card-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
html.dark-mode .cs-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
html.dark-mode .cs-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
html.dark-mode .cs-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
html.dark-mode .cs-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .cs-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
html.dark-mode .cs-card-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
.cs-card-header h2 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--cs-ink);
}
.cs-card-header p {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--cs-muted);
    margin-top: 2px;
}
.cs-card-body { padding: 24px; }

/* ===== DETAIL GRID ===== */
.cs-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}
@media (min-width: 640px) {
    .cs-detail-grid { grid-template-columns: 1fr 1fr; }
}
.cs-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border-radius: var(--cs-radius-xs);
    border: 1px solid var(--cs-line);
    background: var(--cs-raised);
    transition: all 0.2s ease;
}
.cs-detail-item:hover {
    background: var(--cs-surface);
    border-color: var(--cs-line-strong);
    box-shadow: var(--cs-shadow-md);
    transform: translateY(-1px);
}
.cs-detail-item.full-width { grid-column: 1 / -1; }
.cs-detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cs-detail-icon svg { width: 20px; height: 20px; }
.cs-detail-icon.blue { background: #dbeafe; color: #2563eb; }
.cs-detail-icon.emerald { background: #d1fae5; color: #059669; }
.cs-detail-icon.amber { background: #fef3c7; color: #b45309; }
.cs-detail-icon.rose { background: #ffe4e6; color: #e11d48; }
.cs-detail-icon.purple { background: #ede9fe; color: #7c3aed; }
.cs-detail-icon.gray { background: #f3f4f6; color: #4b5563; }
.dark .cs-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .cs-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .cs-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .cs-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
.dark .cs-detail-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
.dark .cs-detail-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
html.dark-mode .cs-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
html.dark-mode .cs-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
html.dark-mode .cs-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .cs-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
html.dark-mode .cs-detail-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
html.dark-mode .cs-detail-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
.cs-detail-content { min-width: 0; }
.cs-detail-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--cs-muted);
    margin-bottom: 4px;
}
.cs-detail-value {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--cs-ink);
    line-height: 1.4;
}
.cs-detail-value.muted {
    color: var(--cs-ink-secondary);
    font-weight: 500;
    line-height: 1.6;
}

/* ===== TIMELINE ===== */
.cs-timeline {
    position: relative;
    padding-left: 28px;
}
.cs-timeline::before {
    content: "";
    position: absolute;
    left: 8px;
    top: 4px;
    bottom: 4px;
    width: 2px;
    background: linear-gradient(180deg, #6366f1, #f59e0b);
    border-radius: 2px;
    opacity: 0.3;
}
.cs-timeline-item {
    position: relative;
    padding-bottom: 24px;
}
.cs-timeline-item:last-child { padding-bottom: 0; }
.cs-timeline-dot {
    position: absolute;
    left: -28px;
    top: 2px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--cs-surface);
    border: 3px solid #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.cs-timeline-item.completed .cs-timeline-dot {
    border-color: #10b981;
    background: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
}
.cs-timeline-content {
    background: var(--cs-raised);
    border: 1px solid var(--cs-line);
    border-radius: var(--cs-radius-xs);
    padding: 16px;
    transition: all 0.2s ease;
}
.cs-timeline-content:hover {
    background: var(--cs-surface);
    border-color: var(--cs-line-strong);
    box-shadow: var(--cs-shadow-md);
}
.cs-timeline-date {
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--cs-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.cs-timeline-date svg { width: 14px; height: 14px; }
.cs-timeline-progress {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 100px;
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    background: #fef3c7;
    color: #b45309;
    margin-bottom: 8px;
}
.dark .cs-timeline-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .cs-timeline-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
.cs-timeline-remarks {
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--cs-ink-secondary);
    line-height: 1.5;
}

/* ===== SIDEBAR ===== */
.cs-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
@media (min-width: 1024px) {
    .cs-sidebar { position: sticky; top: 24px; }
}

/* Progress mini */
.cs-progress-mini {
    padding: 20px 24px;
}
.cs-progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.cs-progress-label {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--cs-muted);
}
.cs-progress-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 800;
    color: #d97706;
}
.cs-progress-track {
    height: 8px;
    background: var(--cs-raised);
    border-radius: 100px;
    overflow: hidden;
    border: 1px solid var(--cs-line);
}
.cs-progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.3);
    transition: width 1s ease;
}
.cs-progress-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--cs-muted);
}

/* ===== PROJECT IMAGE ===== */
.cs-image-wrap {
    border-radius: var(--cs-radius-xs);
    overflow: hidden;
    border: 1px solid var(--cs-line);
}
.cs-image-wrap img {
    width: 100%;
    display: block;
    object-fit: cover;
}

/* ===== EMPTY STATE ===== */
.cs-empty {
    text-align: center;
    padding: 40px 24px;
}
.cs-empty-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 12px;
    border-radius: 14px;
    background: #fef3c7;
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dark .cs-empty-icon { background: rgba(251,191,36,0.15); }
html.dark-mode .cs-empty-icon { background: rgba(251,191,36,0.15); }
.cs-empty-icon svg { width: 24px; height: 24px; }
.cs-empty h4 {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--cs-ink);
    margin-bottom: 4px;
}
.cs-empty p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--cs-muted);
}

/* ===== BACK LINK ===== */
.cs-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    background: var(--cs-raised);
    border: 1px solid var(--cs-line);
    color: var(--cs-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-bottom: 16px;
}
.cs-back:hover {
    background: var(--cs-surface);
    border-color: var(--cs-line-strong);
    color: var(--cs-ink);
    transform: translateY(-1px);
    box-shadow: var(--cs-shadow-sm);
}
.cs-back svg { width: 16px; height: 16px; }

/* ===== ANIMATIONS ===== */
@keyframes csFadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.cs-animate {
    animation: csFadeUp 0.4s ease forwards;
    opacity: 0;
}
.cs-animate:nth-child(1) { animation-delay: 0.03s; }
.cs-animate:nth-child(2) { animation-delay: 0.06s; }
.cs-animate:nth-child(3) { animation-delay: 0.09s; }
.cs-animate:nth-child(4) { animation-delay: 0.12s; }
.cs-animate:nth-child(5) { animation-delay: 0.15s; }
.cs-animate:nth-child(6) { animation-delay: 0.18s; }
@media (prefers-reduced-motion: reduce) {
    .cs-animate { animation: none; opacity: 1; }
}
</style>

<div class="cs-wrap">
    <!-- Back Link -->
    <a href="{{ route('city.projects.index') }}" class="cs-back cs-animate">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Projects
    </a>

    <!-- HERO -->
    <div class="cs-hero cs-animate">
        <div class="cs-hero-grid"></div>
        <div class="cs-hero-content">
            <div class="cs-hero-meta">
                <span class="cs-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 3.75h15m-16.5 3.75h15m-16.5 3.75h15"/></svg>
                    {{ $project->project_code }}
                </span>
                <span class="cs-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    {{ $project->project_type ?? 'City Project' }}
                </span>
                <span class="cs-status">{{ $project->current_status }}</span>
            </div>
            <h1 class="cs-hero-title">{{ $project->project_name }}</h1>
            <div class="cs-hero-subtitle">
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    {{ $project->barangay?->barangay_name ?? 'Citywide' }}
                </span>
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ₱{{ number_format($project->approved_budget ?? 0, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="cs-grid">
        <!-- LEFT COLUMN -->
        <div class="cs-main">
            <!-- Project Details -->
            <div class="cs-card cs-animate">
                <div class="cs-card-header">
                    <div class="cs-card-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    <div>
                        <h2>Project Details</h2>
                        <p>Key information about this project.</p>
                    </div>
                </div>
                <div class="cs-card-body">
                    <div class="cs-detail-grid">
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon purple">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Barangay</div>
                                <div class="cs-detail-value">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon blue">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Project Type</div>
                                <div class="cs-detail-value">{{ $project->project_type ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon emerald">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Approved Budget</div>
                                <div class="cs-detail-value">₱{{ number_format($project->approved_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Actual Budget</div>
                                <div class="cs-detail-value">₱{{ number_format($project->actual_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Start Date</div>
                                <div class="cs-detail-value">{{ $project->start_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item">
                            <div class="cs-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Target Completion</div>
                                <div class="cs-detail-value">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item full-width">
                            <div class="cs-detail-icon gray">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Location Description</div>
                                <div class="cs-detail-value muted">{{ $project->location_description ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="cs-detail-item full-width">
                            <div class="cs-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                            <div class="cs-detail-content">
                                <div class="cs-detail-label">Remarks</div>
                                <div class="cs-detail-value muted">{{ $project->remarks ?? 'No remarks' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Updates -->
            <div class="cs-card cs-animate">
                <div class="cs-card-header">
                    <div class="cs-card-icon emerald">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <h2>Project Updates</h2>
                        <p>Timeline of project milestones and progress reports.</p>
                    </div>
                </div>
                <div class="cs-card-body">
                    @if ($project->updates->isEmpty())
                        <div class="cs-empty">
                            <div class="cs-empty-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            </div>
                            <h4>No updates yet</h4>
                            <p>Progress updates will appear here once they are logged.</p>
                        </div>
                    @else
                        <div class="cs-timeline">
                            @foreach ($project->updates as $update)
                                <div class="cs-timeline-item {{ $loop->index < $project->updates->count() - 1 ? 'completed' : '' }}">
                                    <div class="cs-timeline-dot"></div>
                                    <div class="cs-timeline-content">
                                        <div class="cs-timeline-date">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            {{ $update->update_date?->format('M d, Y') ?? '—' }}
                                        </div>
                                        <div class="cs-timeline-progress">{{ $update->progress_percentage ?? '0' }}% Complete</div>
                                        <div class="cs-timeline-remarks">{{ $update->remarks ?? '' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (Sidebar) -->
        <div class="cs-sidebar">
            <!-- Progress Mini Card -->
            <div class="cs-card cs-animate">
                <div class="cs-progress-mini">
                    <div class="cs-progress-header">
                        <span class="cs-progress-label">Overall Progress</span>
                        <span class="cs-progress-value">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="cs-progress-track">
                        <div class="cs-progress-fill" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="cs-progress-footer">
                        <span>Started {{ $project->start_date?->format('M d, Y') ?? '—' }}</span>
                        <span>{{ max(0, $today->diffInDays($endDate, false)) }} days remaining</span>
                    </div>
                </div>
            </div>

            <!-- Project Image -->
            @if($project->project_image)
                <div class="cs-card cs-animate">
                    <div class="cs-card-header">
                        <div class="cs-card-icon purple">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                        <div>
                            <h2>Project Image</h2>
                            <p>Visual documentation</p>
                        </div>
                    </div>
                    <div class="cs-card-body" style="padding-top:0;">
                        <div class="cs-image-wrap">
                            <img src="{{ asset('storage/' . $project->project_image) }}" alt="{{ $project->project_name }}">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
