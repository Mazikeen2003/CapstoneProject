@extends('layouts.department')

@section('content')
<style>
    .dept-report-container {
        --dr-bg: #f8f7f5;
        --dr-surface: #ffffff;
        --dr-surface-hover: #fafaf9;
        --dr-ink: #1e1b4b;
        --dr-ink-secondary: #374151;
        --dr-muted: #9ca3af;
        --dr-line: rgba(0, 0, 0, 0.06);
        --dr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --dr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --dr-radius: 16px;
        --dr-radius-sm: 12px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        background: var(--dr-bg);
        color: var(--dr-ink);
        transition: background 0.3s, color 0.3s;
    }

    @media (min-width: 640px) { .dept-report-container { padding: 32px; } }
    @media (min-width: 1024px) { .dept-report-container { padding: 40px; } }

    html:not(.dark-mode) body:has(.dept-report-container) { background: #f8f7f5 !important; }
    html.dark-mode body:has(.dept-report-container) { background: #0f172a !important; }

    html.dark-mode .dept-report-container,
    .dark .dept-report-container {
        --dr-bg: #0f172a;
        --dr-surface: #1e293b;
        --dr-surface-hover: #243247;
        --dr-ink: #f8fafc;
        --dr-ink-secondary: #cbd5e1;
        --dr-muted: #64748b;
        --dr-line: rgba(148, 163, 184, 0.2);
        --dr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --dr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    }

    .dept-report-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 28px;
    }

    .dept-report-icon {
        display: flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(180, 83, 9, 0.5);
    }

    .dept-report-icon svg { width: 26px; height: 26px; }

    .dept-report-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        line-height: 1.2;
        color: var(--dr-ink);
    }

    .dept-report-subtitle { margin-top: 4px; font-size: 0.875rem; color: var(--dr-muted); }

    @keyframes deptReportFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dept-report-animate {
        opacity: 0;
        animation: deptReportFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    .dept-report-animate:nth-child(1) { animation-delay: 0.05s; }
    .dept-report-animate:nth-child(2) { animation-delay: 0.1s; }
    .dept-report-animate:nth-child(3) { animation-delay: 0.15s; }
    .dept-report-animate:nth-child(4) { animation-delay: 0.2s; }
    @media (prefers-reduced-motion: reduce) {
        .dept-report-animate { animation: none; opacity: 1; }
    }

    .dept-report-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 18px;
    }

    @media (min-width: 1024px) {
        .dept-report-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    .dept-report-container .dept-report-card {
        min-height: 100%;
        border: 1px solid var(--dr-line);
        border-radius: var(--dr-radius-sm);
        background: var(--dr-surface);
        box-shadow: var(--dr-shadow-sm);
        transition: transform 0.2s, background 0.3s, border-color 0.2s, box-shadow 0.2s;
    }

    .dept-report-container .dept-report-card:hover {
        border-color: rgba(245, 158, 11, 0.45);
        box-shadow: var(--dr-shadow-md);
    }

    .dept-report-container .dept-report-card > div:first-child {
        background: transparent;
    }

    .dept-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(180, 83, 9, 0.35);
        ring-color: rgba(245, 158, 11, 0.15);
    }

    .dept-report-container .dept-report-card span.text-amber-600 { color: #d97706; }
    .dept-report-container .dept-report-card h2 { color: var(--dr-ink); }
    .dept-report-container .dept-report-card p { color: var(--dr-muted); }
    .dept-report-container .dept-report-card > div:last-child { border-color: var(--dr-line); }
    .dept-report-container .dept-report-card a { color: var(--dr-ink); }
    .dept-report-container .dept-report-card a:hover { color: #d97706; }
    .dept-report-container .dept-report-card > div:last-child span { color: var(--dr-muted); }

    .dept-report-info {
        margin-top: 18px;
        padding: 24px;
        border: 1px solid var(--dr-line);
        border-radius: var(--dr-radius-sm);
        background: var(--dr-surface);
        box-shadow: var(--dr-shadow-sm);
    }

    .dept-report-info-header { display: flex; align-items: center; gap: 12px; }
    .dept-report-info-icon {
        display: flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        font-weight: 800;
    }
    .dept-report-info h3 { font-size: 1rem; font-weight: 700; color: var(--dr-ink); }
    .dept-report-info ul { display: grid; gap: 10px; margin-top: 18px; color: var(--dr-ink-secondary); font-size: 0.875rem; }
    @media (min-width: 640px) { .dept-report-info ul { grid-template-columns: repeat(2, minmax(0, 1fr)); } }

    html.dark-mode .dept-report-container .dept-report-card > div:nth-child(2) > div:first-child,
    .dark .dept-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
        color: #fff;
    }
</style>

<div class="dept-report-container">
    <div class="dept-report-header dept-report-animate">
        <div class="dept-report-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V10.5M9.333 19.5V4.5M14.667 19.5v-7M20 19.5V7.5M2.5 19.5h19" />
            </svg>
        </div>
        <div>
            <h1 class="dept-report-title">Reports &amp; Exports</h1>
            <p class="dept-report-subtitle">Generate official project, financial, and compliance reports.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="dept-report-animate mb-18 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="dept-report-grid dept-report-animate">
        <div class="dept-report-animate"><x-report-card eyebrow="Project overview" title="Projects Report" description="Complete list of all your projects with details and budget information." icon="document" :route="route('department.reports.projects-pdf')" /></div>
        <div class="dept-report-animate"><x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Detailed budget breakdown by status and spending analysis." icon="budget" :route="route('department.reports.budget-pdf')" /></div>
        <div class="dept-report-animate"><x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment." icon="document" :route="route('department.reports.sglg-pdf')" /></div>
    </div>

    <div class="dept-report-info dept-report-animate">
        <div class="dept-report-info-header">
            <span class="dept-report-info-icon">&#10003;</span>
            <h3>About These Reports</h3>
        </div>
        <ul>
            <li>✓ Reports are generated in PDF format for easy viewing and printing</li>
            <li>✓ All reports include your projects based on your department role</li>
            <li>✓ Reports are timestamped and suitable for official documentation</li>
            <li>✓ PDF format preserves formatting on any device</li>
        </ul>
    </div>
</div>
@endsection