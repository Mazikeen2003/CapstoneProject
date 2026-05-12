@extends('layouts.city')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">City Reports</h1>
        <p style="color: #c9a84c;">City-level reports and status updates</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 2px solid #B2BEB5;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #0f1e3d;">
                        <th class="text-left py-3 px-4 font-semibold text-black">Report Title</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Period</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-black">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #0f1e3d;">
                        <td class="py-3 px-4 text-black">Quarterly Project Progress</td>
                        <td class="py-3 px-4" style="color: #black;">Q4 2023</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs" style="background-color: #c9a84c; color: #0f1e3d;">Available</span></td>
                        <td class="py-3 px-4">
                            <a href="#" style="color: #black;">View</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #0f1e3d;">
                        <td class="py-3 px-4 text-black">Budget Utilization Report</td>
                        <td class="py-3 px-4" style="color: #black;">Annual 2023</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs" style="background-color: #c9a84c; color: #0f1e3d;">Available</span></td>
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
