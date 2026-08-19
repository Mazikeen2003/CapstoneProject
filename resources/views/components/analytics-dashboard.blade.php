@php
    $statusOrder = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'On Hold', 'Cancelled'];
    $statusColors = ['#fbbf24', '#f59e0b', '#3b82f6', '#8b5cf6', '#0ea5e9', '#10b981', '#ef4444', '#64748b'];
    $statusAliases = [
        'Planning' => 'Proposed',
        'Procurement' => 'For bidding',
        'Bidding - Success' => 'Award of contract',
        'On Going' => 'Implementation',
    ];
    $lifecycleStatusCounts = collect($byStatus)->reduce(function ($counts, $item, $status) use ($statusAliases) {
        $lifecycleStatus = $statusAliases[$status] ?? $status;
        $counts[$lifecycleStatus] = ($counts[$lifecycleStatus] ?? 0) + ($item['count'] ?? 0);
        return $counts;
    }, []);
    $statusCounts = collect($statusOrder)->map(fn ($status) => $lifecycleStatusCounts[$status] ?? 0)->values();
    $remainingBudget = max(($budgetStats['total_budget'] ?? 0) - ($budgetStats['total_spent'] ?? 0), 0);
    $barangayLabels = isset($byBarangay) ? $byBarangay->take(10)->keys()->values() : collect();
    $barangayValues = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['budget'] ?? 0)->values() : collect();
    $barangayProjectCounts = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['count'] ?? 0)->values() : collect();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div><h1 class="text-3xl font-bold text-slate-900">{{ $heading }}</h1><p class="mt-1 text-sm text-slate-500">Visual overview of project progress and funding.</p></div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
        @foreach ([
            ['Total Projects', $stats['total_projects'], '#0f172a'],
            ['Completed', $stats['completed'], '#10b981'],
            ['Ongoing', $stats['ongoing'], '#3b82f6'],
            ['On Hold', $stats['on_hold'], '#ef4444'],
            ['Total Budget', '₱' . number_format($stats['total_budget'], 0), '#0f172a'],
        ] as [$label, $value, $color])
            <div class="rounded-3xl bg-white p-4 border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-bold" style="color: {{ $color }};">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['Needs attention', $insights['overdue'], 'Overdue projects', '#dc2626'],
            ['Coming up', $insights['due_soon'], 'Due within 30 days', '#d97706'],
            ['Missing updates', $insights['without_updates'], 'Active projects', '#2563eb'],
            ['Budget used', number_format($insights['budget_utilization'], 1) . '%', 'Actual versus approved', '#059669'],
        ] as [$label, $value, $caption, $color])
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-bold" style="color: {{ $color }};">{{ $value }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $caption }}</p>
            </div>
        @endforeach
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Lifecycle Overview</h2>
                <p class="text-sm text-slate-500">Projects grouped by their current lifecycle stage.</p>
            </div>
            <span class="text-xs text-slate-500">Completed and exception statuses remain visible.</span>
        </div>
        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'On Hold', 'Cancelled'] as $lifecycleStatus)
                @php $lifecycleCount = $insights['lifecycle_counts'][$lifecycleStatus] ?? 0; @endphp
                <div class="rounded-2xl bg-slate-50 p-3">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <span class="font-semibold text-slate-700">{{ $lifecycleStatus }}</span>
                        <span class="font-bold text-slate-900">{{ $lifecycleCount }}</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $stats['total_projects'] > 0 ? min(100, ($lifecycleCount / $stats['total_projects']) * 100) : 0 }}%;"></div></div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="rounded-3xl bg-white p-5 border border-slate-200 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <h2 class="text-lg font-bold text-slate-900">Project Status Distribution</h2>
                <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-end sm:gap-3 w-full sm:w-auto">
                    <input type="hidden" name="budget_year" value="{{ $budgetYear }}">
                    <select aria-label="Filter project status by year" name="status_year" class="min-w-[11rem] flex-1 h-10 rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700">
                        <option value="">All years</option>
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" @selected((string) $statusYear === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex h-10 min-w-[7rem] items-center justify-center rounded-full bg-slate-900 px-4 text-sm font-semibold text-white">Filter</button>
                </form>
            </div>
            <div class="h-[320px] sm:h-80"><canvas id="statusChart"></canvas></div>
        </section>
        <section class="rounded-3xl bg-white p-5 border border-slate-200 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <h2 class="text-lg font-bold text-slate-900">Budget Comparison</h2>
                <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-end sm:gap-3 w-full sm:w-auto">
                    <input type="hidden" name="status_year" value="{{ $statusYear }}">
                    <select aria-label="Filter budget comparison by year" name="budget_year" class="min-w-[11rem] flex-1 h-10 rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700">
                        <option value="">All years</option>
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" @selected((string) $budgetYear === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex h-10 min-w-[7rem] items-center justify-center rounded-full bg-slate-900 px-4 text-sm font-semibold text-white">Filter</button>
                </form>
            </div>
            <div class="h-[320px] sm:h-80"><canvas id="budgetChart"></canvas></div>
        </section>
    </div>

    @if (isset($byBarangay))
        <section class="rounded-3xl bg-white p-5 border border-slate-200 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-slate-900">Barangay Budget Share</h2>
            <div class="h-[340px] sm:h-96"><canvas id="barangayChart"></canvas></div>
        </section>
    @endif

    @if ($stats['total_projects'] === 0)
        <div class="rounded-lg border border-blue-300 bg-blue-50 p-4 text-sm text-blue-700">No project data is available yet.</div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const analyticsScrollPosition = sessionStorage.getItem('analyticsScrollPosition');
    if (analyticsScrollPosition !== null) {
        sessionStorage.removeItem('analyticsScrollPosition');
        window.scrollTo(0, Number(analyticsScrollPosition));
    }

    document.querySelectorAll('form[method="GET"]').forEach(form => {
        form.addEventListener('submit', () => {
            sessionStorage.setItem('analyticsScrollPosition', String(window.scrollY));
        });
    });

    const statusLabels = @json($statusOrder);
    const statusCounts = @json($statusCounts);
    const statusColors = @json($statusColors);
    const peso = value => '₱' + Number(value || 0).toLocaleString();
    const smoothAnimation = { duration: 1300, easing: 'easeOutQuart' };
    const smoothHover = { mode: 'nearest', intersect: true, animationDuration: 420 };

    new Chart(document.getElementById('statusChart'), {
        type: 'polarArea',
        data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: statusColors, hoverOffset: 18, borderWidth: 2, borderColor: '#ffffff' }] },
        options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { r: { ticks: { precision: 0 } } }, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('budgetChart'), {
        type: 'bar',
        data: { labels: ['Allocated', 'Spent', 'Remaining'], datasets: [{ data: @json([$budgetStats['total_budget'], $budgetStats['total_spent'], $remainingBudget]), backgroundColor: ['#162347', '#c9a84c', '#10b981'], hoverBackgroundColor: ['#243a70', '#dfbe63', '#34c995'], borderRadius: 8, hoverBorderRadius: 12 }] },
        options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { y: { beginAtZero: true, ticks: { callback: value => peso(value) } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)}` } } } }
    });

    @if (isset($byBarangay))
        const barangayProjectCounts = @json($barangayProjectCounts);
        new Chart(document.getElementById('barangayChart'), {
            type: 'doughnut',
            data: { labels: @json($barangayLabels), datasets: [{ data: @json($barangayValues), backgroundColor: ['#162347', '#c9a84c', '#10b981', '#3b82f6', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6', '#64748b', '#eab308'], hoverOffset: 20, borderWidth: 2, borderColor: '#ffffff' }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, plugins: { legend: { position: 'bottom', labels: { generateLabels: chart => chart.data.labels.map((label, index) => ({ text: `${label} — ${barangayProjectCounts[index] || 0} project(s)`, fillStyle: chart.data.datasets[0].backgroundColor[index], strokeStyle: chart.data.datasets[0].backgroundColor[index], lineWidth: 0, index })) } }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)} · ${barangayProjectCounts[context.dataIndex] || 0} project(s)` } } } }
        });
    @endif
});
</script>
