@extends('layouts.barangay')

@section('content')
<style>
    .barangay-report-container {
        --br-bg: #f8f7f5;
        --br-surface: #ffffff;
        --br-surface-hover: #fafaf9;
        --br-ink: #1e1b4b;
        --br-ink-secondary: #374151;
        --br-muted: #9ca3af;
        --br-line: rgba(0, 0, 0, 0.06);
        --br-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --br-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --br-radius-sm: 12px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        background: var(--br-bg);
        color: var(--br-ink);
        transition: background 0.3s, color 0.3s;
    }

    @media (min-width: 640px) { .barangay-report-container { padding: 32px; } }
    @media (min-width: 1024px) { .barangay-report-container { padding: 40px; } }

    html:not(.dark-mode) body:has(.barangay-report-container) { background: #f8f7f5 !important; }
    html.dark-mode body:has(.barangay-report-container) { background: #0f172a !important; }

    html.dark-mode .barangay-report-container,
    .dark .barangay-report-container {
        --br-bg: #0f172a;
        --br-surface: #1e293b;
        --br-surface-hover: #243247;
        --br-ink: #f8fafc;
        --br-ink-secondary: #cbd5e1;
        --br-muted: #64748b;
        --br-line: rgba(148, 163, 184, 0.2);
        --br-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --br-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    }

    .barangay-report-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 28px;
    }

    .barangay-report-icon {
        display: flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(153, 27, 27, 0.5);
    }

    .barangay-report-icon svg { width: 26px; height: 26px; }

    .barangay-report-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        line-height: 1.2;
        color: var(--br-ink);
    }

    .barangay-report-subtitle { margin-top: 4px; font-size: 0.875rem; color: var(--br-muted); }

    .barangay-report-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 18px;
    }

    @media (min-width: 768px) {
        .barangay-report-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    .barangay-report-container .dept-report-card {
        min-height: 100%;
        border: 1px solid var(--br-line);
        border-radius: var(--br-radius-sm);
        background: var(--br-surface);
        box-shadow: var(--br-shadow-sm);
        transition: transform 0.2s, background 0.3s, border-color 0.2s, box-shadow 0.2s;
    }

    .barangay-report-container .dept-report-card:hover {
        border-color: rgba(245, 158, 11, 0.45);
        box-shadow: var(--br-shadow-md);
    }

    .barangay-report-container .dept-report-card > div:first-child { background: transparent; }

    .barangay-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(153, 27, 27, 0.35);
    }

    .barangay-report-container .dept-report-card span.text-amber-600 { color: #d97706; }
    .barangay-report-container .dept-report-card h2 { color: var(--br-ink); }
    .barangay-report-container .dept-report-card p { color: var(--br-muted); }
    .barangay-report-container .dept-report-card > div:last-child { border-color: var(--br-line); }
    .barangay-report-container .dept-report-card a { color: var(--br-ink); }
    .barangay-report-container .dept-report-card a:hover { color: #d97706; }
    .barangay-report-container .dept-report-card > div:last-child span { color: var(--br-muted); }

    .barangay-report-info {
        margin-top: 18px;
        padding: 24px;
        border: 1px solid var(--br-line);
        border-radius: var(--br-radius-sm);
        background: var(--br-surface);
        box-shadow: var(--br-shadow-sm);
    }

    .barangay-report-info-header { display: flex; align-items: center; gap: 12px; }
    .barangay-report-info-icon {
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
    .barangay-report-info h3 { font-size: 1rem; font-weight: 700; color: var(--br-ink); }
    .barangay-report-info ul { display: grid; gap: 10px; margin-top: 18px; color: var(--br-ink-secondary); font-size: 0.875rem; }
    @media (min-width: 640px) { .barangay-report-info ul { grid-template-columns: repeat(2, minmax(0, 1fr)); } }

    html.dark-mode .barangay-report-container .dept-report-card > div:nth-child(2) > div:first-child,
    .dark .barangay-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
        color: #fff;
    }
</style>

<div class="barangay-report-container">
    <div class="barangay-report-header">
        <div class="barangay-report-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V10.5M9.333 19.5V4.5M14.667 19.5v-7M20 19.5V7.5M2.5 19.5h19" />
            </svg>
        </div>
        <div>
            <h1 class="barangay-report-title">Reports &amp; Exports</h1>
            <p class="barangay-report-subtitle">Generate official project and financial reports for your barangay.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="barangay-report-grid">
        <x-report-card eyebrow="Project overview" title="Projects Report" description="Complete list of all projects in your barangay with details and budget information." icon="document" :route="route('barangay.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Detailed budget breakdown by status and spending analysis for your barangay." icon="budget" :route="route('barangay.reports.budget-pdf')" />
    </div>

    <div class="barangay-report-info">
        <div class="barangay-report-info-header">
            <span class="barangay-report-info-icon">&#10003;</span>
            <h3>About These Reports</h3>
        </div>
        <ul>
            <li>✓ Reports show only projects assigned to your barangay.</li>
            <li>✓ PDF format is ideal for official distribution and archiving.</li>
            <li>✓ All reports include your barangay name and generation timestamp.</li>
            <li>✓ Formatted for easy printing and sharing.</li>
        </ul>
    </div>
</div>
@endsection