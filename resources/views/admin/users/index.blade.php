@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: #1F2937;">User Access</h1>
        <p style="color: #6B7280;">Manage role-based access permissions for all system users.</p>
    </div>

    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search user or barangay..." class="w-full pl-10 pr-4 py-2 rounded border" style="border-color: #D1D5DB; background-color: #ffffff; color: #1F2937;">
            </div>
            <button class="px-4 py-2 rounded flex items-center gap-2" style="background-color: #ffffff; border: 1px solid #D1D5DB; color: #1F2937;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
        </div>
        <a href="{{ url('/admin/users/create') }}" class="px-4 py-2 rounded flex items-center gap-2" style="background-color: #1F2937; color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New User
        </a>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">FULL NAME & USERNAME</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">ROLE</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">EMAIL ADDRESS</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">ASSIGNED BARANGAY</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">ACCESS LEVEL</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="background-color: #fbbf24; color: #0f1e3d;">RM</div>
                                <div>
                                    <p style="color: #1F2937; font-weight: 500;">Ricardo Mappantay</p>
                                    <p style="color: #6B7280; font-size: 0.875rem;">@rmappantay.city</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">City Official</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">ricardo.m@cabuyao.gov.ph</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Sala</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DBEAFE; color: #0C63E4;">READ ONLY</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DCFCE7; color: #15803D;">ACTIVE</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="background-color: #c9a84c; color: #0f1e3d;">MA</div>
                                <div>
                                    <p style="color: #1F2937; font-weight: 500;">Maria Agoncillo</p>
                                    <p style="color: #6B7280; font-size: 0.875rem;">@m.agoncillo</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Dept Personnel</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">m.agoncillo@cabuyao.gov.ph</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Barangay</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DDD6FE; color: #6366F1;">FULL CRUD</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DCFCE7; color: #15803D;">ACTIVE</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="background-color: #f59e0b; color: white;">ER</div>
                                <div>
                                    <p style="color: #1F2937; font-weight: 500;">Emilio Rizal</p>
                                    <p style="color: #6B7280; font-size: 0.875rem;">@erizal.brgy</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Barangay Official</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">erizal@brgy.mamatid.ph</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Mamatid</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #FED7AA; color: #B45309;\">BARANGAY VIEW</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold\" style="background-color: #FEE2E2; color: #991B1B;\">INACTIVE</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold\" style="background-color: #ef4444; color: white;\">BC</div>
                                <div>
                                    <p style="color: #1F2937; font-weight: 500;\">Bernardo Carpio</p>
                                    <p style="color: #6B7280; font-size: 0.875rem;\">@carpio.admin</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">System Admin</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">b.carpio@system.cabuyao.ph</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">Diezmo</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold\" style="background-color: #F3E8FF; color: #7C3AED;\">SYSTEM MANAGEMENT</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold\" style="background-color: #DCFCE7; color: #15803D;\">ACTIVE</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold\" style="background-color: #fbbf24; color: #0f1e3d;\">SL</div>
                                <div>
                                    <p style="color: #1F2937; font-weight: 500;\">Sisa Luna</p>
                                    <p style="color: #6B7280; font-size: 0.875rem;\">@sisa.gulod</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">Barangay Official</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">s.luna@brgy.gulod.ph</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;\">Gulod</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold\" style="background-color: #FED7AA; color: #B45309;\">BARANGAY VIEW</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold\" style="background-color: #DCFCE7; color: #15803D;\">ACTIVE</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between mt-6 pt-4\" style="border-top: 1px solid #E5E7EB;\">
            <p class="text-sm\" style="color: #6B7280;\">Showing 5 of 92 Users</p>
            <div class="flex gap-2\">
                <button class=\"px-3 py-1 rounded text-sm\" style=\"background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;\">← Prev</button>
                <button class=\"px-3 py-1 rounded text-sm font-semibold\" style=\"background-color: #2563EB; color: white;\">1</button>
                <button class=\"px-3 py-1 rounded text-sm\" style=\"background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;\">2</button>
                <button class=\"px-3 py-1 rounded text-sm\" style=\"background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;\">3</button>
                <button class=\"px-3 py-1 rounded text-sm\" style=\"background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;\">...</button>
                <button class=\"px-3 py-1 rounded text-sm\" style=\"background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;\">Next →</button>
            </div>
        </div>
    </div>
</div>
@endsection
