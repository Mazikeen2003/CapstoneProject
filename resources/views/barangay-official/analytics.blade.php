@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Analytics</h1>
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
                    <p class="text-4xl font-bold text-black mt-2">90%</p>
                    <p class="text-sm text-green-600 mt-2">+7% this month</p>
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
                    <p class="text-4xl font-bold text-black mt-2">₱1.2M</p>
                    <p class="text-sm text-gray-500 mt-2">of ₱1.8M allocated</p>
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
                    <p class="text-4xl font-bold text-black mt-2">8</p>
                    <p class="text-sm text-gray-500 mt-2">3 in progress</p>
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
                    <p class="text-4xl font-bold text-black mt-2">0</p>
                    <p class="text-sm text-green-600 mt-2">All on track</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <!-- Project Status Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-black mb-4">Project Status Distribution</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                        <span class="text-sm font-bold text-gray-900">4 projects</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                        <span class="text-sm font-bold text-gray-900">3 projects</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 38%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">On Hold</span>
                        <span class="text-sm font-bold text-gray-900">1 project</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 12%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-black mb-4">Budget Overview</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Spent</span>
                        <span class="text-sm font-bold text-gray-900">₱1.2M</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 67%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Remaining</span>
                        <span class="text-sm font-bold text-gray-900">₱600K</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 33%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-black mb-4">Recent Projects</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Progress</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm text-gray-700">Budget</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm text-black">Cabuyao City Hall Renovation</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">In Progress</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">79%</td>
                        <td class="py-3 px-4 text-sm font-semibold text-black">₱950K</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm text-black">Barangay Drainage System</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">In Progress</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">88%</td>
                        <td class="py-3 px-4 text-sm font-semibold text-black">₱700K</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td class="py-3 px-4 text-sm text-black">Environmental Park Development</td>
                        <td class="py-3 px-4 text-sm"><span class="px-3 py-1 rounded-full text-white text-xs" style="background-color: #10b981;">Completed</span></td>
                        <td class="py-3 px-4 text-sm text-gray-600">100%</td>
                        <td class="py-3 px-4 text-sm font-semibold text-black">₱400K</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
