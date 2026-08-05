@extends('layouts.barangay')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-3xl p-5 bg-white border border-slate-200 shadow-sm">
            <p class="text-xs text-slate-500 uppercase tracking-[0.18em]">Total Projects</p>
            <p class="text-3xl font-bold text-slate-900 mt-3">{{ $stats['total_projects'] }}</p>
        </div>

        <div class="rounded-3xl p-5 bg-white border border-slate-200 shadow-sm">
            <p class="text-xs text-slate-500 uppercase tracking-[0.18em]">Ongoing Projects</p>
            <p class="text-3xl font-bold text-slate-900 mt-3">{{ $stats['ongoing'] }}</p>
        </div>

        <div class="rounded-3xl p-5 bg-white border border-slate-200 shadow-sm">
            <p class="text-xs text-slate-500 uppercase tracking-[0.18em]">Completed Projects</p>
            <p class="text-3xl font-bold text-slate-900 mt-3">{{ $stats['completed'] }}</p>
        </div>

        <div class="rounded-3xl p-5 bg-white border border-slate-200 shadow-sm">
            <p class="text-xs text-slate-500 uppercase tracking-[0.18em]">Budget Allocated</p>
            <p class="text-3xl font-bold text-slate-900 mt-3">₱{{ number_format($stats['budget_allocated'] ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Map Section -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900 mb-4">{{ $barangayName }} Project Locations</h2>
        <div id="barangay-map" class="h-[42vh] sm:h-[48vh] md:h-[56vh] overflow-hidden rounded-3xl border border-slate-200"></div>
    </div>

    <!-- Recent Projects -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Recent Projects</h2>
                <p class="text-sm text-slate-500">Latest barangay project activity.</p>
            </div>
        </div>

        @if ($recentProjects->isEmpty())
            <p class="mt-4 text-sm text-slate-500">No projects in this barangay yet.</p>
        @else
            <div class="mt-4 grid gap-4">
                @foreach ($recentProjects as $project)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $project->current_status }}</p>
                            </div>
                            <a href="{{ route('barangay.projects.show', $project->project_id) }}" class="inline-flex rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features?.find(f => f.properties?.kind === 'boundary');
                const boundaryGeoJson = boundaryFeature ? boundaryFeature : (geojson.features?.length ? geojson : null);

                if (!boundaryGeoJson) {
                    console.error('GeoJSON boundary is missing or malformed:', geojson);
                    return;
                }

                const cabuyaoBounds = L.geoJSON(boundaryGeoJson).getBounds();

                const map = L.map('barangay-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(boundaryGeoJson, {
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