@extends('layouts.city')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">City Official Dashboard</h1>
        <p style="color: #6B7280;">Overview for City Officials</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Total Projects" value="50" iconColor="#3b82f6">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="City Budget" value="₱10M" iconColor="#06b6d4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Barangays" value="18" iconColor="#10b981">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Completion Rate" value="78%" iconColor="#f59e0b">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- City Projects Table -->
    <div class="rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <h2 class="text-2xl font-semibold text-black mb-6">City-Wide Projects</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid #E5E7EB;">
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PROJECT NAME</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">LOCATION</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">STATUS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PRIORITY</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">BUDGET</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Infrastructure Development</td>
                        <td class="px-4 py-4 text-black">Multiple Barangays</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #ef4444; color: white;">HIGH</span></td>
                        <td class="px-4 py-4 text-black">₱3M</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Education & Health Initiative</td>
                        <td class="px-4 py-4 text-black">City-Wide</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #10b981; color: white;">COMPLETED</span></td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #06b6d4; color: white;">MEDIUM</span></td>
                        <td class="px-4 py-4 text-black">₱2M</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-black">Environmental & Safety Program</td>
                        <td class="px-4 py-4 text-black">Multiple Barangays</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #06b6d4; color: white;">MEDIUM</span></td>
                        <td class="px-4 py-4 text-black">₱1.5M</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
