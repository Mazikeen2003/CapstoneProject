@extends('layouts.public-map-full')

@section('content')
<!-- Map Area -->
<div class="flex-1 flex flex-col relative" style="background-color: #ffffff;">
    <div id="map" class="w-full" style="background-color: #e0e0e0; min-height: calc(100vh - 140px); position: relative; z-index: 1;"></div>
</div>

<!-- Project Explorer Panel -->
<div class="w-96 flex flex-col" style="background-color: #ffffff; border-left: 1px solid #E5E7EB;">
    <!-- Panel Header -->
    <div class="p-6 border-b" style="border-color: #E5E7EB;">
        <h2 class="text-2xl font-bold" style="color: #1F2937;">Project Explorer</h2>
        <p class="text-sm mt-1" style="color: #6B7280;">View project locations and details</p>
    </div>

    <!-- Projects List -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="projects-list">
        <!-- Project Card 1 -->
        <div class="rounded-lg border overflow-hidden cursor-pointer transition hover:shadow-lg project-card" data-id="1" data-lat="14.3595" data-lng="121.0239" style="border-color: #E5E7EB;">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=250&fit=crop" alt="Infrastructure Project" class="w-full h-40 object-cover">
            <div class="p-4">
                <h3 class="font-semibold" style="color: #1F2937;">Sunny Winds Multi-Purpose Hall</h3>
                <p class="text-xs mt-2" style="color: #6B7280;">Community Infrastructure Project</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold text-white" style="background-color: #10b981;">Active</span>
                    <span style="color: #6B7280;" class="text-xs">₱2,500,000.00</span>
                </div>
            </div>
        </div>

        <!-- Project Card 2 -->
        <div class="rounded-lg border overflow-hidden cursor-pointer transition hover:shadow-lg project-card" data-id="2" data-lat="14.3650" data-lng="121.0300" style="border-color: #E5E7EB;">
            <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=400&h=250&fit=crop" alt="Drainage System" class="w-full h-40 object-cover">
            <div class="p-4">
                <h3 class="font-semibold" style="color: #1F2937;">Barangay Drainage System Phase 2</h3>
                <p class="text-xs mt-2" style="color: #6B7280;">Infrastructure & Water Management</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold text-white" style="background-color: #f59e0b;">In Progress</span>
                    <span style="color: #6B7280;" class="text-xs">₱1,800,000.00</span>
                </div>
            </div>
        </div>

        <!-- Project Card 3 -->
        <div class="rounded-lg border overflow-hidden cursor-pointer transition hover:shadow-lg project-card" data-id="3" data-lat="14.3550" data-lng="121.0180" style="border-color: #E5E7EB;">
            <img src="https://images.unsplash.com/photo-1585919912445-85e1c9a4a8e3?w=400&h=250&fit=crop" alt="Environmental Park" class="w-full h-40 object-cover">
            <div class="p-4">
                <h3 class="font-semibold" style="color: #1F2937;">Environmental Park Development</h3>
                <p class="text-xs mt-2" style="color: #6B7280;">Community Green Space Initiative</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold text-white" style="background-color: #6366f1;">Planning</span>
                    <span style="color: #6B7280;" class="text-xs">₱950,000.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // EXACT CABUYAO CITY, LAGUNA BOUNDARIES
        // ============================================
        // These coordinates define the exact boundaries of Cabuyao City
        // Based on the city's administrative limits
        
        const cabuyaoBounds = {
            // Southwest corner (southernmost and westernmost points)
            southWest: {
                lat: 14.2300,  // Southern boundary (near San Pedro boundary)
                lng: 121.0000  // Western boundary (near Laguna de Bay)
            },
            // Northeast corner (northernmost and easternmost points)
            northEast: {
                lat: 14.4100,  // Northern boundary (near Santa Rosa)
                lng: 121.0950  // Eastern boundary (mountainous area)
            }
        };
        
        // Center of Cabuyao City (City Hall approximate location)
        const cabuyaoCenter = {
            lat: 14.2750,  // Cabuyao City Hall area
            lng: 121.1250  // Cabuyao City Hall area
        };
        
        // Alternative center (computed from bounds)
        const computedCenter = {
            lat: (cabuyaoBounds.southWest.lat + cabuyaoBounds.northEast.lat) / 2,
            lng: (cabuyaoBounds.southWest.lng + cabuyaoBounds.northEast.lng) / 2
        };
        
        // Initialize the map with Cabuyao as the center
        const map = L.map('map', {
            center: [computedCenter.lat, computedCenter.lng],
            zoom: 13,
            minZoom: 12,  // Minimum zoom - cannot zoom out beyond city level
            maxZoom: 19,   // Maximum zoom - can zoom in very close
            maxBoundsViscosity: 1.0  // Makes bounds restriction very sticky
        });
        
        // Create the bounds object
        const bounds = L.latLngBounds(
            L.latLng(cabuyaoBounds.southWest.lat, cabuyaoBounds.southWest.lng),
            L.latLng(cabuyaoBounds.northEast.lat, cabuyaoBounds.northEast.lng)
        );
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
            minZoom: 12
        }).addTo(map);
        
        // Set max bounds - user cannot pan outside Cabuyao City
        map.setMaxBounds(bounds);
        
        // Set min and max zoom
        map.setMinZoom(12);
        map.setMaxZoom(19);
        
        // Optional: Add a visual boundary overlay to show Cabuyao limits
        const boundaryPolygon = L.polygon([
            [cabuyaoBounds.southWest.lat, cabuyaoBounds.southWest.lng],
            [cabuyaoBounds.northEast.lat, cabuyaoBounds.southWest.lng],
            [cabuyaoBounds.northEast.lat, cabuyaoBounds.northEast.lng],
            [cabuyaoBounds.southWest.lat, cabuyaoBounds.northEast.lng]
        ], {
            color: '#c9a84c',
            weight: 3,
            opacity: 0.8,
            fillOpacity: 0.05,
            fillColor: '#c9a84c',
            dashArray: '8, 6'
        }).addTo(map);
        
        // Add a tooltip for the boundary
        boundaryPolygon.bindTooltip('🏙️ Cabuyao City Boundary', { 
            permanent: false, 
            direction: 'center',
            className: 'boundary-tooltip'
        });
        
        // Project data with actual Cabuyao locations
        const projects = [
            {
                id: 1,
                name: 'Cabuyao City Hall',
                lat: 14.2750,
                lng: 121.1250,
                category: 'Government',
                status: 'Completed',
                budget: 50000000,
                progress: 100,
                color: '#10b981',
                barangay: 'Barangay 2'
            },
            {
                id: 2,
                name: 'Cabuyao Sports Complex',
                lat: 14.2820,
                lng: 121.1180,
                category: 'Infrastructure',
                status: 'Active',
                budget: 25000000,
                progress: 75,
                color: '#10b981',
                barangay: 'Barangay 4'
            },
            {
                id: 3,
                name: 'Cabuyao Public Market',
                lat: 14.2780,
                lng: 121.1220,
                category: 'Commercial',
                status: 'In Progress',
                budget: 15000000,
                progress: 45,
                color: '#f59e0b',
                barangay: 'Barangay 3'
            },
            {
                id: 4,
                name: 'Cabuyao Lake Shore Development',
                lat: 14.2600,
                lng: 121.1000,
                category: 'Tourism',
                status: 'Planning',
                budget: 30000000,
                progress: 10,
                color: '#6366f1',
                barangay: 'Barangay 1'
            },
            {
                id: 5,
                name: 'Cabuyao Eco-Park',
                lat: 14.2900,
                lng: 121.1300,
                category: 'Environment',
                status: 'Active',
                budget: 8000000,
                progress: 60,
                color: '#10b981',
                barangay: 'Barangay 5'
            },
            {
                id: 6,
                name: 'Cabuyao Flood Control Project',
                lat: 14.2500,
                lng: 121.1050,
                category: 'Infrastructure',
                status: 'In Progress',
                budget: 20000000,
                progress: 30,
                color: '#f59e0b',
                barangay: 'Barangay 6'
            }
        ];
        
        // Store markers in an object for easy access
        const markers = {};
        
        // Add markers to map
        projects.forEach(project => {
            const marker = L.circleMarker([project.lat, project.lng], {
                radius: 10,
                fillColor: project.color,
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);
            
            // Store marker reference
            markers[project.id] = marker;
            
            // Bind popup with HTML content
            marker.bindPopup(`
                <div style="color: #0f1e3d; font-size: 13px; min-width: 250px; font-family: sans-serif;">
                    <strong style="font-size: 15px; color: #1F2937;">🏗️ ${project.name}</strong><br>
                    <hr style="margin: 8px 0; border-color: #E5E7EB;">
                    <div style="margin: 5px 0;">
                        <strong>📍 Barangay:</strong> ${project.barangay}<br>
                        <strong>📂 Category:</strong> ${project.category}<br>
                        <strong>📊 Status:</strong> <span style="color: ${project.color};">${project.status}</span><br>
                        <strong>💰 Budget:</strong> ₱${project.budget.toLocaleString()}<br>
                        <strong>📈 Progress:</strong> ${project.progress}%
                    </div>
                    <div style="background-color: #e5e7eb; border-radius: 4px; margin-top: 8px; height: 6px; width: 100%;">
                        <div style="background-color: ${project.color}; width: ${project.progress}%; height: 6px; border-radius: 4px;"></div>
                    </div>
                </div>
            `);
            
            // Add click event to marker
            marker.on('click', () => {
                scrollToProject(project.id);
                marker.openPopup();
            });
        });
        
        // Fit map bounds to show all markers within Cabuyao bounds
        if (projects.length > 0) {
            const projectBounds = L.latLngBounds(projects.map(p => [p.lat, p.lng]));
            map.fitBounds(projectBounds, { padding: [20, 20] });
        } else {
            map.setView([computedCenter.lat, computedCenter.lng], 13);
        }
        
        // Enforce bounds on drag with more strict control
        map.on('drag', function() {
            const center = map.getCenter();
            if (!bounds.contains(center)) {
                // Clamp the center to bounds
                const clampedLat = Math.max(bounds.getSouth(), Math.min(bounds.getNorth(), center.lat));
                const clampedLng = Math.max(bounds.getWest(), Math.min(bounds.getEast(), center.lng));
                map.panTo([clampedLat, clampedLng]);
            }
        });
        
        // Also enforce on zoom end in case user zooms out too far
        map.on('zoomend', function() {
            const currentZoom = map.getZoom();
            const center = map.getCenter();
            
            if (currentZoom === 12) {
                // Show a temporary message when at minimum zoom
                const message = L.popup()
                    .setLatLng(center)
                    .setContent('<div style="color: #c9a84c; font-weight: bold; padding: 5px;">🏙️ View restricted to Cabuyao City</div>')
                    .openOn(map);
                setTimeout(() => {
                    map.closePopup(message);
                }, 2000);
            }
            
            // Ensure center stays within bounds
            if (!bounds.contains(center)) {
                const clampedLat = Math.max(bounds.getSouth(), Math.min(bounds.getNorth(), center.lat));
                const clampedLng = Math.max(bounds.getWest(), Math.min(bounds.getEast(), center.lng));
                map.setView([clampedLat, clampedLng], currentZoom);
            }
        });
        
        // Scroll to project card when marker is clicked
        function scrollToProject(projectId) {
            const card = document.querySelector(`.project-card[data-id="${projectId}"]`);
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                // Highlight the card temporarily
                card.style.backgroundColor = '#fef3c7';
                card.style.transition = 'background-color 0.3s ease';
                setTimeout(() => {
                    card.style.backgroundColor = '';
                }, 2000);
            }
        }
        
        // Update project cards with new data
        const projectCards = document.querySelectorAll('.project-card');
        if (projectCards.length === projects.length) {
            projectCards.forEach((card, index) => {
                const project = projects[index];
                if (project) {
                    card.setAttribute('data-id', project.id);
                    card.setAttribute('data-lat', project.lat);
                    card.setAttribute('data-lng', project.lng);
                    
                    // Update card content if needed
                    const titleElement = card.querySelector('h3');
                    const statusElement = card.querySelector('.px-2.py-1');
                    const budgetElement = card.querySelector('.text-xs:last-child');
                    
                    if (titleElement) titleElement.textContent = project.name;
                    if (statusElement) {
                        statusElement.textContent = project.status;
                        statusElement.style.backgroundColor = project.color;
                    }
                    if (budgetElement) budgetElement.textContent = `₱${project.budget.toLocaleString()}`;
                }
            });
        }
        
        // Click on project card to center map and highlight marker
        document.querySelectorAll('.project-card').forEach(card => {
            card.addEventListener('click', () => {
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);
                const projectId = parseInt(card.dataset.id);
                
                // Center map on the project location with zoom
                map.setView([lat, lng], 16);
                
                // Open the corresponding marker popup
                if (markers[projectId]) {
                    markers[projectId].openPopup();
                }
                
                // Highlight the marker temporarily
                if (markers[projectId]) {
                    markers[projectId].setStyle({
                        radius: 14,
                        weight: 3,
                        color: '#c9a84c'
                    });
                    setTimeout(() => {
                        markers[projectId].setStyle({
                            radius: 10,
                            weight: 2,
                            color: '#fff'
                        });
                    }, 1500);
                }
            });
        });
        
        // Fix map size when the container is fully rendered
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        // Also invalidate size on window resize
        window.addEventListener('resize', () => {
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        });
        
        // Add scale bar
        L.control.scale({ 
            metric: true, 
            imperial: false,
            position: 'bottomleft'
        }).addTo(map);
        
        // Add a custom control showing Cabuyao info
        const infoControl = L.control({ position: 'topright' });
        infoControl.onAdd = function() {
            const div = L.DomUtil.create('div', 'info-control');
            div.innerHTML = `
                <div style="background: white; padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); font-size: 12px; font-weight: bold; color: #1F2937; border-left: 3px solid #c9a84c;">
                    🏙️ Cabuyao City, Laguna
                </div>
            `;
            return div;
        };
        infoControl.addTo(map);
        
        console.log('Map initialized successfully with Cabuyao City bounds');
    });
</script>

<style>
    .boundary-tooltip {
        background-color: rgba(201, 168, 76, 0.9);
        color: white;
        font-weight: bold;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
    }
    
    .info-control {
        z-index: 1000;
    }
    
    .project-card {
        transition: all 0.3s ease;
    }
    
    .project-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection