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
        <div class="p-6 text-sm text-gray-500">
            No projects have been added yet.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(feature => feature.properties.kind === 'boundary');
                const barangays = geojson.features.filter(feature => feature.properties.kind === 'barangay');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const map = L.map('map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                barangays.forEach(function(barangay) {
                    const lng = barangay.geometry.coordinates[0];
                    const lat = barangay.geometry.coordinates[1];
                    L.circleMarker([lat, lng], {
                        radius: 8,
                        fillColor: '#e5e7eb',
                        color: '#6b7280',
                        weight: 2,
                        opacity: 0.7,
                        fillOpacity: 0.6
                    }).bindPopup(`<div class="text-sm"><h4 class="font-bold text-black">${barangay.properties.name}</h4></div>`).addTo(map);
                });

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(console.error);
    });
</script>
@endsection
