@extends('layouts.city')

@section('content')
<style>
    .city-portal-usage {
        overflow: hidden;
        border: 1px solid #dbe4ef;
        border-radius: 1.5rem;
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 36px rgba(15, 23, 42, .07);
    }
    .city-portal-usage__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.5rem 1.5rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .city-portal-usage__eyebrow, .city-portal-usage__title, .city-portal-usage__chart-title { font-family: "Plus Jakarta Sans", "Inter", sans-serif; }
    .city-portal-usage__eyebrow { color: #0f9f77; font-size: .69rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .city-portal-usage__title { margin: .25rem 0 0; color: #0f172a; font-size: 1rem; font-weight: 800; letter-spacing: -.01em; }
    .city-portal-usage__description, .city-portal-usage__note { margin: .3rem 0 0; color: #64748b; font-size: .75rem; }
    .city-portal-usage__note { padding: .5rem .7rem; border: 1px solid #dbe4ef; border-radius: .6rem; background: #f8fafc; white-space: nowrap; }
    .city-portal-usage__body { padding: 1.5rem; }
    .city-portal-usage__metric { padding: 1rem; border: 1px solid #dbe4ef; border-radius: 1rem; background: #fff; }
    .city-portal-usage__label { color: #64748b; font-size: .68rem; font-weight: 800; letter-spacing: .055em; text-transform: uppercase; }
    .city-portal-usage__value { margin-top: .45rem; color: #0f172a; font-size: 1.7rem; font-weight: 800; line-height: 1; }
    .city-portal-usage__value.is-accent { color: #d79b16; }
    .city-portal-usage__chart { padding: 1.1rem; border: 1px solid #dbe4ef; border-radius: 1rem; background: rgba(255,255,255,.72); }
    .city-portal-usage__chart-title { margin: 0 0 1rem; color: #1e293b; font-size: 1rem; font-weight: 800; letter-spacing: -.01em; }
    /* Match the shared analytics cards exactly in dark mode. */
    html.dark-mode .city-portal-usage { border-color: #020617; background: #141321; box-shadow: inset 0 0 0 1px #1e293b, 0 1px 3px rgba(0,0,0,.15), 0 4px 12px rgba(0,0,0,.1); }
    html.dark-mode .city-portal-usage__top { border-color: #1e293b; }
    html.dark-mode .city-portal-usage__title, html.dark-mode .city-portal-usage__value, html.dark-mode .city-portal-usage__chart-title { color: #f8fafc; }
    html.dark-mode .city-portal-usage__description, html.dark-mode .city-portal-usage__note, html.dark-mode .city-portal-usage__label { color: #94a3b8; }
    html.dark-mode .city-portal-usage__note, html.dark-mode .city-portal-usage__metric, html.dark-mode .city-portal-usage__chart { border-color: #1e293b; background: #141321; }
    html.dark-mode .city-portal-usage__value.is-accent { color: #fbbf24; }
    @media (max-width: 640px) {
        .city-portal-usage__top { padding: 1.2rem; flex-direction: column; }
        .city-portal-usage__note { white-space: normal; }
        .city-portal-usage__body { padding: 1rem; }
    }
</style>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    @include('components.analytics-dashboard', ['heading' => 'Citywide Analytics'])

    <section class="city-portal-usage">
        <div class="city-portal-usage__top">
            <div>
                <p class="city-portal-usage__eyebrow">Transparency portal</p>
                <h2 class="city-portal-usage__title">Portal Usage Analytics</h2>
                <p class="city-portal-usage__description">Anonymous visits to the public transparency portal.</p>
            </div>
            <p class="city-portal-usage__note">Staff visits are excluded</p>
        </div>

        <div class="city-portal-usage__body">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ([
                ['Total Visits', $portalVisitStats['total_visits'] ?? 0, '#0f1e3d'],
                ['Visits Today', $portalVisitStats['visits_today'] ?? 0, '#c9a84c'],
                ['Visits This Week', $portalVisitStats['visits_this_week'] ?? 0, '#0f1e3d'],
                ['Visits This Month', $portalVisitStats['visits_this_month'] ?? 0, '#c9a84c'],
                ['Est. Unique Visitors', $portalVisitStats['estimated_unique_visitors'] ?? 0, '#0f1e3d'],
            ] as [$label, $value, $color])
                <div class="city-portal-usage__metric">
                    <p class="city-portal-usage__label">{{ $label }}</p>
                    <p class="city-portal-usage__value {{ $color === '#c9a84c' ? 'is-accent' : '' }}">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 xl:grid-cols-3">
            <section class="city-portal-usage__chart xl:col-span-2">
                <h3 class="city-portal-usage__chart-title">Daily Visits <span class="font-normal opacity-60">· Last 30 days</span></h3>
                <div class="h-80 sm:h-[28rem]"><canvas id="portalVisitTrendChart"></canvas></div>
            </section>
            <section class="city-portal-usage__chart">
                <h3 class="city-portal-usage__chart-title">Page Breakdown</h3>
                <div class="h-64 sm:h-72"><canvas id="portalVisitBreakdownChart"></canvas></div>
            </section>
        </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dailyLabels = @json(collect($dailyVisits)->pluck('date'));
        const mapSeries = @json(collect($dailyVisits)->pluck('map'));
        const analyticsSeries = @json(collect($dailyVisits)->pluck('analytics'));
        const pageBreakdownLabels = ['Map', 'Analytics'];
        const pageBreakdownValues = [@json($pageBreakdown['map'] ?? 0), @json($pageBreakdown['analytics'] ?? 0)];
        const darkMode = document.documentElement.classList.contains('dark-mode');
        const chartTextColor = darkMode ? '#e2e8f0' : '#334155';
        const chartGridColor = darkMode ? 'rgba(148, 163, 184, 0.22)' : 'rgba(148, 163, 184, 0.18)';

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1200, easing: 'easeOutQuart' },
            plugins: { legend: { position: 'bottom' } }
        };

        new Chart(document.getElementById('portalVisitTrendChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [
                    {
                        label: 'Map Visits',
                        data: mapSeries,
                        borderColor: darkMode ? '#60a5fa' : '#0f1e3d',
                        backgroundColor: darkMode ? 'rgba(96, 165, 250, 0.16)' : 'rgba(15, 30, 61, 0.12)',
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: darkMode ? '#60a5fa' : '#0f1e3d',
                        pointBorderColor: darkMode ? '#60a5fa' : '#0f1e3d'
                    },
                    {
                        label: 'Analytics Visits',
                        data: analyticsSeries,
                        borderColor: darkMode ? '#fbbf24' : '#c9a84c',
                        backgroundColor: darkMode ? 'rgba(251, 191, 36, 0.18)' : 'rgba(201, 168, 76, 0.18)',
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: darkMode ? '#fbbf24' : '#c9a84c',
                        pointBorderColor: darkMode ? '#fbbf24' : '#c9a84c'
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: { y: { beginAtZero: true, grid: { color: chartGridColor }, ticks: { precision: 0, color: chartTextColor } }, x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } } }
            }
        });

        new Chart(document.getElementById('portalVisitBreakdownChart'), {
            type: 'doughnut',
            data: {
                labels: pageBreakdownLabels,
                datasets: [{
                    data: pageBreakdownValues,
                    backgroundColor: darkMode ? ['#60a5fa', '#fbbf24'] : ['#0f1e3d', '#c9a84c'],
                    borderColor: darkMode ? ['#1e293b', '#1e293b'] : ['#ffffff', '#ffffff'],
                    borderWidth: 2
                }]
            },
            options: {
                ...commonOptions,
                plugins: { legend: { position: 'bottom', labels: { color: chartTextColor } } }
            }
        });
    });
</script>
@endsection
