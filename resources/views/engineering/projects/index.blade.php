@extends('layouts.department')

@section('content')
<style>
/* ===== ENGINEERING PROJECTS - DEPT STYLE ===== */
.ep-wrap {
    --ep-bg: #f8f7f5;
    --ep-surface: #ffffff;
    --ep-raised: #fafaf9;
    --ep-ink: #0f172a;
    --ep-ink-secondary: #374151;
    --ep-muted: #9ca3af;
    --ep-line: rgba(0,0,0,0.06);
    --ep-line-strong: rgba(0,0,0,0.12);
    --ep-shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    --ep-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --ep-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --ep-radius-sm: 12px;
    --ep-radius: 16px;
    --ep-radius-xl: 20px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: var(--ep-bg);
    color: var(--ep-ink);
    transition: background 0.3s, color 0.3s;
}
@media (min-width: 640px) { .ep-wrap { padding: 32px; } }
@media (min-width: 1024px) { .ep-wrap { padding: 40px; } }

html:not(.dark-mode) body:has(.ep-wrap) { background: #f8f7f5 !important; }
html.dark-mode body:has(.ep-wrap) { background: #0f172a !important; }

.dark .ep-wrap,
html.dark-mode .ep-wrap {
    --ep-bg: #0f172a;
    --ep-surface: #1e293b;
    --ep-raised: #243247;
    --ep-ink: #f8fafc;
    --ep-ink-secondary: #cbd5e1;
    --ep-muted: #64748b;
    --ep-line: rgba(148,163,184,0.2);
    --ep-line-strong: rgba(148,163,184,0.35);
    --ep-shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.3);
    --ep-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    --ep-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
}

/* ===== HERO ===== */
.ep-hero {
    position: relative;
    border-radius: var(--ep-radius-xl);
    padding: 36px 40px;
    margin-bottom: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, #0a4353 0%, #134e5c 30%, #22a6b8 70%, #67e8f9 100%);
    box-shadow: var(--ep-shadow-lg);
}
@media (min-width: 640px) { .ep-hero { padding: 44px 48px; } }
.ep-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.5;
    pointer-events: none;
}
.ep-hero::after {
    content: "";
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(103,232,249,0.22) 0%, transparent 60%);
    pointer-events: none;
}
.ep-hero-content { position: relative; z-index: 1; }
.ep-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #a5f3fc;
    margin-bottom: 12px;
}
.ep-hero-eyebrow::before {
    content: "";
    display: block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #67e8f9;
    box-shadow: 0 0 0 4px rgba(103,232,249,0.25);
}
.ep-hero-title {
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: white;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.ep-hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.65);
    max-width: 620px;
    line-height: 1.6;
}
.ep-hero-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 24px;
    flex-wrap: wrap;
}
.ep-hero-badge {
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
.ep-hero-badge svg { width: 14px; height: 14px; }

/* ===== HEADER ===== */
.ep-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 28px;
}
.ep-titlewrap {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.ep-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    flex-shrink: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #0a4353, #22a6b8);
    color: #fff;
    box-shadow: 0 4px 14px -4px rgba(34,166,184,0.5);
}
.ep-icon svg { width: 26px; height: 26px; }
.ep-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    line-height: 1.2;
    color: var(--ep-ink);
}
.ep-subtitle {
    margin-top: 4px;
    font-size: 0.875rem;
    color: var(--ep-muted);
}

/* ===== TOOLBAR ===== */
.ep-toolbar {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
    padding: 16px;
    background: var(--ep-surface);
    border: 1px solid var(--ep-line);
    border-radius: var(--ep-radius-sm);
    box-shadow: var(--ep-shadow-sm);
    transition: background 0.3s, border-color 0.3s;
}
@media (min-width: 640px) {
    .ep-toolbar { flex-direction: row; align-items: center; justify-content: space-between; }
}
.ep-search {
    position: relative;
    flex: 1;
    max-width: 400px;
}
.ep-search svg {
    position: absolute;
    left: 14px;
    top: 50%;
    width: 18px;
    height: 18px;
    transform: translateY(-50%);
    color: var(--ep-muted);
}
.ep-search input {
    width: 100%;
    padding: 10px 14px 10px 42px;
    border: 1px solid var(--ep-line);
    border-radius: 100px;
    outline: none;
    background: var(--ep-raised);
    color: var(--ep-ink);
    font-size: 0.875rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.ep-search input:focus {
    border-color: #22a6b8;
    box-shadow: 0 0 0 3px rgba(34,166,184,0.12);
}

/* ===== STATUS DROPDOWN (RETAINED) ===== */
.ep-status-wrap {
    position: relative;
}
.ep-status-wrap::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 15px;
    width: 7px;
    height: 7px;
    border-right: 2px solid var(--ep-muted);
    border-bottom: 2px solid var(--ep-muted);
    transform: translateY(-65%) rotate(45deg);
    pointer-events: none;
}
.ep-status-filter {
    min-width: 190px;
    padding: 10px 36px 10px 14px;
    border: 1px solid var(--ep-line);
    border-radius: 100px;
    background: var(--ep-raised);
    color: var(--ep-ink-secondary);
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    outline: none;
    appearance: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.ep-status-filter:focus {
    border-color: #22a6b8;
    box-shadow: 0 0 0 3px rgba(34,166,184,0.12);
}

/* Dark mode for form elements */
html.dark-mode .ep-search input,
.dark .ep-search input,
html.dark-mode .ep-status-filter,
.dark .ep-status-filter {
    background: #0f172a;
    color: #e5edf9;
    border-color: rgba(148,163,184,0.18);
}

/* ===== CARD ===== */
.ep-card {
    overflow: hidden;
    background: var(--ep-surface);
    border: 1px solid var(--ep-line);
    border-radius: var(--ep-radius-sm);
    box-shadow: var(--ep-shadow-sm);
    transition: background 0.3s, border-color 0.3s;
}

/* ===== TABLE ===== */
.ep-tablewrap {
    overflow-x: auto;
}
.ep-table {
    width: 100%;
    min-width: 800px;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.875rem;
}
.ep-table th {
    padding: 14px 20px;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ep-muted);
    background: var(--ep-raised);
    border-bottom: 1px solid var(--ep-line);
    white-space: nowrap;
    transition: background 0.3s, border-color 0.3s;
}
.ep-table th:first-child { padding-left: 24px; }
.ep-table th:last-child { padding-right: 24px; text-align: right; }
.ep-table td {
    padding: 16px 20px;
    color: var(--ep-ink-secondary);
    border-bottom: 1px solid var(--ep-line);
    transition: background 0.2s, border-color 0.3s;
}
.ep-table td:first-child { padding-left: 24px; }
.ep-table td:last-child { padding-right: 24px; }
.ep-table tr:hover { background: var(--ep-raised); }
.ep-table tr:last-child td { border-bottom: 0; }

/* ===== PROJECT CELLS ===== */
.ep-project-name {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ep-project-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
}
.ep-project-name-text .name {
    color: var(--ep-ink);
    font-weight: 700;
}
.ep-project-name-text .code {
    margin-top: 2px;
    color: var(--ep-muted);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

/* ===== STATUS BADGES ===== */
.ep-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: capitalize;
    white-space: nowrap;
}
.ep-status::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}
.status-planning { background: rgba(37,99,235,0.10); color: #2563eb; }
.status-ongoing { background: rgba(6,182,212,0.10); color: #06b6d4; }
.status-hold { background: rgba(220,38,38,0.10); color: #dc2626; }
.status-completed { background: rgba(22,163,74,0.10); color: #16a34a; }
.status-cancelled { background: rgba(100,116,139,0.10); color: #64748b; }

/* Dark mode status overrides */
.dark .status-planning,
html.dark-mode .status-planning { background: rgba(37,99,235,0.12); color: #60a5fa; }
.dark .status-ongoing,
html.dark-mode .status-ongoing { background: rgba(6,182,212,0.12); color: #67e8f9; }
.dark .status-hold,
html.dark-mode .status-hold { background: rgba(220,38,38,0.12); color: #f87171; }
.dark .status-completed,
html.dark-mode .status-completed { background: rgba(22,163,74,0.12); color: #4ade80; }
.dark .status-cancelled,
html.dark-mode .status-cancelled { background: rgba(100,116,139,0.12); color: #cbd5e1; }

.ep-project-barangay {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8125rem;
    color: var(--ep-ink-secondary);
}
.ep-project-barangay svg {
    width: 14px;
    height: 14px;
    color: var(--ep-muted);
}
.ep-project-budget {
    color: var(--ep-ink);
    font-weight: 700;
    white-space: nowrap;
}
.ep-project-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    background: #dbeafe;
    color: #1d4ed8;
    border: 1px solid rgba(59,130,246,0.2);
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.15s;
}
.ep-project-action:hover {
    background: #3b82f6;
    color: #fff;
}
.dark .ep-project-action,
html.dark-mode .ep-project-action {
    background: rgba(59,130,246,0.15);
    color: #60a5fa;
}
.dark .ep-project-action:hover,
html.dark-mode .ep-project-action:hover {
    background: #3b82f6;
    color: #fff;
}

/* ===== MOBILE CARDS ===== */
.ep-mobile {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 16px;
}
.ep-mobile-card {
    padding: 20px;
    border: 1px solid var(--ep-line);
    border-radius: var(--ep-radius-sm);
    background: var(--ep-surface);
    transition: background 0.3s, border-color 0.3s;
}
.ep-mobile-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
}
.ep-mobile-title {
    color: var(--ep-ink);
    font-size: 0.9375rem;
    font-weight: 700;
}
.ep-mobile-code {
    margin-top: 4px;
    color: var(--ep-muted);
    font-size: 0.6875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.ep-mobile-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 16px;
}
.ep-mobile-label {
    display: block;
    margin-bottom: 4px;
    color: var(--ep-muted);
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.ep-mobile-value {
    color: var(--ep-ink-secondary);
    font-size: 0.8125rem;
    font-weight: 600;
}
.ep-mobile-action {
    padding-top: 14px;
    border-top: 1px solid var(--ep-line);
}
.ep-mobile-action .ep-project-action {
    display: flex;
    justify-content: center;
    width: 100%;
}

/* ===== EMPTY STATE ===== */
.ep-empty {
    text-align: center;
    padding: 64px 24px;
    color: var(--ep-muted);
}
.ep-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 16px;
    background: #cffafe;
    color: #0891b2;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dark .ep-empty-icon,
html.dark-mode .ep-empty-icon {
    background: rgba(8,145,178,0.15);
    color: #67e8f9;
}
.ep-empty h4 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ep-ink);
    margin-bottom: 4px;
}
.ep-empty p {
    font-size: 0.875rem;
    color: var(--ep-muted);
}

/* ===== RESPONSIVE ===== */
@media (min-width: 768px) {
    .ep-mobile { display: none; }
}
@media (max-width: 767.98px) {
    .ep-desktop { display: none; }
    .ep-header { display: block; }
    .ep-icon { display: none; }
}

/* ===== ANIMATIONS ===== */
@keyframes epFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.ep-animate {
    animation: epFadeUp 0.5s ease forwards;
    opacity: 0;
}
.ep-animate:nth-child(1) { animation-delay: 0.05s; }
.ep-animate:nth-child(2) { animation-delay: 0.1s; }
.ep-animate:nth-child(3) { animation-delay: 0.15s; }

@media (prefers-reduced-motion: reduce) {
    .ep-animate { animation: none; opacity: 1; }
}
</style>

<div class="ep-wrap">
    <!-- HEADER -->
    <div class="ep-header ep-animate">
        <div class="ep-titlewrap">
            <div class="ep-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg></div>
            <div>
                <h1 class="ep-title">Engineering Projects</h1>
                <p class="ep-subtitle">View and monitor all projects across Cabuyao City.</p>
            </div>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="ep-toolbar ep-animate">
        <div class="ep-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="text" id="projectSearch" placeholder="Search projects by name or code...">
        </div>
        <div class="ep-status-wrap">
            <select id="projectStatusFilter" class="ep-status-filter" aria-label="Filter projects by status">
                <option value="all">All statuses</option>
                <option value="Proposed">Proposed</option>
                <option value="For bidding">For bidding</option>
                <option value="Bidding ongoing">Bidding ongoing</option>
                <option value="Award of contract">Award of contract</option>
                <option value="Implementation">Implementation</option>
                <option value="Completed">Completed</option>
                <option value="On Hold">On Hold</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- CARD -->
    <div class="ep-card ep-animate">
        @if ($projects->isEmpty())
            <div class="ep-empty">
                <div class="ep-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
                <h4>No projects yet</h4>
                <p>No projects have been added yet.</p>
            </div>
        @else
            <!-- DESKTOP TABLE -->
            <div class="ep-desktop">
                <div class="ep-tablewrap">
                    <table class="ep-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Barangay</th>
                                <th>Budget</th>
                                <th style="text-align:right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                @php
                                    $statusClass = match($project->current_status) {
                                        'Proposed', 'Planning', 'For bidding', 'Procurement', 'Bidding ongoing', 'Bidding - Success', 'Bidding - Failed' => 'status-planning',
                                        'Implementation', 'On Going' => 'status-ongoing',
                                        'On Hold' => 'status-hold',
                                        'Completed' => 'status-completed',
                                        'Cancelled' => 'status-cancelled',
                                        default => 'status-planning',
                                    };
                                    $words = explode(' ', $project->project_name);
                                    $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                                    $avatarGradients = [
                                        'linear-gradient(135deg, #0a4353, #22a6b8)',
                                        'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                                        'linear-gradient(135deg, #10b981, #047857)',
                                        'linear-gradient(135deg, #8b5cf6, #6d28d9)',
                                        'linear-gradient(135deg, #f43f5e, #be123c)',
                                    ];
                                @endphp
                                <tr class="project-row"
                                    data-status="{{ $project->current_status }}"
                                    data-name="{{ strtolower($project->project_name) }}"
                                    data-code="{{ strtolower($project->project_code) }}">
                                    <td>
                                        <div class="ep-project-name">
                                            <div class="ep-project-avatar" style="background: {{ $avatarGradients[$loop->index % count($avatarGradients)] }}">{{ $initials }}</div>
                                            <div class="ep-project-name-text">
                                                <div class="name">{{ $project->project_name }}</div>
                                                <div class="code">{{ $project->project_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ep-status {{ $statusClass }}">{{ $project->current_status }}</span>
                                    </td>
                                    <td>
                                        <span class="ep-project-barangay">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            {{ $project->barangay?->barangay_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="ep-project-budget">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                    <td style="text-align:right">
                                        <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="ep-project-action">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MOBILE CARDS -->
            <div class="ep-mobile">
                @foreach ($projects as $project)
                    @php
                        $statusClass = match($project->current_status) {
                            'Proposed', 'Planning', 'For bidding', 'Procurement', 'Bidding ongoing', 'Bidding - Success', 'Bidding - Failed' => 'status-planning',
                            'Implementation', 'On Going' => 'status-ongoing',
                            'On Hold' => 'status-hold',
                            'Completed' => 'status-completed',
                            'Cancelled' => 'status-cancelled',
                            default => 'status-planning',
                        };
                    @endphp
                    <div class="ep-mobile-card project-row"
                         data-status="{{ $project->current_status }}"
                         data-name="{{ strtolower($project->project_name) }}"
                         data-code="{{ strtolower($project->project_code) }}">
                        <div class="ep-mobile-header">
                            <div>
                                <div class="ep-mobile-title">{{ $project->project_name }}</div>
                                <div class="ep-mobile-code">{{ $project->project_code }}</div>
                            </div>
                            <span class="ep-status {{ $statusClass }}">{{ $project->current_status }}</span>
                        </div>
                        <div class="ep-mobile-meta">
                            <div>
                                <span class="ep-mobile-label">Barangay</span>
                                <span class="ep-mobile-value">{{ $project->barangay?->barangay_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="ep-mobile-label">Budget</span>
                                <span class="ep-mobile-value">₱{{ number_format($project->approved_budget ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="ep-mobile-action">
                            <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="ep-project-action">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($projects->hasPages())
            <div class="ep-pagination" style="padding: 20px 24px 24px; display:flex; justify-content:flex-end;">
                <div class="ep-pagebtns">{{ $projects->links() }}</div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('projectSearch');
    const statusFilter = document.getElementById('projectStatusFilter');
    const rows = document.querySelectorAll('.project-row');

    function apply() {
        const query = (search?.value || '').toLowerCase();
        const filter = statusFilter?.value || 'all';

        rows.forEach(row => {
            const matchesSearch = (row.dataset.name || '').includes(query) || (row.dataset.code || '').includes(query);
            const matchesFilter = filter === 'all' || row.dataset.status === filter;
            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    search?.addEventListener('input', apply);
    statusFilter?.addEventListener('change', apply);
});
</script>
@endsection
