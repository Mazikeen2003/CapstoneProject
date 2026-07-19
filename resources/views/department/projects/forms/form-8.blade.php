@extends('layouts.department')
@section('content')
@php
$sections = [
 ['title' => 'Meeting Details', 'fields' => ['project_title' => ['type' => 'project', 'label' => 'Program/Project Title'], 'issue_details' => ['type' => 'textarea', 'label' => 'Issue Details'], 'issue_typology' => ['type' => 'textarea', 'label' => 'Issue Typology'], 'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'], 'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']], 'meeting_date' => ['type' => 'date', 'label' => 'Date of Meeting'], 'concerned_agencies' => ['type' => 'textarea', 'label' => 'Concerned Agencies'], 'agreements_reached' => ['type' => 'textarea', 'label' => 'Agreements Reached']]],
]; $number = 8; $title = 'Problem Solving Sessions/Facilitation Meeting Conducted'; $formType = 'form_8';
@endphp
@include('department.projects.forms.standard-form')
@endsection
