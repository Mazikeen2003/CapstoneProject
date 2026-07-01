@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)] overflow-hidden rounded-lg border border-gray-300 shadow-sm">
    <div class="flex-1 relative" id="map" style="background-color: #f0f0f0;"></div>

    <div class="w-[360px] bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-lg font-bold text-black">Department Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Cabuyao City Projects</p>
            <div id="departmentSidebarAction" class="mt-4"></div>
        </div>
        <div id="departmentProjectList" class="divide-y divide-gray-200"></div>
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

        function formatCurrency(value) {
            return `₱${Number(value || 0).toLocaleString()}`;
        }

        function renderProjectCard(project, index, isSingle = false) {
            const props = project.properties;
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
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                                <div class="grid gap-3 text-sm text-slate-600">
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Barangay</span><span class="text-right font-semibold text-slate-900">${props.barangay || 'Not specified'}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Budget</span><span class="font-semibold text-slate-900">${formatCurrency(props.budget)}</span></div>
                                </div>
                                <p class="mt-4 text-sm leading-6 text-slate-600">${props.description || 'No description available.'}</p>
                            </div>
                            <button type="button" id="showAllProjects" class="mt-5 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View all projects</button>
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
                        </div>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">${props.description || 'No description available.'}</p>
                    </div>
                </div>
            `;
        }

        function updateSidebarAction(isSingle) {
            const actionContainer = document.getElementById('departmentSidebarAction');
            actionContainer.innerHTML = '';
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
            updateSidebarAction(isSingle);
            projectList.innerHTML = projects.map(function(project, index) {
                return renderProjectCard(project, project.originalIndex, isSingle);
            }).join('');

            const cards = document.querySelectorAll('.department-project-card');
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    selectProject(projectFeatures[index], index);
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
                map.fitBounds(boundedArea, {
                    padding: [24, 24],
                    animate: true,
                    duration: 0.7,
                    easeLinearity: 0.3
                });
            }
        }

        function selectProject(project, index) {
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

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(feature => feature.properties.kind === 'boundary');
                const barangays = geojson.features.filter(feature => feature.properties.kind === 'barangay');
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
                                fillColor: '#2563eb',
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });

                            marker.bindPopup(`<div class="text-sm"><h4 class="font-bold text-black">${project.properties.name}</h4><p class="text-xs text-gray-600">${project.properties.status || 'Unknown'}</p></div>`);
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

                        function restoreListOnMapClick() {
                            if (selectedProjectIndex !== null) {
                                showAllProjects();
                            }
                        }

                        map.on('click', restoreListOnMapClick);
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
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(console.error);
    });
</script>
@endsection
