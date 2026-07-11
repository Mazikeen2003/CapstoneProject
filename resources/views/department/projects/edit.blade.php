@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: black;">Edit Project</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $project->project_name }}</p>
    </div>

    {{-- Progress Bar Section --}}
    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <h2 class="text-lg font-bold text-black mb-4">Project Progress</h2>
        
        @php
            $startDate = \Carbon\Carbon::parse($project->start_date);
            $endDate = \Carbon\Carbon::parse($project->target_end_date);
            $today = \Carbon\Carbon::now();
            
            $totalDays = $startDate->diffInDays($endDate);
            $daysElapsed = $startDate->diffInDays($today);
            $progress = ($totalDays > 0) ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
        @endphp
        
        <div class="mb-3">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Timeline Progress</span>
                <span class="text-sm font-bold" style="color: #c9a84c;">{{ number_format($progress, 1) }}%</span>
            </div>
            <div class="h-4 bg-gray-300 rounded-full overflow-hidden">
                <div class="h-full transition-all duration-300" style="width: {{ $progress }}%; background-color: #c9a84c;"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
            <div class="p-3 bg-gray-50 rounded" style="border: 1px solid #B2BEB5;">
                <p class="text-xs text-gray-600">Start Date</p>
                <p class="text-sm font-semibold text-black">{{ $startDate->format('M d, Y') }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded" style="border: 1px solid #B2BEB5;">
                <p class="text-xs text-gray-600">Target Completion</p>
                <p class="text-sm font-semibold text-black">{{ $endDate->format('M d, Y') }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded" style="border: 1px solid #B2BEB5;">
                <p class="text-xs text-gray-600">Days Remaining</p>
                <p class="text-sm font-semibold" style="color: #c9a84c;">{{ max(0, $endDate->diffInDays($today)) }} days</p>
            </div>
        </div>
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

    {{-- Permission Status Alert --}}
    <div id="permissionAlert" class="bg-blue-50 border border-blue-300 text-blue-700 rounded-md p-4" style="display: none;">
        <p class="text-sm font-semibold">⏳ Permission Request Pending</p>
        <p class="text-xs mt-1">Your request to edit critical project fields has been submitted to the System Administrator for approval.</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <form method="POST" action="{{ route('department.projects.update', $project->project_id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-black">Project Code</label>
                    <input type="text" name="project_code" value="{{ old('project_code', $project->project_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Project Type *</label>
                    <select name="project_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" required>
                        <option value="">-- Select Project Type --</option>
                        <option value="Bridges" @selected(old('project_type', $project->project_type) == 'Bridges')>Bridges</option>
                        <option value="Buildings and Facilities" @selected(old('project_type', $project->project_type) == 'Buildings and Facilities')>Buildings and Facilities</option>
                        <option value="Flood Control and Drainage" @selected(old('project_type', $project->project_type) == 'Flood Control and Drainage')>Flood Control and Drainage</option>
                        <option value="Roads" @selected(old('project_type', $project->project_type) == 'Roads')>Roads</option>
                        <option value="Septage and Sewerage Plants" @selected(old('project_type', $project->project_type) == 'Septage and Sewerage Plants')>Septage and Sewerage Plants</option>
                        <option value="Water Provision and Storage" @selected(old('project_type', $project->project_type) == 'Water Provision and Storage')>Water Provision and Storage</option>
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Project Name</label>
                <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Barangay</label>
                <select id="barangay_id" name="barangay_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" disabled>
                    <option value="">-- None / Citywide --</option>
                    @foreach ($barangays as $barangay)
                        <option value="{{ $barangay->barangay_id }}" @selected(old('barangay_id', $project->barangay_id) == $barangay->barangay_id)>
                            {{ $barangay->barangay_name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Automatically detected from map location (locked for consistency)</p>
            </div>

            {{-- Map Section (Read-only) --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-black mb-2">Project Location (Locked)</label>
                <div id="project-location-map" class="h-80 w-full overflow-hidden rounded-md border" style="border-color: #B2BEB5; opacity: 0.8;"></div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mt-2">Selected Address</label>
                    <input id="project-address" type="text" readonly value="{{ $project->location_description }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #f9fafb; border-color: #B2BEB5; color: black;">
                </div>
                <p class="text-xs text-gray-500 mt-1">📍 Map location is locked. Contact System Administrator to change project location.</p>
            </div>

            {{-- Locked Fields Section --}}
            <div class="mt-6 p-4 bg-gray-50 rounded" style="border: 2px solid #B2BEB5;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-black">Critical Project Details (Requires Approval)</h3>
                    <button type="button" id="askPermissionBtn" class="px-3 py-1 text-xs font-semibold rounded transition" style="background-color: #c9a84c; color: #0f1e3d;">
                        🔒 Ask Permission to Edit
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-black">Start Date *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="locked-field mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($canEditCriticalFields ?? false)
                                ✅ Permission approved — you can now edit this field.
                            @else
                                🔒 Locked - Requires Admin Approval
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Target Completion *</label>
                        <input type="date" id="target_end_date" name="target_end_date" value="{{ old('target_end_date', $project->target_end_date?->format('Y-m-d')) }}" class="locked-field mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($canEditCriticalFields ?? false)
                                ✅ Permission approved — you can now edit this field.
                            @else
                                🔒 Locked - Requires Admin Approval
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mt-3">
                    <div>
                        <label class="block text-sm font-medium text-black">Approved Budget *</label>
                        <input type="number" step="0.01" id="approved_budget" name="approved_budget" value="{{ old('approved_budget', $project->approved_budget) }}" class="locked-field mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($canEditCriticalFields ?? false)
                                ✅ Permission approved — you can now edit this field.
                            @else
                                🔒 Locked - Requires Admin Approval
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black">Actual Budget Spent</label>
                        <input type="number" step="0.01" id="actual_budget" name="actual_budget" value="{{ old('actual_budget', $project->actual_budget) }}" class="locked-field mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" @if(!($canEditCriticalFields ?? false)) disabled @endif>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($canEditCriticalFields ?? false)
                                ✅ Permission approved — you can now edit this field.
                            @else
                                🔒 Locked - Requires Admin Approval
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Editable Fields Section --}}
            <div class="mt-6">
                <h3 class="text-sm font-bold text-black mb-3">Project Details</h3>

                <div>
                    <label class="block text-sm font-medium text-black">Status</label>
                    @php $status = old('current_status', $project->current_status); @endphp
                    <select name="current_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        <option value="Planning" @selected($status == 'Planning')>Planning</option>
                        <option value="On Going" @selected($status == 'On Going')>On Going</option>
                        <option value="On Hold" @selected($status == 'On Hold')>On Hold</option>
                        <option value="Completed" @selected($status == 'Completed')>Completed</option>
                        <option value="Cancelled" @selected($status == 'Cancelled')>Cancelled</option>
                        <option value="Bidding - Success" @selected($status == 'Bidding - Success')>Bidding - Success</option>
                        <option value="Bidding - Failed" @selected($status == 'Bidding - Failed')>Bidding - Failed</option>
                        <option value="Procurement" @selected($status == 'Procurement')>Procurement</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-black">Remarks / Notes</label>
                    <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('remarks', $project->remarks) }}</textarea>
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-black">Replace Project Image</label>
                    <input type="file" name="project_image" accept="image/*" class="mt-1 block w-full rounded-md border border-gray-300 text-sm shadow-sm">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('department.projects.show', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- Hidden form for permission request --}}
<form id="permissionRequestForm" method="POST" action="{{ route('department.projects.request-edit-permission', $project->project_id) }}" style="display: none;">
    @csrf
    <input type="hidden" name="reason" id="permissionReasonInput">
</form>

<div id="permissionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
        <h3 class="text-lg font-semibold text-black">Reason for Edit Request</h3>
        <p class="mt-2 text-sm text-gray-600">Please tell the administrator why you need to edit these critical project details.</p>
        <textarea id="permissionReasonTextarea" rows="4" class="mt-4 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Example: We need to update the approved budget due to revised funding."></textarea>
        <div class="mt-5 flex justify-end gap-2">
            <button type="button" id="cancelPermissionBtn" class="rounded bg-gray-200 px-4 py-2 text-sm text-gray-700">Cancel</button>
            <button type="button" id="submitPermissionBtn" class="rounded bg-amber-600 px-4 py-2 text-sm font-semibold text-white">Send Request</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map (locked mode - display only)
    const map = L.map('project-location-map').setView([{{ $project->latitude ?? 14.8497 }}, {{ $project->longitude ?? 121.0074 }}], 14);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add marker for project location
    @if($project->latitude && $project->longitude)
    L.marker([{{ $project->latitude }}, {{ $project->longitude }}]).addTo(map)
        .bindPopup('{{ $project->location_description }}');
    @endif
    
    // Disable map interactions (locked mode)
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();

    const permissionModal = document.getElementById('permissionModal');
    const permissionReasonTextarea = document.getElementById('permissionReasonTextarea');
    const permissionReasonInput = document.getElementById('permissionReasonInput');

    document.getElementById('askPermissionBtn').addEventListener('click', function(e) {
        e.preventDefault();
        permissionReasonTextarea.value = '';
        permissionModal.classList.remove('hidden');
        permissionModal.classList.add('flex');
    });

    document.getElementById('cancelPermissionBtn').addEventListener('click', function() {
        permissionModal.classList.add('hidden');
        permissionModal.classList.remove('flex');
    });

    document.getElementById('submitPermissionBtn').addEventListener('click', function() {
        permissionReasonInput.value = permissionReasonTextarea.value.trim();
        if (!permissionReasonInput.value) {
            alert('Please enter a reason for the edit request.');
            return;
        }

        permissionModal.classList.add('hidden');
        permissionModal.classList.remove('flex');
        document.getElementById('permissionRequestForm').submit();
    });

    // Check if permission was just requested
    @if(session('permission_requested'))
    document.getElementById('permissionAlert').style.display = 'block';
    @endif
});
</script>
@endsection