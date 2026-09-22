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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 3px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { min-height: 24px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 18px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 18%; }
        .signature .value { width: 32%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 10</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>RPMC AND RDC RESOLUTIONS RELATED TO IMPLEMENTATION OF THE RPMES<br>Resolutions Passed in [Year]</div>

<table>
    <colgroup><col style="width:14%"><col style="width:23%"><col style="width:13%"><col style="width:26.5%"><col style="width:23.5%"></colgroup>
    <thead><tr><th>Resolution Number</th><th>Resolution Title</th><th>Date Approved</th><th>Resolution</th><th>Link to the Resolution</th></tr></thead>
    <tbody>
    <?php foreach ($formTenProjects as $formTenProject): ?>
        <?php $data = $formTenProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $data['resolution_number'] ?? '' }}</td><td>{{ $data['resolution_title'] ?? '' }}</td><td>{{ $data['date_approved'] ?? '' }}</td><td>{{ $data['resolution'] ?? '' }}</td><td>{{ $data['resolution_link'] ?? '' }}</td></tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table class="signature">
    <tr><td class="label">Submitted by:</td><td class="value">{{ $downloadFormData['submitted_by'] ?? '' }}</td><td class="label">Approved by:</td><td class="value">{{ $downloadFormData['approved_by'] ?? '' }}</td></tr>
    <tr><td class="label">Designation/Office:</td><td class="value">{{ $downloadFormData['submitted_designation'] ?? '' }}</td><td></td><td class="value">{{ $downloadFormData['approved_designation'] ?? 'Regional Director' }}</td></tr>
    <tr><td class="label">Date:</td><td class="value">{{ $downloadFormData['submitted_date'] ?? '' }}</td><td class="label">Date:</td><td class="value">{{ $downloadFormData['approved_date'] ?? '' }}</td></tr>
</table>
</body>
</html>
