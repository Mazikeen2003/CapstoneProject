<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | City Transparency Portal</title>
    @include('layouts.favicon')
    @include('components.theme-init')

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
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
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

            <div class="flex items-center gap-2">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button bg-slate-900 text-white px-5 py-2.5 rounded-md font-semibold text-sm hover:opacity-90 transition-all duration-200 shrink-0">Login</a>
            </div>
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
        $remainingBudget = max(($budgetStats['total_budget'] ?? 0) - ($budgetStats['total_spent'] ?? 0), 0);
        $totalBudget = (float) ($stats['total_budget'] ?? 0);
        $totalBudgetDisplay = $totalBudget >= 1000000000
            ? '₱' . number_format($totalBudget / 1000000000, 1) . 'B'
            : ($totalBudget >= 1000000 ? '₱' . number_format($totalBudget / 1000000, 1) . 'M' : '₱' . number_format($totalBudget, 0));
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
                ['Total Budget', $totalBudgetDisplay, 'text-slate-900'],
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
                @foreach ($statusOrder as $statusIndex => $lifecycleStatus)
                    @php $lifecycleCount = $insights['lifecycle_counts'][$lifecycleStatus] ?? 0; @endphp
                    <div class="rounded-xl bg-slate-50 p-3">
                        <div class="flex items-center justify-between gap-3 text-sm"><span class="font-semibold text-slate-700">{{ $lifecycleStatus }}</span><span class="font-bold text-slate-900">{{ $lifecycleCount }}</span></div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full" style="width: {{ $stats['total_projects'] > 0 ? min(100, ($lifecycleCount / $stats['total_projects']) * 100) : 0 }}%; background-color: {{ $statusColors[$statusIndex] }};"></div></div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Project Status Distribution</h2>
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
                <div class="h-80"><canvas id="statusChart"></canvas></div>
            </section>
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Budget Comparison</h2>
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
                <div class="h-80"><canvas id="budgetChart"></canvas></div>
            </section>
        </div>

        @if (isset($byBarangay))
            <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="barangay-budget-share-heading mb-4 text-lg font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">Barangay Budget Share</h2>
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
        window.addEventListener('theme:changed', () => window.location.reload());

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
        const darkMode = document.documentElement.classList.contains('dark-mode');
        const chartTextColor = darkMode ? '#e2e8f0' : '#334155';
        const chartGridColor = darkMode ? 'rgba(148, 163, 184, 0.22)' : 'rgba(148, 163, 184, 0.18)';
        Chart.defaults.color = chartTextColor;
        const statusColors = darkMode
            ? ['#fcd34d', '#fbbf24', '#60a5fa', '#c4b5fd', '#38bdf8', '#34d399', '#fb7185', '#94a3b8']
            : @json($statusColors);
        const peso = value => '₱' + Number(value || 0).toLocaleString();
        const smoothAnimation = { duration: 1300, easing: 'easeOutQuart' };
        const smoothHover = { mode: 'nearest', intersect: true, animationDuration: 420 };

        new Chart(document.getElementById('statusChart'), {
            type: 'polarArea',
            data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: statusColors, hoverOffset: 18, borderWidth: 2, borderColor: '#ffffff' }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { r: { ticks: { precision: 0, color: chartTextColor }, grid: { color: chartGridColor } } }, plugins: { legend: { position: 'bottom', labels: { color: chartTextColor } } } }
        });

        new Chart(document.getElementById('budgetChart'), {
            type: 'bar',
            data: { labels: ['Allocated', 'Spent', 'Remaining'], datasets: [{ data: @json([$budgetStats['total_budget'], $budgetStats['total_spent'], $remainingBudget]), backgroundColor: darkMode ? ['#60a5fa', '#fbbf24', '#34d399'] : ['#0f172a', '#059669', '#10b981'], hoverBackgroundColor: darkMode ? ['#93c5fd', '#fcd34d', '#6ee7b7'] : ['#1e293b', '#047857', '#34d399'], borderRadius: 8, hoverBorderRadius: 12 }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { y: { beginAtZero: true, grid: { color: chartGridColor }, ticks: { color: chartTextColor, callback: value => peso(value) } }, x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)}` } } } }
        });

        @if (isset($byBarangay))
            const barangayProjectCounts = @json($barangayProjectCounts);
            new Chart(document.getElementById('barangayChart'), {
                type: 'doughnut',
                data: { labels: @json($barangayLabels), datasets: [{ data: @json($barangayValues), backgroundColor: darkMode ? ['#60a5fa', '#fbbf24', '#34d399', '#93c5fd', '#c4b5fd', '#fb923c', '#f472b6', '#2dd4bf', '#94a3b8', '#fcd34d'] : ['#0f172a', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6', '#64748b', '#eab308'], hoverOffset: 20, borderWidth: 2, borderColor: darkMode ? '#1e293b' : '#ffffff' }] },
                options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, plugins: { legend: { position: 'bottom', labels: { color: chartTextColor, generateLabels: chart => { const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart); return labels.map((item, index) => ({ ...item, text: `${chart.data.labels[index]} — ${barangayProjectCounts[index] || 0} project(s)` })); } } }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)} · ${barangayProjectCounts[context.dataIndex] || 0} project(s)` } } } }
            });
        @endif
    });
    </script>
</body>
</html>