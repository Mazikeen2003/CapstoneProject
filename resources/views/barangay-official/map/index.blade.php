@extends('layouts.barangay')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)]">
    <div class="flex-1 relative" id="map" style="background-color: #f0f0f0;"></div>

    <div class="w-96 bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-lg font-bold text-black">Barangay Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Projects in your barangay</p>
        </div>
        <div class="p-6">
            @forelse($projects as $project)
                <div class="mb-4 pb-4 border-b border-gray-200 last:border-0">
                    <h3 class="font-bold text-black text-sm">{{ $project->project_name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $project->project_code }}</p>
                    <div class="space-y-1 mt-2 text-xs">
                        <p><strong>Status:</strong> {{ $project->current_status }}</p>
                        <p><strong>Budget:</strong> ₱{{ number_format($project->approved_budget ?? 0, 0) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No projects in your barangay yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    const map = L.map('map').setView([14.3574, 121.0216], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'OpenStreetMap contributors'
    }).addTo(map);

    const statusColor = {
        'Planning': '#fbbf24',
        'On Going': '#3b82f6',
        'On Hold': '#ef4444',
        'Completed': '#10b981',
        'Cancelled': '#6b7280'
    };

    fetch('{{ route("api.projects.geojson") }}')
        .then(r => r.json())
        .then(data => {
            L.geoJSON(data, {
                pointToLayer: (f, ll) => L.circleMarker(ll, {
                    radius: 8,
                    fillColor: statusColor[f.properties.status] || '#6b7280',
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }),
                onEachFeature: (f, layer) => {
                    layer.bindPopup(`
                        <strong>${f.properties.name}</strong><br/>
                        Status: ${f.properties.status}<br/>
                        Budget: ₱${f.properties.budget?.toLocaleString() || 0}
                    `);
                }
            }).addTo(map);
        })
        .catch(err => console.error('Error loading map:', err));

    map.fitBounds([[14.2, 121.0], [14.5, 121.1]]);
</script>
@endsection