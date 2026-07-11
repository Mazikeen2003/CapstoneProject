@extends('layouts.barangay')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="space-y-6">
    <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-black">Barangay Map</h2>
                    <p class="text-sm text-gray-500 mt-1">Project locations for your barangay, displayed across Cabuyao City.</p>
                </div>
            </div>
            <div class="mt-5 h-[680px] overflow-hidden rounded-xl border-2 border-blue-500" id="map"></div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-black">Barangay Projects</h2>
                <p class="text-sm text-gray-500 mt-1">Tap a marker or project card to open project details.</p>
                <div id="barangayProjectList" class="mt-5 space-y-4"></div>
                <div id="emptyState" class="mt-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-sm text-gray-500">
                    Loading barangay projects...
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-black">Selected Project</h2>
                <p class="text-sm text-gray-500 mt-1">Details appear here when a project is selected.</p>
                <div id="selectedProjectDetails" class="mt-5 space-y-4"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let mapBounds = null;
        const projectList = document.getElementById('barangayProjectList');
        const emptyState = document.getElementById('emptyState');

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(feature => feature.properties.kind === 'boundary');
                const barangays = geojson.features.filter(feature => feature.properties.kind === 'barangay');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const boundedArea = cabuyaoBounds.pad(0.02);
                mapBounds = boundedArea;
                const map = L.map('map', {
                    center: cabuyaoBounds.getCenter(),
                    zoom: 12,
                    minZoom: 11,
                    maxZoom: 16,
                    maxBounds: boundedArea,
                    maxBoundsViscosity: 1.0,
                    zoomControl: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(boundaryFeature, {
                    style: {
                        color: '#2563eb',
                        weight: 4,
                        opacity: 0.85,
                        fillOpacity: 0
                    }
                }).addTo(map);

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());

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
                const selectedProjectDetails = document.getElementById('selectedProjectDetails');
                let selectedProjectIndex = null;

                function getOffsetLatLng(lat, lng, index) {
                    const angle = index * (Math.PI / 4);
                    const offset = 0.00006 + (index * 0.00004);
                    return [lat + Math.sin(angle) * offset, lng + Math.cos(angle) * offset];
                }

                function formatCurrency(value) {
                    return `₱${Number(value || 0).toLocaleString()}`;
                }

                function openSidebar() {
                    // no-op for this layout
                }

                function clearSelection() {
                    document.querySelectorAll('.barangay-project-card').forEach(function(card) {
                        card.classList.remove('border', 'border-slate-200', 'bg-slate-50');
                    });
                }

                function highlightProject(index) {
                    clearSelection();
                    const card = document.querySelector(`.barangay-project-card[data-index="${index}"]`);
                    if (card) {
                        card.classList.add('border', 'border-slate-200', 'bg-slate-50');
                        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
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
                    const cardIndex = project.originalIndex ?? index;
                    const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const imageHtml = props.image
                        ? `<img src="${props.image}" alt="${props.name}" class="h-40 w-full rounded-2xl object-cover bg-slate-100">`
                        : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

                    if (isSingle) {
                        return `
                            <div class="barangay-project-card cursor-pointer overflow-hidden rounded-[24px] border border-slate-200 bg-white text-slate-800 shadow-sm" data-index="${cardIndex}">
                                <div class="p-5 sm:p-6">
                                    <div class="mb-4 flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Selected project</p>
                                            <h3 class="mt-2 text-xl font-semibold text-slate-900">${props.name}</h3>
                                        </div>
                                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-700">${props.status || 'Unknown'}</span>
                                    </div>
                                    <div class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-2">${imageHtml}</div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                                        <div class="grid gap-3 text-sm text-slate-600">
                                            <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Barangay</span><span class="text-right font-semibold text-slate-900">${props.barangay || 'N/A'}</span></div>
                                            <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Budget</span><span class="font-semibold text-slate-900">${formatCurrency(props.budget)}</span></div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-slate-300">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-semibold text-slate-600">Timeline</span>
                                                <span class="text-xs font-bold text-slate-700">${progress.toFixed(1)}%</span>
                                            </div>
                                            <div class="h-2 bg-gray-300 rounded-full overflow-hidden">
                                                <div class="h-full transition-all duration-300" style="width: ${progress}%; background-color: #3b82f6;"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-slate-500 mt-1">
                                                <span>Start: ${startDate}</span>
                                                <span>Target: ${targetDate}</span>
                                            </div>
                                        </div>
                                        <p class="mt-4 text-sm leading-6 text-slate-600">${props.description || 'No description available.'}</p>
                                    </div>
                                    <button type="button" id="showAllProjects" class="mt-5 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View all projects</button>
                                </div>
                            </div>
                        `;
                    }

                    return `
                        <div class="barangay-project-card cursor-pointer overflow-hidden rounded-3xl bg-white shadow-sm transition hover:shadow-md ${selectedProjectIndex === cardIndex ? 'border border-slate-200 bg-slate-50' : 'border border-transparent'}" data-index="${cardIndex}">
                            <div class="overflow-hidden p-2">${imageHtml}</div>
                            <div class="p-4">
                                <h3 class="text-base font-semibold text-slate-900">${props.name}</h3>
                                <p class="mt-2 text-xs text-slate-500">${props.barangay || 'Barangay not specified'}</p>
                                <div class="mt-3 grid gap-2 text-xs text-slate-600">
                                    <div><span class="font-semibold">Status:</span> ${props.status || 'Unknown'}</div>
                                    <div><span class="font-semibold">Budget:</span> ${formatCurrency(props.budget)}</div>
                                </div>
                                <p class="mt-3 text-sm text-slate-600 leading-relaxed">${props.description || 'No description available.'}</p>
                            </div>
                        </div>
                    `;
                }

                function updateSidebarAction(isSingle) {
                    sidebarAction.innerHTML = '';
                }

                function renderProjectList(projects) {
                    const isSingle = projects.length === 1;
                    updateSidebarAction(isSingle);
                    projectList.innerHTML = '';

                    if (!projects.length) {
                        emptyState.classList.remove('hidden');
                        emptyState.innerHTML = '<div class="p-6 text-sm text-gray-500">No projects found for your barangay.</div>';
                        return;
                    }

                    emptyState.classList.add('hidden');
                    projectList.innerHTML = projects.map(function(project, index) {
                        return renderProjectCard(project, index, isSingle);
                    }).join('');

                    selectedProjectDetails.innerHTML = '';

                    document.querySelectorAll('.barangay-project-card').forEach(function(card) {
                        card.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'), 10);
                            const project = projectFeatures[index];
                            if (project) {
                                selectProject(project, index, false);
                            }
                        });
                    });

                    const showAllBtn = document.getElementById('showAllProjects');
                    if (showAllBtn) {
                        showAllBtn.addEventListener('click', function(event) {
                            event.stopPropagation();
                            showAllProjects();
                        });
                    }
                }

                function showAllProjects() {
                    selectedProjectIndex = null;
                    renderProjectList(projectFeatures);
                    if (map && mapBounds) {
                        map.fitBounds(mapBounds, { padding: [20, 20] });
                    }
                }

                function selectProject(project, index, zoomOnSelect = true) {
                    selectedProjectIndex = index;
                    renderProjectList([project]);
                    renderSelectedProjectDetails(project);
                    if (zoomOnSelect && map && project && project.geometry && project.geometry.coordinates) {
                        const coords = project.geometry.coordinates;
                        map.flyTo([coords[1], coords[0]], 15, {
                            duration: 0.7,
                            easeLinearity: 0.35
                        });
                    }
                }

                function renderSelectedProjectDetails(project) {
                    const props = project.properties;
                    const progress = calculateProgress(project);
                    const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    selectedProjectDetails.innerHTML = `
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-slate-900">${props.name}</h3>
                                <p class="text-sm text-slate-500 mt-1">${props.barangay || 'Barangay not specified'}</p>
                            </div>
                            <div class="grid gap-3 text-sm text-slate-600">
                                <div class="flex items-center justify-between"><span class="text-slate-500">Status</span><span class="font-semibold text-slate-900">${props.status || 'Unknown'}</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500">Budget</span><span class="font-semibold text-slate-900">${formatCurrency(props.budget)}</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500">Start</span><span class="font-semibold text-slate-900">${startDate}</span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-500">Target</span><span class="font-semibold text-slate-900">${targetDate}</span></div>
                            </div>
                            <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4">
                                <p class="text-sm text-slate-600 leading-6">${props.description || 'No description available.'}</p>
                                <div class="mt-4 text-xs uppercase tracking-[0.24em] text-slate-500">Timeline</div>
                                <div class="mt-2 h-2 rounded-full bg-slate-200 overflow-hidden">
                                    <div class="h-full bg-blue-500 transition-all duration-300" style="width: ${progress}%;"></div>
                                </div>
                                <div class="mt-2 flex justify-between text-xs text-slate-500">
                                    <span>${progress.toFixed(1)}% complete</span>
                                    <span>${startDate} - ${targetDate}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

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

                        projectFeatures.length = 0;
                        Object.keys(duplicateCount).forEach(key => delete duplicateCount[key]);

                        filteredProjects.forEach(function(project, index) {
                            const coords = project.geometry && project.geometry.coordinates;
                            if (!coords || coords.length < 2) {
                                return;
                            }

                            const projectIndex = projectFeatures.length;
                            projectFeatures.push(Object.assign({ originalIndex: projectIndex }, project));

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

                            marker.on('click', function(e) {
                                L.DomEvent.stopPropagation(e);
                                selectProject(projectFeatures[projectIndex], projectIndex, true);
                            });

                            projectMarkers.addLayer(marker);
                        });

                        map.on('click', function() {
                            if (selectedProjectIndex !== null) {
                                showAllProjects();
                            }
                        });

                        projectMarkers.addTo(map);
                        renderProjectList(projectFeatures);
                    })
                    .catch(function(error) {
                        console.error(error);
                        emptyState.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                    });

                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(console.error);
    });
</script>
@endsection
