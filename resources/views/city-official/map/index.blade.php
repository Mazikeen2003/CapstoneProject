@extends('layouts.city')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="flex gap-0 h-[calc(100vh-80px)] min-w-0 overflow-hidden rounded-lg border border-gray-300 shadow-sm">
    <div class="flex-1 min-w-0 relative h-full" id="map" style="background-color: #f0f0f0;"></div>

    <div class="w-[360px] bg-white border-l border-gray-200 overflow-y-auto shadow-sm">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-lg font-bold text-black">City Projects</h2>
            <p class="text-sm text-gray-500 mt-1">Citywide projects displayed on the map.</p>
            <div id="citySidebarAction" class="mt-4"></div>
        </div>

        <div id="cityProjectList" class="divide-y divide-gray-200">
            <div class="p-6 text-sm text-gray-500">
                Loading city projects...
            </div>
        </div>
    </div>
</div>

@php
    use Illuminate\Support\Facades\Storage;

    $projectMarkers = $projects->map(function($project) {
        return [
            'id' => $project->project_id,
            'name' => $project->project_name,
            'type' => $project->project_type,
            'status' => $project->current_status,
            'barangay' => $project->barangay?->barangay_name ?? 'Citywide',
            'approved_budget' => $project->approved_budget !== null ? (float) $project->approved_budget : 0,
            'actual_budget' => $project->actual_budget !== null ? (float) $project->actual_budget : 0,
            'image' => $project->project_image ? Storage::url($project->project_image) : null,
            'description' => $project->location_description ?? '',
            'lat' => $project->latitude,
            'lng' => $project->longitude,
        ];
    })->all();
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectMarkers = @json($projectMarkers);
        const projectList = document.getElementById('cityProjectList');
        const sidebarAction = document.getElementById('citySidebarAction');
        let map = null;
        let markerGroup = null;
        let selectedProjectId = null;

        function formatCurrency(value) {
            const numeric = Number(String(value).replace(/,/g, '')) || 0;
            return `₱${numeric.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        function getStatusColor(status) {
            switch (status) {
                case 'Completed': return '#10b981';
                case 'On Going': return '#3b82f6';
                case 'On Hold': return '#ef4444';
                case 'Planning': return '#fbbf24';
                default: return '#6b7280';
            }
        }

        function getStatusTextClass(status) {
            switch (status) {
                case 'Completed': return 'text-emerald-600';
                case 'On Going': return 'text-blue-600';
                case 'On Hold': return 'text-rose-600';
                case 'Planning': return 'text-amber-600';
                default: return 'text-slate-600';
            }
        }

        function renderProjectCard(project, isSelected = false, isSingle = false) {
            const imageHtml = project.image
                ? `<img src="${project.image}" alt="${project.name}" class="h-40 w-full rounded-2xl object-cover bg-slate-100">`
                : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

            if (isSingle) {
                return `
                    <div class="city-project-card cursor-pointer overflow-hidden rounded-[24px] border border-slate-200 bg-white text-slate-800 shadow-sm" data-project-id="${project.id}">
                        <div class="p-5 sm:p-6">
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Selected project</p>
                                    <h3 class="mt-2 text-xl font-semibold text-slate-900">${project.name}</h3>
                                </div>
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-700">${project.status}</span>
                            </div>
                            <div class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-2">${imageHtml}</div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                                <div class="grid gap-3 text-sm text-slate-600">
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Project type</span><span class="text-right font-semibold text-slate-900">${project.type}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Barangay</span><span class="text-right font-semibold text-slate-900">${project.barangay}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Approved</span><span class="text-right font-semibold text-slate-900">${formatCurrency(project.approved_budget)}</span></div>
                                    <div class="flex items-center justify-between gap-3"><span class="text-slate-500">Spent</span><span class="text-right font-semibold text-slate-900">${formatCurrency(project.actual_budget)}</span></div>
                                </div>
                                <p class="mt-4 text-sm leading-6 text-slate-600">${project.description || 'No description available.'}</p>
                            </div>
                            <button type="button" id="showAllProjects" class="mt-5 inline-flex w-full items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View all projects</button>
                        </div>
                    </div>
                `;
            }

            return `
                <div class="city-project-card cursor-pointer overflow-hidden rounded-3xl bg-white shadow-sm transition hover:shadow-md ${isSelected ? 'border border-slate-200 bg-slate-50' : 'border border-transparent'}" data-project-id="${project.id}">
                    <div class="overflow-hidden p-2">${imageHtml}</div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold text-slate-900">${project.name}</h3>
                        <p class="mt-2 text-xs text-slate-500 truncate">${project.barangay}</p>
                        <div class="mt-3 grid gap-2 text-xs text-slate-600">
                            <div><span class="font-semibold">Status:</span> ${project.status}</div>
                            <div><span class="font-semibold">Approved:</span> ${formatCurrency(project.approved_budget)}</div>
                            <div><span class="font-semibold">Spent:</span> ${formatCurrency(project.actual_budget)}</div>
                        </div>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">${project.description || 'No description available.'}</p>
                    </div>
                </div>
            `;
        }

        function showAllProjects() {
            selectedProjectId = null;
            renderProjectList(projectMarkers);
            updateSidebarAction(false);
            if (markerGroup && markerGroup.getLayers().length > 0) {
                const bounds = markerGroup.getBounds();
                if (bounds.isValid()) {
                    map.flyToBounds(bounds.pad(0.2), {
                        animate: true,
                        duration: 0.7,
                        easeLinearity: 0.35
                    });
                }
            }
        }

        function renderProjectList(projects, highlightId = null) {
            const isSingle = projects.length === 1;
            projectList.innerHTML = projects.map(project => renderProjectCard(project, project.id === highlightId, isSingle)).join('');
            document.querySelectorAll('.city-project-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    const projectId = parseInt(this.dataset.projectId, 10);
                    const project = projectMarkers.find(p => p.id === projectId);
                    if (project) {
                        selectProject(project);
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

        function updateSidebarAction(showButton) {
            sidebarAction.innerHTML = '';
        }

        function restoreListOnMapClick() {
            if (selectedProjectId !== null) {
                showAllProjects();
            }
        }

        function selectProject(project) {
            selectedProjectId = project.id;
            renderProjectList([project], project.id);
            updateSidebarAction(true);

            const lat = Number(project.lat);
            const lng = Number(project.lng);
            if (map && Number.isFinite(lat) && Number.isFinite(lng)) {
                map.flyTo([lat, lng], 15, {
                    duration: 0.7,
                    easeLinearity: 0.35
                });
            }
        }

        fetch("{{ asset('data/cabuyao-map.geojson') }}")
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(feature => feature.properties.kind === 'boundary');
                const cabuyaoBounds = boundaryFeature ? L.geoJSON(boundaryFeature).getBounds() : L.latLngBounds([14.325, 121.050], [14.350, 121.100]);
                map = L.map('map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0,
                    zoomControl: true,
                    minZoom: 11,
                    maxZoom: 19
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                if (boundaryFeature) {
                    // Boundary display temporarily removed.
                }

                const mappedMarkers = projectMarkers.filter(p => p.lat !== null && p.lat !== undefined && p.lng !== null && p.lng !== undefined);
                markerGroup = L.featureGroup();

                mappedMarkers.forEach(function(project) {
                    const lat = Number(project.lat);
                    const lng = Number(project.lng);
                    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                        return;
                    }

                    const marker = L.circleMarker([lat, lng], {
                        radius: 12,
                        fillColor: getStatusColor(project.status),
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    });

                    marker.on('click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        selectProject(project);
                    });

                    markerGroup.addLayer(marker);
                });

                if (markerGroup.getLayers().length > 0) {
                    markerGroup.addTo(map);
                    map.fitBounds(markerGroup.getBounds().pad(0.2));
                } else {
                    map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                }

                map.setMaxBounds(cabuyaoBounds);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
                map.on('click', restoreListOnMapClick);

                renderProjectList(projectMarkers);
                updateSidebarAction(false);
            })
            .catch(console.error);

        const cabuyaoBounds = [[14.2, 121.0], [14.5, 121.1]];
        map.fitBounds(cabuyaoBounds);
    });
</script>
@endsection