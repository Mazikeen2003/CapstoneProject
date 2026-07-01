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
                <ul class="space-y-4">
                    @foreach($recentActivity as $activity)
                        <li class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-gray-900">{{ optional($activity->user)->username ?? optional($activity->user)->user_email ?? 'System' }}</div>
                                    <div class="text-xs text-gray-400">{{ optional($activity->created_at)->diffForHumans() }}</div>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">{{ $activity->action }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-500">No recent activity.</div>
            @endif
        </div>
    </div>
</div>
@endsection
