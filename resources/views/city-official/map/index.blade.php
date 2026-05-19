@extends('layouts.city')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)]">
    <!-- Map Container (Left - Takes remaining space) -->
    <div class="flex-1 relative" id="map" style="background-color: #f0f0f0;"></div>

    <!-- Sidebar - Project List (Right) -->
    <div class="w-96 bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-lg font-bold text-black">City Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
        </div>

        <!-- Project Cards -->
        <div class="divide-y divide-gray-200">
            <!-- Project 1 -->
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card">
                <div class="mb-3">
                    <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=300&fit=crop" alt="City Hall" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-black">Cabuyao City Hall Renovation</h3>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: #10b981;">In Progress</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700">79% Done</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Budget:</span>
                            <span class="font-semibold text-black">₱950K / ₱1.2M</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Barangay:</span>
                            <span class="font-semibold text-black">Diezmo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project 2 -->
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card">
                <div class="mb-3">
                    <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1581488627687-46e97395999f?w=400&h=300&fit=crop" alt="Drainage" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-black">Barangay Drainage System Phase 2</h3>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: #10b981;">In Progress</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700">88% Done</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Budget:</span>
                            <span class="font-semibold text-black">₱700K / ₱800K</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Barangay:</span>
                            <span class="font-semibold text-black">Bigaa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project 3 -->
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card">
                <div class="mb-3">
                    <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop" alt="Park" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-black">Environmental Park Development</h3>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: #10b981;">Completed</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700">100% Done</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Budget:</span>
                            <span class="font-semibold text-black">₱400K / ₱600K</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Barangay:</span>
                            <span class="font-semibold text-black">Marinig</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project 4 -->
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card">
                <div class="mb-3">
                    <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1517457373614-b7152f800fd1?w=400&h=300&fit=crop" alt="Hall" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-black">Sunny Winds Multi-Purpose Hall</h3>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: #f59e0b;">On Hold</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700">65% Done</span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Budget:</span>
                            <span class="font-semibold text-black">₱450K / ₱500K</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Barangay:</span>
                            <span class="font-semibold text-black">Leismer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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


