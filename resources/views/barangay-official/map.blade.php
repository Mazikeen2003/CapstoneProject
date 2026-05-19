@extends('layouts.department')

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

        <!-- Project Cards -->
        <div class="divide-y divide-gray-200">
            <!-- Project 1 -->
            <div class="p-3 md:p-4 hover:bg-gray-50 cursor-pointer transition project-card" data-lat="14.2567" data-lng="121.1267" data-barangay="Diezmo">
                <div class="mb-2 md:mb-3">
                    <div class="w-full h-24 md:h-32 bg-gray-200 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=300&fit=crop" alt="City Hall" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm md:text-base text-black">Cabuyao City Hall Renovation</h3>
                    <div class="flex gap-2 mt-2">
                        <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: #10b981;">In Progress</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700">79% Done</span>
                    </div>
                    <div class="mt-2 md:mt-3 space-y-2">
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
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card" data-lat="14.2789" data-lng="121.1345" data-barangay="Bigaa">
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
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card" data-lat="14.2345" data-lng="121.1123" data-barangay="Marinig">
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
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card" data-lat="14.2678" data-lng="121.1345" data-barangay="Leismer">
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

        // Initialize map
        const map = L.map('map').setView([14.2595, 121.1239], 12);
        
        // Cabuyao City boundaries
        const cabuyaoBounds = L.latLngBounds(
            L.latLng(14.2200, 121.0950),
            L.latLng(14.2920, 121.1490)
        );
        
        // Set maximum bounds
        map.setMaxBounds(cabuyaoBounds);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
            minZoom: 11
        }).addTo(map);

        // Load GeoJSON data
        fetch('/data/cabuyao-map.geojson')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    pointToLayer: function(feature, latlng) {
                        const kind = feature.properties.kind;
                        
                        if (kind === 'boundary') {
                            return null;
                        }
                        
                        if (kind === 'barangay') {
                            return L.circleMarker(latlng, {
                                radius: 8,
                                fillColor: '#e5e7eb',
                                color: '#6b7280',
                                weight: 2,
                                opacity: 0.7,
                                fillOpacity: 0.6
                            });
                        }
                        
                        return L.circleMarker(latlng, {
                            radius: 6,
                            fillColor: '#3b82f6',
                            color: '#1e40af',
                            weight: 2,
                            opacity: 0.8,
                            fillOpacity: 0.7
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const props = feature.properties;
                        layer.bindPopup(`<strong>${props.name}</strong>`);
                        
                        if (feature.geometry.type === 'Point') {
                            layer.on('mouseover', function() {
                                this.setStyle({ fillColor: '#3b82f6', weight: 3, fillOpacity: 0.8 });
                            });
                            layer.on('mouseout', function() {
                                const kind = feature.properties.kind;
                                if (kind === 'barangay') {
                                    this.setStyle({ fillColor: '#e5e7eb', weight: 2, fillOpacity: 0.6 });
                                }
                            });
                        }
                    }
                }).addTo(map);
            });

        // Project markers
        const projects = [
            { name: 'Cabuyao City Hall Renovation', lat: 14.2567, lng: 121.1267, barangay: 'Diezmo', color: '#10b981', status: 'In Progress' },
            { name: 'Barangay Drainage System Phase 2', lat: 14.2789, lng: 121.1345, barangay: 'Bigaa', color: '#10b981', status: 'In Progress' },
            { name: 'Environmental Park Development', lat: 14.2345, lng: 121.1123, barangay: 'Marinig', color: '#10b981', status: 'Completed' },
            { name: 'Sunny Winds Multi-Purpose Hall', lat: 14.2678, lng: 121.1345, barangay: 'Leismer', color: '#f59e0b', status: 'On Hold' }
        ];

        const projectMarkers = L.featureGroup();

        projects.forEach((project, index) => {
            const marker = L.circleMarker([project.lat, project.lng], {
                radius: 14,
                fillColor: project.color,
                color: '#ffffff',
                weight: 3,
                opacity: 1,
                fillOpacity: 0.9
            });

            marker.bindPopup(`
                <div class="p-2 text-sm">
                    <h4 class="font-bold text-black">${project.name}</h4>
                    <p class="text-xs text-gray-600 mt-1">Barangay: ${project.barangay}</p>
                    <p class="text-xs text-gray-600">Status: ${project.status}</p>
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

        projectMarkers.addTo(map);

        // Card click handlers
        document.querySelectorAll('.project-card').forEach((card, index) => {
            card.addEventListener('click', function() {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));
                map.setView([lat, lng], 14);
                
                document.querySelectorAll('.project-card').forEach(c => c.style.backgroundColor = '');
                this.style.backgroundColor = '#f3f4f6';
                
                // Close sidebar on mobile after selection
                if (isMobile()) {
                    setTimeout(() => {
                        sidebar.style.display = 'none';
                        toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>';
                    }, 300);
                }
            });
        });

        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });
</script>
@endsection
