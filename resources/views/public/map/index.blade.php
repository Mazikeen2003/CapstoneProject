@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-3xl font-bold text-black">Cabuyao City Projects Map</h1>
        <p style="color: #6B7280;">Completed and ongoing public infrastructure projects in Cabuyao City</p>
    </div>

    <div id="map" style="height: 600px; border-radius: 8px; border: 1px solid #B2BEB5;"></div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full" style="background-color: #10b981;"></div>
            <span class="text-gray-700">Completed Projects</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full" style="background-color: #3b82f6;"></div>
            <span class="text-gray-700">Ongoing Projects</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full" style="background-color: #6b7280;"></div>
            <span class="text-gray-700">Other Projects</span>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-300 rounded-lg p-4">
        <p class="text-sm text-blue-700">This map displays only completed and ongoing city projects. For more information, please contact the Cabuyao City Government.</p>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
    // Initialize map centered on Cabuyao
    const map = L.map('map').setView([14.3574, 121.0216], 12);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Fetch public GeoJSON
    fetch('/api/public/projects/geojson')
        .then(r => r.json())
        .then(function(data) {
            const statusColor = {
                'Completed': '#10b981',
                'On Going': '#3b82f6',
                'default': '#6b7280'
            };

            L.geoJSON(data, {
                pointToLayer: function(feature, latlng) {
                    const color = statusColor[feature.properties.status] || statusColor['default'];

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
                    const color = statusColor[props.status] || statusColor['default'];
                    
                    const popup = `
                        <div style="min-width: 250px;">
                            <h4 style="margin: 0 0 8px 0; font-weight: bold; color: #0f1e3d;">${props.name}</h4>
                            <div style="font-size: 12px; color: #666;">
                                <p style="margin: 4px 0;"><strong>Type:</strong> ${props.type}</p>
                                <p style="margin: 4px 0;"><strong>Status:</strong> <span style="color: ${color};"><strong>${props.status}</strong></span></p>
                            </div>
                        </div>
                    `;
                    layer.bindPopup(popup);
                }
            }).addTo(map);
        })
        .catch(err => console.error('Error loading map data:', err));

    // Fit to Cabuyao bounds
    const cabuyaoBounds = [[14.2, 121.0], [14.5, 121.1]];
    map.fitBounds(cabuyaoBounds);
</script>
@endsection