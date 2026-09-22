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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.5px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.16; }
        td { min-height: 22px; vertical-align: top; font-size: 6.5px; white-space: pre-line; }
        .signature { width: 100%; margin-top: 16px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; }
        .signature .label { width: 8%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 2</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PHYSICAL AND FINANCIAL ACCOMPLISHMENT REPORT<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency:<span class="agency-value">{{ $downloadFormData['implementing_agency'] ?? '' }}</span></div>

<table>
    <colgroup>
        <col style="width:8.2%"><col style="width:4.8%"><col style="width:4.8%"><col style="width:3.4%"><col style="width:3.7%"><col style="width:5.5%">
        <col style="width:5.4%"><col style="width:5.4%"><col style="width:5.4%"><col style="width:5.4%">
        <col style="width:4.8%"><col style="width:4.8%"><col style="width:4.4%"><col style="width:4.1%"><col style="width:4.5%"><col style="width:4.5%"><col style="width:5.2%"><col style="width:2.5%"><col style="width:2.5%"><col style="width:8.8%">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2">Program/Project Title</th><th colspan="2">Implementation Schedule</th><th rowspan="2">Fund Source</th><th rowspan="2">Funding Agency</th><th rowspan="2">Total Program/Project Cost (PHP)</th>
            <th colspan="4">Financial Status (in PHP exact figures)</th><th colspan="3">Physical Accomplishment</th><th rowspan="2">Output Indicator</th><th rowspan="2">End-of-Project Target</th><th rowspan="2">Target to Date</th><th rowspan="2">Actual to Date</th><th colspan="2">Employment Generated</th><th rowspan="2">Remarks</th>
        </tr>
        <tr>
            <th>Start Date<br>(mm-dd-yyyy)</th><th>End Date<br>(mm-dd-yyyy)</th><th>Appropriations</th><th>Allotment</th><th>Obligations</th><th>Disbursements</th><th>Target OWPA<br>to Date (%)</th><th>Actual OWPA<br>to Date (%)</th><th>Slippage</th><th>M</th><th>F</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($formTwoProjects as $formTwoProject): ?>
            <?php
                $projectForm = $formTwoProject->forms->first();
                $data = $projectForm?->form_data ?? [];
                $indicators = collect(range(1, 5))->map(fn ($number) => $data['output_indicator_' . $number] ?? null)->filter()->implode("\n");
            ?>
            <tr>
                <td>{{ $formTwoProject->project_name }}</td><td>{{ $data['start_date'] ?? $formTwoProject->start_date?->format('m-d-Y') ?? '' }}</td><td>{{ $data['end_date'] ?? $formTwoProject->target_end_date?->format('m-d-Y') ?? '' }}</td><td>{{ $data['fund_source'] ?? '' }}</td><td>{{ $data['funding_agency'] ?? '' }}</td><td>{{ $data['total_project_cost'] ?? '' }}</td>
                <td>{{ $data['appropriations'] ?? '' }}</td><td>{{ $data['allotment'] ?? '' }}</td><td>{{ $data['obligations'] ?? '' }}</td><td>{{ $data['disbursements'] ?? '' }}</td><td>{{ $data['target_owpa_to_date'] ?? '' }}</td><td>{{ $data['actual_owpa_to_date'] ?? '' }}</td><td>{{ $data['slippage'] ?? '' }}</td><td>{{ $indicators }}</td><td>{{ $data['end_of_project_target'] ?? '' }}</td><td>{{ $data['target_to_date'] ?? '' }}</td><td>{{ $data['actual_to_date'] ?? '' }}</td><td>{{ $data['employment_generated_male'] ?? '' }}</td><td>{{ $data['employment_generated_female'] ?? '' }}</td><td>{{ $data['remarks'] ?? '' }}</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $downloadFormData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $downloadFormData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $downloadFormData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $downloadFormData['approved_designation'] ?? '' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $downloadFormData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $downloadFormData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
