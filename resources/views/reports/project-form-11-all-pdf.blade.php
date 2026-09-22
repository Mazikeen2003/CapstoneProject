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
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: .5px solid #000; padding: 2px; overflow-wrap: break-word; }
        th { background: #002d6a; color: #fff; font-size: 7px; font-weight: bold; text-align: center; vertical-align: middle; line-height: 1.15; }
        td { min-height: 22px; vertical-align: top; font-size: 7.5px; white-space: pre-line; }
        .signature { margin-top: 14px; font-size: 8px; }
        .signature td { height: 16px; padding: 2px 3px; white-space: normal; }
        .signature .label { width: 12.5%; }
        .signature .value { width: 37.5%; }
    </style>
</head>
<body>
@php($downloadFormData = $form->form_data ?? [])
<div class="form-number">RPMES FORM 11</div>
<div class="title">REGIONAL PROJECT MONITORING AND EVALUATION SYSTEM (RPMES)<br>KEY LESSONS LEARNED FROM ISSUES RESOLVED AND BEST PRACTICES<br>In [Year]</div>

<table>
    <colgroup><col style="width:13.5%"><col style="width:13.5%"><col style="width:10%"><col style="width:7.5%"><col style="width:7.5%"><col style="width:15.5%"><col style="width:16%"><col style="width:16.5%"></colgroup>
    <thead><tr><th rowspan="2">Program/Project Title</th><th rowspan="2">Location</th><th rowspan="2">Implementing Agency</th><th colspan="2">Problem/Issue</th><th rowspan="2">Strategies/Actions Taken to Resolve the Problem/Issue</th><th rowspan="2">Responsible Entity/Key Actors and their Specific Assistance</th><th rowspan="2">Lessons Learned and Good Practices that Could Be Shared to the NPMC/Other PMCs</th></tr><tr><th>Nature</th><th>Details</th></tr></thead>
    <tbody>
    <?php foreach ($formElevenProjects as $formElevenProject): ?>
        <?php $data = $formElevenProject->forms->first()?->form_data ?? []; ?>
        <tr><td>{{ $formElevenProject->project_name }}</td><td>{{ $formElevenProject->location_description ?? ($formElevenProject->barangay->barangay_name ?? '') }}</td><td>{{ $data['implementing_agency'] ?? '' }}</td><td>{{ $data['problem_nature'] ?? '' }}</td><td>{{ $data['problem_details'] ?? '' }}</td><td>{{ $data['strategies_actions_taken'] ?? '' }}</td><td>{{ $data['responsible_entities_assistance'] ?? '' }}</td><td>{{ $data['lessons_learned_best_practices'] ?? '' }}</td></tr>
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
