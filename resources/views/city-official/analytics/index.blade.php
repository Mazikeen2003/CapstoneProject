@extends('layouts.city')

@section('content')
    @include('components.analytics-dashboard', ['heading' => 'Citywide Analytics'])

    <div class="mt-8 rounded-lg bg-white p-6 shadow-sm" style="border: 1px solid #B2BEB5;">
        <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-xl font-bold" style="color: #0f1e3d;">Portal Usage Analytics</h2>
                <p class="text-sm text-slate-500">Tracks anonymous visits to the public transparency portal.</p>
            </div>
            <p class="text-xs text-slate-500">Visits by logged-in staff are not counted.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            @foreach ([
                ['Total Visits', $portalVisitStats['total_visits'] ?? 0, '#0f1e3d'],
                ['Visits Today', $portalVisitStats['visits_today'] ?? 0, '#c9a84c'],
                ['Visits This Week', $portalVisitStats['visits_this_week'] ?? 0, '#0f1e3d'],
                ['Visits This Month', $portalVisitStats['visits_this_month'] ?? 0, '#c9a84c'],
                ['Est. Unique Visitors', $portalVisitStats['estimated_unique_visitors'] ?? 0, '#0f1e3d'],
            ] as [$label, $value, $color])
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-bold" style="color: {{ $color }};">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
            <section class="rounded-lg border border-slate-200 bg-slate-50 p-4 xl:col-span-2">
                <h3 class="mb-3 text-lg font-semibold" style="color: #0f1e3d;">Daily Visits (Last 30 Days)</h3>
                <div class="h-80">
                    <canvas id="portalVisitTrendChart"></canvas>
                </div>
            </section>
            <section class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <h3 class="mb-3 text-lg font-semibold" style="color: #0f1e3d;">Page Breakdown</h3>
                <div class="h-64">
                    <canvas id="portalVisitBreakdownChart"></canvas>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dailyLabels = @json(collect($dailyVisits)->pluck('date'));
            const mapSeries = @json(collect($dailyVisits)->pluck('map'));
            const analyticsSeries = @json(collect($dailyVisits)->pluck('analytics'));
            const pageBreakdownLabels = ['Map', 'Analytics'];
            const pageBreakdownValues = [@json($pageBreakdown['map'] ?? 0), @json($pageBreakdown['analytics'] ?? 0)];

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: {
                    legend: { position: 'bottom' }
                }
            };

            new Chart(document.getElementById('portalVisitTrendChart'), {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [
                        {
                            label: 'Map Visits',
                            data: mapSeries,
                            borderColor: '#0f1e3d',
                            backgroundColor: 'rgba(15, 30, 61, 0.12)',
                            fill: false,
                            tension: 0.35,
                            pointBackgroundColor: '#0f1e3d',
                            pointBorderColor: '#0f1e3d'
                        },
                        {
                            label: 'Analytics Visits',
                            data: analyticsSeries,
                            borderColor: '#c9a84c',
                            backgroundColor: 'rgba(201, 168, 76, 0.18)',
                            fill: false,
                            tension: 0.35,
                            pointBackgroundColor: '#c9a84c',
                            pointBorderColor: '#c9a84c'
                        }
                    ]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            new Chart(document.getElementById('portalVisitBreakdownChart'), {
                type: 'doughnut',
                data: {
                    labels: pageBreakdownLabels,
                    datasets: [{
                        data: pageBreakdownValues,
                        backgroundColor: ['#0f1e3d', '#c9a84c'],
                        borderColor: ['#ffffff', '#ffffff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
@endsection
