@extends('layouts.department')

@section('content')
<style>
    .engineering-report-container {
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

    @media (min-width: 640px) { .engineering-report-container { padding: 32px; } }
    @media (min-width: 1024px) { .engineering-report-container { padding: 40px; } }

    html:not(.dark-mode) body:has(.engineering-report-container) { background: #f8f7f5 !important; }
    html.dark-mode body:has(.engineering-report-container) { background: #0f172a !important; }

    html.dark-mode .engineering-report-container,
    .dark .engineering-report-container {
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

    .engineering-report-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 28px;
    }

    .engineering-report-icon {
        display: flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #0a4353 0%, #0c5c70 30%, #11788a 70%, #22a6b8 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(34, 166, 184, 0.5);
    }

    .engineering-report-icon svg { width: 26px; height: 26px; }

    .engineering-report-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        line-height: 1.2;
        color: var(--br-ink);
    }

    .engineering-report-subtitle { margin-top: 4px; font-size: 0.875rem; color: var(--br-muted); }

    .engineering-report-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 18px;
    }

    @media (min-width: 768px) {
        .engineering-report-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (min-width: 1280px) {
        .engineering-report-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    .engineering-report-container .dept-report-card {
        min-height: 100%;
        border: 1px solid var(--br-line);
        border-radius: var(--br-radius-sm);
        background: var(--br-surface);
        box-shadow: var(--br-shadow-sm);
        transition: transform 0.2s, background 0.3s, border-color 0.2s, box-shadow 0.2s;
    }

    .engineering-report-container .dept-report-card:hover {
        border-color: rgba(34, 166, 184, 0.45);
        box-shadow: var(--br-shadow-md);
    }

    .engineering-report-container .dept-report-card > div:first-child { background: transparent; }

    .engineering-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #0a4353 0%, #0c5c70 30%, #11788a 70%, #22a6b8 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(34, 166, 184, 0.35);
    }

    .engineering-report-container .dept-report-card span.text-amber-600 { color: #0f6a7c; }
    .engineering-report-container .dept-report-card h2 { color: var(--br-ink); }
    .engineering-report-container .dept-report-card p { color: var(--br-muted); }
    .engineering-report-container .dept-report-card > div:last-child { border-color: var(--br-line); }
    .engineering-report-container .dept-report-card a { color: var(--br-ink); }
    .engineering-report-container .dept-report-card a:hover { color: #0f6a7c; }
    .engineering-report-container .dept-report-card > div:last-child span { color: var(--br-muted); }

    .engineering-report-info {
        margin-top: 18px;
        padding: 24px;
        border: 1px solid var(--br-line);
        border-radius: var(--br-radius-sm);
        background: var(--br-surface);
        box-shadow: var(--br-shadow-sm);
    }

    .engineering-report-info-header { display: flex; align-items: center; gap: 12px; }
    .engineering-report-info-icon {
        display: flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(34, 166, 184, 0.12);
        color: #0f6a7c;
        font-weight: 800;
    }
    .engineering-report-info h3 { font-size: 1rem; font-weight: 700; color: var(--br-ink); }
    .engineering-report-info ul { display: grid; gap: 10px; margin-top: 18px; color: var(--br-ink-secondary); font-size: 0.875rem; }
    @media (min-width: 640px) { .engineering-report-info ul { grid-template-columns: repeat(2, minmax(0, 1fr)); } }

    html.dark-mode .engineering-report-container .dept-report-card > div:nth-child(2) > div:first-child,
    .dark .engineering-report-container .dept-report-card > div:nth-child(2) > div:first-child {
        background: linear-gradient(135deg, #0a4353 0%, #0c5c70 30%, #11788a 70%, #22a6b8 100%);
        color: #fff;
    }
</style>

<div class="engineering-report-container engineering-page-enter">
    <div class="engineering-report-header">
        <div class="engineering-report-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V10.5M9.333 19.5V4.5M14.667 19.5v-7M20 19.5V7.5M2.5 19.5h19" />
            </svg>
        </div>
        <div>
            <h1 class="engineering-report-title">Engineering Reports</h1>
            <p class="engineering-report-subtitle">Generate project, financial, and compliance reports for field monitoring.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="engineering-report-grid">
        <x-report-card eyebrow="Project overview" title="Projects Report" description="Complete list of all current projects with implementation details and funding information." icon="document" :route="route('engineering.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Detailed budget review by funding allocation, expenditure, and implementation status." icon="budget" :route="route('engineering.reports.budget-pdf')" />
        <x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation and transparency checks aligned with compliance and assessment reporting." icon="document" :route="route('engineering.reports.sglg-pdf')" />
    </div>

    <div class="engineering-report-info">
        <div class="engineering-report-info-header">
            <span class="engineering-report-info-icon">&#10003;</span>
            <h3>About These Reports</h3>
        </div>
        <ul>
            <li>✓ Reports cover all active engineering and infrastructure projects.</li>
            <li>✓ PDF format is ideal for field monitoring and official archiving.</li>
            <li>✓ All reports include department details and generation timestamp.</li>
            <li>✓ Formatted for compliance review and easy distribution.</li>
        </ul>
    </div>
</div>
@endsection