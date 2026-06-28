@extends('layouts.public-map')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex flex-col md:flex-row gap-0 h-[calc(100vh-140px)] -mx-6 -mb-6 rounded-lg border border-gray-300 shadow-sm">
    <div class="flex-1 relative order-2 md:order-1 border-r-0 md:border-r md:border-gray-300" id="map" style="background-color: #f0f0f0; min-height: 50vh;"></div>

    <button id="toggleSidebar" class="fixed bottom-6 right-6 md:hidden z-50 bg-blue-500 text-white rounded-full p-4 shadow-lg hover:bg-blue-600 transition" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div id="projectSidebar" class="w-full md:w-[360px] bg-white border-t md:border-t-0 md:border-l border-gray-200 overflow-y-auto shadow-sm order-3 md:order-2 max-h-96 md:max-h-full hidden md:block">
        <div class="p-4 md:p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-base md:text-lg font-bold text-black">Projects Map</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
        </div>
        <div id="projectList" class="divide-y divide-gray-200"></div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('projectSidebar');
        const isMobile = () => window.innerWidth < 768;

        function syncSidebar() {
            if (isMobile()) {
                toggleBtn.style.display = 'block';
                sidebar.classList.add('hidden');
                sidebar.classList.remove('block');
            } else {
                toggleBtn.style.display = 'none';
                sidebar.classList.remove('hidden');
                sidebar.classList.add('block');
            }
        }

        syncSidebar();

        toggleBtn.addEventListener('click', function() {
            const isVisible = !sidebar.classList.contains('hidden');
            sidebar.classList.toggle('hidden', isVisible);
            sidebar.classList.toggle('block', !isVisible);
            toggleBtn.innerHTML = isVisible
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        });

        window.addEventListener('resize', syncSidebar);

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
                const projectList = document.getElementById('projectList');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const boundedArea = cabuyaoBounds.pad(0.02);
                const map = L.map('map', {
                    maxBounds: boundedArea,
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

                fetch('{{ url('/api/projects/geojson') }}')
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Unable to load projects from database');
                        }
                        return response.json();
                    })
                    .then(function(projectData) {
                        const projectFeatures = [];

                        if (projectData && projectData.features) {
                            projectData.features.forEach(function(project, index) {
                                const coords = project.geometry && project.geometry.coordinates;
                                if (!coords || coords.length < 2) {
                                    return;
                                }

                                const lng = coords[0];
                                const lat = coords[1];
                                const marker = L.circleMarker([lat, lng], {
                                    radius: 14,
                                    fillColor: '#2563eb',
                                    color: '#ffffff',
                                    weight: 3,
                                    opacity: 1,
                                    fillOpacity: 0.9
                                });

                                marker.bindPopup(`
                                    <div class="p-2 text-sm">
                                        <h4 class="font-bold text-black">${project.properties.name}</h4>
                                        <p class="text-xs text-gray-600 mt-1">Barangay: ${project.properties.barangay || 'N/A'}</p>
                                        <p class="text-xs text-gray-600">Status: ${project.properties.status || 'Unknown'}</p>
                                    </div>
                                `);

                                marker.on('click', function() {
                                    highlightProject(index);
                                });

                                projectMarkers.addLayer(marker);
                                projectFeatures.push({ feature: project, marker: marker, index: index });
                            });
                        }

                        function highlightProject(index) {
                            const cards = document.querySelectorAll('.project-card');
                            if (cards[index]) {
                                cards.forEach(function(card) { card.style.backgroundColor = ''; });
                                cards[index].style.backgroundColor = '#f3f4f6';
                                cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        }

                        if (projectFeatures.length) {
                            const listMarkup = projectFeatures.map(function(item) {
                                const project = item.feature;
                                const props = project.properties;
                                const imageHtml = props.image
                                    ? `<img src="${props.image}" alt="${props.name}" class="h-24 w-full rounded-md object-cover">`
                                    : '<div class="h-24 w-full rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

                                return `
                                    <div class="project-card cursor-pointer p-4 hover:bg-gray-50" data-index="${item.index}">
                                        <div class="mb-3">${imageHtml}</div>
                                        <h3 class="font-semibold text-black">${props.name}</h3>
                                        <p class="mt-1 text-xs text-gray-600">${props.barangay || 'Barangay not specified'}</p>
                                        <p class="mt-1 text-xs text-gray-600">Status: ${props.status || 'Unknown'}</p>
                                        <p class="mt-1 text-xs text-gray-600">Budget: ₱${Number(props.budget || 0).toLocaleString()}</p>
                                        <p class="mt-2 text-xs text-gray-500">${props.description || 'No description available.'}</p>
                                    </div>
                                `;
                            }).join('');
                            projectList.innerHTML = listMarkup;
                        } else {
                            projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">No projects have been added yet.</div>';
                        }

                        document.querySelectorAll('.project-card').forEach(function(card) {
                            card.addEventListener('click', function() {
                                const index = parseInt(this.getAttribute('data-index'), 10);
                                const project = projectFeatures[index].feature;
                                const coords = project.geometry.coordinates;
                                map.setView([coords[1], coords[0]], 14);
                                highlightProject(index);
                            });
                        });
                    })
                    .catch(function(error) {
                        console.error(error);
                        projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                    });

                barangayMarkers.addTo(map);
                projectMarkers.addTo(map);
                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());

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
