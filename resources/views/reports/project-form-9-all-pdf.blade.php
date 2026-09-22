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
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 10%; }
        .signature .value { width: 40%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 9</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>TRAINING/WORKSHOP CONDUCTED/FACILITATED/ATTENDED BY THE PMC<br>(Month, Day, Year) (Quarter)</div>

<table>
    <colgroup><col style="width:15%"><col style="width:15%"><col style="width:8%"><col style="width:11.5%"><col style="width:8%"><col style="width:13.5%"><col style="width:5.5%"><col style="width:5.5%"><col style="width:5.5%"><col style="width:12.5%"></colgroup>
    <thead><tr><th rowspan="2">Title of Training/Workshop</th><th rowspan="2">Objective of the Training/Workshop</th><th rowspan="2">Date</th><th rowspan="2">Conducted/Facilitated/Attended</th><th rowspan="2">Lead Office/Unit</th><th rowspan="2">Participating Offices/Agencies/Organizations</th><th colspan="3">Total No. of Participants</th><th rowspan="2">Results and Feedback</th></tr><tr><th>M</th><th>F</th><th>Total</th></tr></thead>
    <tbody>
    <?php foreach ($formNineProjects as $formNineProject): ?>
        <?php $data = $formNineProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $data['training_title'] ?? '' }}</td><td>{{ $data['training_objective'] ?? '' }}</td><td>{{ $data['training_date'] ?? '' }}</td><td>{{ $data['participation_type'] ?? '' }}</td><td>{{ $data['lead_office'] ?? '' }}</td><td>{{ $data['participating_offices'] ?? '' }}</td><td>{{ $data['participants_male'] ?? '' }}</td><td>{{ $data['participants_female'] ?? '' }}</td><td>{{ $data['participants_total'] ?? '' }}</td><td>{{ $data['results_feedback'] ?? '' }}</td></tr>
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
