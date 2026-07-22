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
            @php
                $standardTypes = ['Bridges', 'Buildings and Facilities', 'Flood Control and Drainage', 'Roads', 'Septage and Sewerage Plants', 'Water Provision and Storage'];
                $oldType = old('project_type');
                $isOtherType = $oldType && !in_array($oldType, $standardTypes);
            @endphp
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.9fr)]">
                <div class="space-y-3">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-black">Project Code</label>
                            <input type="text" name="project_code" value="{{ old('project_code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-black">Project Type *</label>
                            <select id="project_type_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" required>
                                <option value="">-- Select Project Type --</option>
                                <option value="Bridges" @selected($oldType == 'Bridges')>Bridges</option>
                                <option value="Buildings and Facilities" @selected($oldType == 'Buildings and Facilities')>Buildings and Facilities</option>
                                <option value="Flood Control and Drainage" @selected($oldType == 'Flood Control and Drainage')>Flood Control and Drainage</option>
                                <option value="Roads" @selected($oldType == 'Roads')>Roads</option>
                                <option value="Septage and Sewerage Plants" @selected($oldType == 'Septage and Sewerage Plants')>Septage and Sewerage Plants</option>
                                <option value="Water Provision and Storage" @selected($oldType == 'Water Provision and Storage')>Water Provision and Storage</option>
                                <option value="Others" @selected($isOtherType)>Others</option>
                            </select>
                            <input type="hidden" id="project_type" name="project_type" value="{{ $oldType }}">
                            <div id="project_type_other_wrapper" class="mt-2" style="{{ $isOtherType ? '' : 'display: none;' }}">
                                <label class="block text-xs font-medium text-gray-600">Please specify</label>
                                <input type="text" id="project_type_other" value="{{ $isOtherType ? $oldType : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" placeholder="Enter project type">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Project Name</label>
                        <input type="text" name="project_name" value="{{ old('project_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Barangay</label>
                        <select id="barangay_id" name="barangay_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                            <option value="">-- None / Citywide --</option>
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->barangay_id }}" data-name="{{ $barangay->barangay_name }}" @selected(old('barangay_id') == $barangay->barangay_id)>
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
                            <label class="block text-sm font-medium text-black">Status *</label>
                            <input type="hidden" name="current_status" value="Planning">
                            <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" style="border: 1px solid #B2BEB5; background-color: #f9fafb; color: black; border-radius: 0.375rem;">
                                Planning
                            </div>
                            <p class="text-xs text-gray-600 mt-1">All new projects start in Planning status. Other statuses can be changed when editing the project.</p>
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
                <a href="{{ route('department.projects.index') }}" class="px-4 py-2 rounded" style="background-color: #c1d1d7;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #162347; color: #f2f3f7;">Create Project</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Project type "Others" toggle logic
        const projectTypeSelect = document.getElementById('project_type_select');
        const projectTypeHidden = document.getElementById('project_type');
        const projectTypeOtherWrapper = document.getElementById('project_type_other_wrapper');
        const projectTypeOtherInput = document.getElementById('project_type_other');

        function syncProjectType() {
            if (projectTypeSelect.value === 'Others') {
                projectTypeOtherWrapper.style.display = 'block';
                projectTypeHidden.value = projectTypeOtherInput.value;
            } else {
                projectTypeOtherWrapper.style.display = 'none';
                projectTypeHidden.value = projectTypeSelect.value;
            }
        }

        projectTypeSelect.addEventListener('change', syncProjectType);
        projectTypeOtherInput.addEventListener('input', function() {
            projectTypeHidden.value = projectTypeOtherInput.value;
        });

        // Initialize on page load (handles old() repopulation after validation errors)
        syncProjectType();

        const latitudeInput = document.getElementById('project-latitude');
        const longitudeInput = document.getElementById('project-longitude');
        const addressDisplay = document.getElementById('project-address');
        const addressInput = document.getElementById('project-address-value');
        const barangaySelect = document.getElementById('barangay_id');
        const initialBarangayId = @json(old('barangay_id', ''));
        const initialLatitude = @json(old('latitude', ''));
        const initialLongitude = @json(old('longitude', ''));
        const initialAddress = @json(old('location_description', ''));

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(function(response) {
                if (!response.ok) { throw new Error('Unable to load Cabuyao GeoJSON'); }
                return response.json();
            })
            .then(function(geojson) {
                const boundaryFeature = geojson.features.find(f => f.properties.kind === 'boundary');
                const defaultFeature = geojson.features.find(f => f.properties.kind === 'default_location');
                const barangayFeatures = geojson.features.filter(f => f.properties.kind === 'barangay');
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
                const barangayCoordinates = {};
                barangayFeatures.forEach(function(feature) {
                    if (!feature.properties || !feature.properties.name) return;
                    barangayCoordinates[feature.properties.name] = feature.geometry.coordinates;
                });

                function setAddress(value, persist = true) {
                    addressDisplay.value = value;
                    if (persist) {
                        addressInput.value = value;
                    }
                }

                function updateProjectAddress(latlng) {
                    setAddress('Finding address...', false);
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                        .then(r => { if (!r.ok) throw new Error('Address lookup failed'); return r.json(); })
                        .then(data => setAddress(data.display_name || 'Address unavailable for this location'))
                        .catch(() => setAddress('Address unavailable. Location pin has still been saved.'));
                }

                function setProjectLocation(latlng, label = null) {
                    if (!cabuyaoBounds.contains(latlng)) return;
                    marker.setLatLng(latlng);
                    latitudeInput.value = latlng.lat.toFixed(6);
                    longitudeInput.value = latlng.lng.toFixed(6);

                    if (label) {
                        setAddress(label);
                    } else {
                        updateProjectAddress(latlng);
                    }
                }

                function applyBarangaySelection(barangayId) {
                    const selectedOption = barangaySelect.options[barangaySelect.selectedIndex];
                    const selectedName = selectedOption?.dataset.name || selectedOption?.text || '';

                    if (!barangayId) {
                        setProjectLocation(defaultLocation);
                        if (initialAddress) {
                            setAddress(initialAddress);
                        } else {
                            addressDisplay.value = 'Use map or choose a barangay';
                        }
                        return;
                    }

                    const coordinates = barangayCoordinates[selectedName];
                    if (coordinates && coordinates.length >= 2) {
                        const latlng = L.latLng(coordinates[1], coordinates[0]);
                        locationMap.setView(latlng, 14);
                        setProjectLocation(latlng, `${selectedName}, Cabuyao City`);
                    } else {
                        setAddress(selectedName || 'Selected barangay');
                    }
                }

                barangaySelect?.addEventListener('change', function() {
                    applyBarangaySelection(this.value);
                });

                if (initialLatitude && initialLongitude) {
                    setProjectLocation(L.latLng(parseFloat(initialLatitude), parseFloat(initialLongitude)), initialAddress || null);
                } else if (initialBarangayId) {
                    applyBarangaySelection(initialBarangayId);
                } else {
                    setProjectLocation(defaultLocation);
                    setAddress(initialAddress || 'Use map or choose a barangay');
                }

                locationMap.on('click', e => setProjectLocation(e.latlng));
                marker.on('dragend', e => setProjectLocation(e.target.getLatLng()));
                setTimeout(() => locationMap.invalidateSize(), 100);
            })
            .catch(() => { addressDisplay.value = 'Unable to load Cabuyao map data.'; });
    });
</script>
@endsection