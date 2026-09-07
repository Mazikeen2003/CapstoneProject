@extends('layouts.barangay')

@section('content')
<style>
    .barangay-projects-page {
        --bp-bg: #f8f7f5;
        --bp-surface: #ffffff;
        --bp-surface-hover: #fafaf9;
        --bp-ink: #1e1b4b;
        --bp-muted: #9ca3af;
        --bp-line: rgba(0,0,0,0.06);
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        color: var(--bp-ink);
    }
    @media (min-width: 640px) { .barangay-projects-page { padding: 32px; } }
    @media (min-width: 1024px) { .barangay-projects-page { padding: 40px; } }
    html.dark-mode .barangay-projects-page {
        --bp-bg: #0f172a;
        --bp-surface: #1e293b;
        --bp-surface-hover: #243247;
        --bp-ink: #f8fafc;
        --bp-muted: #94a3b8;
        --bp-line: rgba(148,163,184,0.2);
    }
    .barangay-projects-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
    .barangay-projects-titlewrap { display: flex; align-items: flex-start; gap: 16px; }
    .barangay-projects-icon { display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; flex-shrink: 0; border-radius: 14px; background: linear-gradient(135deg,#991b1b,#dc2626); color: #fff; box-shadow: 0 4px 14px -4px rgba(220,38,38,0.5); }
    .barangay-projects-icon svg { width: 26px; height: 26px; }
    .barangay-projects-title { color: var(--bp-ink); font-size: clamp(1.5rem,3vw,2rem); font-weight: 800; line-height: 1.2; }
    .barangay-projects-subtitle { margin-top: 4px; color: var(--bp-muted); font-size: 0.875rem; }
    .barangay-projects-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; padding: 16px; border: 1px solid var(--bp-line); border-radius: 12px; background: var(--bp-surface); }
    .barangay-projects-search { position: relative; flex: 1; max-width: 400px; }
    .barangay-projects-search svg { position: absolute; left: 14px; top: 50%; width: 18px; height: 18px; transform: translateY(-50%); color: var(--bp-muted); }
    .barangay-projects-search input { width: 100%; padding: 10px 14px 10px 42px; border: 1px solid var(--bp-line); border-radius: 100px; outline: none; background: var(--bp-bg); color: var(--bp-ink); }
    .barangay-projects-filters { display: flex; flex-wrap: wrap; gap: 8px; }
    .barangay-projects-filter { padding: 8px 16px; border: 1px solid var(--bp-line); border-radius: 100px; background: var(--bp-bg); color: var(--bp-ink); font-size: 0.8125rem; font-weight: 600; cursor: pointer; }
    .barangay-projects-filter.active { background: var(--bp-ink); color: var(--bp-surface); }
    .barangay-projects-card { overflow: hidden; border: 1px solid var(--bp-line); border-radius: 16px; background: var(--bp-surface); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .barangay-projects-tablewrap { overflow-x: auto; }
    .barangay-projects-table { width: 100%; min-width: 640px; border-collapse: collapse; font-size: 0.875rem; }
    .barangay-projects-table th { padding: 14px 20px; background: var(--bp-surface-hover); border-bottom: 1px solid var(--bp-line); color: var(--bp-muted); font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.08em; text-align: left; text-transform: uppercase; }
    .barangay-projects-table td { padding: 16px 20px; border-bottom: 1px solid var(--bp-line); color: var(--bp-ink); }
    .barangay-projects-table tr:last-child td { border-bottom: 0; }
    .barangay-projects-table tr:hover td { background: var(--bp-surface-hover); }
    .barangay-project-name { display: flex; align-items: center; gap: 12px; font-weight: 700; }
    .barangay-project-name-text { display: block; min-width: 0; }
    .barangay-project-name-text > span:first-child { display: block; }
    .barangay-project-code { margin-top: 4px; color: var(--bp-muted); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
    .barangay-project-avatar { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; color: #fff; font-size: 0.75rem; font-weight: 800; }
    .barangay-project-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .barangay-project-status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-planning { background: #fef3c7; color: #b45309; }
    .status-bidding { background: #fef3c7; color: #b45309; }
    .status-award { background: #ede9fe; color: #6d28d9; }
    .status-ongoing { background: #dbeafe; color: #1d4ed8; }
    .status-hold { background: #fee2e2; color: #b91c1c; }
    .status-completed { background: #d1fae5; color: #047857; }
    .status-cancelled { background: #f3f4f6; color: #4b5563; }
    .barangay-project-view { display: inline-flex; align-items: center; justify-content: center; padding: 7px 14px; border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; background: #dbeafe; color: #1d4ed8; font-size: 0.75rem; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .barangay-project-view:hover { background: #3b82f6; color: #fff; }
    html.dark-mode .barangay-project-view { background: rgba(59,130,246,0.16); border-color: rgba(59,130,246,0.35); color: #60a5fa; }
    html.dark-mode .barangay-project-view:hover { background: #3b82f6; color: #fff; }
    .barangay-projects-mobile { display: none; flex-direction: column; gap: 12px; padding: 16px; }
    .barangay-project-mobile-card { padding: 16px; border: 1px solid var(--bp-line); border-radius: 24px; background: var(--bp-surface-hover); }
    .barangay-project-mobile-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .barangay-project-mobile-name { color: var(--bp-ink); font-weight: 700; }
    .barangay-project-mobile-top .barangay-project-code { display: block; }
    .barangay-project-mobile-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; color: var(--bp-muted); font-size: 0.8125rem; }
    .barangay-project-mobile-meta strong { display: block; margin-top: 4px; color: var(--bp-ink); }
    html.dark-mode .barangay-projects-card { border-color: #475569; background: #1e293b; }
    html.dark-mode .barangay-projects-tablewrap { border: 1px solid #475569; border-radius: 16px; box-shadow: 0 0 0 1px rgba(148,163,184,0.24); }
    html.dark-mode .barangay-projects-table th { background: #111827; border-bottom-color: #334155; color: #94a3b8; }
    html.dark-mode .barangay-projects-table td { background: #1e293b; border-bottom-color: #334155; color: #cbd5e1; }
    html.dark-mode .barangay-projects-table tr:hover td { background: #243247; }
    html.dark-mode .barangay-project-code { color: #94a3b8; }
    html.dark-mode .status-bidding { background: rgba(245,158,11,0.16); color: #fbbf24; }
    html.dark-mode .status-award { background: rgba(139,92,246,0.16); color: #a78bfa; }
    @media (max-width: 767px) {
        .barangay-projects-page { padding: 24px 16px; }
        .barangay-projects-header { display: block; }
        .barangay-projects-icon { display: none; }
        .barangay-projects-toolbar { display: none; }
        .barangay-projects-tablewrap { display: none; }
        .barangay-projects-mobile { display: flex; }
    }
</style>

<div class="barangay-projects-page">
    <div class="barangay-projects-header">
        <div class="barangay-projects-titlewrap">
            <div class="barangay-projects-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg></div>
            <div>
                <h1 class="barangay-projects-title">Barangay Projects</h1>
                <p class="barangay-projects-subtitle">Projects assigned to this barangay will appear here.</p>
            </div>
        </div>
    </div>

    <div class="barangay-projects-toolbar">
        <div class="barangay-projects-search"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg><input type="text" id="barangayProjectSearch" placeholder="Search projects by name or code..."></div>
        <div class="barangay-projects-filters">
            <button type="button" class="barangay-projects-filter active" data-filter="all">All</button>
            <button type="button" class="barangay-projects-filter" data-filter="Planning">Planning</button>
            <button type="button" class="barangay-projects-filter" data-filter="On Going">On Going</button>
            <button type="button" class="barangay-projects-filter" data-filter="Completed">Completed</button>
            <button type="button" class="barangay-projects-filter" data-filter="On Hold">On Hold</button>
        </div>
    </div>

    <div class="barangay-projects-card">
        @if ($projects->isEmpty())
            <div class="p-8 text-center text-sm text-slate-500">No projects have been added yet.</div>
        @else
            <div class="barangay-projects-tablewrap">
                <table class="barangay-projects-table">
                    <thead><tr><th>Project</th><th>Status</th><th>Barangay</th><th>Budget</th><th>Actions</th></tr></thead>
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
                            @endphp
                            <tr class="barangay-project-row" data-status="{{ $project->current_status }}" data-search="{{ strtolower($project->project_name . ' ' . ($project->project_code ?? '')) }}">
                                <td><div class="barangay-project-name"><span class="barangay-project-avatar" style="background:linear-gradient(135deg,#991b1b,#dc2626)">{{ $initials }}</span><span class="barangay-project-name-text"><span>{{ $project->project_name }}</span><span class="barangay-project-code">{{ $project->project_code ?? 'No project code' }}</span></span></div></td>
                                <td><span class="barangay-project-status {{ $statusClass }}">{{ $project->current_status }}</span></td>
                                <td>{{ $project->barangay?->barangay_name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td><a class="barangay-project-view" href="{{ route('barangay.projects.show', $project->project_id) }}">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="barangay-projects-mobile">
                @foreach ($projects as $project)
                    @php
                        $statusClass = match($project->current_status) { 'On Going', 'Implementation' => 'status-ongoing', 'Bidding ongoing' => 'status-bidding', 'Award of contract' => 'status-award', 'On Hold' => 'status-hold', 'Completed' => 'status-completed', 'Cancelled' => 'status-cancelled', default => 'status-planning' };
                    @endphp
                    <div class="barangay-project-mobile-card barangay-project-row" data-status="{{ $project->current_status }}" data-search="{{ strtolower($project->project_name . ' ' . ($project->project_code ?? '')) }}">
                        <div class="barangay-project-mobile-top"><div><span class="barangay-project-mobile-name">{{ $project->project_name }}</span><span class="barangay-project-code">{{ $project->project_code ?? 'No project code' }}</span></div><span class="barangay-project-status {{ $statusClass }}">{{ $project->current_status }}</span></div>
                        <div class="barangay-project-mobile-meta"><div>Barangay<strong>{{ $project->barangay?->barangay_name ?? 'N/A' }}</strong></div><div>Budget<strong>₱{{ number_format($project->approved_budget ?? 0, 2) }}</strong></div><div>Action<strong><a class="barangay-project-view" href="{{ route('barangay.projects.show', $project->project_id) }}">View</a></strong></div></div>
                    </div>
                @endforeach
            </div>
        @endif
        @if($projects->hasPages())<div class="p-6">{{ $projects->links() }}</div>@endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('barangayProjectSearch');
    const filters = document.querySelectorAll('.barangay-projects-filter');
    const rows = document.querySelectorAll('.barangay-project-row');
    let activeFilter = 'all';
    function updateProjects() {
        const query = (search?.value || '').toLowerCase().trim();
        rows.forEach(row => { row.hidden = (activeFilter !== 'all' && row.dataset.status !== activeFilter) || (query && !row.dataset.search.includes(query)); });
    }
    search?.addEventListener('input', updateProjects);
    filters.forEach(filter => filter.addEventListener('click', function () { filters.forEach(item => item.classList.remove('active')); this.classList.add('active'); activeFilter = this.dataset.filter; updateProjects(); }));
});
</script>
@endsection