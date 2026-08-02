@extends('layouts.city')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="space-y-2">
        <h1 class="text-3xl font-bold text-slate-900">City Map</h1>
        <p class="text-sm text-slate-500">Browse city projects by location with interactive markers and project details.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.45fr_0.95fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Interactive Map</h2>
                    <p class="text-sm text-slate-500">Tap markers to review project information.</p>
                </div>
            </div>
            <div id="map" class="h-[58vh] sm:h-[64vh] lg:h-[72vh] rounded-3xl border border-slate-200 bg-slate-100"></div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm overflow-hidden">
            <div class="mb-5">
                <h2 class="text-xl font-semibold text-slate-900">Project List</h2>
                <p class="text-sm text-slate-500 mt-1">Select a project card to fly to its map marker.</p>
            </div>
            <div id="departmentSidebarAction" class="mb-5"></div>
            <div id="departmentProjectList" class="space-y-4 overflow-hidden"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectList = document.getElementById('departmentProjectList');
        const selectedClass = 'border border-slate-200 bg-slate-50';
        let selectedProjectIndex = null;
        let map = null;
        let boundedArea = null;
        let projectFeatures = [];
        let barangayLayer = null;
        let selectedBarangayLayer = null;
        let selectedBarangayName = null;
        const markersByBarangay = {};
        let allMarkers = null;

        function barangayColor(name) {
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            const hue = Math.abs(hash) % 360;
            return `hsl(${hue}, 65%, 55%)`;
        }

        function formatCurrency(value) {
            return `₱${Number(value || 0).toLocaleString()}`;
        }

        function calculateProgress(project) {
            if (!project.properties.start_date || !project.properties.target_end_date) {
                return 0;
            }
            const startDate = new Date(project.properties.start_date);
            const endDate = new Date(project.properties.target_end_date);
            const today = new Date();
            const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24);
            const daysElapsed = (today - startDate) / (1000 * 60 * 60 * 24);
            return totalDays > 0 ? Math.min(100, Math.max(0, (daysElapsed / totalDays) * 100)) : 0;
        }

        function renderProjectCard(project, index, isSingle = false) {
            const props = project.properties;
            const progress = calculateProgress(project);
            const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            const imageHtml = props.image
                ? `<img src="${props.image}" alt="${props.name}" class="h-40 w-full rounded-3xl object-cover bg-slate-100">`
                : '<div class="h-40 w-full rounded-3xl bg-slate-100 flex items-center justify-center text-xs text-slate-500">No image available</div>';

            if (isSingle) {
                return `
                    <div class="department-project-card overflow-hidden rounded-[24px] bg-white text-slate-900 shadow-sm ${selectedProjectIndex === index ? selectedClass : 'border border-slate-200'}" data-index="${index}">
                        <div class="p-5">
                            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Selected Project</p>
                                    <h3 class="mt-2 text-xl font-semibold text-slate-900">${props.name}</h3>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-700">${props.status || 'Unknown'}</span>
                            </div>
                            <div class="mb-4 overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 p-2">${imageHtml}</div>
                            <div class="grid gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                <div class="flex justify-between"><span>Barangay</span><span class="font-semibold text-slate-900">${props.barangay || 'Not specified'}</span></div>
                                <div class="flex justify-between"><span>Budget</span><span class="font-semibold text-slate-900">${formatCurrency(props.budget)}</span></div>
                                <div class="flex justify-between"><span>Progress</span><span class="font-semibold text-slate-900">${progress.toFixed(1)}%</span></div>
                            </div>
                            <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                    <span>Timeline</span>
                                    <span>${progress.toFixed(1)}%</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full bg-blue-600" style="width: ${progress}%"></div>
                                </div>
                                <div class="mt-3 grid gap-2 text-xs text-slate-500">
                                    <div>Start: ${startDate}</div>
                                    <div>Target: ${targetDate}</div>
                                </div>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-600">${props.description || 'No description available.'}</p>
                            <button type="button" id="showAllProjects" class="mt-5 inline-flex w-full items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View all projects</button>
                        </div>
                    </div>
                `;
            }

            return `
                <div class="department-project-card overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md ${selectedProjectIndex === index ? selectedClass : ''}" data-index="${index}">
                    <div class="overflow-hidden p-2">${imageHtml}</div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold text-slate-900">${props.name}</h3>
                        <p class="mt-2 text-xs text-slate-500">${props.barangay || 'Barangay not specified'}</p>
                        <div class="mt-3 grid gap-2 text-xs text-slate-600">
                            <div class="flex justify-between"><span>Status</span><span>${props.status || 'Unknown'}</span></div>
                            <div class="flex justify-between"><span>Budget</span><span>${formatCurrency(props.budget)}</span></div>
                            <div class="flex justify-between"><span>Progress</span><span>${progress.toFixed(1)}%</span></div>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">${props.description || 'No description available.'}</p>
                    </div>
                </div>
            `;
        }

        function updateSidebarAction() {
            const actionContainer = document.getElementById('departmentSidebarAction');
            if (selectedBarangayName) {
                actionContainer.innerHTML = `
                    <button type="button" id="backToAllBarangays" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to all barangays
                    </button>
                `;
                document.getElementById('backToAllBarangays').addEventListener('click', resetToAllBarangays);
            } else {
                actionContainer.innerHTML = '';
            }
        }

        function clearSelection() {
            document.querySelectorAll('.department-project-card').forEach(function(card) {
                card.classList.remove('border', 'border-slate-200', 'bg-slate-50');
            });
        }

        function highlightProject(index) {
            selectedProjectIndex = index;
            clearSelection();
            const card = document.querySelector(`.department-project-card[data-index="${index}"]`);
            if (card) {
                card.classList.add('border', 'border-slate-200', 'bg-slate-50');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function renderProjectList(projects) {
            const isSingle = projects.length === 1;
            updateSidebarAction();
            if (projects.length === 0) {
                projectList.innerHTML = `<div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-sm text-slate-500">No public projects recorded in ${selectedBarangayName || 'this barangay'} yet.</div>`;
                return;
            }
            projectList.innerHTML = projects.map(function(project) {
                return renderProjectCard(project, project.originalIndex, isSingle);
            }).join('');
            document.querySelectorAll('.department-project-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    selectProject(projectFeatures[index], index);
                });
            });
            const showAllBtn = document.getElementById('showAllProjects');
            if (showAllBtn) {
                showAllBtn.addEventListener('click', showAllProjects);
            }
        }

        function showAllProjects() {
            selectedProjectIndex = null;
            const activeList = selectedBarangayName
                ? projectFeatures.filter(p => p.properties.barangay === selectedBarangayName)
                : projectFeatures;
            renderProjectList(activeList);
            if (map && boundedArea && !selectedBarangayName) {
                map.fitBounds(boundedArea, { padding: [24, 24], animate: true, duration: 0.7, easeLinearity: 0.3 });
            }
        }

        function selectProject(project, index) {
            highlightProject(index);
            renderProjectList([project]);
            if (map && project?.geometry?.coordinates) {
                const coords = project.geometry.coordinates;
                map.flyTo([coords[1], coords[0]], 15, { duration: 0.7, easeLinearity: 0.35 });
            }
        }

        function resetToAllBarangays() {
            if (selectedBarangayLayer) {
                barangayLayer.resetStyle(selectedBarangayLayer);
                selectedBarangayLayer = null;
            }
            selectedBarangayName = null;
            if (allMarkers) {
                map.addLayer(allMarkers);
            }
            selectedProjectIndex = null;
            renderProjectList(projectFeatures);
            if (map && boundedArea) {
                map.fitBounds(boundedArea, { padding: [24, 24] });
            }
        }

        function selectBarangayOnMap(layer, name) {
            if (selectedBarangayLayer) {
                barangayLayer.resetStyle(selectedBarangayLayer);
            }
            selectedBarangayLayer = layer;
            layer.setStyle({ fillOpacity: 0.75, weight: 3, color: '#162347' });
            selectedBarangayName = name;
            selectedProjectIndex = null;
            if (map && layer.getBounds) {
                map.fitBounds(layer.getBounds(), { padding: [40, 40] });
            }
            if (allMarkers) {
                map.removeLayer(allMarkers);
            }
            (markersByBarangay[name] || []).forEach(marker => marker.addTo(map));
            const filtered = projectFeatures.filter(p => p.properties.barangay === name);
            renderProjectList(filtered);
        }

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const cabuyaoBounds = L.geoJSON(geojson).getBounds();
                boundedArea = cabuyaoBounds.pad(0.02);
                map = L.map('map', {
                    maxBounds: boundedArea,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                barangayLayer = L.geoJSON(geojson, {
                    style: feature => ({
                        fillColor: barangayColor(feature.properties.name),
                        fillOpacity: 0.35,
                        color: '#ffffff',
                        weight: 1.5,
                    }),
                    onEachFeature: (feature, layer) => {
                        const name = feature.properties.name;
                        layer.bindTooltip(name, { sticky: true, className: 'barangay-tooltip' });
                        layer.on({
                            mouseover: e => {
                                if (layer !== selectedBarangayLayer) e.target.setStyle({ fillOpacity: 0.6, weight: 2.5 });
                            },
                            mouseout: e => {
                                if (layer !== selectedBarangayLayer) barangayLayer.resetStyle(e.target);
                            },
                            click: () => selectBarangayOnMap(layer, name),
                        });
                    }
                }).addTo(map);

                function getMarkerColor(status) {
                    return ({ 'Completed': '#10b981', 'On Going': '#3b82f6', 'On Hold': '#ef4444', 'Planning': '#fbbf24' })[status] || '#64748b';
                }

                allMarkers = L.featureGroup();
                projectFeatures = [];
                window.projectFeatures = projectFeatures;

                fetch('{{ route('api.projects.geojson') }}')
                    .then(response => response.json())
                    .then(function(projectData) {
                        if (!projectData || !projectData.features) {
                            throw new Error('Invalid project data');
                        }
                        projectData.features.forEach(function(project, index) {
                            const coords = project.geometry?.coordinates;
                            if (!coords || coords.length < 2) return;
                            const marker = L.circleMarker([coords[1], coords[0]], {
                                radius: 12,
                                fillColor: getMarkerColor(project.properties.status),
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });
                            marker.bindPopup(`<div class="text-sm"><strong>${project.properties.name}</strong><br><span class="text-xs text-gray-600">${project.properties.status || 'Unknown'}</span></div>`);
                            marker.on('click', function(e) {
                                L.DomEvent.stopPropagation(e);
                                map.flyTo([coords[1], coords[0]], 16, { duration: 0.7, easeLinearity: 0.35 });
                                selectProject(project, index);
                            });
                            allMarkers.addLayer(marker);
                            projectFeatures.push(Object.assign({ originalIndex: index }, project));
                            const barangayName = project.properties.barangay;
                            if (barangayName) {
                                markersByBarangay[barangayName] = markersByBarangay[barangayName] || [];
                                markersByBarangay[barangayName].push(marker);
                            }
                        });
                        map.on('click', function() {
                            if (selectedProjectIndex !== null && !selectedBarangayName) {
                                showAllProjects();
                            }
                        });
                        map.addLayer(allMarkers);
                        renderProjectList(projectFeatures);
                    })
                    .catch(function(error) {
                        console.error(error);
                        projectList.innerHTML = '<div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-sm text-slate-500">Unable to load projects.</div>';
                    });

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 150);
                window.addEventListener('resize', () => setTimeout(() => map.invalidateSize(), 150));
            })
            .catch(console.error);
    });
</script>
@endsection
