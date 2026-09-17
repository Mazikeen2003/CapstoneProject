@if ((string) $form_number === '1')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 7mm 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 7.5px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.5px; font-weight: bold; line-height: 1.35; margin-bottom: 12px; }
        .agency { font-size: 7px; margin: 0 0 9px; }
        .agency-value { display: inline-block; width: 115px; border-bottom: .6px solid #000; margin-left: 12px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 1.5px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; }
        .data { font-size: 7px; }
        .data td { padding: 1.5px 2px; }
        .monthly td { height: 24px; }
        .month { font-style: italic; }
        .total { font-size: 6.5px; font-weight: bold; line-height: 1.1; }
        .indicator-note { text-align: center; vertical-align: middle; font-size: 7px; font-weight: bold; white-space: pre-line; }
        .signature { width: 85%; margin-top: 16px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; }
        .signature .label { width: 9%; }
        .signature .value { width: 32%; }
    </style>
</head>
<body>
@php
    $formData = $form->form_data ?? [];
    $value = fn ($key) => $formData[$key] ?? '—';
    $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
    $indicators = array_filter(array_map(
        fn ($number) => $formData['output_indicator_' . $number] ?? null,
        range(1, 5)
    ));
@endphp

<div class="form-number">RPMES FORM 1</div>
<div class="title">
    REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>
    INITIAL PROJECT REPORT<br>
    (Month, Day, Year/Quarter)
</div>
<div class="agency">Implementing Agency:<span class="agency-value">{{ $value('implementing_agency') }}</span></div>

<table class="data">
    <colgroup>
        <col style="width:8.35%"><col style="width:4.85%"><col style="width:3.45%"><col style="width:4.85%"><col style="width:5.25%"><col style="width:5.3%"><col style="width:3.35%">
        <col style="width:3.45%"><col style="width:3.45%"><col style="width:3.45%"><col style="width:3.8%"><col style="width:3.8%"><col style="width:5.25%"><col style="width:2.55%"><col style="width:2.55%"><col style="width:4.25%">
        <col style="width:4.25%"><col style="width:3.8%"><col style="width:5.4%"><col style="width:4.57%"><col style="width:4.57%"><col style="width:4.57%"><col style="width:4.57%"><col style="width:4.57%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th rowspan="2">Component Details</th><th rowspan="2">Fund Source</th><th rowspan="2">Funding Agency</th><th rowspan="2">Mode of Implementation</th><th rowspan="2">Total Program/<br>Project Cost<br>(PHP)</th><th rowspan="2">Sector</th>
            <th colspan="3">Location</th><th rowspan="2">Start Date<br>(mm-dd-yy)</th><th rowspan="2">End date<br>(mm-dd-yy)</th><th rowspan="2">Remarks</th><th colspan="2">Target Employment Generated</th><th rowspan="2">Output Indicators</th>
            <th rowspan="2">Month</th><th rowspan="2">Financial Targets</th><th rowspan="2">Physical Target<br>(in %)</th><th rowspan="2">Targets of Output Indicator 1</th><th rowspan="2">Targets of Output Indicator 2</th><th rowspan="2">Targets of Output Indicator 3</th><th rowspan="2">Targets of Output Indicator 4</th><th rowspan="2">Targets of Output Indicator 5</th>
        </tr>
        <tr><th>Province</th><th>City/<br>Municipality</th><th>Barangay</th><th>M</th><th>F</th></tr>
    </thead>
    <tbody>
        <tr class="monthly">
            <td rowspan="13">{{ $project->project_name }}</td><td rowspan="13">{{ $value('component_details') }}</td><td rowspan="13">{{ $value('fund_source') }}</td><td rowspan="13">{{ $value('funding_agency') }}</td><td rowspan="13">{{ $value('mode_of_implementation') }}</td><td rowspan="13">{{ $value('total_project_cost') }}</td><td rowspan="13">{{ $value('sector') }}</td>
            <td rowspan="13">Laguna</td><td rowspan="13">Cabuyao City</td><td rowspan="13">{{ $project->barangay->barangay_name ?? '—' }}</td><td rowspan="13">{{ $project->start_date?->format('m-d-y') ?? '—' }}</td><td rowspan="13">{{ $project->target_end_date?->format('m-d-y') ?? '—' }}</td><td rowspan="13">{{ $value('remarks') }}</td><td rowspan="13">{{ $value('target_employment_male') }}</td><td rowspan="13">{{ $value('target_employment_female') }}</td>
            <td rowspan="13" class="indicator-note">{{ implode("\n\n", $indicators) }}</td>
            <td class="total">Total Target for<br>the year</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
    @foreach ($months as $month)
        @php($target = $formData['monthly'][$month] ?? [])
        <tr class="monthly"><td class="month">{{ ucfirst($month) }}</td><td>{{ $target['financial_target'] ?? '' }}</td><td>{{ $target['physical_target'] ?? '' }}</td><td>{{ $target['oi_1'] ?? '' }}</td><td>{{ $target['oi_2'] ?? '' }}</td><td>{{ $target['oi_3'] ?? '' }}</td><td>{{ $target['oi_4'] ?? '' }}</td><td>{{ $target['oi_5'] ?? '' }}</td></tr>
    @endforeach
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '2')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 7mm 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 7px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.5px; font-weight: bold; line-height: 1.35; margin-bottom: 12px; }
        .agency { font-size: 7px; margin: 0 0 9px; }
        .agency-value { display: inline-block; width: 160px; border-bottom: .6px solid #000; margin-left: 12px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.5px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.16; }
        td { height: 22px; vertical-align: top; font-size: 6.5px; }
        .signature { width: 100%; margin-top: 16px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; }
        .signature .label { width: 8%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 2</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PHYSICAL AND FINANCIAL ACCOMPLISHMENT REPORT<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency:<span class="agency-value">{{ $formData['implementing_agency'] ?? '' }}</span></div>

<table>
    <colgroup>
        <col style="width:8.2%"><col style="width:4.8%"><col style="width:4.8%"><col style="width:3.4%"><col style="width:3.7%"><col style="width:5.5%">
        <col style="width:5.4%"><col style="width:5.4%"><col style="width:5.4%"><col style="width:5.4%">
        <col style="width:4.8%"><col style="width:4.8%"><col style="width:4.4%"><col style="width:4.1%"><col style="width:4.5%"><col style="width:4.5%"><col style="width:5.2%"><col style="width:2.5%"><col style="width:2.5%"><col style="width:8.8%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th colspan="2">Implementation Schedule</th><th rowspan="2">Fund Source</th><th rowspan="2">Funding Agency</th><th rowspan="2">Total Program/Project Cost (PHP)</th>
            <th colspan="4">Financial Status (in PHP exact figures)</th><th colspan="3">Physical Accomplishment</th><th rowspan="2">Output Indicator</th><th rowspan="2">End-of-Project Target</th><th rowspan="2">Target to date</th><th rowspan="2">Actual to date</th><th colspan="2">Employment Generated</th><th rowspan="2">Remarks</th>
        </tr>
        <tr>
            <th>Start Date<br>(mm-dd-yyyy)</th><th>End Date<br>(mm-dd-yyyy)</th><th>Appropriations</th><th>Allotment</th><th>Obligations</th><th>Disbursements</th><th>Target OWPA<br>to date (%)</th><th>Actual OWPA<br>to date (%)</th><th>Slippage</th><th>M</th><th>F</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $project->project_name }}</td><td>{{ $formData['start_date'] ?? $project->start_date?->format('m-d-Y') ?? '' }}</td><td>{{ $formData['end_date'] ?? $project->target_end_date?->format('m-d-Y') ?? '' }}</td><td>{{ $formData['fund_source'] ?? '' }}</td><td>{{ $formData['funding_agency'] ?? '' }}</td><td>{{ $formData['total_project_cost'] ?? '' }}</td>
            <td>{{ $formData['appropriations'] ?? '' }}</td><td>{{ $formData['allotment'] ?? '' }}</td><td>{{ $formData['obligations'] ?? '' }}</td><td>{{ $formData['disbursements'] ?? '' }}</td><td>{{ $formData['target_owpa_to_date'] ?? '' }}</td><td>{{ $formData['actual_owpa_to_date'] ?? '' }}</td><td>{{ $formData['slippage'] ?? '' }}</td><td>{{ collect(range(1, 5))->map(fn ($number) => $formData['output_indicator_' . $number] ?? null)->filter()->implode("\n") }}</td><td>{{ $formData['end_of_project_target'] ?? '' }}</td><td>{{ $formData['target_to_date'] ?? '' }}</td><td>{{ $formData['actual_to_date'] ?? '' }}</td><td>{{ $formData['employment_generated_male'] ?? '' }}</td><td>{{ $formData['employment_generated_female'] ?? '' }}</td><td>{{ $formData['remarks'] ?? '' }}</td>
        </tr>
        @for ($row = 1; $row <= 12; $row++)
            <tr>
                @for ($column = 1; $column <= 20; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '3')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 7px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.5px; font-weight: bold; line-height: 1.35; margin-bottom: 20px; }
        .agency { font-size: 7px; margin: 0 0 10px; }
        .agency-value { display: inline-block; width: 180px; border-bottom: .6px solid #000; margin-left: 12px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; font-size: 7px; white-space: pre-line; }
        .signature { width: 100%; margin-top: 18px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 13%; }
        .signature .value { width: 37%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 3</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT EXCEPTION REPORT<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency/NGOs/Concerned Citizens:<span class="agency-value">{{ $formData['implementing_agency'] ?? '' }}</span></div>

<table>
    <colgroup>
        <col style="width:13.7%"><col style="width:9.5%"><col style="width:8.5%"><col style="width:6.8%"><col style="width:9%"><col style="width:6.8%"><col style="width:8.2%"><col style="width:5.8%"><col style="width:5.5%"><col style="width:8.8%"><col style="width:8.8%"><col style="width:8.6%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th rowspan="2">Implementing Agency</th><th rowspan="2">Sector</th><th colspan="3">Location</th><th rowspan="2">Findings</th><th rowspan="2">Typology</th><th rowspan="2">Issue Status</th><th rowspan="2">Reasons</th><th rowspan="2">Actions Taken</th><th rowspan="2">Actions to be Taken</th>
        </tr>
        <tr><th>Province</th><th>City/Municipality</th><th>Barangay</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $project->project_name }}</td><td>{{ $formData['implementing_agency'] ?? '' }}</td><td>{{ $formData['sector'] ?? '' }}</td><td>Laguna</td><td>Cabuyao City</td><td>{{ $project->barangay->barangay_name ?? '' }}</td><td>{{ $formData['findings'] ?? '' }}</td><td>{{ $formData['typology'] ?? '' }}</td><td>{{ $formData['issue_status'] ?? '' }}</td><td>{{ $formData['reasons'] ?? '' }}</td><td>{{ $formData['actions_taken'] ?? '' }}</td><td>{{ $formData['actions_to_be_taken'] ?? '' }}</td>
        </tr>
        @for ($row = 1; $row <= 8; $row++)
            <tr>
                @for ($column = 1; $column <= 12; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '4')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 8px; font-weight: bold; line-height: 1.35; margin-bottom: 20px; }
        .agency { font-size: 8px; font-weight: bold; margin: 0 0 10px; }
        .agency-value { display: inline-block; width: 175px; border-bottom: .6px solid #000; margin-left: 5px; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 3px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 28px; vertical-align: top; white-space: pre-line; }
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 10%; }
        .signature .value { width: 40%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 4</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT RESULTS<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency:<span class="agency-value">{{ $formData['implementing_agency'] ?? '' }}</span></div>

<table>
    <colgroup><col style="width:26%"><col style="width:26%"><col style="width:23%"><col style="width:25%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Program/Project Objectives</th><th>Results/Outcome Indicator/Target</th><th>Observed Results/Outcome/Impact</th></tr></thead>
    <tbody>
        <tr><td>{{ $project->project_name }}</td><td>{{ $formData['program_objectives'] ?? '' }}</td><td>{{ $formData['results_outcome_target'] ?? '' }}</td><td>{{ $formData['observed_results'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 8; $row++)
            <tr><td></td><td></td><td></td><td></td></tr>
        @endfor
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '5')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 7mm 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 7px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.5px; font-weight: bold; line-height: 1.35; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.3px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.14; }
        td { height: 21px; vertical-align: top; font-size: 6.5px; }
        .signature { margin-top: 16px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; }
        .signature .label { width: 14%; }
        .signature .value { width: 36%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 5</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>SUMMARY OF FINANCIAL AND PHYSICAL ACCOMPLISHMENTS<br>(Month, Day, Year) (Quarter)</div>
<table>
    <colgroup>
        <col style="width:14%"><col style="width:6%"><col style="width:5.5%"><col style="width:6.5%">
        <col style="width:6.2%"><col style="width:6.2%"><col style="width:6.2%"><col style="width:6.2%"><col style="width:5%"><col style="width:5.3%">
        <col style="width:5%"><col style="width:5%"><col style="width:4.5%"><col style="width:3.8%"><col style="width:3.8%"><col style="width:8%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th rowspan="2">Fund Source</th><th rowspan="2">Funding Agency</th><th rowspan="2">Total Program/Project Cost (PHP)</th>
            <th colspan="6">Financial Status (in PHP exact figures)</th><th colspan="3">Physical Accomplishment</th><th colspan="2">Employment Generated</th><th rowspan="2">Remarks</th>
        </tr>
        <tr>
            <th>Appropriations</th><th>Allotment</th><th>Obligations</th><th>Disbursements</th><th>Funding Support (%)<br><span style="font-size:5px">Allotment/Appropriations × 100</span></th><th>Fund Utilization (%)</th><th>Target OWPA to date (%)</th><th>Actual OWPA to date (%)</th><th>Slippage</th><th>M</th><th>F</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $project->project_name }}</td><td>{{ $formData['fund_source'] ?? '' }}</td><td>{{ $formData['funding_agency'] ?? '' }}</td><td>{{ $formData['total_project_cost'] ?? '' }}</td><td>{{ $formData['appropriations'] ?? '' }}</td><td>{{ $formData['allotment'] ?? '' }}</td><td>{{ $formData['obligations'] ?? '' }}</td><td>{{ $formData['disbursements'] ?? '' }}</td><td>{{ $formData['funding_support'] ?? '' }}</td><td>{{ $formData['fund_utilization'] ?? '' }}</td><td>{{ $formData['target_owpa_to_date'] ?? '' }}</td><td>{{ $formData['actual_owpa_to_date'] ?? '' }}</td><td>{{ $formData['slippage'] ?? '' }}</td><td>{{ $formData['employment_generated_male'] ?? '' }}</td><td>{{ $formData['employment_generated_female'] ?? '' }}</td><td>{{ $formData['remarks'] ?? '' }}</td>
        </tr>
        @for ($row = 1; $row <= 14; $row++)
            <tr>
                @for ($column = 1; $column <= 16; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '6')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 7mm 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 7px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.2px; font-weight: bold; line-height: 1.35; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.2px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.14; }
        td { height: 21px; vertical-align: top; font-size: 6.4px; white-space: pre-line; }
        .signature { width: 91%; margin-top: 18px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 16%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 6</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>REPORT ON THE STATUS OF PROJECTS ENCOUNTERING IMPLEMENTATION PROBLEMS<br>(Month, Day, Year) (Quarter)</div>
<table>
    <colgroup>
        <col style="width:14.5%"><col style="width:5.8%"><col style="width:5.7%"><col style="width:4.3%"><col style="width:5.1%"><col style="width:5.1%"><col style="width:5.3%"><col style="width:7.3%"><col style="width:4.5%"><col style="width:4.2%"><col style="width:6.6%"><col style="width:8.8%"><col style="width:8.7%"><col style="width:4.6%"><col style="width:8.5%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th rowspan="2">Location</th><th rowspan="2">IA</th><th rowspan="2">Fund Utilization (%)</th><th colspan="3">Physical Accomplishment</th><th rowspan="2">Issue Details</th><th rowspan="2">Issue Typology</th><th rowspan="2">Issue Status</th><th rowspan="2">Source of Information</th><th rowspan="2">Actions Taken</th><th rowspan="2">Actions to be Taken</th><th rowspan="2">For NPMC Action (Y/N)</th><th rowspan="2">Requested Actions from the NPMC</th>
        </tr>
        <tr><th>Target OWPA to date (%)</th><th>Actual OWPA to date (%)</th><th>Slippage</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $project->project_name }}</td><td>{{ $project->location_description ?? ($project->barangay->barangay_name ?? '') }}</td><td>{{ $formData['implementing_agency'] ?? '' }}</td><td>{{ $formData['fund_utilization'] ?? '' }}</td><td>{{ $formData['target_owpa_to_date'] ?? '' }}</td><td>{{ $formData['actual_owpa_to_date'] ?? '' }}</td><td>{{ $formData['slippage'] ?? '' }}</td><td>{{ $formData['issue_details'] ?? '' }}</td><td>{{ $formData['issue_typology'] ?? '' }}</td><td>{{ $formData['issue_status'] ?? '' }}</td><td>{{ $formData['source_of_information'] ?? '' }}</td><td>{{ $formData['actions_taken'] ?? '' }}</td><td>{{ $formData['actions_to_be_taken'] ?? '' }}</td><td>{{ $formData['for_npmc_action'] ?? '' }}</td><td>{{ $formData['requested_actions_from_npmc'] ?? '' }}</td>
        </tr>
        @for ($row = 1; $row <= 15; $row++)
            <tr>
                @for ($column = 1; $column <= 15; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '7')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 8px; font-weight: bold; line-height: 1.35; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 16px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 16%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 7</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT INSPECTION REPORT<br>(Month, Day, Year) (Quarter)</div>
<table>
    <colgroup><col style="width:16%"><col style="width:8%"><col style="width:7%"><col style="width:5%"><col style="width:7%"><col style="width:7%"><col style="width:10%"><col style="width:13.5%"><col style="width:13.5%"><col style="width:13%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Total Program/Project Cost (PHP)</th><th>Location</th><th>IA</th><th>Date of Project Inspection</th><th>Details on Site(s) Inspected</th><th>Findings</th><th>Issues</th><th>Actions Taken</th><th>Actions to be Taken</th></tr></thead>
    <tbody>
        <tr><td>{{ $project->project_name }}</td><td>{{ $formData['total_project_cost'] ?? '' }}</td><td>{{ $project->location_description ?? ($project->barangay->barangay_name ?? '') }}</td><td>{{ $formData['implementing_agency'] ?? '' }}</td><td>{{ $formData['inspection_date'] ?? '' }}</td><td>{{ $formData['site_details'] ?? '' }}</td><td>{{ $formData['findings'] ?? '' }}</td><td>{{ $formData['issues'] ?? '' }}</td><td>{{ $formData['actions_taken'] ?? '' }}</td><td>{{ $formData['actions_to_be_taken'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 13; $row++)
            <tr>
                @for ($column = 1; $column <= 10; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '8')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 7.5px; font-weight: bold; line-height: 1.35; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 16px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 16%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 8</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROBLEM SOLVING SESSIONS/FACILITATION MEETING CONDUCTED<br>(Month, Day, Year) (Quarter)</div>
<table>
    <colgroup><col style="width:16.5%"><col style="width:14%"><col style="width:9.5%"><col style="width:10.5%"><col style="width:9.5%"><col style="width:10%"><col style="width:13%"><col style="width:17%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Issue Details</th><th>Issue Typology</th><th>Location</th><th>IA</th><th>Date of Meeting</th><th>Concerned Agencies</th><th>Agreements Reached</th></tr></thead>
    <tbody>
        <tr><td>{{ $project->project_name }}</td><td>{{ $formData['issue_details'] ?? '' }}</td><td>{{ $formData['issue_typology'] ?? '' }}</td><td>{{ $project->location_description ?? ($project->barangay->barangay_name ?? '') }}</td><td>{{ $formData['implementing_agency'] ?? '' }}</td><td>{{ $formData['meeting_date'] ?? '' }}</td><td>{{ $formData['concerned_agencies'] ?? '' }}</td><td>{{ $formData['agreements_reached'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 12; $row++)
            <tr>
                @for ($column = 1; $column <= 8; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '9')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 8px; font-weight: bold; line-height: 1.35; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 10%; }
        .signature .value { width: 40%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 9</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>TRAINING/WORKSHOP CONDUCTED/FACILITATED/ATTENDED BY THE PMC<br>(Month, Day, Year) (Quarter)</div>
<table>
    <colgroup><col style="width:15%"><col style="width:15%"><col style="width:8%"><col style="width:11.5%"><col style="width:8%"><col style="width:13.5%"><col style="width:5.5%"><col style="width:5.5%"><col style="width:5.5%"><col style="width:12.5%"></colgroup>
    <thead>
        <tr><th rowspan="2">Title of Training/Workshop</th><th rowspan="2">Objective of the Training/Workshop</th><th rowspan="2">Date</th><th rowspan="2">Conducted/Facilitated/Attended</th><th rowspan="2">Lead Office/Unit</th><th rowspan="2">Participating Offices/Agencies/Organizations</th><th colspan="3">Total No. of Participants</th><th rowspan="2">Results and Feedback</th></tr>
        <tr><th>M</th><th>F</th><th>Total</th></tr>
    </thead>
    <tbody>
        <tr><td>{{ $formData['training_title'] ?? '' }}</td><td>{{ $formData['training_objective'] ?? '' }}</td><td>{{ $formData['training_date'] ?? '' }}</td><td>{{ $formData['participation_type'] ?? '' }}</td><td>{{ $formData['lead_office'] ?? '' }}</td><td>{{ $formData['participating_offices'] ?? '' }}</td><td>{{ $formData['participants_male'] ?? '' }}</td><td>{{ $formData['participants_female'] ?? '' }}</td><td>{{ $formData['participants_total'] ?? '' }}</td><td>{{ $formData['results_feedback'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 10; $row++)
            <tr>
                @for ($column = 1; $column <= 10; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '10')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 8px; font-weight: bold; line-height: 1.35; margin-bottom: 28px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 3px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 18px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 18%; }
        .signature .value { width: 32%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 10</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>RPMC AND RDC RESOLUTIONS RELATED TO IMPLEMENTATION OF THE RPMES<br>Resolutions Passed in [Year]</div>
<table>
    <colgroup><col style="width:14%"><col style="width:23%"><col style="width:13%"><col style="width:26.5%"><col style="width:23.5%"></colgroup>
    <thead><tr><th>Resolution Number</th><th>Resolution Title</th><th>Date Approved</th><th>Resolution</th><th>Link to the Resolution</th></tr></thead>
    <tbody>
        <tr><td>{{ $formData['resolution_number'] ?? '' }}</td><td>{{ $formData['resolution_title'] ?? '' }}</td><td>{{ $formData['date_approved'] ?? '' }}</td><td>{{ $formData['resolution'] ?? '' }}</td><td>{{ $formData['resolution_link'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 10; $row++)
            <tr><td></td><td></td><td></td><td></td><td></td></tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? 'Regional Director' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@elseif ((string) $form_number === '11')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 8px; }
        .form-number { text-align: right; font-size: 7px; font-weight: bold; margin: 0 0 10px; }
        .title { text-align: center; font-size: 8px; font-weight: bold; line-height: 1.35; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: .5px solid #000; padding: 2px 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { height: 22px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 12.5%; }
        .signature .value { width: 37.5%; }
    </style>
</head>
<body>
@php($formData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 11</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>KEY LESSONS LEARNED FROM ISSUES RESOLVED AND BEST PRACTICES<br>In [Year]</div>
<table>
    <colgroup><col style="width:13.5%"><col style="width:13.5%"><col style="width:10%"><col style="width:7.5%"><col style="width:7.5%"><col style="width:15.5%"><col style="width:16%"><col style="width:16.5%"></colgroup>
    <thead>
        <tr><th rowspan="2">Program/Project Title</th><th rowspan="2">Location</th><th rowspan="2">Implementing Agency</th><th colspan="2">Problem/Issue</th><th rowspan="2">Strategies/Actions Taken to Resolve the Problem/Issue</th><th rowspan="2">Responsible Entity/Key Actors and their Specific Assistance</th><th rowspan="2">Lessons learned and Good Practices that could be shared to the NPMC/Other PMCs</th></tr>
        <tr><th>Nature</th><th>Details</th></tr>
    </thead>
    <tbody>
        <tr><td>{{ $project->project_name }}</td><td>{{ $project->location_description ?? ($project->barangay->barangay_name ?? '') }}</td><td>{{ $formData['implementing_agency'] ?? '' }}</td><td>{{ $formData['problem_nature'] ?? '' }}</td><td>{{ $formData['problem_details'] ?? '' }}</td><td>{{ $formData['strategies_actions_taken'] ?? '' }}</td><td>{{ $formData['responsible_entities_assistance'] ?? '' }}</td><td>{{ $formData['lessons_learned_best_practices'] ?? '' }}</td></tr>
        @for ($row = 1; $row <= 10; $row++)
            <tr>
                @for ($column = 1; $column <= 8; $column++)
                    <td></td>
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $formData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $formData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $formData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $formData['approved_designation'] ?? 'Regional Director' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $formData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $formData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
@else
<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>
body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; }
.header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #0f1e3d; padding-bottom: 10px; } .header h1 { font-size: 18px; margin: 0; color: #0f1e3d; } .header p { font-size: 11px; color: #555; margin: 4px 0 0; }
.meta { font-size: 10px; color: #666; margin-bottom: 16px; } h2 { font-size: 13px; color: #0f1e3d; margin: 18px 0 8px; }
table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #B2BEB5; padding: 7px 8px; text-align: left; vertical-align: top; } th { background: #f2f3f7; font-weight: bold; } .label { width: 34%; font-weight: bold; color: #0f1e3d; } .empty { color: #666; font-style: italic; }
.signatories { margin-top: 25px; } .signatories td { width: 50%; height: 72px; } .signatories strong { color: #0f1e3d; } .footer { margin-top: 25px; font-size: 9px; color: #666; text-align: center; border-top: 1px solid #ccc; padding-top: 8px; }
</style>
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #172033; background: #f4f6f8; }
.header { text-align: left; background: #0b1220; border-bottom: 4px solid #c9a84c; padding: 18px 20px 15px; }
.header h1 { color: #ffffff; } .header p { color: #d6deeb; }
h2 { color: #0b1220; border-left: 4px solid #c9a84c; padding-left: 8px; }
table { border-collapse: collapse; } th { background: #162347; color: #ffffff; } td, th { border-color: #cbd5e1; } tr:nth-child(even) { background: #eef2f7; }
.signatories td { background: #172b4d; color: #ffffff; border-color: #27446f; border-top: 3px solid #c9a84c; } .signatories strong { color: #ffffff; }
</style></head><body>
<div class="header"><h1>{{ $form_title }}</h1><p>Form {{ $form_number }} &mdash; {{ $project->project_name }} ({{ $project->project_code }})</p></div>
<div class="meta">Generated on: {{ now()->format('M d, Y h:i A') }}</div>
@php($formData = $form->form_data ?? [])
@if (empty($formData))<p class="empty">Not yet filled out.</p>@else
<h2>Form Details</h2><table><tbody>
@foreach ($formData as $key => $value)
@continue(in_array($key, ['submitted_by', 'submitted_designation', 'submitted_date', 'approved_by', 'approved_designation', 'approved_date', 'monthly'], true))
<tr><td class="label">{{ $field_labels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}</td><td>@if(is_array($value))<ul style="margin:0; padding-left:15px;">@foreach($value as $itemKey => $itemValue)<li>{{ is_array($itemValue) ? $itemKey . ': ' . implode(', ', $itemValue) : $itemValue }}</li>@endforeach</ul>@else{{ $value ?: '—' }}@endif</td></tr>
@endforeach
</tbody></table>
@if (!empty($formData['monthly']) && is_array($formData['monthly']))<h2>Monthly Targets</h2><table><thead><tr><th>Month</th><th>Financial Target</th><th>Physical Target</th><th>OI 1</th><th>OI 2</th><th>OI 3</th><th>OI 4</th><th>OI 5</th></tr></thead><tbody>@foreach($formData['monthly'] as $month => $targets)<tr><td>{{ ucfirst($month) }}</td><td>{{ $targets['financial_target'] ?? '—' }}</td><td>{{ $targets['physical_target'] ?? '—' }}</td><td>{{ $targets['oi_1'] ?? '—' }}</td><td>{{ $targets['oi_2'] ?? '—' }}</td><td>{{ $targets['oi_3'] ?? '—' }}</td><td>{{ $targets['oi_4'] ?? '—' }}</td><td>{{ $targets['oi_5'] ?? '—' }}</td></tr>@endforeach</tbody></table>@endif
@endif
<table class="signatories"><tr><td><strong>Submitted By</strong><br><br>{{ $formData['submitted_by'] ?? '—' }}<br>{{ $formData['submitted_designation'] ?? '—' }}<br>{{ $formData['submitted_date'] ?? '—' }}</td><td><strong>Approved By</strong><br><br>{{ $formData['approved_by'] ?? '—' }}<br>{{ $formData['approved_designation'] ?? '—' }}<br>{{ $formData['approved_date'] ?? '—' }}</td></tr></table>
<div class="footer">Generated by the Department Project Forms System &middot; For Official Use Only</div></body></html>
@endif
