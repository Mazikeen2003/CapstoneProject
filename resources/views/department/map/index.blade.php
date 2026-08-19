@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="space-y-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Department Map</h1>
            <p class="mt-1 text-sm text-slate-500">Tap a barangay to view its projects and explore the city map.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-[1.35fr_1fr] gap-4">
            <div class="rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm" style="height: calc(100vh - 13.5rem);">
                <div class="min-w-0 w-full h-full relative" id="map" style="background-color: #f0f0f0;"></div>
            </div>

            <div id="projectSidebar" class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col" style="max-height: calc(100vh - 13.5rem);">
                <div class="p-6 border-b border-gray-200 bg-white">
                    <h2 class="text-lg font-bold text-slate-900">Department Projects</h2>
                    <p class="text-sm text-slate-500 mt-1">Cabuyao City Projects</p>
                    <div id="departmentSidebarAction" class="mt-4"></div>
                </div>
                <div id="departmentProjectList" class="space-y-4 overflow-y-auto bg-slate-50 p-4 min-h-0 flex-1"></div>
            </div>
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectList = document.getElementById('departmentProjectList');
        const selectedClass = 'bg-slate-50 border border-slate-200';
        let selectedProjectIndex = null;
        let map = null;
        let boundedArea = null;
        let projectFeatures = [];
        let barangayLayer = null;
        let selectedBarangayLayer = null;
        let selectedBarangayName = null;
        const markersByBarangay = {}; // barangay name -> array of Leaflet markers
        let allMarkers = null; // featureGroup holding every marker

        function isMobile() {
            return window.innerWidth < 768;
        }

        if (map) {
            requestAnimationFrame(() => map.invalidateSize());
        }

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

        function renderLifecycleStepper(status) {
            const steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation'];
            const stageByStatus = { Proposed: 0, 'For bidding': 1, 'Bidding ongoing': 2, 'Award of contract': 3, Implementation: 4, Completed: 4, Planning: 0, Procurement: 1, 'Bidding - Success': 3, 'On Going': 4 };
            const activeStep = stageByStatus[status];
            const completedProject = status === 'Completed';

            return `<div class="mt-4 mb-4 rounded-2xl border border-slate-200 bg-white p-4"><div class="flex items-center justify-between gap-3"><span class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Project lifecycle</span><span class="text-xs font-semibold text-slate-700">${status || 'Unknown'}</span></div><div class="mt-4 grid grid-cols-5 gap-1 sm:gap-2">${steps.map((step, stepIndex) => { const complete = activeStep !== undefined && (stepIndex < activeStep || completedProject); const current = activeStep !== undefined && stepIndex === activeStep && !completedProject; const circle = complete ? 'bg-emerald-600 border-emerald-600 text-white' : (current ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-slate-300 text-slate-400'); const label = complete || current ? 'text-slate-700' : 'text-slate-400'; const line = activeStep !== undefined && (stepIndex < activeStep || completedProject) ? 'bg-emerald-600' : 'bg-slate-200'; return `<div class="relative text-center">${stepIndex < steps.length - 1 ? `<div class="absolute left-1/2 top-3.5 h-0.5 w-full ${line}"></div>` : ''}<div class="relative z-10 mx-auto flex h-7 w-7 items-center justify-center rounded-full border-2 text-[10px] font-bold ${circle}">${complete ? '&#10003;' : stepIndex + 1}</div><p class="mt-2 text-[9px] font-semibold leading-tight sm:text-[10px] ${label}">${step}</p></div>`; }).join('')}</div></div>`;
        }

        function renderProjectCard(project, index, isSingle = false) {
            const props = project.properties;
            const progress = calculateProgress(project);
            const allocatedBudget = Number(props.budget || 0);
            const expenditure = Number(props.actual_budget || 0);
            const expenditureProgress = allocatedBudget > 0 ? Math.min(100, Math.max(0, (expenditure / allocatedBudget) * 100)) : 0;
            const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
            const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';

            const imageHtml = props.image
                ? `<img src="${props.image}" alt="${props.name}" class="h-40 w-full rounded-2xl object-cover bg-slate-100">`
                : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

            if (isSingle) {
                return `
                    <div class="department-project-card cursor-pointer overflow-hidden rounded-[24px] border border-slate-200 bg-white text-slate-800 shadow-sm" data-index="${index}">
                        <div class="p-5 sm:p-6">
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Selected project</p>
                                    <h3 class="mt-2 text-xl font-semibold text-slate-900">${props.name}</h3>
                                </div>
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-700">${props.status || 'Unknown'}</span>
                            </div>
                            <div class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-2">${imageHtml}</div>
                            ${renderLifecycleStepper(props.status)}
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                                <div class="grid gap-3 text-sm text-slate-600">
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Barangay</span><span class="text-right font-semibold text-slate-900">${props.barangay || 'Not specified'}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Allocated budget</span><span class="font-semibold text-slate-900">${formatCurrency(allocatedBudget)}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Expenditure</span><span class="font-semibold text-slate-900">${formatCurrency(expenditure)}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Progress</span><span class="font-semibold text-slate-900">${progress.toFixed(1)}%</span></div>
                                </div>
                                <div class="mt-3 border-t border-slate-300 pt-3"><div class="flex items-center justify-between text-xs text-slate-500"><span>Expenditure progress</span><span class="font-semibold text-slate-700">${expenditureProgress.toFixed(1)}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-emerald-500" style="width: ${expenditureProgress}%"></div></div></div>
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
                                <p class="mt-4 whitespace-pre-wrap break-words text-sm leading-6 text-slate-600" style="overflow-wrap:anywhere;">${props.description || 'No description available.'}</p>
                            </div>
                            <button type="button" class="show-all-projects-btn mt-5 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View all projects</button>
                        </div>
                    </div>
                `;
            }

            return `
                <div class="department-project-card cursor-pointer overflow-hidden rounded-3xl bg-white shadow-sm transition hover:shadow-md ${selectedProjectIndex === index ? selectedClass : 'border border-transparent'}" data-index="${index}">
                    <div class="overflow-hidden p-2">${imageHtml}</div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold text-slate-900">${props.name}</h3>
                        <p class="mt-2 text-xs text-slate-500">${props.barangay || 'Barangay not specified'}</p>
                        <div class="mt-3 grid gap-2 text-xs text-slate-600">
                            <div><span class="font-semibold">Status:</span> ${props.status || 'Unknown'}</div>
                            <div><span class="font-semibold">Budget:</span> ${formatCurrency(props.budget)}</div>
                            <div><span class="font-semibold">Progress:</span> ${progress.toFixed(1)}%</div>
                        </div>
                        <p class="mt-3 max-h-20 overflow-hidden break-words text-sm leading-relaxed text-slate-600" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow-wrap:anywhere;">${props.description || 'No description available.'}</p>
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
                projectList.innerHTML = `<div class="p-6 text-sm text-gray-500">No public projects recorded in ${selectedBarangayName} yet.</div>`;
                return;
            }

            projectList.innerHTML = projects.map(function(project) {
                return renderProjectCard(project, project.originalIndex, isSingle);
            }).join('');

            const cards = document.querySelectorAll('.department-project-card');
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    selectProject(projectFeatures[index], index);
                });
            });

            document.querySelectorAll('.show-all-projects-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.stopPropagation();
                    showAllProjects();
                });
            });
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
            if (map && project && project.geometry && project.geometry.coordinates) {
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

            map.fitBounds(layer.getBounds(), { padding: [40, 40] });

            // Show only markers belonging to this barangay
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

                // Draw barangay shapes
                barangayLayer = L.geoJSON(geojson, {
                    style: (feature) => ({
                        fillColor: barangayColor(feature.properties.name),
                        fillOpacity: 0.35,
                        color: '#ffffff',
                        weight: 1.5,
                    }),
                    onEachFeature: (feature, layer) => {
                        const name = feature.properties.name;
                        layer.bindTooltip(name, { sticky: true, className: 'barangay-tooltip' });
                        layer.on({
                            mouseover: (e) => {
                                if (layer !== selectedBarangayLayer) e.target.setStyle({ fillOpacity: 0.6, weight: 2.5 });
                            },
                            mouseout: (e) => {
                                if (layer !== selectedBarangayLayer) barangayLayer.resetStyle(e.target);
                            },
                            click: () => selectBarangayOnMap(layer, name),
                        });
                    },
                }).addTo(map);

                function getMarkerColor(status) {
                    return ({ 'Completed': '#10b981', 'On Going': '#3b82f6', 'On Hold': '#ef4444', 'Planning': '#fbbf24' })[status] || '#64748b';
                }

                allMarkers = L.featureGroup();
                projectFeatures = [];
                window.projectFeatures = projectFeatures;

                fetch('{{ url('/api/projects/geojson') }}')
                    .then(response => response.json())
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
                                radius: 12,
                                fillColor: getMarkerColor(project.properties.status),
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });

                            marker.bindPopup(`<div class="text-sm"><h4 class="font-bold text-black">${project.properties.name}</h4><p class="text-xs text-gray-600">${project.properties.status || 'Unknown'}</p></div>`);
                            marker.on('click', function(e) {
                                L.DomEvent.stopPropagation(e);
                                map.flyTo([coords[1], coords[0]], 16, { duration: 0.7, easeLinearity: 0.35 });
                                selectProject(project, index);
                            });

                            allMarkers.addLayer(marker);
                            projectFeatures.push(Object.assign({ originalIndex: index }, project));

                            const barangayName = project.properties.barangay;
                            if (barangayName) {
                                if (!markersByBarangay[barangayName]) markersByBarangay[barangayName] = [];
                                markersByBarangay[barangayName].push(marker);
                            }
                        });

                        function restoreListOnMapClick() {
                            if (selectedProjectIndex !== null && !selectedBarangayName) {
                                showAllProjects();
                            }
                        }

                        map.on('click', restoreListOnMapClick);
                        allMarkers.addTo(map);
                        renderProjectList(projectFeatures);
                    })
                    .catch(function(error) {
                        console.error(error);
                        projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                    });

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);

                window.addEventListener('resize', function() {
                    if (map) {
                        setTimeout(() => map.invalidateSize(), 100);
                    }
                });
            })
            .catch(console.error);
    });
</script>
@endsection
