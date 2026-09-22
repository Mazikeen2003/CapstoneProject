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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { min-height: 24px; vertical-align: top; font-size: 7px; white-space: pre-line; }
        .signature { width: 100%; margin-top: 18px; font-size: 7px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 13%; }
        .signature .value { width: 37%; }
    </style>
</head>
<body>
@php
    $downloadFormData = $form->form_data ?? [];
    $downloadAgencyName = trim((string) ($downloadFormData['implementing_agency'] ?? ''));
    $downloadAgencyType = trim((string) ($downloadFormData['implementing_agency_type'] ?? ''));
    $downloadAgency = $downloadAgencyName !== '' && $downloadAgencyType !== ''
        ? $downloadAgencyName . ' (' . $downloadAgencyType . ')'
        : ($downloadAgencyName ?: $downloadAgencyType);
@endphp
<div class="form-number">RPMES FORM 3</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT EXCEPTION REPORT<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency/NGOs/Concerned Citizens:<span class="agency-value">{{ $downloadAgency }}</span></div>

<table>
    <colgroup><col style="width:13.7%"><col style="width:9.5%"><col style="width:8.5%"><col style="width:6.8%"><col style="width:9%"><col style="width:6.8%"><col style="width:8.2%"><col style="width:5.8%"><col style="width:5.5%"><col style="width:8.8%"><col style="width:8.8%"><col style="width:8.6%"></colgroup>
    <thead>
        <tr><th rowspan="2">Program/Project Title</th><th rowspan="2">Implementing Agency</th><th rowspan="2">Sector</th><th colspan="3">Location</th><th rowspan="2">Findings</th><th rowspan="2">Typology</th><th rowspan="2">Issue Status</th><th rowspan="2">Reasons</th><th rowspan="2">Actions Taken</th><th rowspan="2">Actions to be Taken</th></tr>
        <tr><th>Province</th><th>City/Municipality</th><th>Barangay</th></tr>
    </thead>
    <tbody>
    <?php foreach ($formThreeProjects as $formThreeProject): ?>
        <?php
            $data = $formThreeProject->forms->first()?->form_data ?? [];
            $agencyName = trim((string) ($data['implementing_agency'] ?? ''));
            $agencyType = trim((string) ($data['implementing_agency_type'] ?? ''));
            $agency = $agencyName !== '' && $agencyType !== ''
                ? $agencyName . ' (' . $agencyType . ')'
                : ($agencyName ?: $agencyType);
        ?>
        <tr>
            <td>{{ $formThreeProject->project_name }}</td><td>{{ $agency }}</td><td>{{ $data['sector'] ?? '' }}</td><td>Laguna</td><td>Cabuyao City</td><td>{{ $formThreeProject->barangay->barangay_name ?? '' }}</td><td>{{ $data['findings'] ?? '' }}</td><td>{{ $data['typology'] ?? '' }}</td><td>{{ $data['issue_status'] ?? '' }}</td><td>{{ $data['reasons'] ?? '' }}</td><td>{{ $data['actions_taken'] ?? '' }}</td><td>{{ $data['actions_to_be_taken'] ?? '' }}</td>
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
