@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Users</p>
            <p class="text-4xl font-bold text-black mt-2">{{ $users ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Projects</p>
            <p class="text-4xl font-bold text-black mt-2">{{ $projects ?? 0 }}</p>
            <div class="text-sm text-gray-500 mt-2">
                <span class="mr-4">Published: {{ $projects_published ?? 0 }}</span>
                <span>Drafts: {{ $projects_draft ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Reports</p>
            <p class="text-4xl font-bold text-black mt-2">{{ $reports ?? 0 }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Audit Logs</p>
            <p class="text-4xl font-bold text-black mt-2">{{ $auditLogs ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-black">Recent Activity</h2>
            <p class="text-sm text-gray-500 mt-1">Latest system actions and changes.</p>
        </div>
        <div class="p-6">
            @if(!empty($recentActivity) && $recentActivity->count())
                <ul class="space-y-3">
                    @foreach($recentActivity as $activity)
                        <li class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm transition hover:border-blue-200 hover:bg-white">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="text-sm font-semibold text-slate-900">{{ optional($activity->user)->username ?? optional($activity->user)->user_email ?? 'System' }}</div>
                                        <div class="text-xs font-medium text-slate-500">{{ optional($activity->created_at)->diffForHumans() }}</div>
                                    </div>
                                    <div class="mt-2 text-sm leading-6 text-slate-600">{{ $activity->action }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">No recent activity.</div>
            @endif
        </div>
    </div>
</div>
@endsection
