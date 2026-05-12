@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Projects</h1>
        <p style="color: #c9a84c;">Manage barangay-level projects</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 2px solid #B2BEB5;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #162347;">
                        <th class="text-left py-3 px-4 font-semibold text-black">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Budget</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #0f1e3d;">
                        <td class="py-3 px-4 text-black">Street Lighting</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs" style="background-color: #c9a84c; color: #0f1e3d;">In Progress</span></td>
                        <td class="py-3 px-4" style="color: #black;">₱150,000</td>
                        <td class="py-3 px-4">
                            <a href="#" style="color: #black;">View</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #0f1e3d;">
                        <td class="py-3 px-4 text-black">Community Center Repair</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs" style="background-color: #c9a84c; color: #0f1e3d;">Planning</span></td>
                        <td class="py-3 px-4" style="color: #black;">₱200,000</td>
                        <td class="py-3 px-4">
                            <a href="#" style="color: #black;">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
