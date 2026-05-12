@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-black">Admin Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system users, monitor activity, and configure system settings.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg flex items-center gap-2 bg-red-600 text-white hover:bg-red-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form> -->
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #3b82f6;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium" style="letter-spacing: 0.5px;">Total Users</p>
                    <p class="text-4xl font-bold text-black mt-2" style="letter-spacing: -0.5px;">92</p>
                    <p class="text-sm text-gray-500 mt-3 font-medium" style="letter-spacing: 0.3px;">+12 this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #10b981;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium" style="letter-spacing: 0.5px;">Active Sessions</p>
                    <p class="text-4xl font-bold text-black mt-2" style="letter-spacing: -0.5px;">12</p>
                    <p class="text-sm text-green-600 mt-3 font-medium" style="letter-spacing: 0.3px;">↑ 3 from yesterday</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #f59e0b;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium" style="letter-spacing: 0.5px;">System Uptime</p>
                    <p class="text-4xl font-bold text-black mt-2" style="letter-spacing: -0.5px;">99.9%</p>
                    <p class="text-sm text-gray-500 mt-3 font-medium" style="letter-spacing: 0.3px;">30 days uptime</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #ef4444;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium" style="letter-spacing: 0.5px;">Pending Issues</p>
                    <p class="text-4xl font-bold text-black mt-2" style="letter-spacing: -0.5px;">2</p>
                    <p class="text-sm text-red-600 mt-3 font-medium" style="letter-spacing: 0.3px;">Requires attention</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-black">User Management</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage system users and their access permissions</p>
                </div>
                <button class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New User
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Barangay</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                    <p class="font-semibold text-black">Maria Angela Cruz</p>
                                    <p class="text-xs text-gray-500">@mariaac</p>
                                </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm text-gray-700">Dept Personnel</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">m.angelac@cabuyao.gov.ph</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Barangay Imok</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-black">Jose Santos</p>
                                <p class="text-xs text-gray-500">@jsantos</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-sm text-gray-700">City Official</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">jose.santos@cabuyao.gov.ph</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Manduli</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-black">Ricardo Luna</p>
                                <p class="text-xs text-gray-500">@rluna</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm text-gray-700">Barangay Official</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">r.luna@cabuyao.gov.ph</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Disiño</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Inactive</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-black">Juan Dela Cruz</p>
                                <p class="text-xs text-gray-500">@jdelacruz</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-sm text-gray-700">System Admin</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">juan.dc@cabuyao.gov.ph</td>
                        <td class="px-6 py-4 text-sm text-gray-600">City-Wide</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-black">Elena Reyes</p>
                                <p class="text-xs text-gray-500">@ereyes</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm text-gray-700">Dept Personnel</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">elena.reyes@cabuyao.gov.ph</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Gatid</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="p-1 text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing 5 of 81 Users</p>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Previous</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-black text-white hover:bg-gray-800 transition">1</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">2</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">3</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">...</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">10</button>
                <button class="px-3 py-1 rounded-lg text-sm bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection