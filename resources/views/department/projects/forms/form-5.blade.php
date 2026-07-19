@extends('layouts.department')
@section('content')
@php
$fundSources = ['ODA Loan', 'ODA Grant', 'ODA Loan and Grant', 'LFP', 'PPP', 'NTA', 'Local Development Fund'];
$sections = [
 ['title' => 'Project and Financial Information', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'fund_source' => ['type' => 'select', 'label' => 'Fund Source', 'options' => $fundSources], 'funding_agency' => ['label' => 'Funding Agency'], 'total_project_cost' => ['label' => 'Total Program/Project Cost (PHP)'], 'appropriations' => ['label' => 'Appropriations (PHP)'], 'allotment' => ['label' => 'Allotment (PHP)'], 'obligations' => ['label' => 'Obligations (PHP)'], 'disbursements' => ['label' => 'Disbursements (PHP)'], 'funding_support' => ['label' => 'Funding Support (%)'], 'fund_utilization' => ['label' => 'Fund Utilization (%)']]],
 ['title' => 'Physical Accomplishment', 'fields' => ['target_owpa_to_date' => ['label' => 'Target OWPA to Date (%)'], 'actual_owpa_to_date' => ['label' => 'Actual OWPA to Date (%)'], 'slippage' => ['label' => 'Slippage'], 'employment_generated_male' => ['type' => 'number', 'label' => 'Employment Generated — Male'], 'employment_generated_female' => ['type' => 'number', 'label' => 'Employment Generated — Female'], 'remarks' => ['type' => 'textarea', 'label' => 'Remarks', 'wide' => true]]],
]; $number = 5; $title = 'Summary of Financial and Physical Accomplishments'; $formType = 'form_5';
@endphp
@include('department.projects.forms.standard-form')
@endsection
