@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="space-y-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Engineering Map</h1>
            <p class="mt-1 text-sm text-slate-500">Review project locations and field implementation coverage.</p>
        </div>
        <div class="rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm" style="height: calc(100vh - 13.5rem);">
            <div class="min-w-0 w-full h-full relative" id="engineering-map" style="background-color: #f0f0f0;">
                @include('components.map-status-legend')
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(res => res.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features?.find(f => f.properties?.kind === 'boundary');
                const geoJsonBoundary = boundaryFeature ? boundaryFeature : (geojson.features?.length ? geojson : null);

                if (!geoJsonBoundary) {
                    console.error('GeoJSON boundary is missing or malformed:', geojson);
                    return;
                }

                const cabuyaoBounds = L.geoJSON(geoJsonBoundary).getBounds();
                const map = L.map('engineering-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(geoJsonBoundary, {
                    style: {
                        color: '#3b82f6',
                        weight: 2,
                        opacity: 0.6,
                        fillOpacity: 0.1
                    }
                }).addTo(map);

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());

                fetch('{{ route("api.projects.geojson") }}')
                    .then(r => r.json())
                    .then(function(data) {
                        L.geoJSON(data, {
                            pointToLayer: function(feature, latlng) {
                                const statusColor = {
                                    'Proposed': '#2563eb',
                                    'For bidding': '#f59e0b',
                                    'Bidding ongoing': '#06b6d4',
                                    'Award of contract': '#8b5cf6',
                                    'Implementation': '#0f766e',
                                    'Completed': '#16a34a',
                                    'On Hold': '#dc2626',
                                    'Cancelled': '#64748b'
                                };
                                const normalizedStatus = String(feature.properties.status || '').trim();

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: statusColor[normalizedStatus] || '#64748b',
                                    color: '#000',
                                    weight: 2,
                                    opacity: 0.8,
                                    fillOpacity: 0.7
                                });
                            },
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                const description = props.description && props.description !== 'No description available.'
                                    ? `<p class="text-xs mt-2"><strong>Description:</strong> ${props.description}</p>`
                                    : '';

                                layer.bindPopup(`
                                    <div class="text-sm">
                                        <h4 class="font-bold">${props.name}</h4>
                                        <p class="text-xs text-gray-600">${props.code}</p>
                                        <p class="text-xs"><strong>Status:</strong> ${props.status}</p>
                                        <p class="text-xs"><strong>Barangay:</strong> ${props.barangay}</p>
                                        <p class="text-xs"><strong>Budget:</strong> ₱${parseInt(props.budget).toLocaleString()}</p>
                                        ${description}
                                        <a href="${props.url}" class="text-blue-600 text-xs mt-2 inline-block">View Details</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    });

                setTimeout(() => map.invalidateSize(), 100);
            });
    });
</script>
@endsection
