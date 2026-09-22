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
<div class="form-number">RPMES FORM 8</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROBLEM SOLVING SESSIONS/FACILITATION MEETING CONDUCTED<br>(Month, Day, Year) (Quarter)</div>

<table>
    <colgroup><col style="width:16.5%"><col style="width:14%"><col style="width:9.5%"><col style="width:10.5%"><col style="width:9.5%"><col style="width:10%"><col style="width:13%"><col style="width:17%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Issue Details</th><th>Issue Typology</th><th>Location</th><th>IA</th><th>Date of Meeting</th><th>Concerned Agencies</th><th>Agreements Reached</th></tr></thead>
    <tbody>
    <?php foreach ($formEightProjects as $formEightProject): ?>
        <?php $data = $formEightProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $formEightProject->project_name }}</td><td>{{ $data['issue_details'] ?? '' }}</td><td>{{ $data['issue_typology'] ?? '' }}</td><td>{{ $formEightProject->location_description ?? ($formEightProject->barangay->barangay_name ?? '') }}</td><td>{{ $data['implementing_agency'] ?? '' }}</td><td>{{ $data['meeting_date'] ?? '' }}</td><td>{{ $data['concerned_agencies'] ?? '' }}</td><td>{{ $data['agreements_reached'] ?? '' }}</td></tr>
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
