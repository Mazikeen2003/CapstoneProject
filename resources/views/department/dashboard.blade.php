@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Department Dashboard</h1>
        <p style="color: #6B7280;">Welcome to the Department Dashboard for Cabuyao City Government</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Total Projects" value="25" iconColor="#1e40af">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Ongoing Projects" value="10" iconColor="#fbbf24">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Completed Projects" value="15" iconColor="#10b981">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </x-stat-card>
        <x-stat-card title="Budget Allocated" value="₱5M" iconColor="#f43f5e">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
        </x-stat-card>
    </div>

    <!-- Projects Table -->
    <div class="rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <h2 class="text-2xl font-semibold text-black mb-6">Department Projects</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid #E5E7EB;">
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PROJECT NAME</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">BARANGAY</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">STATUS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">PROGRESS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">BUDGET</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold" style="color: #374151;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Barangay Road Improvement</td>
                        <td class="px-4 py-4 text-black">Barangay 1</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4 text-black">65%</td>
                        <td class="px-4 py-4 text-black">₱500K</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="px-4 py-4 text-black">Community Center Construction</td>
                        <td class="px-4 py-4 text-black">Barangay 2</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #10b981; color: white;">COMPLETED</span></td>
                        <td class="px-4 py-4 text-black">100%</td>
                        <td class="px-4 py-4 text-black">₱750K</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-black">Water System Upgrade</td>
                        <td class="px-4 py-4 text-black">Barangay 3</td>
                        <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #f59e0b; color: white;">IN PROGRESS</span></td>
                        <td class="px-4 py-4 text-black">40%</td>
                        <td class="px-4 py-4 text-black">₱1M</td>
                        <td class="px-4 py-4"><div class="flex items-center gap-2"><button class="p-1"><svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button><button class="p-1"><svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
