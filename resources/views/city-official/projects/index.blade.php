@extends('layouts.city')

@section('content')
<style>
    .city-projects-page {
        --cp-bg: #f8f7f5;
        --cp-surface: #ffffff;
        --cp-surface-hover: #fafaf9;
        --cp-ink: #0f172a;
        --cp-muted: #9ca3af;
        --cp-line: rgba(0,0,0,0.06);
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        color: var(--cp-ink);
    }
    @media (min-width: 640px) { .city-projects-page { padding: 32px; } }
    @media (min-width: 1024px) { .city-projects-page { padding: 40px; } }
    html.dark-mode .city-projects-page {
        --cp-bg: #0f172a;
        --cp-surface: #1e293b;
        --cp-surface-hover: #243247;
        --cp-ink: #f8fafc;
        --cp-muted: #94a3b8;
        --cp-line: rgba(148,163,184,0.2);
    }

    /* Animations */
    @keyframes cpFadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .cp-animate { animation: cpFadeUp 0.5s ease forwards; opacity: 0; }
    .cp-animate:nth-child(1) { animation-delay: 0.05s; }
    .cp-animate:nth-child(2) { animation-delay: 0.1s; }
    .cp-animate:nth-child(3) { animation-delay: 0.15s; }
    .cp-animate:nth-child(4) { animation-delay: 0.2s; }
    @media (prefers-reduced-motion: reduce) { .cp-animate { animation: none; opacity: 1; } }

    /* Header */
    .city-projects-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
    .city-projects-titlewrap { display: flex; align-items: flex-start; gap: 16px; }
    .city-projects-icon { display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; flex-shrink: 0; border-radius: 14px; background: linear-gradient(135deg,#4c1d95,#6d28d9); color: #fff; box-shadow: 0 4px 14px -4px rgba(109,40,217,0.5); }
    .city-projects-icon svg { width: 26px; height: 26px; }
    .city-projects-title { font-family: "Plus Jakarta Sans", "Inter", sans-serif; color: var(--cp-ink); font-size: clamp(1.5rem,3vw,2rem); font-weight: 800; line-height: 1.2; }
    .city-projects-subtitle { font-family: "Inter", system-ui, sans-serif; margin-top: 4px; color: var(--cp-muted); font-size: 0.875rem; }

    /* Toolbar */
    .city-projects-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; padding: 16px; border: 1px solid var(--cp-line); border-radius: 12px; background: var(--cp-surface); }
    .city-projects-search { position: relative; flex: 1; max-width: 400px; }
    .city-projects-search svg { position: absolute; left: 14px; top: 50%; width: 18px; height: 18px; transform: translateY(-50%); color: var(--cp-muted); }
    .city-projects-search input { width: 100%; padding: 10px 14px 10px 42px; border: 1px solid var(--cp-line); border-radius: 100px; outline: none; background: var(--cp-bg); color: var(--cp-ink); font-size: 0.875rem; }
    .city-projects-search input:focus { border-color: #6d28d9; box-shadow: 0 0 0 3px rgba(109,40,217,0.12); }
    .city-projects-filters { display: flex; flex-wrap: wrap; gap: 8px; }
    .city-projects-filter { padding: 8px 16px; border: 1px solid var(--cp-line); border-radius: 100px; background: var(--cp-bg); color: var(--cp-ink); font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: all 0.15s ease; }
    .city-projects-filter.active { background: var(--cp-ink); color: var(--cp-surface); }
    .city-projects-filter:hover:not(.active) { background: var(--cp-surface-hover); }
    .city-projects-status-wrap { position: relative; }
    .city-projects-status-wrap::after { content: ""; position: absolute; top: 50%; right: 15px; width: 7px; height: 7px; border-right: 2px solid var(--cp-muted); border-bottom: 2px solid var(--cp-muted); transform: translateY(-65%) rotate(45deg); pointer-events: none; }
    .city-projects-status-filter { min-width: 190px; padding: 10px 36px 10px 14px; border: 1px solid var(--cp-line); border-radius: 100px; outline: none; appearance: none; background: var(--cp-bg); color: var(--cp-ink); font-size: 0.8125rem; font-weight: 600; cursor: pointer; }
    .city-projects-status-filter:focus { border-color: #6d28d9; box-shadow: 0 0 0 3px rgba(109,40,217,0.12); }
    html.dark-mode .city-projects-status-filter { background: #0f172a; color: #e5edf9; border-color: rgba(148,163,184,0.18); }

    /* Card & Table */
    .city-projects-card { overflow: hidden; border: 1px solid var(--cp-line); border-radius: 16px; background: var(--cp-surface); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .city-projects-tablewrap { overflow-x: auto; }
    .city-projects-table { width: 100%; min-width: 640px; border-collapse: collapse; font-size: 0.875rem; }
    .city-projects-table th { padding: 14px 20px; background: var(--cp-surface-hover); border-bottom: 1px solid var(--cp-line); color: var(--cp-muted); font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.08em; text-align: left; text-transform: uppercase; }
    .city-projects-table td { padding: 16px 20px; border-bottom: 1px solid var(--cp-line); color: var(--cp-ink); }
    .city-projects-table tr:last-child td { border-bottom: 0; }
    .city-projects-table tr:hover td { background: var(--cp-surface-hover); }

    /* Project cells */
    .city-project-name { display: flex; align-items: center; gap: 12px; font-weight: 700; }
    .city-project-name-text { display: block; min-width: 0; }
    .city-project-name-text > span:first-child { display: block; }
    .city-project-code { margin-top: 4px; color: var(--cp-muted); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .city-project-avatar { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; color: #fff; font-size: 0.75rem; font-weight: 800; }

    /* Status badges */
    .city-project-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .city-project-status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-planning { background: #fef3c7; color: #b45309; }
    .status-bidding { background: #fef3c7; color: #b45309; }
    .status-award { background: #ede9fe; color: #6d28d9; }
    .status-ongoing { background: #dbeafe; color: #1d4ed8; }
    .status-hold { background: #fee2e2; color: #b91c1c; }
    .status-completed { background: #d1fae5; color: #047857; }
    .status-cancelled { background: #f3f4f6; color: #4b5563; }

    /* View button */
    .city-project-view { display: inline-flex; align-items: center; justify-content: center; padding: 7px 14px; border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; background: #dbeafe; color: #1d4ed8; font-size: 0.75rem; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .city-project-view:hover { background: #3b82f6; color: #fff; }
    html.dark-mode .city-project-view { background: rgba(59,130,246,0.16); border-color: rgba(59,130,246,0.35); color: #60a5fa; }
    html.dark-mode .city-project-view:hover { background: #3b82f6; color: #fff; }

    /* Mobile cards */
    .city-projects-mobile { display: none; flex-direction: column; gap: 12px; padding: 16px; }
    .city-project-mobile-card { padding: 16px; border: 1px solid var(--cp-line); border-radius: 24px; background: var(--cp-surface-hover); }
    .city-project-mobile-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .city-project-mobile-name { color: var(--cp-ink); font-weight: 700; }
    .city-project-mobile-top .city-project-code { display: block; }
    .city-project-mobile-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; color: var(--cp-muted); font-size: 0.8125rem; }
    .city-project-mobile-meta strong { display: block; margin-top: 4px; color: var(--cp-ink); }

    /* Empty state */
    .city-projects-empty { text-align: center; padding: 48px 24px; }
    .city-projects-empty-icon { width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 16px; background: #ede9fe; color: #6d28d9; display: flex; align-items: center; justify-content: center; }
    .city-projects-empty h4 { font-size: 1rem; font-weight: 700; color: var(--cp-ink); margin-bottom: 4px; }
    .city-projects-empty p { font-size: 0.875rem; color: var(--cp-muted); }

    /* Pagination */
    .city-projects-pagination { padding: 20px 24px; }

    /* Dark mode overrides */
    html.dark-mode .city-projects-card { border-color: #475569; background: #1e293b; }
    html.dark-mode .city-projects-tablewrap { border: 1px solid #475569; border-radius: 16px; box-shadow: 0 0 0 1px rgba(148,163,184,0.24); }
    html.dark-mode .city-projects-table th { background: #111827; border-bottom-color: #334155; color: #94a3b8; }
    html.dark-mode .city-projects-table td { background: #1e293b; border-bottom-color: #334155; color: #cbd5e1; }
    html.dark-mode .city-projects-table tr:hover td { background: #243247; }
    html.dark-mode .city-project-code { color: #94a3b8; }
    html.dark-mode .status-bidding { background: rgba(245,158,11,0.16); color: #fbbf24; }
    html.dark-mode .status-award { background: rgba(139,92,246,0.16); color: #a78bfa; }

    @media (max-width: 767px) {
        .city-projects-page { padding: 24px 16px; }
        .city-projects-header { display: block; }
        .city-projects-icon { display: none; }
        .city-projects-toolbar { display: none; }
        .city-projects-tablewrap { display: none; }
        .city-projects-mobile { display: flex; }
    }
</style>

<div class="city-projects-page">
    <div class="city-projects-header cp-animate">
        <div class="city-projects-titlewrap">
            <div class="city-projects-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/>
                </svg>
            </div>
            <div>
                <h1 class="city-projects-title">City Projects</h1>
                <p class="city-projects-subtitle">Browse all city projects across Cabuyao.</p>
            </div>
        </div>
    </div>

    <div class="city-projects-toolbar cp-animate">
        <div class="city-projects-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" id="cityProjectSearch" placeholder="Search projects by name or code...">
        </div>
        <div class="city-projects-status-wrap">
            <select id="cityProjectStatusFilter" class="city-projects-status-filter" aria-label="Filter projects by status">
                <option value="all">All statuses</option>
                <option value="Proposed">Proposed</option>
                <option value="Planning">Planning</option>
                <option value="For bidding">For bidding</option>
                <option value="Procurement">Procurement</option>
                <option value="Bidding ongoing">Bidding ongoing</option>
                <option value="Bidding - Success">Bidding - Success</option>
                <option value="Bidding - Failed">Bidding - Failed</option>
                <option value="Award of contract">Award of contract</option>
                <option value="Implementation">Implementation</option>
                <option value="On Going">On Going</option>
                <option value="On Hold">On Hold</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <div class="city-projects-card cp-animate">
        @if ($projects->isEmpty())
            <div class="city-projects-empty">
                <div class="city-projects-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <h4>No projects yet</h4>
                <p>No projects have been recorded for this city.</p>
            </div>
        @else
            <div class="city-projects-tablewrap">
                <table class="city-projects-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Barangay</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            @php
                                $statusClass = match($project->current_status) {
                                    'On Going', 'Implementation' => 'status-ongoing',
                                    'Bidding ongoing' => 'status-bidding',
                                    'Award of contract' => 'status-award',
                                    'On Hold' => 'status-hold',
                                    'Completed' => 'status-completed',
                                    'Cancelled' => 'status-cancelled',
                                    default => 'status-planning',
                                };
                                $words = explode(' ', $project->project_name);
                                $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                                $avatarGradient = match($loop->index % 4) {
                                    0 => 'linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%)',
                                    1 => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                    2 => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                                    3 => 'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)',
                                };
                            @endphp
                            <tr class="city-project-row" data-status="{{ $project->current_status }}" data-search="{{ strtolower($project->project_name . ' ' . ($project->project_code ?? '')) }}">
                                <td>
                                    <div class="city-project-name">
                                        <span class="city-project-avatar" style="background: {{ $avatarGradient }}">{{ $initials }}</span>
                                        <span class="city-project-name-text">
                                            <span>{{ $project->project_name }}</span>
                                            <span class="city-project-code">{{ $project->project_code ?? 'No project code' }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td><span class="city-project-status {{ $statusClass }}">{{ $project->current_status }}</span></td>
                                <td>{{ $project->barangay?->barangay_name ?? 'Citywide' }}</td>
                                <td>₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td>
                                    <a class="city-project-view" href="{{ route('city.projects.show', $project->project_id) }}">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="city-projects-mobile">
                @foreach ($projects as $project)
                    @php
                        $statusClass = match($project->current_status) {
                            'On Going', 'Implementation' => 'status-ongoing',
                            'Bidding ongoing' => 'status-bidding',
                            'Award of contract' => 'status-award',
                            'On Hold' => 'status-hold',
                            'Completed' => 'status-completed',
                            'Cancelled' => 'status-cancelled',
                            default => 'status-planning',
                        };
                        $words = explode(' ', $project->project_name);
                        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                        $avatarGradient = match($loop->index % 4) {
                                    0 => 'linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%)',
                            1 => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                            2 => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                            3 => 'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)',
                        };
                    @endphp
                    <div class="city-project-mobile-card city-project-row" data-status="{{ $project->current_status }}" data-search="{{ strtolower($project->project_name . ' ' . ($project->project_code ?? '')) }}">
                        <div class="city-project-mobile-top">
                            <div class="flex items-center gap-3">
                                <span class="city-project-avatar" style="background: {{ $avatarGradient }}">{{ $initials }}</span>
                                <div>
                                    <span class="city-project-mobile-name">{{ $project->project_name }}</span>
                                    <span class="city-project-code">{{ $project->project_code ?? 'No project code' }}</span>
                                </div>
                            </div>
                            <span class="city-project-status {{ $statusClass }}">{{ $project->current_status }}</span>
                        </div>
                        <div class="city-project-mobile-meta">
                            <div>Barangay<strong>{{ $project->barangay?->barangay_name ?? 'Citywide' }}</strong></div>
                            <div>Budget<strong>₱{{ number_format($project->approved_budget ?? 0, 2) }}</strong></div>
                            <div>Action<strong><a class="city-project-view" href="{{ route('city.projects.show', $project->project_id) }}">View</a></strong></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($projects->hasPages())
            <div class="city-projects-pagination">{{ $projects->links() }}</div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('cityProjectSearch');
    const statusFilter = document.getElementById('cityProjectStatusFilter');
    const rows = document.querySelectorAll('.city-project-row');

    function updateProjects() {
        const query = (search?.value || '').toLowerCase().trim();
        const activeFilter = statusFilter?.value || 'all';
        rows.forEach(row => {
            const matchesFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
            const matchesSearch = !query || row.dataset.search.includes(query);
            row.hidden = !(matchesFilter && matchesSearch);
        });
    }

    search?.addEventListener('input', updateProjects);
    statusFilter?.addEventListener('change', updateProjects);
});
</script>
@endsection
