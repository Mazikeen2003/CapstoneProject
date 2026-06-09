@extends('layouts.public-map')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex flex-col md:flex-row gap-0 h-[calc(100vh-140px)] -mx-6 -mb-6">
    <!-- Map Container (Top on mobile, Left on desktop) -->
    <div class="flex-1 relative order-2 md:order-1" id="map" style="background-color: #f0f0f0; min-height: 50vh; md:min-height: auto;"></div>

    <!-- Toggle Button for Mobile -->
    <button id="toggleSidebar" class="fixed bottom-6 right-6 md:hidden z-50 bg-blue-500 text-white rounded-full p-4 shadow-lg hover:bg-blue-600 transition" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar - Project List (Right on desktop, Bottom on mobile) -->
    <div id="projectSidebar" class="w-full md:w-96 bg-white border-t md:border-t-0 md:border-l border-gray-200 overflow-y-auto shadow-sm order-3 md:order-2 max-h-96 md:max-h-auto" style="display: none; md:display: block;">
        <div class="p-4 md:p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-base md:text-lg font-bold text-black">Projects Map</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
        </div>

        <div class="divide-y divide-gray-200">
            <div class="p-6 text-sm text-gray-500">
                No projects have been added yet.
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('projectSidebar');
        const isMobile = () => window.innerWidth < 768;

        // Show toggle button on mobile
        if (isMobile()) {
            toggleBtn.style.display = 'block';
            sidebar.style.display = 'none';
        }

        toggleBtn.addEventListener('click', function() {
            const isVisible = sidebar.style.display === 'block';
            sidebar.style.display = isVisible ? 'none' : 'block';
            toggleBtn.innerHTML = isVisible 
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (!isMobile()) {
                toggleBtn.style.display = 'none';
                sidebar.style.display = 'block';
            } else {
                toggleBtn.style.display = 'block';
                sidebar.style.display = 'none';
            }
        });

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Unable to load Cabuyao GeoJSON');
                }

                return response.json();
            })
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(function(feature) {
                    return feature.properties.kind === 'boundary';
                });
                const barangays = geojson.features.filter(function(feature) {
                    return feature.properties.kind === 'barangay';
                });
                const projects = geojson.features.filter(function(feature) {
                    return feature.properties.kind === 'project';
                });
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const map = L.map('map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Ac OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                const barangayMarkers = L.featureGroup();
                const projectMarkers = L.featureGroup();

                barangays.forEach(function(barangay) {
                    const lng = barangay.geometry.coordinates[0];
                    const lat = barangay.geometry.coordinates[1];
                    const marker = L.circleMarker([lat, lng], {
                        radius: 8,
                        fillColor: '#e5e7eb',
                        color: '#6b7280',
                        weight: 2,
                        opacity: 0.7,
                        fillOpacity: 0.6
                    });

                    marker.bindPopup(`
                        <div class="text-sm">
                            <h4 class="font-bold text-black">${barangay.properties.name}</h4>
                        </div>
                    `);

                    marker.on('mouseover', function() {
                        this.setStyle({ fillColor: '#3b82f6', weight: 3, fillOpacity: 0.8 });
                    });
                    marker.on('mouseout', function() {
                        this.setStyle({ fillColor: '#e5e7eb', weight: 2, fillOpacity: 0.6 });
                    });

                    barangayMarkers.addLayer(marker);
                });

                projects.forEach(function(project, index) {
                    const lng = project.geometry.coordinates[0];
                    const lat = project.geometry.coordinates[1];
                    const marker = L.circleMarker([lat, lng], {
                        radius: 14,
                        fillColor: project.properties.color,
                        color: '#ffffff',
                        weight: 3,
                        opacity: 1,
                        fillOpacity: 0.9
                    });

                    marker.bindPopup(`
                        <div class="p-2 text-sm">
                            <h4 class="font-bold text-black">${project.properties.name}</h4>
                            <p class="text-xs text-gray-600 mt-1">Barangay: ${project.properties.barangay}</p>
                            <p class="text-xs text-gray-600">Status: ${project.properties.status}</p>
                        </div>
                    `);

                    marker.on('click', function() {
                        const cards = document.querySelectorAll('.project-card');
                        if (cards[index]) {
                            cards.forEach(c => c.style.backgroundColor = '');
                            cards[index].style.backgroundColor = '#f3f4f6';
                            cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    });

                    projectMarkers.addLayer(marker);
                });

                barangayMarkers.addTo(map);
                projectMarkers.addTo(map);
                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());

                document.querySelectorAll('.project-card').forEach(function(card, index) {
                    card.addEventListener('click', function() {
                        const project = projects[index];
                        const lng = project.geometry.coordinates[0];
                        const lat = project.geometry.coordinates[1];
                        map.setView([lat, lng], 14);

                        document.querySelectorAll('.project-card').forEach(c => c.style.backgroundColor = '');
                        this.style.backgroundColor = '#f3f4f6';
                    });
                });

                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
            })
            .catch(function(error) {
                console.error(error);
            });
    });
</script>
@endsection
