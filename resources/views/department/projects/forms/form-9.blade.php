@extends('layouts.department')
@section('content')
@php
$sections = [
 ['title' => 'Training/Workshop Details', 'fields' => ['training_title' => ['label' => 'Title of Training/Workshop'], 'training_objective' => ['type' => 'textarea', 'label' => 'Objective of the Training/Workshop'], 'training_date' => ['type' => 'date', 'label' => 'Date'], 'participation_type' => ['label' => 'Conducted/Facilitated/Attended'], 'lead_office' => ['label' => 'Lead Office/Unit'], 'participating_offices' => ['type' => 'textarea', 'label' => 'Participating Offices/Agencies/Organizations', 'wide' => true]]],
 ['title' => 'Participants and Results', 'fields' => ['participants_male' => ['type' => 'number', 'label' => 'Total Participants — Male'], 'participants_female' => ['type' => 'number', 'label' => 'Total Participants — Female'], 'participants_total' => ['type' => 'number', 'label' => 'Total Participants', 'readonly' => true], 'results_feedback' => ['type' => 'textarea', 'label' => 'Results and Feedback', 'wide' => true]]],
]; $number = 9; $title = 'Training/Workshop Conducted/Facilitated/Attended by the PMC'; $formType = 'form_9';
@endphp
@include('department.projects.forms.standard-form')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const male = document.querySelector('[name="participants_male"]');
    const female = document.querySelector('[name="participants_female"]');
    const total = document.querySelector('[name="participants_total"]');
    const updateTotal = () => total.value = (parseInt(male.value || 0, 10) + parseInt(female.value || 0, 10)) || '';
    male.addEventListener('input', updateTotal); female.addEventListener('input', updateTotal); updateTotal();
});
</script>
@endsection
