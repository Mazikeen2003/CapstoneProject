@php
    $data = old() ?: ($form->form_data ?? []);
    $location = ($project->location_description ?? ($project->barangay->barangay_name ?? 'Citywide')) . ', Cabuyao City, Laguna';
@endphp
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-black">Form {{ $number }} — {{ $title }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $project->project_name }} ({{ $project->project_code }})</p>
        <p class="text-xs text-gray-400 mt-1">@if ($form) Form created on {{ $form->created_at->format('M d, Y h:i A') }} &middot; Last updated {{ $form->updated_at->format('M d, Y h:i A') }} @else Not yet filled out @endif</p>
    </div>
    @if ($errors->any())<div class="bg-red-50 border border-red-300 text-red-700 rounded-md p-3 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('department.projects.forms.update', [$project->project_id, $formType]) }}" class="space-y-6">@csrf @method('PUT')
        @foreach ($sections as $section)
            <div class="bg-white rounded-lg p-6 space-y-4" style="border: 1px solid #B2BEB5;"><h2 class="text-lg font-bold text-black">{{ $section['title'] }}</h2><div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($section['fields'] as $field => $definition)
                    @php $kind = $definition['type'] ?? 'text'; $label = $definition['label'] ?? $field; @endphp
                    <div class="{{ ($definition['wide'] ?? false) ? 'md:col-span-2' : '' }}"><label class="block text-sm font-medium text-black">{{ $label }}</label>
                        @if ($kind === 'project')
                            <input type="text" value="{{ $project->project_name }}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;"><p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p>
                        @elseif ($kind === 'location')
                            <input type="text" value="{{ $location }}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;"><p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p>
                        @elseif ($kind === 'textarea')
                            <textarea name="{{ $field }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old($field, $data[$field] ?? '') }}</textarea>
                        @elseif ($kind === 'select')
                            @php $selected = old($field, $data[$field] ?? ''); @endphp
                            <select name="{{ $field }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"><option value="">-- Select --</option>@foreach ($definition['options'] as $option)<option value="{{ $option }}" @selected($selected === $option)>{{ $option }}</option>@endforeach</select>
                        @else
                            <input type="{{ $kind }}" @if ($kind === 'number') min="0" @endif name="{{ $field }}" value="{{ old($field, $data[$field] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;" @if ($definition['readonly'] ?? false) readonly @endif>
                        @endif
                    </div>
                @endforeach
            </div></div>
        @endforeach
        @include('department.projects.forms.signatories', ['project' => $project, 'data' => $data])
        <div class="flex justify-end space-x-3"><a href="{{ route('department.projects.show', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Cancel</a>@if ($form)<a href="{{ route('department.projects.forms.pdf', [$project->project_id, $formType]) }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Download PDF</a>@else<span title="Save the form first before generating a PDF" class="px-4 py-2 rounded cursor-not-allowed" style="background-color: #e5e7eb; color: #9ca3af;">Download PDF</span>@endif<button type="submit" class="px-4 py-2 rounded" style="background-color: #162347; color: #f2f3f7;">Save Form</button></div>
    </form>
</div>
