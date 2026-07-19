@extends('layouts.department')
@section('content')
@php
$sections = [
 ['title' => 'Project Inspection Details', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'total_project_cost' => ['label' => 'Total Program/Project Cost (PHP)'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'inspection_date' => ['type' => 'date', 'label' => 'Date of Project Inspection'], 'site_details' => ['type' => 'textarea', 'label' => 'Details on Site(s) Inspected', 'wide' => true]]],
 ['title' => 'Findings and Actions', 'fields' => ['findings' => ['type' => 'textarea', 'label' => 'Findings'], 'issues' => ['type' => 'textarea', 'label' => 'Issues'], 'actions_taken' => ['type' => 'textarea', 'label' => 'Actions Taken'], 'actions_to_be_taken' => ['type' => 'textarea', 'label' => 'Actions to be Taken']]],
]; $number = 7; $title = 'Project Inspection Report'; $formType = 'form_7';
@endphp
@include('department.projects.forms.standard-form')
@endsection
