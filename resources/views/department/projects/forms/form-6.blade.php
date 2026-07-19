@extends('layouts.department')
@section('content')
@php
$sections = [
 ['title' => 'Project Status', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'fund_utilization' => ['label' => 'Fund Utilization (%)']]],
 ['title' => 'Physical Accomplishment and Issues', 'fields' => ['target_owpa_to_date' => ['label' => 'Target OWPA to Date (%)'], 'actual_owpa_to_date' => ['label' => 'Actual OWPA to Date (%)'], 'slippage' => ['label' => 'Slippage'], 'issue_details' => ['type' => 'textarea', 'label' => 'Issue Details'], 'issue_typology' => ['type' => 'textarea', 'label' => 'Issue Typology'], 'issue_status' => ['label' => 'Issue Status'], 'source_of_information' => ['type' => 'textarea', 'label' => 'Source of Information'], 'actions_taken' => ['type' => 'textarea', 'label' => 'Actions Taken'], 'actions_to_be_taken' => ['type' => 'textarea', 'label' => 'Actions to be Taken'], 'for_npmc_action' => ['type' => 'select', 'label' => 'For NPMC Action', 'options' => ['Yes', 'No']], 'requested_actions_from_npmc' => ['type' => 'textarea', 'label' => 'Requested Actions from the NPMC', 'wide' => true]]],
]; $number = 6; $title = 'Report on the Status of Projects Encountering Implementation Problems'; $formType = 'form_6';
@endphp
@include('department.projects.forms.standard-form')
@endsection
