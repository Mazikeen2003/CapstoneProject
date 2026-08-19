<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | City Transparency Portal</title>
    @include('layouts.favicon')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(248, 249, 255, 0.8);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
        <nav class="relative flex items-center py-4 w-full mx-auto px-12 justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-slate-900 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tighter text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-500 opacity-70" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center text-xs uppercase tracking-widest gap-6" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Home</a>
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Analytics</a>
            </div>

            <a href="{{ route('login') }}"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-md font-semibold text-sm hover:opacity-90 transition-all duration-200 shrink-0">
                Login
            </a>
        </nav>
        <div class="md:hidden border-t border-slate-200 bg-white">
            <div class="flex flex-wrap items-center justify-center gap-3 px-4 py-3 text-xs uppercase tracking-widest text-slate-600">
                <a href="{{ url('/') }}" class="hover:text-emerald-700 transition-colors">Home</a>
                <a href="{{ route('public.map') }}" class="hover:text-emerald-700 transition-colors">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-emerald-700 font-semibold">Analytics</a>
            </div>
        </div>
    </header>
        </nav>
    </header>

    @php
        $statusOrder = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'On Hold', 'Cancelled'];
        $statusColors = ['#fbbf24', '#f59e0b', '#3b82f6', '#8b5cf6', '#0ea5e9', '#10b981', '#ef4444', '#64748b'];
        $statusAliases = ['Planning' => 'Proposed', 'Procurement' => 'For bidding', 'Bidding - Success' => 'Award of contract', 'On Going' => 'Implementation'];
        $lifecycleStatusCounts = collect($byStatus)->reduce(function ($counts, $item, $status) use ($statusAliases) {
            $lifecycleStatus = $statusAliases[$status] ?? $status;
            $counts[$lifecycleStatus] = ($counts[$lifecycleStatus] ?? 0) + ($item['count'] ?? 0);
            return $counts;
        }, []);
        $statusCounts = collect($statusOrder)->map(fn ($status) => $lifecycleStatusCounts[$status] ?? 0)->values();
        $remainingBudget = max(($stats['total_budget'] ?? 0) - ($stats['total_spent'] ?? 0), 0);
        $barangayLabels = isset($byBarangay) ? $byBarangay->take(10)->keys()->values() : collect();
        $barangayValues = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['budget'] ?? 0)->values() : collect();
        $barangayProjectCounts = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['count'] ?? 0)->values() : collect();
    @endphp

    <main class="max-w-7xl mx-auto px-6 md:px-12 py-10 space-y-8">
        <div>
            <span class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-bold mb-2 block" style="font-family:'Public Sans',sans-serif;">Transparency Tool</span>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tighter text-slate-900" style="font-family:'Manrope',sans-serif;">Public Project Analytics</h1>
            <p class="text-slate-600 mt-2">Visual overview of project progress and funding.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            @foreach ([
                ['Total Projects', $stats['total_projects'], 'text-slate-900'],
                ['Completed', $stats['completed'], 'text-emerald-700'],
                ['Ongoing', $stats['ongoing'], 'text-blue-600'],
                ['On Hold', $stats['on_hold'], 'text-red-500'],
                ['Total Budget', '₱' . number_format($stats['total_budget'], 0), 'text-slate-900'],
            ] as [$label, $value, $colorClass])
                <div class="rounded-xl bg-white border border-slate-200 shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500" style="font-family:'Public Sans',sans-serif;">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-extrabold {{ $colorClass }}" style="font-family:'Manrope',sans-serif;">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['Needs attention', $insights['overdue'], 'Overdue projects', 'text-red-600'],
                ['Coming up', $insights['due_soon'], 'Due within 30 days', 'text-amber-600'],
                ['Missing updates', $insights['without_updates'], 'Active projects', 'text-blue-600'],
                ['Budget used', number_format($insights['budget_utilization'], 1) . '%', 'Actual versus approved', 'text-emerald-600'],
            ] as [$label, $value, $caption, $colorClass])
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-extrabold {{ $colorClass }}">{{ $value }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $caption }}</p>
                </div>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
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
                    <div class="rounded-xl bg-slate-50 p-3">
                        <div class="flex items-center justify-between gap-3 text-sm"><span class="font-semibold text-slate-700">{{ $lifecycleStatus }}</span><span class="font-bold text-slate-900">{{ $lifecycleCount }}</span></div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $stats['total_projects'] > 0 ? min(100, ($lifecycleCount / $stats['total_projects']) * 100) : 0 }}%;"></div></div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="mb-4 text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Project Status Distribution</h2>
                <div class="h-80"><canvas id="statusChart"></canvas></div>
            </section>
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="mb-4 text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Budget Comparison</h2>
                <div class="h-80"><canvas id="budgetChart"></canvas></div>
            </section>
        </div>

        @if (isset($byBarangay))
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="mb-4 text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Barangay Budget Share</h2>
                <div class="h-96"><canvas id="barangayChart"></canvas></div>
            </section>
        @endif

        @if ($stats['total_projects'] === 0)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">No project data is available yet.</div>
        @endif
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-slate-100 border-t border-slate-200 mt-12">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 w-full max-w-7xl mx-auto">
            <div class="mb-8 md:mb-0">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-slate-900 rounded flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xs">account_balance</span>
                    </div>
                    <span class="font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                </div>
                <p class="text-xs uppercase tracking-widest text-slate-500">© {{ date('Y') }} Cabuyao City Government. All rights reserved.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8 text-xs uppercase tracking-widest">
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-600 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4">Public Map</a>
                <a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-600 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4">Login</a>
            </div>
        </div>
    </footer>

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
            data: { labels: ['Allocated', 'Spent', 'Remaining'], datasets: [{ data: @json([$stats['total_budget'], $stats['total_spent'], $remainingBudget]), backgroundColor: ['#0f172a', '#059669', '#10b981'], hoverBackgroundColor: ['#1e293b', '#047857', '#34d399'], borderRadius: 8, hoverBorderRadius: 12 }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { y: { beginAtZero: true, ticks: { callback: value => peso(value) } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)}` } } } }
        });

        @if (isset($byBarangay))
            const barangayProjectCounts = @json($barangayProjectCounts);
            new Chart(document.getElementById('barangayChart'), {
                type: 'doughnut',
                data: { labels: @json($barangayLabels), datasets: [{ data: @json($barangayValues), backgroundColor: ['#0f172a', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6', '#64748b', '#eab308'], hoverOffset: 20, borderWidth: 2, borderColor: '#ffffff' }] },
                options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, plugins: { legend: { position: 'bottom', labels: { generateLabels: chart => chart.data.labels.map((label, index) => ({ text: `${label} — ${barangayProjectCounts[index] || 0} project(s)`, fillStyle: chart.data.datasets[0].backgroundColor[index], strokeStyle: chart.data.datasets[0].backgroundColor[index], lineWidth: 0, index })) } }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)} · ${barangayProjectCounts[context.dataIndex] || 0} project(s)` } } } }
            });
        @endif
    });
    </script>
</body>
</html>