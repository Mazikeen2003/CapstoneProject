@php
    $statusOrder = ['Planning', 'On Going', 'On Hold', 'Completed'];
    $statusColors = ['#fbbf24', '#3b82f6', '#ef4444', '#10b981'];
    $statusCounts = collect($statusOrder)->map(fn ($status) => $byStatus[$status]['count'] ?? 0)->values();
    $remainingBudget = max(($stats['total_budget'] ?? 0) - ($stats['total_spent'] ?? 0), 0);
    $barangayLabels = isset($byBarangay) ? $byBarangay->take(10)->keys()->values() : collect();
    $barangayValues = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['budget'] ?? 0)->values() : collect();
    $barangayProjectCounts = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['count'] ?? 0)->values() : collect();
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">{{ $heading }}</h1>
        <p class="mt-1 text-sm text-slate-500">Visual overview of project progress and funding.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        @foreach ([
            ['Total Projects', $stats['total_projects'], '#0f172a'],
            ['Completed', $stats['completed'], '#10b981'],
            ['Ongoing', $stats['ongoing'], '#3b82f6'],
            ['On Hold', $stats['on_hold'], '#ef4444'],
            ['Total Budget', '₱' . number_format($stats['total_budget'], 0), '#0f172a'],
        ] as [$label, $value, $color])
            <div class="rounded-lg bg-white p-4" style="border: 1px solid #B2BEB5;">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-bold" style="color: {{ $color }};">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="rounded-lg bg-white p-5" style="border: 1px solid #B2BEB5;">
            <h2 class="mb-4 text-lg font-bold text-black">Project Status Distribution</h2>
            <div class="h-80"><canvas id="statusChart"></canvas></div>
        </section>
        <section class="rounded-lg bg-white p-5" style="border: 1px solid #B2BEB5;">
            <h2 class="mb-4 text-lg font-bold text-black">Budget Comparison</h2>
            <div class="h-80"><canvas id="budgetChart"></canvas></div>
        </section>
    </div>

    @if (isset($byBarangay))
        <section class="rounded-lg bg-white p-5" style="border: 1px solid #B2BEB5;">
            <h2 class="mb-4 text-lg font-bold text-black">Barangay Budget Share</h2>
            <div class="h-96"><canvas id="barangayChart"></canvas></div>
        </section>
    @endif

    @if ($stats['total_projects'] === 0)
        <div class="rounded-lg border border-blue-300 bg-blue-50 p-4 text-sm text-blue-700">No project data is available yet.</div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
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
        data: { labels: ['Allocated', 'Spent', 'Remaining'], datasets: [{ data: @json([$stats['total_budget'], $stats['total_spent'], $remainingBudget]), backgroundColor: ['#162347', '#c9a84c', '#10b981'], hoverBackgroundColor: ['#243a70', '#dfbe63', '#34c995'], borderRadius: 8, hoverBorderRadius: 12 }] },
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
