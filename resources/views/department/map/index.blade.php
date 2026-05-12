@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)]">
    <!-- Map Container (Left - Takes remaining space) -->
    <div class="flex-1 relative" id="map" style="background-color: #f0f0f0;"></div>

    <!-- Sidebar - Project List (Right) -->
    <div class="w-96 bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-lg font-bold text-black">Department Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
        </div>

        <!-- Project Cards -->
        <div class="divide-y divide-gray-200">
            <!-- Project 1 -->
            <div class="p-4 hover:bg-gray-50 cursor-pointer transition project-card" data-lat="14.2567" data-lng="121.1267" data-barangay="Diezmo">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([14.2595, 121.1239], 12);
        
        // Cabuyao City boundaries (approximate bounding box)
        // SW corner: 14.2234, 121.0987
        // NE corner: 14.2890, 121.1456
        const cabuyaoBounds = L.latLngBounds(
            L.latLng(14.2200, 121.0950), // Southwest corner (expanded slightly)
            L.latLng(14.2920, 121.1490)  // Northeast corner (expanded slightly)
        );
        
        // Set maximum bounds - users can't pan outside this area
        map.setMaxBounds(cabuyaoBounds);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
            minZoom: 11
        }).addTo(map);

        // All 18 Barangays of Cabuyao City
        const barangays = [
            { name: 'Banay-banay', lat: 14.2547, lng: 121.1089 },
            { name: 'Banadero', lat: 14.2698, lng: 121.1234 },
            { name: 'Bigaa', lat: 14.2789, lng: 121.1345 },
            { name: 'Butong', lat: 14.2456, lng: 121.1123 },
            { name: 'Casile', lat: 14.2234, lng: 121.0987 },
            { name: 'Diezmo', lat: 14.2567, lng: 121.1267 },
            { name: 'Gulod', lat: 14.2678, lng: 121.1189 },
            { name: 'Halang', lat: 14.2890, lng: 121.1456 },
            { name: 'Laguerta', lat: 14.2345, lng: 121.1056 },
            { name: 'Lawa', lat: 14.2456, lng: 121.1178 },
            { name: 'Lecheria', lat: 14.2567, lng: 121.1289 },
            { name: 'Leismer', lat: 14.2678, lng: 121.1345 },
            { name: 'Mamatid', lat: 14.2789, lng: 121.1234 },
            { name: 'Marinig', lat: 14.2345, lng: 121.1123 },
            { name: 'Niugan', lat: 14.2567, lng: 121.1067 },
            { name: 'Pittland', lat: 14.2678, lng: 121.1178 },
            { name: 'Pulo', lat: 14.2890, lng: 121.1289 },
            { name: 'Sala', lat: 14.2456, lng: 121.1345 }
        ];

        // Project data
        const projects = [
            { name: 'Cabuyao City Hall Renovation', lat: 14.2567, lng: 121.1267, barangay: 'Diezmo', color: '#10b981', status: 'In Progress' },
            { name: 'Barangay Drainage System Phase 2', lat: 14.2789, lng: 121.1345, barangay: 'Bigaa', color: '#10b981', status: 'In Progress' },
            { name: 'Environmental Park Development', lat: 14.2345, lng: 121.1123, barangay: 'Marinig', color: '#10b981', status: 'Completed' },
            { name: 'Sunny Winds Multi-Purpose Hall', lat: 14.2678, lng: 121.1345, barangay: 'Leismer', color: '#f59e0b', status: 'On Hold' }
        ];

        // Create feature group for map bounds
        const barangayMarkers = L.featureGroup();
        const projectMarkers = L.featureGroup();

        // Add barangay markers
        barangays.forEach((barangay) => {
            const marker = L.circleMarker([barangay.lat, barangay.lng], {
                radius: 8,
                fillColor: '#e5e7eb',
                color: '#6b7280',
                weight: 2,
                opacity: 0.7,
                fillOpacity: 0.6
            });

            marker.bindPopup(`
                <div class="text-sm">
                    <h4 class="font-bold text-black">${barangay.name}</h4>
                    <p class="text-xs text-gray-600">Lat: ${barangay.lat.toFixed(4)}</p>
                    <p class="text-xs text-gray-600">Lng: ${barangay.lng.toFixed(4)}</p>
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

        // Add project markers
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

        // Add layers to map
        barangayMarkers.addTo(map);
        projectMarkers.addTo(map);

        // Set map bounds to show all markers and fit to Cabuyao bounds
        const allMarkers = barangayMarkers.getLayers().concat(projectMarkers.getLayers());
        if (allMarkers.length > 0) {
            const group = new L.featureGroup(allMarkers);
            map.fitBounds(group.getBounds().pad(0.05), { maxZoom: 13 });
        }

        // Click on project cards to pan map
        document.querySelectorAll('.project-card').forEach((card, index) => {
            card.addEventListener('click', function() {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));
                map.setView([lat, lng], 14);
                
                document.querySelectorAll('.project-card').forEach(c => c.style.backgroundColor = '');
                this.style.backgroundColor = '#f3f4f6';
            });
        });

        // Trigger map resize
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });
</script>
@endsection
