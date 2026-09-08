@extends('layouts.city')

@section('content')
<style>
/* ===== CITY REPORTS - RICH REDESIGN ===== */
.cr-wrap {
    --cr-bg: #f8f7f5;
    --cr-surface: #ffffff;
    --cr-surface-hover: #fafaf9;
    --cr-ink: #0f172a;
    --cr-ink-secondary: #374151;
    --cr-muted: #9ca3af;
    --cr-line: rgba(0, 0, 0, 0.06);
    --cr-line-strong: rgba(0, 0, 0, 0.12);
    --cr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --cr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --cr-radius-sm: 12px;
    --cr-radius: 16px;
    --cr-radius-xl: 20px;
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: var(--cr-bg);
    color: var(--cr-ink);
    transition: background 0.3s, color 0.3s;
}

@media (min-width: 640px) { .cr-wrap { padding: 32px; } }
@media (min-width: 1024px) { .cr-wrap { padding: 40px; } }

html:not(.dark-mode) body:has(.cr-wrap) { background: #f8f7f5 !important; }
html.dark-mode body:has(.cr-wrap) { background: #0f172a !important; }

html.dark-mode .cr-wrap,
.dark .cr-wrap {
    --cr-bg: #0f172a;
    --cr-surface: #1e293b;
    --cr-surface-hover: #243247;
    --cr-ink: #f8fafc;
    --cr-ink-secondary: #cbd5e1;
    --cr-muted: #64748b;
    --cr-line: rgba(148, 163, 184, 0.2);
    --cr-line-strong: rgba(148, 163, 184, 0.35);
    --cr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    --cr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
}

/* ===== HERO ===== */
.cr-hero {
    position: relative;
    border-radius: var(--cr-radius-xl);
    padding: 36px 28px;
    margin-bottom: 28px;
    overflow: hidden;
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}
@media (min-width: 640px) { .cr-hero { padding: 44px 40px; } }
.cr-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.cr-hero::after {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 60%);
    pointer-events: none;
}
.cr-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.cr-hero-content { position: relative; z-index: 1; }
.cr-hero-badges {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.cr-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 100px;
    background: rgba(99,102,241,0.12);
    border: 1px solid rgba(165,180,252,0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #a5b4fc;
}
.cr-hero-badge svg { width: 14px; height: 14px; }
.cr-hero-badge.secondary {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
}
.cr-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
}
.cr-hero-subtitle {
    font-family: var(--font-body);
    font-size: 1rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.6;
    max-width: 640px;
}

/* ===== HEADER (below hero, for page title row) ===== */
.cr-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 28px;
}
.cr-header-start { display: flex; align-items: flex-start; gap: 16px; }
.cr-header-icon {
    display: flex;
    width: 52px;
    height: 52px;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #4c1d95, #6d28d9);
    color: #fff;
    box-shadow: 0 4px 14px -4px rgba(99,102,241,0.5);
}
.cr-header-icon svg { width: 26px; height: 26px; }
.cr-header-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    line-height: 1.2;
    color: var(--cr-ink);
}
.cr-header-subtitle { margin-top: 4px; font-size: 0.875rem; color: var(--cr-muted); }
.cr-header-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 100px;
    background: var(--cr-surface-hover);
    border: 1px solid var(--cr-line);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--cr-muted);
}

/* ===== ALERT ===== */
.cr-alert {
    padding: 16px 20px;
    border-radius: var(--cr-radius);
    background: rgba(16,185,129,0.06);
    border: 1px solid rgba(16,185,129,0.15);
    color: #059669;
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 24px;
}
.dark .cr-alert,
html.dark-mode .cr-alert {
    background: rgba(16,185,129,0.05);
    border-color: rgba(16,185,129,0.2);
    color: #34d399;
}

/* ===== REPORT CARDS GRID ===== */
.cr-grid {
    display: grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}
@media (min-width: 768px) { .cr-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

/* Target the report card component */
.cr-wrap .dept-report-card {
    min-height: 100%;
    border: 1px solid var(--cr-line);
    border-radius: var(--cr-radius-sm);
    background: var(--cr-surface);
    box-shadow: var(--cr-shadow-sm);
    transition: transform 0.2s, background 0.3s, border-color 0.2s, box-shadow 0.2s;
}
.cr-wrap .dept-report-card:hover {
    border-color: rgba(99,102,241,0.45);
    box-shadow: var(--cr-shadow-md);
    transform: translateY(-2px);
}
.cr-wrap .dept-report-card > div:first-child { background: transparent; }
.cr-wrap .dept-report-card > div:nth-child(2) > div:first-child {
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    color: #fff;
    box-shadow: 0 4px 14px -4px rgba(99,102,241,0.35);
}
.cr-wrap .dept-report-card span.text-amber-600 { color: #6d28d9; }
.cr-wrap .dept-report-card h2 { color: var(--cr-ink); }
.cr-wrap .dept-report-card p { color: var(--cr-muted); }
.cr-wrap .dept-report-card > div:last-child { border-color: var(--cr-line); }
.cr-wrap .dept-report-card a { color: var(--cr-ink); }
.cr-wrap .dept-report-card a:hover { color: #6d28d9; }
.cr-wrap .dept-report-card > div:last-child span { color: var(--cr-muted); }

html.dark-mode .cr-wrap .dept-report-card > div:nth-child(2) > div:first-child,
.dark .cr-wrap .dept-report-card > div:nth-child(2) > div:first-child {
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    color: #fff;
}

/* ===== INFO PANEL ===== */
.cr-info {
    margin-top: 18px;
    padding: 24px;
    border: 1px solid var(--cr-line);
    border-radius: var(--cr-radius-sm);
    background: var(--cr-surface);
    box-shadow: var(--cr-shadow-sm);
    transition: background 0.3s, border-color 0.2s;
}
.cr-info-header { display: flex; align-items: center; gap: 12px; }
.cr-info-icon {
    display: flex;
    width: 36px;
    height: 36px;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
    flex-shrink: 0;
}
.cr-info-icon svg { width: 20px; height: 20px; }
.dark .cr-info-icon,
html.dark-mode .cr-info-icon { background: rgba(16,185,129,0.08); color: #34d399; }
.cr-info h3 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--cr-ink);
}
.cr-info ul {
    display: grid;
    gap: 10px;
    margin-top: 18px;
    color: var(--cr-ink-secondary);
    font-family: var(--font-body);
    font-size: 0.875rem;
    list-style: none;
    padding: 0;
}
@media (min-width: 640px) { .cr-info ul { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
.cr-info li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
}
.cr-info li::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
    margin-top: 7px;
    flex-shrink: 0;
}
.dark .cr-info li::before,
html.dark-mode .cr-info li::before { background: #34d399; }

/* ===== ANIMATIONS ===== */
@keyframes crFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.cr-animate {
    animation: crFadeUp 0.5s ease forwards;
    opacity: 0;
}
.cr-animate:nth-child(1) { animation-delay: 0.04s; }
.cr-animate:nth-child(2) { animation-delay: 0.08s; }
.cr-animate:nth-child(3) { animation-delay: 0.12s; }
.cr-animate:nth-child(4) { animation-delay: 0.16s; }

@media (prefers-reduced-motion: reduce) {
    .cr-animate { animation: none; opacity: 1; }
}
</style>

<div class="cr-wrap">
    <!-- HEADER -->
    <div class="cr-header cr-animate">
        <div class="cr-header-start">
            <div class="cr-header-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V10.5M9.333 19.5V4.5M14.667 19.5v-7M20 19.5V7.5M2.5 19.5h19" />
                </svg>
            </div>
            <div>
                <h1 class="cr-header-title">Reports &amp; Exports</h1>
                <p class="cr-header-subtitle">Generate official citywide project, financial, and compliance reports.</p>
            </div>
        </div>
        <span class="cr-header-badge">City Portal</span>
    </div>

    @if (session('success'))
        <div class="cr-alert cr-animate">
            {{ session('success') }}
        </div>
    @endif

    <!-- REPORT CARDS -->
    <div class="cr-grid cr-animate">
        <x-report-card eyebrow="Citywide overview" title="Citywide Projects" description="Complete list of all projects across all departments with full details." icon="document" :route="route('city.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Citywide budget breakdown by status and barangay with spending analysis." icon="budget" :route="route('city.reports.budget-pdf')" />
        <x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment." icon="document" :route="route('city.reports.sglg-pdf')" />
    </div>

    <!-- INFO PANEL -->
    <div class="cr-info cr-animate">
        <div class="cr-info-header">
            <span class="cr-info-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </span>
            <h3>About These Reports</h3>
        </div>
        <ul>
            <li>Reports show all projects from all departments in the city.</li>
            <li>PDF format is ideal for official distribution and archiving.</li>
            <li>All reports include generation timestamp and your name.</li>
            <li>Formatted for easy printing and sharing.</li>
        </ul>
    </div>
</div>
@endsection
