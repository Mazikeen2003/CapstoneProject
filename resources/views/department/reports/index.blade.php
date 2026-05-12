@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="color: black;">Reports & Exports</h1>
        <p style="color: #c9a84c;">Generate and export department reports</p>
    </div>

    <div class="flex justify-end">
        <button class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Export PDF</button>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 2px solid #B2BEB5;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #B2BEB5;">
                        <th class="text-left py-3 px-4 font-semibold text-black">Report Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Type</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Generated</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #B2BEB5;">
                        <td class="py-3 px-4 text-black">Monthly Project Summary</td>
                        <td class="py-3 px-4" style="color: #black;">PDF</td>
                        <td class="py-3 px-4" style="color: #black;">2024-01-15</td>
                        <td class="py-3 px-4">
                            <a href="#" style="color: #black;">Download</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #B2BEB5;">
                        <td class="py-3 px-4 text-black">Budget Analysis Report</td>
                        <td class="py-3 px-4" style="color: #black;">Excel</td>
                        <td class="py-3 px-4" style="color: #black;">2024-01-10</td>
                        <td class="py-3 px-4">
                            <a href="#" style="background-color: #black;">Download</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection