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
            <h2 class="text-base md:text-lg font-bold text-black">Barangay Projects</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Projects assigned to this barangay will appear here.</p>
        </div>
        <div id="sidebarContent" class="p-4 md:p-6 space-y-4">
            <div id="projectDetails" class="rounded-xl border border-gray-200 bg-slate-50 p-4 hidden">
                <h3 class="text-sm font-semibold text-slate-900">Select a project marker to see details</h3>
                <div id="projectDetailsContent" class="mt-3 text-sm text-slate-700"></div>
            </div>
            <div id="projectList" class="divide-y divide-gray-200"></div>
            <div id="emptyState" class="p-6 text-sm text-gray-500">
                Loading barangay projects...
            </div>
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

                const projectMarkers = L.featureGroup();
                const projectFeatures = [];
                const duplicateCount = {};
                const barangayId = {{ Auth::user()?->barangay_id ?? 'null' }};
                const projectList = document.getElementById('projectList');
                const projectDetails = document.getElementById('projectDetails');
                const projectDetailsContent = document.getElementById('projectDetailsContent');
                const emptyState = document.getElementById('emptyState');

                function getOffsetLatLng(lat, lng, index) {
                    const angle = index * (Math.PI / 4);
                    const offset = 0.00006 + (index * 0.00004);
                    return [lat + Math.sin(angle) * offset, lng + Math.cos(angle) * offset];
                }

                function formatCurrency(value) {
                    return `₱${Number(value || 0).toLocaleString()}`;
                }

                function openSidebar() {
                    if (window.innerWidth < 768) {
                        sidebar.style.display = 'block';
                    }
                }

                function clearSelection() {
                    document.querySelectorAll('.barangay-project-card').forEach(function(card) {
                        card.style.backgroundColor = '';
                    });
                }

                function highlightProject(index) {
                    clearSelection();
                    const cards = document.querySelectorAll('.barangay-project-card');
                    if (cards[index]) {
                        cards[index].style.backgroundColor = '#f8fafc';
                        cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }

                function renderProjectDetails(project) {
                    const props = project.properties;
                    projectDetailsContent.innerHTML = `
                        <div class="space-y-3 text-sm text-slate-700">
                            <div class="text-base font-semibold text-slate-900">${props.name}</div>
                            <div><span class="font-semibold">Status:</span> ${props.status || 'Unknown'}</div>
                            <div><span class="font-semibold">Budget:</span> ${formatCurrency(props.budget)}</div>
                            <div><span class="font-semibold">Barangay:</span> ${props.barangay || 'N/A'}</div>
                            <div><span class="font-semibold">Description:</span> ${props.description || 'No description available.'}</div>
                        </div>
                    `;
                    projectDetails.classList.remove('hidden');
                }

                function hideProjectDetails() {
                    projectDetailsContent.innerHTML = '';
                    projectDetails.classList.add('hidden');
                }

                function selectProject(project, index) {
                    openSidebar();
                    clearSelection();
                    highlightProject(index);
                    renderProjectDetails(project);
                }

                hideProjectDetails();

                fetch('{{ url('/api/projects/geojson') }}')
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Unable to load projects from database');
                        }
                        return response.json();
                    })
                    .then(function(projectData) {
                        if (!projectData || !projectData.features) {
                            throw new Error('Invalid project data');
                        }

                        const filteredProjects = projectData.features.filter(function(project) {
                            return project.properties.barangay_id === barangayId;
                        });

                        if (!filteredProjects.length) {
                            emptyState.innerHTML = 'No barangay projects are available.';
                            return;
                        }

                        projectList.innerHTML = filteredProjects.map(function(project, index) {
                            return `
                                <div class="barangay-project-card cursor-pointer p-4 hover:bg-slate-50 border-b border-gray-100" data-index="${index}">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <h3 class="font-semibold text-black">${project.properties.name}</h3>
                                            <p class="mt-1 text-xs text-gray-600">${project.properties.barangay || 'N/A'}</p>
                                        </div>
                                        <div class="text-xs text-slate-500">${project.properties.status || 'Unknown'}</div>
                                    </div>
                                    <div class="mt-3 text-xs text-slate-600">
                                        <div>Budget: ${formatCurrency(project.properties.budget)}</div>
                                        <div class="mt-1">${project.properties.description ? project.properties.description.slice(0, 80) + '...' : 'No description available.'}</div>
                                    </div>
                                </div>
                            `;
                        }).join('');

                        emptyState.remove();

                        document.querySelectorAll('.barangay-project-card').forEach(function(card) {
                            card.addEventListener('click', function() {
                                const index = parseInt(this.getAttribute('data-index'), 10);
                                const project = filteredProjects[index];
                                const coords = project.geometry.coordinates;
                                map.setView([coords[1], coords[0]], 14);
                                selectProject(project, index);
                            });
                        });

                        filteredProjects.forEach(function(project, index) {
                            const coords = project.geometry && project.geometry.coordinates;
                            if (!coords || coords.length < 2) {
                                return;
                            }

                            const key = `${coords[1].toFixed(6)}_${coords[0].toFixed(6)}`;
                            const occurrence = duplicateCount[key] || 0;
                            duplicateCount[key] = occurrence + 1;
                            const [markerLat, markerLng] = occurrence === 0 ? [coords[1], coords[0]] : getOffsetLatLng(coords[1], coords[0], occurrence);

                            const marker = L.circleMarker([markerLat, markerLng], {
                                radius: 12,
                                fillColor: '#2563eb',
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });

                            marker.bindPopup(`
                                <div class="p-2 text-sm">
                                    <h4 class="font-bold text-black">${project.properties.name}</h4>
                                    <p class="text-xs text-gray-600 mt-1">Status: ${project.properties.status || 'Unknown'}</p>
                                </div>
                            `);

                            marker.on('click', function() {
                                selectProject(project, index);
                            });

                            projectMarkers.addLayer(marker);
                        });

                        projectMarkers.addTo(map);
                    })
                    .catch(function(error) {
                        console.error(error);
                        emptyState.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                    });

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(console.error);
    });
</script>
@endsection
