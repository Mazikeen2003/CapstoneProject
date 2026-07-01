@extends('layouts.department')

@section('content')
<div class="space-y-5">
    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-slate-900">Department Analytics</h1>
            <p class="text-sm text-slate-500">Project analytics will appear here after records are added.</p>
        </div>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-stat-card title="Total Projects" value="{{ $stats['total_projects'] }}" iconColor="#3b82f6" />
            <x-stat-card title="Completed" value="{{ $stats['completed'] }}" iconColor="#10b981" />
            <x-stat-card title="Ongoing" value="{{ $stats['ongoing'] }}" iconColor="#f59e0b" />
            <x-stat-card title="Budget Used" value="₱{{ number_format($stats['budget_used'], 2) }}" iconColor="#ef4444" />
        </div>

        <div class="mt-6 rounded-[28px] border border-slate-200 bg-slate-50 p-5">
            <h3 class="text-lg font-semibold text-slate-900">Project Breakdown</h3>
            <p class="mt-2 text-sm text-slate-500">No analytics data is available yet.</p>
        </div>
    </div>
</div>
@endsection
