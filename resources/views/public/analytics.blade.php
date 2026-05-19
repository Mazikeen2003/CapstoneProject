@extends('layouts.public-map')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold" style="color: black;">Cabuyao City Analytics</h1>
        <p class="text-sm text-gray-500 mt-1">Project performance and budget tracking</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #3b82f6;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">On-Time Projects</p>
                    <p class="text-4xl font-bold" style="color: black; margin-top: 0.5rem;">82%</p>
                    <p class="text-sm" style="color: #10b981; margin-top: 0.5rem;">+3% this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #10b981;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Budget Spent</p>
                    <p class="text-4xl font-bold" style="color: black; margin-top: 0.5rem;">₱3.2M</p>
                    <p class="text-sm text-gray-500 mt-2">of ₱4.5M allocated</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #f59e0b;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Projects</p>
                    <p class="text-4xl font-bold" style="color: black; margin-top: 0.5rem;">18</p>
                    <p class="text-sm text-gray-500 mt-2">7 in progress</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div class="rounded-2xl p-3 flex items-center justify-center flex-shrink-0" style="background-color: #ef4444;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">At-Risk Projects</p>
                    <p class="text-4xl font-bold" style="color: black; margin-top: 0.5rem;">1</p>
                    <p class="text-sm text-red-600 mt-2">Needs attention</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <!-- Project Status Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold" style="color: black;">Project Status Distribution</h3>
            <div class="space-y-4 mt-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                        <span class="text-sm font-bold text-gray-900">8 projects</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 44%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                        <span class="text-sm font-bold text-gray-900">7 projects</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 39%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">On Hold</span>
                        <span class="text-sm font-bold text-gray-900">2 projects</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 11%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Planning</span>
                        <span class="text-sm font-bold text-gray-900">1 project</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 6%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold" style="color: black;">Budget Overview</h3>
            <div class="space-y-4 mt-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Infrastructure</span>
                        <span class="text-sm font-bold text-gray-900">₱1.8M</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 56%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Community Services</span>
                        <span class="text-sm font-bold text-gray-900">₱980K</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 31%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Environmental</span>
                        <span class="text-sm font-bold text-gray-900">₱420K</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 13%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold" style="color: black;">Recent Projects</h3>
        <div class="overflow-x-auto mt-6">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Barangay</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Progress</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Budget</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm" style="color: black;">Cabuyao City Hall Renovation</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Diezmo</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">In Progress</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">79%</td>
                        <td class="py-3 px-4 text-sm font-semibold" style="color: black;">₱950K</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm" style="color: black;">Barangay Drainage System Phase 2</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Bigaa</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">In Progress</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">88%</td>
                        <td class="py-3 px-4 text-sm font-semibold" style="color: black;">₱700K</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm" style="color: black;">Environmental Park Development</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Marinig</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">Completed</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">100%</td>
                        <td class="py-3 px-4 text-sm font-semibold" style="color: black;">₱400K</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm" style="color: black;">Sunny Winds Multi-Purpose Hall</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Leismer</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #f59e0b;">On Hold</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">65%</td>
                        <td class="py-3 px-4 text-sm font-semibold" style="color: black;">₱450K</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
