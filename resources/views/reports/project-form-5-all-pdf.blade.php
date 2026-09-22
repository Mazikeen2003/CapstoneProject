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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.3px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.14; }
        td { min-height: 21px; vertical-align: top; font-size: 6.5px; white-space: pre-line; }
        .signature { margin-top: 16px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; }
        .signature .label { width: 14%; }
        .signature .value { width: 36%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 5</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>SUMMARY OF FINANCIAL AND PHYSICAL ACCOMPLISHMENTS<br>(Month, Day, Year) (Quarter)</div>

<table>
    <colgroup>
        <col style="width:14%"><col style="width:6%"><col style="width:5.5%"><col style="width:6.5%"><col style="width:6.2%"><col style="width:6.2%"><col style="width:6.2%"><col style="width:6.2%"><col style="width:5%"><col style="width:5.3%"><col style="width:5%"><col style="width:5%"><col style="width:4.5%"><col style="width:3.8%"><col style="width:3.8%"><col style="width:8%">
    </colgroup>
    <thead>
        <tr><th rowspan="2">Program/Project Title</th><th rowspan="2">Fund Source</th><th rowspan="2">Funding Agency</th><th rowspan="2">Total Program/Project Cost (PHP)</th><th colspan="6">Financial Status (in PHP exact figures)</th><th colspan="3">Physical Accomplishment</th><th colspan="2">Employment Generated</th><th rowspan="2">Remarks</th></tr>
        <tr><th>Appropriations</th><th>Allotment</th><th>Obligations</th><th>Disbursements</th><th>Funding Support (%)<br><span style="font-size:5px">Allotment/Appropriations × 100</span></th><th>Fund Utilization (%)</th><th>Target OWPA to Date (%)</th><th>Actual OWPA to Date (%)</th><th>Slippage</th><th>M</th><th>F</th></tr>
    </thead>
    <tbody>
    <?php foreach ($formFiveProjects as $formFiveProject): ?>
        <?php $data = $formFiveProject->forms->first()?->form_data ?? []; ?>
        <tr>
            <td>{{ $formFiveProject->project_name }}</td><td>{{ $data['fund_source'] ?? '' }}</td><td>{{ $data['funding_agency'] ?? '' }}</td><td>{{ $data['total_project_cost'] ?? '' }}</td><td>{{ $data['appropriations'] ?? '' }}</td><td>{{ $data['allotment'] ?? '' }}</td><td>{{ $data['obligations'] ?? '' }}</td><td>{{ $data['disbursements'] ?? '' }}</td><td>{{ $data['funding_support'] ?? '' }}</td><td>{{ $data['fund_utilization'] ?? '' }}</td><td>{{ $data['target_owpa_to_date'] ?? '' }}</td><td>{{ $data['actual_owpa_to_date'] ?? '' }}</td><td>{{ $data['slippage'] ?? '' }}</td><td>{{ $data['employment_generated_male'] ?? '' }}</td><td>{{ $data['employment_generated_female'] ?? '' }}</td><td>{{ $data['remarks'] ?? '' }}</td>
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
