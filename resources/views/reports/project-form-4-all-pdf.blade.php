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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 3px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { min-height: 28px; vertical-align: top; white-space: pre-line; }
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 10%; }
        .signature .value { width: 40%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 4</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>PROJECT RESULTS<br>(Month, Day, Year) (Quarter)</div>
<div class="agency">Implementing Agency:<span class="agency-value">{{ $downloadFormData['implementing_agency'] ?? '' }}</span></div>

<table>
    <colgroup><col style="width:26%"><col style="width:26%"><col style="width:23%"><col style="width:25%"></colgroup>
    <thead><tr><th>Program/Project Title</th><th>Program/Project Objectives</th><th>Results/Outcome Indicator/Target</th><th>Observed Results/Outcome/Impact</th></tr></thead>
    <tbody>
    <?php foreach ($formFourProjects as $formFourProject): ?>
        <?php $data = $formFourProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $formFourProject->project_name }}</td><td>{{ $data['program_objectives'] ?? '' }}</td><td>{{ $data['results_outcome_target'] ?? '' }}</td><td>{{ $data['observed_results'] ?? '' }}</td></tr>
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
