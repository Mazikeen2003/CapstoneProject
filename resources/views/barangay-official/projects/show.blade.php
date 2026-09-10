@extends('layouts.barangay')

@section('content')
@php
    $startDate = \Carbon\Carbon::parse($project->start_date);
    $endDate = \Carbon\Carbon::parse($project->target_end_date);
    $today = \Carbon\Carbon::today();
    $totalDays = $startDate->diffInDays($endDate);
    $daysElapsed = $startDate->diffInDays($today);
    $timelineProgress = ($totalDays > 0) ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
    $reportedProgress = $project->latestUpdate?->progress_percentage;
    $reportedProgress = $reportedProgress !== null ? min(100, max(0, (float) $reportedProgress)) : null;
@endphp

<style>
/* ===== BARANGAY PROJECT SHOW - RICH REDESIGN ===== */
.bs-wrap {
    --bs-bg: #f8f7f5;
    --bs-surface: #ffffff;
    --bs-raised: #fafaf9;
    --bs-ink: #0f0d1f;
    --bs-ink-secondary: #374151;
    --bs-muted: #6b7280;
    --bs-line: rgba(0,0,0,0.06);
    --bs-line-strong: rgba(0,0,0,0.12);
    --bs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --bs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --bs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --bs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --bs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --bs-radius: 16px;
    --bs-radius-sm: 12px;
    --bs-radius-xs: 10px;
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .bs-wrap {
    --bs-bg: #0f0e1a;
    --bs-surface: #1a1929;
    --bs-raised: #222136;
    --bs-ink: #f8fafc;
    --bs-ink-secondary: #cbd5e1;
    --bs-muted: #64748b;
    --bs-line: rgba(255,255,255,0.06);
    --bs-line-strong: rgba(255,255,255,0.12);
    --bs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --bs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
    --bs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --bs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    --bs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.6), 0 8px 10px -6px rgb(0 0 0 / 0.5);
}
html.dark-mode .bs-wrap {
    --bs-bg: #0f0e1a;
    --bs-surface: #1a1929;
    --bs-raised: #222136;
    --bs-ink: #f8fafc;
    --bs-ink-secondary: #cbd5e1;
    --bs-muted: #64748b;
    --bs-line: rgba(255,255,255,0.06);
    --bs-line-strong: rgba(255,255,255,0.12);
    --bs-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --bs-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
    --bs-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --bs-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    --bs-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.6), 0 8px 10px -6px rgb(0 0 0 / 0.5);
}

.bs-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
    background: var(--bs-bg);
    color: var(--bs-ink);
    transition: background 0.3s, color 0.3s;
}
html:not(.dark-mode) body:has(.bs-wrap) { background: #f8f7f5 !important; }
html:not(.dark-mode) .bs-wrap { background: #f8f7f5; }
html.dark-mode body:has(.bs-wrap) { background: #0f0e1a !important; }
@media (min-width: 640px) { .bs-wrap { padding: 32px; } }
@media (min-width: 1024px) { .bs-wrap { padding: 40px; } }

/* ===== HERO ===== */
.bs-hero {
    position: relative;
    background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
    border-radius: var(--bs-radius);
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--bs-shadow-xl);
    overflow: hidden;
}
@media (min-width: 640px) { .bs-hero { padding: 40px; } }
.bs-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(248,113,113,0.16) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(254,202,202,0.12) 0%, transparent 40%);
    pointer-events: none;
}
.bs-hero::after {
    content: "";
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(248,113,113,0.24) 0%, transparent 60%);
    pointer-events: none;
}
.bs-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.bs-hero-content { position: relative; z-index: 1; }
.bs-hero-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.bs-hero-badge {
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
.bs-hero-badge svg { width: 14px; height: 14px; }
.bs-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.2;
    letter-spacing: -0.02em;
    margin-bottom: 8px;
    overflow-wrap: anywhere;
}
.bs-hero-subtitle {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.bs-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.bs-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.7; }
.bs-status {
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
.bs-status::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #60a5fa;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3);
}

/* ===== MAIN GRID ===== */
.bs-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 24px;
}
.bs-main,
.bs-sidebar,
.bs-card {
    min-width: 0;
}
@media (min-width: 1024px) {
    .bs-grid { grid-template-columns: 1fr 360px; align-items: start; }
}

/* ===== CARD ===== */
.bs-card {
    background: var(--bs-surface);
    border-radius: var(--bs-radius-sm);
    border: 1px solid var(--bs-line);
    box-shadow: var(--bs-shadow-sm);
    overflow: hidden;
    margin-bottom: 24px;
    transition: background 0.3s, border-color 0.3s;
}
html:not(.dark-mode) .bs-card { background: #fff; border-color: rgba(0,0,0,0.06); box-shadow: 0 1px 2px rgb(0 0 0 / 0.05); }
html:not(.dark-mode) .bs-detail-item,
html:not(.dark-mode) .bs-timeline-content { background: #fafaf9; border-color: rgba(0,0,0,0.06); }
html:not(.dark-mode) .bs-detail-item:hover,
html:not(.dark-mode) .bs-timeline-content:hover { background: #fff; border-color: rgba(0,0,0,0.12); }
html.dark-mode .bs-card { background: #1a1929; border-color: rgba(255,255,255,0.06); }
html.dark-mode .bs-detail-item,
html.dark-mode .bs-timeline-content { background: #222136; border-color: rgba(255,255,255,0.06); }
html.dark-mode .bs-detail-item:hover,
html.dark-mode .bs-timeline-content:hover { background: #29283b; border-color: rgba(255,255,255,0.12); }
.bs-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--bs-line);
}
.bs-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bs-card-icon.blue { background: #dbeafe; color: #2563eb; }
.bs-card-icon.emerald { background: #d1fae5; color: #059669; }
.bs-card-icon.purple { background: #ede9fe; color: #7c3aed; }
.bs-card-icon.amber { background: #fef3c7; color: #b45309; }
.bs-card-icon.rose { background: #ffe4e6; color: #e11d48; }
.bs-card-icon.gray { background: #f3f4f6; color: #4b5563; }
.dark .bs-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .bs-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .bs-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
.dark .bs-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .bs-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
.dark .bs-card-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
html.dark-mode .bs-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
html.dark-mode .bs-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
html.dark-mode .bs-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
html.dark-mode .bs-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .bs-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
html.dark-mode .bs-card-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
.bs-card-header h2 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--bs-ink);
}
.bs-card-header p {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--bs-muted);
    margin-top: 2px;
}
.bs-card-header > div:last-child { min-width: 0; overflow-wrap: anywhere; }
.bs-card-body { padding: 24px; }

/* ===== DETAIL GRID ===== */
.bs-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}
@media (min-width: 640px) {
    .bs-detail-grid { grid-template-columns: 1fr 1fr; }
}
.bs-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border-radius: var(--bs-radius-xs);
    border: 1px solid var(--bs-line);
    background: var(--bs-raised);
    transition: all 0.2s ease;
}
.bs-detail-item:hover {
    background: var(--bs-surface);
    border-color: var(--bs-line-strong);
    box-shadow: var(--bs-shadow-md);
    transform: translateY(-1px);
}
.bs-detail-item.full-width { grid-column: 1 / -1; }
.bs-detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bs-detail-icon svg { width: 20px; height: 20px; }
.bs-detail-icon.blue { background: #dbeafe; color: #2563eb; }
.bs-detail-icon.emerald { background: #d1fae5; color: #059669; }
.bs-detail-icon.amber { background: #fef3c7; color: #b45309; }
.bs-detail-icon.rose { background: #ffe4e6; color: #e11d48; }
.bs-detail-icon.purple { background: #ede9fe; color: #7c3aed; }
.bs-detail-icon.gray { background: #f3f4f6; color: #4b5563; }
.dark .bs-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .bs-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .bs-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .bs-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
.dark .bs-detail-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
.dark .bs-detail-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
html.dark-mode .bs-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
html.dark-mode .bs-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
html.dark-mode .bs-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .bs-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
html.dark-mode .bs-detail-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
html.dark-mode .bs-detail-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
.bs-detail-content {
    min-width: 0;
    overflow-wrap: anywhere;
}
.bs-detail-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--bs-muted);
    margin-bottom: 4px;
}
.bs-detail-value {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--bs-ink);
    line-height: 1.4;
    overflow-wrap: anywhere;
    word-break: break-word;
    white-space: normal;
}
.bs-detail-value.muted {
    color: var(--bs-ink-secondary);
    font-weight: 500;
    line-height: 1.6;
}

/* ===== TIMELINE ===== */
.bs-timeline {
    position: relative;
    padding-left: 28px;
}
.bs-timeline::before {
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
.bs-timeline-item {
    position: relative;
    padding-bottom: 24px;
}
.bs-timeline-item:last-child { padding-bottom: 0; }
.bs-timeline-dot {
    position: absolute;
    left: -28px;
    top: 2px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--bs-surface);
    border: 3px solid #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.bs-timeline-item.completed .bs-timeline-dot {
    border-color: #10b981;
    background: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
}
.bs-timeline-content {
    background: var(--bs-raised);
    border: 1px solid var(--bs-line);
    border-radius: var(--bs-radius-xs);
    padding: 16px;
    transition: all 0.2s ease;
}
.bs-timeline-content:hover {
    background: var(--bs-surface);
    border-color: var(--bs-line-strong);
    box-shadow: var(--bs-shadow-md);
}
.bs-timeline-date {
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--bs-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.bs-timeline-date svg { width: 14px; height: 14px; }
.bs-timeline-progress {
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
.dark .bs-timeline-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
html.dark-mode .bs-timeline-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
.bs-timeline-remarks {
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--bs-ink-secondary);
    line-height: 1.5;
    overflow-wrap: anywhere;
}
.bs-timeline-evidence {
    display: grid;
    gap: 10px;
    margin-top: 12px;
}
.bs-timeline-evidence-image,
.bs-timeline-evidence-remarks {
    padding: 10px;
    border: 1px solid var(--bs-line);
    border-radius: var(--bs-radius-xs);
    background: var(--bs-surface);
}
.bs-timeline-evidence-remarks {
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--bs-ink-secondary);
    line-height: 1.5;
    overflow-wrap: anywhere;
}
.bs-timeline-image {
    display: block;
    width: 100%;
    max-height: 360px;
    border-radius: var(--bs-radius-xs);
    object-fit: cover;
    cursor: zoom-in;
}
.bs-image-lightbox {
    position: fixed;
    inset: 0;
    z-index: 100000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(2, 6, 23, .86);
    backdrop-filter: blur(6px);
}
.bs-image-lightbox.is-open { display: flex; }
.bs-image-lightbox-image {
    max-width: min(92vw, 1200px);
    max-height: 84vh;
    border-radius: 10px;
    object-fit: contain;
    transform: scale(1);
    transition: transform .15s ease;
    user-select: none;
}
.bs-image-lightbox-toolbar {
    position: fixed;
    top: 18px;
    right: 18px;
    display: flex;
    gap: 8px;
}
.bs-image-lightbox-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 8px;
    background: rgba(15,23,42,.8);
    color: #fff;
    cursor: pointer;
    font-size: 1.1rem;
}
.bs-image-lightbox-button:hover { background: rgba(51,65,85,.95); }
.bs-project-back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    min-height: 44px;
    padding: 10px 16px;
    border: 1px solid #b91c1c;
    border-radius: 10px;
    background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    color: #fff;
    font-family: var(--font-body);
    font-size: .8125rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px -6px rgba(153,27,27,.45);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.bs-project-back-wrap {
    position: sticky;
    top: 24px;
    z-index: 5;
    width: 100%;
    align-self: stretch;
}
.bs-project-back-button:hover {
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    box-shadow: 0 8px 18px -8px rgba(153,27,27,.7);
    transform: translateY(-1px);
}
.bs-project-back-button:focus-visible {
    outline: 3px solid rgba(220,38,38,.28);
    outline-offset: 2px;
}
.bs-project-back-button svg { width: 16px; height: 16px; }

/* ===== SIDEBAR ===== */
.bs-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
@media (min-width: 1024px) {
    .bs-sidebar { position: sticky; top: 24px; }
}

/* Progress mini */
.bs-progress-mini {
    padding: 20px 24px;
}
.bs-progress-block + .bs-progress-block { margin-top: 18px; }
.bs-progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.bs-progress-label {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--bs-muted);
}
.bs-progress-value {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 800;
    color: #d97706;
}
.bs-progress-track {
    height: 8px;
    background: var(--bs-raised);
    border-radius: 100px;
    overflow: hidden;
    border: 1px solid var(--bs-line);
}
.bs-progress-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.3);
    transition: width 1s ease;
}
.bs-progress-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--bs-muted);
}

/* ===== PROJECT IMAGE ===== */
.bs-image-wrap {
    border-radius: var(--bs-radius-xs);
    overflow: hidden;
    border: 1px solid var(--bs-line);
}
.bs-image-wrap img {
    width: 100%;
    display: block;
    object-fit: cover;
}

/* ===== EMPTY STATE ===== */
.bs-empty {
    text-align: center;
    padding: 40px 24px;
}
.bs-empty-icon {
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
.dark .bs-empty-icon { background: rgba(251,191,36,0.15); }
html.dark-mode .bs-empty-icon { background: rgba(251,191,36,0.15); }
.bs-empty-icon svg { width: 24px; height: 24px; }
.bs-empty h4 {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--bs-ink);
    margin-bottom: 4px;
}
.bs-empty p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bs-muted);
}

/* ===== ANIMATIONS ===== */
@keyframes bsFadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.bs-animate {
    animation: bsFadeUp 0.4s ease forwards;
    opacity: 0;
}
.bs-animate:nth-child(1) { animation-delay: 0.03s; }
.bs-animate:nth-child(2) { animation-delay: 0.06s; }
.bs-animate:nth-child(3) { animation-delay: 0.09s; }
.bs-animate:nth-child(4) { animation-delay: 0.12s; }
.bs-animate:nth-child(5) { animation-delay: 0.15s; }
.bs-animate:nth-child(6) { animation-delay: 0.18s; }
@media (prefers-reduced-motion: reduce) {
    .bs-animate { animation: none; opacity: 1; }
}
</style>

<div class="bs-wrap">
    @include('components.project-stepper', ['project' => $project])

    <!-- HERO -->
    <div class="bs-hero bs-animate">
        <div class="bs-hero-grid"></div>
        <div class="bs-hero-content">
            <div class="bs-hero-meta">
                <span class="bs-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 3.75h15m-16.5 3.75h15m-16.5 3.75h15"/></svg>
                    {{ $project->project_code }}
                </span>
                <span class="bs-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    {{ $project->project_type }}
                </span>
                <span class="bs-status">{{ $project->current_status }}</span>
            </div>
            <h1 class="bs-hero-title">{{ $project->project_name }}</h1>
            <div class="bs-hero-subtitle">
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    {{ $project->barangay->barangay_name ?? 'Unknown' }}
                </span>
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ₱{{ number_format($project->approved_budget ?? 0, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="bs-grid">
        <!-- LEFT COLUMN -->
        <div class="bs-main">
            <!-- Project Details -->
            <div class="bs-card bs-animate">
                <div class="bs-card-header">
                    <div class="bs-card-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <div>
                        <h2>Project Details</h2>
                        <p>Key information about this project.</p>
                    </div>
                </div>
                <div class="bs-card-body">
                    <div class="bs-detail-grid">
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon purple">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Barangay</div>
                                <div class="bs-detail-value">{{ $project->barangay->barangay_name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon blue">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Project Type</div>
                                <div class="bs-detail-value">{{ $project->project_type }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon emerald">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">{{ in_array($project->current_status, ['Award of contract', 'Implementation', 'Completed'], true) ? 'Approved Budget' : 'Proposed Budget' }}</div>
                                <div class="bs-detail-value">₱{{ number_format($project->approved_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Actual Budget</div>
                                <div class="bs-detail-value">₱{{ number_format($project->actual_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Start Date</div>
                                <div class="bs-detail-value">{{ $project->start_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Target End Date</div>
                                <div class="bs-detail-value">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item">
                            <div class="bs-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Actual End Date</div>
                                <div class="bs-detail-value">{{ $project->actual_end_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item full-width">
                            <div class="bs-detail-icon gray">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Location Description</div>
                                <div class="bs-detail-value muted">{{ $project->location_description ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="bs-detail-item full-width">
                            <div class="bs-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                            <div class="bs-detail-content">
                                <div class="bs-detail-label">Remarks</div>
                                <div class="bs-detail-value muted">{{ $project->remarks ?? 'No remarks' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Updates -->
            <div class="bs-card bs-animate">
                <div class="bs-card-header">
                    <div class="bs-card-icon emerald">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <h2>Progress Updates</h2>
                        <p>Timeline of project milestones and progress reports.</p>
                    </div>
                </div>
                <div class="bs-card-body">
                    @if ($project->updates->isEmpty())
                        <div class="bs-empty">
                            <div class="bs-empty-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            </div>
                            <h4>No updates yet</h4>
                            <p>Progress updates will appear here once they are logged.</p>
                        </div>
                    @else
                        <div class="bs-timeline">
                            @php
                                $progressUpdates = $project->updates->sortBy([
                                    ['update_date', 'desc'],
                                    ['update_id', 'desc'],
                                ])->values();
                            @endphp
                            @foreach ($progressUpdates as $update)
                                <div class="bs-timeline-item {{ $loop->index > 0 ? 'completed' : '' }}">
                                    <div class="bs-timeline-dot"></div>
                                    <div class="bs-timeline-content">
                                        <div class="bs-timeline-date">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            {{ $update->update_date?->format('M d, Y') ?? '—' }}
                                        </div>
                                        <div class="bs-timeline-progress">{{ $update->progress_percentage }}% Complete</div>
                                        @if ($update->image_path)
                                            <div class="bs-timeline-evidence">
                                                <div class="bs-timeline-evidence-image">
                                                    <img src="{{ asset('storage/' . $update->image_path) }}" alt="Progress update evidence" class="bs-timeline-image" data-full-image="{{ asset('storage/' . $update->image_path) }}">
                                                </div>
                                                <div class="bs-timeline-evidence-remarks">{{ $update->remarks ?? 'No remarks provided.' }}</div>
                                            </div>
                                        @else
                                            <div class="bs-timeline-remarks">{{ $update->remarks ?? '' }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (Sidebar) -->
        <div class="bs-sidebar">
            <!-- Progress Mini Card -->
            <div class="bs-card bs-animate">
                <div class="bs-progress-mini">
                    <div class="bs-progress-header">
                        <span class="bs-progress-label">Timeline Progress</span>
                        <span class="bs-progress-value">{{ number_format($timelineProgress, 1) }}%</span>
                    </div>
                    <div class="bs-progress-track">
                        <div class="bs-progress-fill" style="width: {{ $timelineProgress }}%"></div>
                    </div>
                    <div class="bs-progress-block">
                        <div class="bs-progress-header">
                            <span class="bs-progress-label">Reported Progress</span>
                            <span class="bs-progress-value">{{ $reportedProgress !== null ? number_format($reportedProgress, 1) . '%' : 'Not reported' }}</span>
                        </div>
                        <div class="bs-progress-track">
                            <div class="bs-progress-fill" style="width: {{ $reportedProgress ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="bs-progress-footer">
                        <span>Started {{ $project->start_date?->format('M d, Y') ?? '—' }}</span>
                        <span>{{ max(0, $today->diffInDays($endDate, false)) }} days remaining</span>
                    </div>
                </div>
            </div>

            <!-- Project Image -->
            @if($project->project_image)
                <div class="bs-card bs-animate">
                    <div class="bs-card-header">
                        <div class="bs-card-icon purple">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                        <div>
                            <h2>Project Image</h2>
                            <p>Visual documentation</p>
                        </div>
                    </div>
                    <div class="bs-card-body" style="padding-top:0;">
                        <div class="bs-image-wrap">
                            <img src="{{ asset('storage/' . $project->project_image) }}" alt="{{ $project->project_name }}">
                        </div>
                    </div>
                </div>
            @endif

            <div class="bs-project-back-wrap">
                <a href="{{ route('barangay.projects.index') }}" class="bs-project-back-button">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bs-image-lightbox" id="barangayProgressImageLightbox" aria-hidden="true">
    <div class="bs-image-lightbox-toolbar">
        <button type="button" class="bs-image-lightbox-button" id="barangayProgressImageZoomOut" aria-label="Zoom out" title="Zoom out">-</button>
        <button type="button" class="bs-image-lightbox-button" id="barangayProgressImageZoomReset" aria-label="Reset zoom" title="Reset zoom">1:1</button>
        <button type="button" class="bs-image-lightbox-button" id="barangayProgressImageZoomIn" aria-label="Zoom in" title="Zoom in">+</button>
        <button type="button" class="bs-image-lightbox-button" id="barangayProgressImageLightboxClose" aria-label="Close image" title="Close">x</button>
    </div>
    <img class="bs-image-lightbox-image" id="barangayProgressImageLightboxImage" alt="Full-size progress evidence">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lightbox = document.getElementById('barangayProgressImageLightbox');
        const lightboxImage = document.getElementById('barangayProgressImageLightboxImage');
        const closeButton = document.getElementById('barangayProgressImageLightboxClose');
        const zoomInButton = document.getElementById('barangayProgressImageZoomIn');
        const zoomOutButton = document.getElementById('barangayProgressImageZoomOut');
        const zoomResetButton = document.getElementById('barangayProgressImageZoomReset');
        let zoom = 1;

        function setZoom(value) {
            zoom = Math.min(4, Math.max(0.5, value));
            lightboxImage.style.transform = 'scale(' + zoom + ')';
        }

        function closeLightbox() {
            lightbox.classList.remove('is-open');
            lightbox.setAttribute('aria-hidden', 'true');
            lightboxImage.removeAttribute('src');
            setZoom(1);
        }

        document.querySelectorAll('.bs-timeline-image').forEach(function (image) {
            image.addEventListener('click', function () {
                lightboxImage.src = image.dataset.fullImage || image.src;
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                setZoom(1);
            });
        });

        closeButton.addEventListener('click', closeLightbox);
        zoomInButton.addEventListener('click', function () { setZoom(zoom + 0.25); });
        zoomOutButton.addEventListener('click', function () { setZoom(zoom - 0.25); });
        zoomResetButton.addEventListener('click', function () { setZoom(1); });
        lightbox.addEventListener('click', function (event) { if (event.target === lightbox) closeLightbox(); });
        lightboxImage.addEventListener('wheel', function (event) {
            event.preventDefault();
            setZoom(zoom + (event.deltaY < 0 ? 0.25 : -0.25));
        }, { passive: false });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && lightbox.classList.contains('is-open')) closeLightbox();
        });
    });
</script>
@endsection