@extends('layouts.department')
@section('content')
@php
$sections = [
 ['title' => 'Resolution Details', 'fields' => ['resolution_number' => ['type' => 'number', 'label' => 'Resolution Number'], 'resolution_title' => ['label' => 'Resolution Title'], 'date_approved' => ['type' => 'date', 'label' => 'Date Approved'], 'resolution' => ['type' => 'textarea', 'label' => 'Resolution', 'wide' => true], 'resolution_link' => ['label' => 'Link to the Resolution', 'wide' => true]]],
]; $number = 10; $title = 'RPMC and RDC Resolutions Related to Implementation of the RPMES'; $formType = 'form_10';
@endphp
@include('department.projects.forms.standard-form')
@endsection
