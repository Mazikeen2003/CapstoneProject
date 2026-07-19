@extends('layouts.public-map')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex flex-col md:flex-row gap-0 min-h-[65svh] md:h-[calc(100svh-140px)] -mx-6 -mb-6 rounded-lg border border-gray-300 shadow-sm">
    <div class="flex-1 min-w-0 w-full relative order-2 md:order-1 border-r-0 md:border-r md:border-gray-300 min-h-[65svh] md:min-h-0" id="map" style="background-color: #f0f0f0;"></div>

    <button id="toggleSidebar" class="fixed bottom-4 right-4 md:hidden z-[10001] bg-blue-500 text-white rounded-full p-4 shadow-lg hover:bg-blue-600 transition" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div id="projectSidebar" class="fixed inset-0 z-[10000] w-full bg-white border-t md:relative md:inset-auto md:w-[360px] md:border-t-0 md:border-l border-gray-200 overflow-y-auto max-h-[100svh] shadow-sm order-3 md:order-2 md:max-h-full hidden md:block" role="dialog" aria-label="Project list">
        <div class="p-4 md:p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-base md:text-lg font-bold text-black">Projects Map</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
            <div id="projectSidebarAction" class="mt-4"></div>
        </div>
        <div id="projectList" class="divide-y divide-gray-200"></div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('projectSidebar');
        const projectList = document.getElementById('projectList');
        const isMobile = () => window.innerWidth < 768;
        let selectedProjectIndex = null;
        let map = null;
        let boundedArea = null;
        let projectFeatures = [];

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

            if (map) {
                requestAnimationFrame(() => map.invalidateSize());
            }
        }

        function formatCurrency(value) {
            return `₱${Number(value || 0).toLocaleString()}`;
        }

        function openSidebar() {
            if (isMobile()) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('block');
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
            const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            
            const imageHtml = props.image
                ? `<img src="${props.image}" alt="${props.name}" class="h-40 w-full rounded-2xl object-cover bg-slate-100">`
                : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

            if (isSingle) {
                return `
                    <div class="project-card cursor-pointer overflow-hidden rounded-[24px] border border-slate-200 bg-white text-slate-800 shadow-sm" data-index="${index}">
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
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Barangay</span><span class="text-right font-semibold text-slate-900">${props.barangay || 'Not specified'}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Budget</span><span class="font-semibold text-slate-900">${formatCurrency(props.budget)}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Progress</span><span class="font-semibold text-slate-900">${progress.toFixed(1)}%</span></div>
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
                <div class="project-card cursor-pointer overflow-hidden rounded-3xl bg-white shadow-sm transition hover:shadow-md ${selectedProjectIndex === index ? 'border border-slate-200 bg-slate-50' : 'border border-transparent'}" data-index="${index}">
                    <div class="overflow-hidden p-2">${imageHtml}</div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold text-slate-900">${props.name}</h3>
                        <p class="mt-2 text-xs text-slate-500">${props.barangay || 'Barangay not specified'}</p>
                        <div class="mt-3 grid gap-2 text-xs text-slate-600">
                            <div><span class="font-semibold">Status:</span> ${props.status || 'Unknown'}</div>
                            <div><span class="font-semibold">Budget:</span> ${formatCurrency(props.budget)}</div>
                            <div><span class="font-semibold">Progress:</span> ${progress.toFixed(1)}%</div>
                        </div>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">${props.description || 'No description available.'}</p>
                    </div>
                </div>
            `;
        }

        function updateSidebarAction(isSingle) {
            const actionContainer = document.getElementById('projectSidebarAction');
            actionContainer.innerHTML = '';
        }

        function clearSelection() {
            document.querySelectorAll('.project-card').forEach(function(card) {
                card.classList.remove('border', 'border-slate-200', 'bg-slate-50');
            });
        }

        function highlightProject(index) {
            selectedProjectIndex = index;
            clearSelection();
            const card = document.querySelector(`.project-card[data-index="${index}"]`);
            if (card) {
                card.classList.add('border', 'border-slate-200', 'bg-slate-50');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function renderProjectList(projects) {
            const isSingle = projects.length === 1;
            updateSidebarAction(isSingle);
            projectList.innerHTML = projects.map(function(project) {
                return renderProjectCard(project, project.originalIndex, isSingle);
            }).join('');

            document.querySelectorAll('.project-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    const project = projectFeatures[index];
                    const coords = project.geometry.coordinates;
                    map.setView([coords[1], coords[0]], 14);
                    selectProject(project, index);
                });
            });

            const showAllBtn = document.getElementById('showAllProjects');
            if (showAllBtn) {
                showAllBtn.addEventListener('click', function() {
                    showAllProjects();
                });
            }
        }

        function showAllProjects() {
            selectedProjectIndex = null;
            renderProjectList(projectFeatures);
            if (map && boundedArea) {
                map.flyToBounds(boundedArea, {
                    padding: [24, 24],
                    duration: 0.7,
                    easeLinearity: 0.3
                });
            }
        }

        function selectProject(project, index) {
            openSidebar();
            highlightProject(index);
            renderProjectList([project]);
            if (map && project && project.geometry && project.geometry.coordinates) {
                const coords = project.geometry.coordinates;
                map.flyTo([coords[1], coords[0]], 15, {
                    duration: 0.7,
                    easeLinearity: 0.35
                });
            }
        }

        toggleBtn.addEventListener('click', function() {
            const isVisible = !sidebar.classList.contains('hidden');
            sidebar.classList.toggle('hidden', isVisible);
            sidebar.classList.toggle('block', !isVisible);
            toggleBtn.innerHTML = isVisible
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        });

        window.addEventListener('resize', syncSidebar);
        syncSidebar();

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
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
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

                function getMarkerColor(status) {
                    switch (status) {
                        case 'Completed': return '#10b981';
                        case 'On Going': return '#3b82f6';
                        case 'On Hold': return '#ef4444';
                        case 'Planning': return '#fbbf24';
                        default: return '#6b7280';
                    }
                }

                projectMarkers = L.featureGroup();
                projectFeatures = [];
                window.projectFeatures = projectFeatures;

                fetch('{{ url('/api/projects/geojson') }}?public=1')
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

                        projectData.features.forEach(function(project, index) {
                            const coords = project.geometry && project.geometry.coordinates;
                            if (!coords || coords.length < 2) {
                                return;
                            }

                            const marker = L.circleMarker([coords[1], coords[0]], {
                                radius: 14,
                                fillColor: getMarkerColor(project.properties.status),
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

                            marker.on('click', function(e) {
                                L.DomEvent.stopPropagation(e);
                                map.flyTo([coords[1], coords[0]], 16, {
                                    duration: 0.7,
                                    easeLinearity: 0.35
                                });
                                selectProject(project, index);
                            });

                            projectMarkers.addLayer(marker);
                            projectFeatures.push(Object.assign({ originalIndex: index }, project));
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
                        projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                    });

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
