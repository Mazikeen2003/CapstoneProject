@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="space-y-2">
    <div>
        <h1 class="text-xl font-bold text-white" style="color: black;">Create New Project</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 rounded-md p-3 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-3" style="background-color: #white; border: 2px solid #B2BEB5;">
        <form method="POST" action="{{ route('department.projects.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.9fr)]">
                <div class="space-y-3">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-black">Project Code</label>
                            <input type="text" name="project_code" value="{{ old('project_code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-black">Project Type</label>
                            <input type="text" name="project_type" value="{{ old('project_type') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Project Name</label>
                        <input type="text" name="project_name" value="{{ old('project_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Barangay</label>
                        <select name="barangay_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                            <option value="">-- None / Citywide --</option>
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->barangay_id }}" @selected(old('barangay_id') == $barangay->barangay_id)>
                                    {{ $barangay->barangay_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-black">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-black">Target Completion</label>
                            <input type="date" name="target_end_date" value="{{ old('target_end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-black">Approved Budget</label>
                            <input type="number" step="0.01" name="approved_budget" value="{{ old('approved_budget') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-black">Status</label>
                            <select name="current_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                                <option value="Planning" @selected(old('current_status') == 'Planning')>Planning</option>
                                <option value="On Going" @selected(old('current_status') == 'On Going')>On Going</option>
                                <option value="On Hold" @selected(old('current_status') == 'On Hold')>On Hold</option>
                                <option value="Completed" @selected(old('current_status') == 'Completed')>Completed</option>
                                <option value="Cancelled" @selected(old('current_status') == 'Cancelled')>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Description / Remarks</label>
                        <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-black">Project Location</label>
                    <div id="project-location-map" class="h-80 w-full overflow-hidden rounded-md border" style="border-color: #B2BEB5;"></div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Selected Address</label>
                        <input id="project-address" type="text" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #f9fafb; border-color: #B2BEB5; color: black;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Project Image</label>
                        <input type="file" name="project_image" accept="image/*" class="mt-1 block w-full rounded-md border border-gray-300 text-sm shadow-sm file:mr-4 file:border-0 file:px-4 file:py-2 file:text-sm file:font-semibold" style="border-color: #B2BEB5; color: black;">
                    </div>
                    <input id="project-address-value" type="hidden" name="location_description">
                    <input id="project-latitude" type="hidden" name="latitude">
                    <input id="project-longitude" type="hidden" name="longitude">
                </div>
            </div>
            <div class="mt-3 flex justify-end space-x-3">
                <a href="{{ route('department.projects.index') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Create Project</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const latitudeInput = document.getElementById('project-latitude');
        const longitudeInput = document.getElementById('project-longitude');
        const addressDisplay = document.getElementById('project-address');
        const addressInput = document.getElementById('project-address-value');

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(function(response) {
                if (!response.ok) { throw new Error('Unable to load Cabuyao GeoJSON'); }
                return response.json();
            })
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(f => f.properties.kind === 'boundary');
                const defaultFeature = geojson.features.find(f => f.properties.kind === 'default_location');
                const cabuyaoBounds = L.geoJSON(boundaryFeature).getBounds();
                const defaultLocation = L.latLng(
                    defaultFeature.geometry.coordinates[1],
                    defaultFeature.geometry.coordinates[0]
                );
                const locationMap = L.map('project-location-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(locationMap);

                locationMap.fitBounds(cabuyaoBounds, { padding: [16, 16] });
                locationMap.setMinZoom(locationMap.getZoom());

                const marker = L.marker(defaultLocation, { draggable: true }).addTo(locationMap);

                function setAddress(value) {
                    addressDisplay.value = value;
                    addressInput.value = value;
                }

                function updateProjectAddress(latlng) {
                    setAddress('Finding address...');
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                        .then(r => { if (!r.ok) throw new Error('Address lookup failed'); return r.json(); })
                        .then(data => setAddress(data.display_name || 'Address unavailable for this location'))
                        .catch(() => setAddress('Address unavailable. Location pin has still been saved.'));
                }

                function setProjectLocation(latlng) {
                    if (!cabuyaoBounds.contains(latlng)) return;
                    marker.setLatLng(latlng);
                    latitudeInput.value = latlng.lat.toFixed(6);
                    longitudeInput.value = latlng.lng.toFixed(6);
                    updateProjectAddress(latlng);
                }

                setProjectLocation(defaultLocation);
                locationMap.on('click', e => setProjectLocation(e.latlng));
                marker.on('dragend', e => setProjectLocation(e.target.getLatLng()));
                setTimeout(() => locationMap.invalidateSize(), 100);
            })
            .catch(() => { addressDisplay.value = 'Unable to load Cabuyao map data.'; });
    });
</script>
@endsection