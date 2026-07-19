@extends('layouts.department')

@section('content')
@php
$sections = [
    ['title' => 'Project Information', 'fields' => [
        'project_title' => ['type' => 'project', 'label' => 'Program/Project Title'],
        'location' => ['type' => 'location', 'label' => 'Location (Barangay, City/Municipality, Province)'],
        'implementing_agency' => ['type' => 'select', 'label' => 'Implementing Agency', 'options' => ['LGU']],
    ]],
    ['title' => 'Problem/Issue and Lessons Learned', 'fields' => [
        'problem_nature' => ['type' => 'textarea', 'label' => 'Problem/Issue — Nature'],
        'problem_details' => ['type' => 'textarea', 'label' => 'Problem/Issue — Details'],
        'strategies_actions_taken' => ['type' => 'textarea', 'label' => 'Strategies/Actions Taken to Resolve the Problem/Issue', 'wide' => true],
        'responsible_entities_assistance' => ['type' => 'textarea', 'label' => 'Responsible Entity/Key Actors and their Specific Assistance', 'wide' => true],
        'lessons_learned_best_practices' => ['type' => 'textarea', 'label' => 'Lessons Learned and Good Practices that Could Be Shared to the NPMC/Other PMCs', 'wide' => true],
    ]],
];
$number = 11;
$title = 'Key Lessons Learned from Issues Resolved and Best Practices';
$formType = 'form_11';
@endphp
@include('department.projects.forms.standard-form')
@endsection
