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
        </div>
        <div id="departmentProjectList" class="divide-y divide-gray-200"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(feature => feature.properties.kind === 'boundary');
                const barangays = geojson.features.filter(feature => feature.properties.kind === 'barangay');
                const projectList = document.getElementById('departmentProjectList');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const boundedArea = cabuyaoBounds.pad(0.02);
                const map = L.map('map', {
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

                fetch('{{ url('/api/projects/geojson') }}')
                    .then(response => response.json())
                    .then(function(projectData) {
                        const projectFeatures = [];

                        if (projectData && projectData.features) {
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
                                marker.on('click', function() {
                                    highlightProject(index);
                                });
                                projectMarkers.addLayer(marker);
                                projectFeatures.push({ feature: project, index: index });
                            });
                        }

                        function highlightProject(index) {
                            const cards = document.querySelectorAll('.department-project-card');
                            if (cards[index]) {
                                cards.forEach(function(card) { card.style.backgroundColor = ''; });
                                cards[index].style.backgroundColor = '#f3f4f6';
                                cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        }

                        if (projectFeatures.length) {
                            projectList.innerHTML = projectFeatures.map(function(item) {
                                const props = item.feature.properties;
                                const imageHtml = props.image
                                    ? `<img src="${props.image}" alt="${props.name}" class="h-24 w-full rounded-md object-cover">`
                                    : '<div class="h-24 w-full rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

                                return `
                                    <div class="department-project-card cursor-pointer p-4 hover:bg-gray-50" data-index="${item.index}">
                                        <div class="mb-3">${imageHtml}</div>
                                        <h3 class="font-semibold text-black">${props.name}</h3>
                                        <p class="mt-1 text-xs text-gray-600">${props.barangay || 'Barangay not specified'}</p>
                                        <p class="mt-1 text-xs text-gray-600">Status: ${props.status || 'Unknown'}</p>
                                        <p class="mt-1 text-xs text-gray-600">Budget: ₱${Number(props.budget || 0).toLocaleString()}</p>
                                        <p class="mt-2 text-xs text-gray-500">${props.description || 'No description available.'}</p>
                                    </div>
                                `;
                            }).join('');
                        } else {
                            projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">No projects have been added yet.</div>';
                        }

                        document.querySelectorAll('.department-project-card').forEach(function(card) {
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

                projectMarkers.addTo(map);
                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(console.error);
    });
</script>
@endsection
