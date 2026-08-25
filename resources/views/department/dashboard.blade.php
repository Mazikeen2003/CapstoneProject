@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="admin-dashboard-hero rounded-3xl px-6 py-7 shadow-lg sm:px-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-amber-300">Department workspace</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Department Dashboard</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">A focused view of your projects, locations, and current delivery progress.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="admin-dashboard-stat admin-dashboard-stat-blue rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-600">Total Projects</p>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['total_projects'] }}</p>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-amber rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-600">Ongoing Projects</p>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['ongoing'] }}</p>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-emerald rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-600">Completed Projects</p>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['completed'] }}</p>
        </div>

        <div class="admin-dashboard-stat admin-dashboard-stat-rose rounded-2xl p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-600">Budget Allocated</p>
            <p class="mt-4 text-4xl font-bold text-slate-950">₱{{ number_format($stats['budget_allocated'] ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Map Section -->
    <div class="admin-dashboard-activity overflow-hidden rounded-2xl shadow-sm">
        <div class="admin-card-header border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-900">Project Locations</h2>
            <p class="mt-1 text-sm text-slate-500">Explore the geographic distribution of your department projects.</p>
        </div>
        <div class="p-6">
        <div id="department-map" class="relative z-0 h-[42vh] overflow-hidden rounded-3xl border border-slate-200 sm:h-[48vh] md:h-[56vh]"></div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="admin-dashboard-activity overflow-hidden rounded-2xl shadow-sm">
        <div class="admin-card-header flex flex-col gap-2 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Recent Projects</h2>
                <p class="text-sm text-slate-500">Latest department project activity.</p>
            </div>
        </div>

        <div class="p-6">
        @if ($recentProjects->isEmpty())
            <p class="mt-4 text-sm text-slate-500">No projects yet.</p>
        @else
            <div class="mt-4 grid gap-4">
                @foreach ($recentProjects as $project)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $project->current_status }}</p>
                            </div>
                            <a href="{{ route('department.projects.show', $project->project_id) }}" class="inline-flex rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features?.find(f => f.properties?.kind === 'boundary');
                const geoJsonBoundary = boundaryFeature ? boundaryFeature : (geojson.features?.length ? geojson : null);

                if (!geoJsonBoundary) {
                    console.error('GeoJSON boundary is missing or malformed:', geojson);
                    return;
                }

                const cabuyaoBounds = L.geoJSON(geoJsonBoundary).getBounds();

                const map = L.map('department-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(geoJsonBoundary, {
                    style: {
                        color: '#3b82f6',
                        weight: 2,
                        opacity: 0.6,
                        fillOpacity: 0.1
                    }
                }).addTo(map);

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());

                fetch('{{ route("api.projects.geojson") }}')
                    .then(r => r.json())
                    .then(function(data) {
                        L.geoJSON(data, {
                            pointToLayer: function(feature, latlng) {
                                const statusColor = {
                                    'Planning': '#fbbf24',
                                    'On Going': '#3b82f6',
                                    'On Hold': '#ef4444',
                                    'Completed': '#10b981',
                                    'Cancelled': '#6b7280'
                                };

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: statusColor[feature.properties.status] || '#9CA3AF',
                                    color: '#000',
                                    weight: 2,
                                    opacity: 0.8,
                                    fillOpacity: 0.7
                                });
                            },
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                layer.bindPopup(`
                                    <div class="text-sm">
                                        <h4 class="font-bold">${props.name}</h4>
                                        <p class="text-xs text-gray-600">${props.code}</p>
                                        <p class="text-xs"><strong>Status:</strong> ${props.status}</p>
                                        <p class="text-xs"><strong>Barangay:</strong> ${props.barangay}</p>
                                        <p class="text-xs"><strong>Budget:</strong> ₱${parseInt(props.budget).toLocaleString()}</p>
                                        <a href="${props.url}" class="text-blue-600 text-xs">View Details</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    })
                    .catch(err => console.error('Failed to load projects:', err));

                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(err => console.error('Failed to load map:', err));
    });
</script>
@endsection
