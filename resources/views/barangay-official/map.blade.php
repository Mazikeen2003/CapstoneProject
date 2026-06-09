@extends('layouts.barangay')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex flex-col md:flex-row gap-0 h-[calc(100vh-140px)] -mx-6 -mb-6">
    <div class="flex-1 relative order-2 md:order-1" id="map" style="background-color: #f0f0f0; min-height: 50vh;"></div>

    <button id="toggleSidebar" class="fixed bottom-6 right-6 md:hidden z-50 bg-blue-500 text-white rounded-full p-4 shadow-lg hover:bg-blue-600 transition" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div id="projectSidebar" class="w-full md:w-96 bg-white border-t md:border-t-0 md:border-l border-gray-200 overflow-y-auto shadow-sm order-3 md:order-2 max-h-96 md:max-h-none" style="display: none;">
        <div class="p-4 md:p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-base md:text-lg font-bold text-black">Projects Map</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Barangay projects</p>
        </div>
        <div class="p-6 text-sm text-gray-500">
            No projects have been added yet.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('projectSidebar');
        const isMobile = () => window.innerWidth < 768;

        function syncSidebar() {
            toggleBtn.style.display = isMobile() ? 'block' : 'none';
            sidebar.style.display = isMobile() ? 'none' : 'block';
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('resize', syncSidebar);
        syncSidebar();

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
