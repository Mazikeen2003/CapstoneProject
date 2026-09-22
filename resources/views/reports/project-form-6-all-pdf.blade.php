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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px 1px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 6.2px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.14; }
        td { min-height: 21px; vertical-align: top; font-size: 6.4px; white-space: pre-line; }
        .signature { width: 91%; margin-top: 18px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 16%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 6</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>REPORT ON THE STATUS OF PROJECTS ENCOUNTERING IMPLEMENTATION PROBLEMS<br>(Month, Day, Year) (Quarter)</div>

<table>
    <colgroup><col style="width:14.5%"><col style="width:5.8%"><col style="width:5.7%"><col style="width:4.3%"><col style="width:5.1%"><col style="width:5.1%"><col style="width:5.3%"><col style="width:7.3%"><col style="width:4.5%"><col style="width:4.2%"><col style="width:6.6%"><col style="width:8.8%"><col style="width:8.7%"><col style="width:4.6%"><col style="width:8.5%"></colgroup>
    <thead>
        <tr><th rowspan="2">Program/Project Title</th><th rowspan="2">Location</th><th rowspan="2">IA</th><th rowspan="2">Fund Utilization (%)</th><th colspan="3">Physical Accomplishment</th><th rowspan="2">Issue Details</th><th rowspan="2">Issue Typology</th><th rowspan="2">Issue Status</th><th rowspan="2">Source of Information</th><th rowspan="2">Actions Taken</th><th rowspan="2">Actions to be Taken</th><th rowspan="2">For NPMC Action (Y/N)</th><th rowspan="2">Requested Actions from the NPMC</th></tr>
        <tr><th>Target OWPA to Date (%)</th><th>Actual OWPA to Date (%)</th><th>Slippage</th></tr>
    </thead>
    <tbody>
    <?php foreach ($formSixProjects as $formSixProject): ?>
        <?php $data = $formSixProject->forms->first()?->form_data ?? []; ?>
        <tr>
            <td>{{ $formSixProject->project_name }}</td><td>{{ $formSixProject->location_description ?? ($formSixProject->barangay->barangay_name ?? '') }}</td><td>{{ $data['implementing_agency'] ?? '' }}</td><td>{{ $data['fund_utilization'] ?? '' }}</td><td>{{ $data['target_owpa_to_date'] ?? '' }}</td><td>{{ $data['actual_owpa_to_date'] ?? '' }}</td><td>{{ $data['slippage'] ?? '' }}</td><td>{{ $data['issue_details'] ?? '' }}</td><td>{{ $data['issue_typology'] ?? '' }}</td><td>{{ $data['issue_status'] ?? '' }}</td><td>{{ $data['source_of_information'] ?? '' }}</td><td>{{ $data['actions_taken'] ?? '' }}</td><td>{{ $data['actions_to_be_taken'] ?? '' }}</td><td>{{ $data['for_npmc_action'] ?? '' }}</td><td>{{ $data['requested_actions_from_npmc'] ?? '' }}</td>
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
