@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Official Dashboard</h1>
        <p style="color: #6B7280;">Barangay workspace for community projects</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Local Projects" value="8" iconColor="#1e40af">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Community Events" value="12" iconColor="#fbbf24">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Residents Served" value="2500" iconColor="#10b981">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Completion Rate" value="92%" iconColor="#f43f5e">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Barangay Projects Table -->
    <div class="rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <h2 class="text-2xl font-semibold text-black mb-6">Community Projects</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid #E5E7EB;">
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PROJECT NAME</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">TYPE</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">STATUS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PROGRESS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">BENEFICIARIES</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Community Garden</td>
                        <td class="px-4 py-4 text-black">Local Initiative</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #10b981; color: white;">COMPLETED</span></td>
                        <td class="px-4 py-4 text-black">100%</td>
                        <td class="px-4 py-4 text-black">500+</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Community Health Program</td>
                        <td class="px-4 py-4 text-black">Health & Wellness</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4 text-black">75%</td>
                        <td class="px-4 py-4 text-black">2500</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-black">Youth Development Center</td>
                        <td class="px-4 py-4 text-black">Community Service</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4 text-black">50%</td>
                        <td class="px-4 py-4 text-black">800+</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
