@extends('layouts.department')

@section('content')
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
