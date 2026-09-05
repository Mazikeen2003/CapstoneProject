@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Database Backups</h1>
        <p class="text-sm text-slate-500">Automated backups are created after significant data changes (throttled to once every 5 minutes) and can also be generated manually.</p>
    </div>

    @if(session('status'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.backups.index') }}" class="rounded-3xl border border-slate-400 bg-white p-5 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5 xl:items-end">
            <div>
                <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="trigger_type" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Trigger type</label>
                <select id="trigger_type" name="trigger_type" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All trigger types</option>
                    @foreach ($triggerTypes as $triggerType)
                        <option value="{{ $triggerType }}" @selected(request('trigger_type') === $triggerType)>{{ ucfirst(str_replace('_', ' ', $triggerType)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="created_date" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">Created date</label>
                <input id="created_date" name="created_date" type="date" value="{{ request('created_date') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
            </div>
            <div class="flex flex-col gap-2 sm:flex-row xl:justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.backups.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Clear</a>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-3xl border border-slate-400 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Backup management</h2>
                <p class="mt-1 text-sm text-slate-500">Create, download, and remove generated database backups from the admin panel.</p>
            </div>
            <form action="{{ route('admin.backups.manual') }}" method="POST" class="inline-flex">
                @csrf
                <button type="submit" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Create Manual Backup
                </button>
            </form>
        </div>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($backups as $backup)
            <div class="rounded-3xl border border-slate-400 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $backup->triggeredBy?->username ?? 'System' }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-[0.14em]">{{ match ($backup->trigger_type) {
                            'manual' => 'Manual',
                            'project_create' => 'Project Create',
                            'project_update' => 'Project Update',
                            'project_delete' => 'Project Delete',
                            'user_create' => 'User Create',
                            'user_update' => 'User Update',
                            'user_delete' => 'User Delete',
                            default => ucfirst(str_replace('_', ' ', $backup->trigger_type)),
                        } }}</p>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($backup->status) }}</span>
                </div>
                <div class="mt-3 text-sm text-slate-600 space-y-2">
                    <p><span class="font-semibold text-slate-700">Date:</span> {{ $backup->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</p>
                    <p><span class="font-semibold text-slate-700">Size:</span> {{ $backup->file_size ? number_format($backup->file_size / 1024, 2) . ' KB' : '-' }}</p>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @if($backup->status === 'completed' && $backup->file_path)
                        <a href="{{ route('admin.backups.download', $backup) }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Download</a>
                    @endif
                    <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full bg-rose-600 px-4 py-2 text-sm font-semibold text-white">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-slate-400 bg-slate-50 p-6 text-center text-sm text-slate-500">No backups have been created yet.</div>
        @endforelse
    </div>

    <div class="hidden md:block overflow-x-auto rounded-3xl border border-slate-400 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="admin-card-header bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="px-4 py-3">Triggered By</th>
                    <th class="px-4 py-3">Trigger Type</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">File Size</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white text-slate-700">
                @forelse($backups as $backup)
                    <tr>
                        <td class="px-4 py-4">{{ $backup->triggeredBy?->username ?? 'System' }}</td>
                        <td class="px-4 py-4 uppercase tracking-[0.14em]">{{ match ($backup->trigger_type) {
                            'manual' => 'Manual',
                            'project_create' => 'Project Create',
                            'project_update' => 'Project Update',
                            'project_delete' => 'Project Delete',
                            'user_create' => 'User Create',
                            'user_update' => 'User Update',
                            'user_delete' => 'User Delete',
                            default => ucfirst(str_replace('_', ' ', $backup->trigger_type)),
                        } }}</td>
                        <td class="px-4 py-4">{{ $backup->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-4">{{ $backup->file_size ? number_format($backup->file_size / 1024, 2) . ' KB' : '-' }}</td>
                        <td class="px-4 py-4">
                            @php
                                $badgeColor = match ($backup->status) {
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'failed' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeColor }}">{{ ucfirst($backup->status) }}</span>
                            @if($backup->status === 'failed' && $backup->error_message)
                                <p class="mt-2 text-xs text-rose-600">{{ $backup->error_message }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 space-x-2">
                            @if($backup->status === 'completed' && $backup->file_path)
                                <a href="{{ route('admin.backups.download', $backup) }}" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">Download</a>
                            @endif
                            <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex rounded-full bg-rose-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No backups have been created yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center justify-between gap-4 rounded-3xl border border-slate-400 bg-white px-4 py-3 text-sm text-slate-500">
        <div>Page {{ $backups->currentPage() }} of {{ $backups->lastPage() }}</div>
        <div class="flex items-center gap-2">
            @if ($backups->onFirstPage())
                <span class="cursor-not-allowed rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 opacity-40">Previous</span>
            @else
                <a href="{{ $backups->previousPageUrl() }}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Previous</a>
            @endif

            @if ($backups->hasMorePages())
                <a href="{{ $backups->nextPageUrl() }}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Next</a>
            @else
                <span class="cursor-not-allowed rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 opacity-40">Next</span>
            @endif
        </div>
    </div>
</div>
@endsection
