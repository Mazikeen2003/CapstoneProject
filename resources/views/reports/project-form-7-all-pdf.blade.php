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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { min-height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 16px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 16%; }
        .signature .value { width: 34%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 7</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT INSPECTION REPORT<br>(Month, Day, Year) (Quarter)</div>

<table>
    <colgroup><col style="width:16%"><col style="width:8%"><col style="width:7%"><col style="width:5%"><col style="width:7%"><col style="width:7%"><col style="width:10%"><col style="width:13.5%"><col style="width:13.5%"><col style="width:13%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Total Program/Project Cost (PHP)</th><th>Location</th><th>IA</th><th>Date of Project Inspection</th><th>Details on Site(s) Inspected</th><th>Findings</th><th>Issues</th><th>Actions Taken</th><th>Actions to be Taken</th></tr></thead>
    <tbody>
    <?php foreach ($formSevenProjects as $formSevenProject): ?>
        <?php $data = $formSevenProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $formSevenProject->project_name }}</td><td>{{ $data['total_project_cost'] ?? '' }}</td><td>{{ $formSevenProject->location_description ?? ($formSevenProject->barangay->barangay_name ?? '') }}</td><td>{{ $data['implementing_agency'] ?? '' }}</td><td>{{ $data['inspection_date'] ?? '' }}</td><td>{{ $data['site_details'] ?? '' }}</td><td>{{ $data['findings'] ?? '' }}</td><td>{{ $data['issues'] ?? '' }}</td><td>{{ $data['actions_taken'] ?? '' }}</td><td>{{ $data['actions_to_be_taken'] ?? '' }}</td></tr>
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
