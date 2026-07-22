@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-black">Form 1 — Initial Project Report</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $project->project_name }} ({{ $project->project_code }})</p>
        <p class="text-xs text-gray-400 mt-1">
            @if ($form)
                Form created on {{ $form->created_at->format('M d, Y h:i A') }}
                &middot; Last updated {{ $form->updated_at->format('M d, Y h:i A') }}
            @else
                Not yet filled out
            @endif
        </p>
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

    @php
        $data = old() ?: ($form->form_data ?? []);
    @endphp

    <form method="POST" action="{{ route('department.projects.forms.update', [$project->project_id, 'form_1']) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Section: Basic Info --}}
        <div class="bg-white rounded-lg p-6 space-y-4" style="border: 1px solid #B2BEB5;">
            <h2 class="text-lg font-bold text-black">Project Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-black">1. Implementing Agency</label>
                    <input type="text" name="implementing_agency" value="{{ old('implementing_agency', $data['implementing_agency'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">2. Program/Project Title</label>
                    <input type="text" value="{{ $project->project_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;" readonly>
                    <p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-black">3. Component Details</label>
                <textarea name="component_details" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('component_details', $data['component_details'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-black">4. Fund Source</label>
                    @php $fundSource = old('fund_source', $data['fund_source'] ?? ''); @endphp
                    <select name="fund_source" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        <option value="">-- Select --</option>
                        @foreach (['ODA Loan', 'ODA Grant', 'ODA Loan and Grant', 'LFP', 'PPP', 'NTA', 'Local Development Fund'] as $option)
                            <option value="{{ $option }}" @selected($fundSource == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">5. Funding Agency</label>
                    <input type="text" name="funding_agency" value="{{ old('funding_agency', $data['funding_agency'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-black">6. Mode of Implementation</label>
                    @php $mode = old('mode_of_implementation', $data['mode_of_implementation'] ?? ''); @endphp
                    <select name="mode_of_implementation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        <option value="">-- Select --</option>
                        @foreach (['By administration', 'By Contract', 'Implemented by the Development Partner/Funding Agency', 'Coursed through NGOs/CSOs'] as $option)
                            <option value="{{ $option }}" @selected($mode == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">7. Total Program/Project Cost (PHP)</label>
                    <input type="text" name="total_project_cost" value="{{ old('total_project_cost', $data['total_project_cost'] ?? number_format($project->approved_budget ?? 0, 2)) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-black">8. Sector</label>
                    @php $sector = old('sector', $data['sector'] ?? ''); @endphp
                    <select name="sector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                        <option value="">-- Select --</option>
                        @foreach (['General Public Services', 'Social Services', 'Economic Services', 'Other Services'] as $option)
                            <option value="{{ $option }}" @selected($sector == $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">9. Location</label>
                    <input type="text" value="{{ $project->location_description ?? ($project->barangay->barangay_name ?? 'Citywide') }}, Cabuyao City, Laguna" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;" readonly>
                    <p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-black">10. Start Date</label>
                    <input type="text" value="{{ $project->start_date?->format('M d, Y') ?? '—' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">11. End Date</label>
                    <input type="text" value="{{ $project->target_end_date?->format('M d, Y') ?? '—' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;" readonly>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-black">12. Remarks</label>
                <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('remarks', $data['remarks'] ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-black">13. Target Employment Generated</label>
                <div class="grid grid-cols-2 gap-4 mt-1">
                    <div>
                        <label class="block text-xs text-gray-500"># of Male</label>
                        <input type="number" min="0" name="target_employment_male" value="{{ old('target_employment_male', $data['target_employment_male'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500"># of Female</label>
                        <input type="number" min="0" name="target_employment_female" value="{{ old('target_employment_female', $data['target_employment_female'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Output Indicators --}}
        <div class="bg-white rounded-lg p-6 space-y-4" style="border: 1px solid #B2BEB5;">
            <h2 class="text-lg font-bold text-black">14. Output Indicators</h2>
            <p class="text-xs text-gray-500">Leave blank for now if not yet defined.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @for ($i = 1; $i <= 5; $i++)
                    <div>
                        <label class="block text-sm font-medium text-black">Output Indicator {{ $i }}</label>
                        <input type="text" name="output_indicator_{{ $i }}" value="{{ old("output_indicator_{$i}", $data["output_indicator_{$i}"] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    </div>
                @endfor
            </div>
        </div>

        {{-- Section: Monthly Targets --}}
        <div class="bg-white rounded-lg p-6 space-y-4" style="border: 1px solid #B2BEB5;">
            <h2 class="text-lg font-bold text-black">15. Monthly Targets</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr style="border-bottom: 2px solid #B2BEB5;">
                            <th class="text-left py-2 px-2 font-semibold text-black">Month</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.1 Financial Target</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.2 Physical Target (%)</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.3.1 OI 1</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.3.2 OI 2</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.3.3 OI 3</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.3.4 OI 4</th>
                            <th class="text-left py-2 px-2 font-semibold text-black">15.3.5 OI 5</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($months as $month)
                            @php $monthData = old("monthly.$month", $data['monthly'][$month] ?? []); @endphp
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td class="py-2 px-2 font-medium text-black capitalize">{{ $month }}</td>
                                <td class="py-2 px-1">
                                    <input type="text" name="monthly[{{ $month }}][financial_target]" value="{{ $monthData['financial_target'] ?? '' }}" class="w-full rounded border-gray-300 text-xs px-2 py-1" style="border-color: #B2BEB5; color: black;">
                                </td>
                                <td class="py-2 px-1">
                                    <input type="text" name="monthly[{{ $month }}][physical_target]" value="{{ $monthData['physical_target'] ?? '' }}" class="w-full rounded border-gray-300 text-xs px-2 py-1" style="border-color: #B2BEB5; color: black;">
                                </td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <td class="py-2 px-1">
                                        <input type="text" name="monthly[{{ $month }}][oi_{{ $i }}]" value="{{ $monthData["oi_{$i}"] ?? '' }}" class="w-full rounded border-gray-300 text-xs px-2 py-1" style="border-color: #B2BEB5; color: black;">
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section: Submitted By / Approved By --}}
        <div class="bg-white rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6" style="border: 1px solid #B2BEB5;">
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-black">Submitted By</h3>
                <div>
                    <label class="block text-sm font-medium text-black">16. Submitted by</label>
                    <input type="text" name="submitted_by" value="{{ old('submitted_by', $data['submitted_by'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">17. Designation/Office</label>
                    <input type="text" name="submitted_designation" value="{{ old('submitted_designation', $data['submitted_designation'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">18. Date</label>
                    <input type="date" name="submitted_date" value="{{ old('submitted_date', $data['submitted_date'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="text-sm font-bold text-black">Approved By</h3>
                <div>
                    <label class="block text-sm font-medium text-black">19. Approved by</label>
                    <input type="text" name="approved_by" value="{{ old('approved_by', $data['approved_by'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">20. Designation/Office</label>
                    <input type="text" name="approved_designation" value="{{ old('approved_designation', $data['approved_designation'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">21. Date</label>
                    <input type="date" name="approved_date" value="{{ old('approved_date', $data['approved_date'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('department.projects.show', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Cancel</a>
            @if ($form)<a href="{{ route('department.projects.forms.pdf', [$project->project_id, 'form_1']) }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Download PDF</a>@else<span title="Save the form first before generating a PDF" class="px-4 py-2 rounded cursor-not-allowed" style="background-color: #e5e7eb; color: #9ca3af;">Download PDF</span>@endif
            <button type="submit" class="px-4 py-2 rounded" style="background-color: #162347; color: #f2f3f7;">Save Form</button>
        </div>
    </form>
</div>
@endsection
