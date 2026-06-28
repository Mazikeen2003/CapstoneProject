@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Department Dashboard</h1>
        <p style="color: #6B7280;">Department workspace for Cabuyao City Government</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-lg p-6 bg-white" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Total Projects</p>
            <p class="text-3xl font-bold text-black mt-2">{{ $stats['total_projects'] }}</p>
        </div>

        <div class="rounded-lg p-6 bg-white" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Ongoing Projects</p>
            <p class="text-3xl font-bold text-black mt-2">{{ $stats['ongoing'] }}</p>
        </div>

        <div class="rounded-lg p-6 bg-white" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Completed Projects</p>
            <p class="text-3xl font-bold text-black mt-2">{{ $stats['completed'] }}</p>
        </div>

        <div class="rounded-lg p-6 bg-white" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Budget Allocated</p>
            <p class="text-3xl font-bold text-black mt-2">₱{{ number_format($stats['budget_allocated'] ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Map Section -->
    <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-bold text-black mb-4">Project Locations</h2>
        <div id="department-map" class="h-96 overflow-hidden rounded-lg border border-gray-300"></div>
    </div>

    <!-- Recent Projects -->
    <div class="rounded-lg p-6 bg-white" style="border: 1px solid #B2BEB5;">
        <h2 class="text-xl font-bold text-black mb-4">Recent Projects</h2>
        @if ($recentProjects->isEmpty())
            <p class="text-sm text-gray-500">No projects yet.</p>
        @else
            <div class="space-y-2">
                @foreach ($recentProjects as $project)
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-black">{{ $project->project_name }}</p>
                            <p class="text-xs text-gray-500">{{ $project->current_status }}</p>
                        </div>
                        <a href="{{ route('department.projects.show', $project->project_id) }}" class="text-blue-600 text-sm">View</a>
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
                const boundaryFeature = geojson.features.find(f => f.properties.kind === 'boundary');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const boundedArea = cabuyaoBounds.pad(0.02);

                const map = L.map('department-map', {
                    maxBounds: boundedArea,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());

                // Fetch and display projects as GeoJSON
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
                                    fillColor: statusColor[feature.properties.status] || '#gray-400',
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