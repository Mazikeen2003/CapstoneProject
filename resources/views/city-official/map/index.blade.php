@extends('layouts.city')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)]">
    <div class="flex-1 relative" id="map" style="background-color: #f0f0f0;"></div>

    <div class="w-96 bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-lg font-bold text-black">City Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
        </div>
        <div class="p-6">
            @forelse($projects as $project)
                <div class="mb-4 pb-4 border-b border-gray-200 last:border-0">
                    <h3 class="font-bold text-black text-sm">{{ $project->project_name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $project->project_code }}</p>
                    <div class="flex justify-between mt-2 text-xs">
                        <span class="px-2 py-1 rounded" style="background-color: 
                            @if($project->current_status === 'Completed') #d1fae5;
                            @elseif($project->current_status === 'On Going') #dbeafe;
                            @else #fef3c7;
                            @endif">
                            {{ $project->current_status }}
                        </span>
                        <span class="text-gray-700 font-semibold">₱{{ number_format($project->approved_budget ?? 0, 0) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No projects have been added yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([14.3574, 121.0216], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

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

                        const color = statusColor[feature.properties.status] || '#6b7280';

                        return L.circleMarker(latlng, {
                            radius: 8,
                            fillColor: color,
                            color: '#fff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const props = feature.properties;
                        const popup = `
                            <div style="min-width: 250px;">
                                <h4 style="margin: 0 0 8px 0; font-weight: bold; color: #0f1e3d;">${props.name}</h4>
                                <div style="font-size: 12px; color: #666;">
                                    <p style="margin: 4px 0;"><strong>Code:</strong> ${props.code}</p>
                                    <p style="margin: 4px 0;"><strong>Status:</strong> ${props.status}</p>
                                    <p style="margin: 4px 0;"><strong>Barangay:</strong> ${props.barangay}</p>
                                    <p style="margin: 4px 0;"><strong>Budget:</strong> ₱${props.budget.toLocaleString()}</p>
                                </div>
                            </div>
                        `;
                        layer.bindPopup(popup);
                    }
                }).addTo(map);
            })
            .catch(console.error);

        const cabuyaoBounds = [[14.2, 121.0], [14.5, 121.1]];
        map.fitBounds(cabuyaoBounds);
    });
</script>
@endsection