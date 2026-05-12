@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: #1F2937;">Audit Logs</h1>
        <p style="color: #6B7280;">Track and monitor all data modifications made within the system.</p>
    </div>

    <div class="flex justify-between items-center gap-4">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search logs..." class="w-full pl-10 pr-4 py-2 rounded border" style="border-color: #D1D5DB; background-color: #ffffff; color: #1F2937;">
            </div>
            <button class="px-4 py-2 rounded flex items-center gap-2" style="background-color: #ffffff; border: 1px solid #D1D5DB; color: #1F2937;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter Options
            </button>
        </div>
        <button class="px-4 py-2 rounded flex items-center gap-2" style="background-color: #1F2937; color: white;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export to PDF
        </button>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <span style="color: #6B7280; font-weight: 500;">TIME PERIOD:</span>
            <select class="px-3 py-2 rounded border" style="border-color: #D1D5DB; background-color: #ffffff; color: #1F2937;">
                <option>Last 24 Hours</option>
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Custom Range</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">LOG ID</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">USER</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">ROLE</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">ACTION</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">TABLE AFFECTED</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">RECORD ID</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">TIMESTAMP</th>
                        <th class="text-left py-3 px-4 font-semibold" style="color: #6B7280; font-size: 0.75rem; letter-spacing: 0.05em;">IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08231</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Mateo Santos</span><br><span style="color: #6B7280; font-size: 0.75rem;">@msantos_admin</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">System Admin</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #FEE2E2; color: #991B1B;">DELETE</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Users</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">USR-4421</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 24, 2023<br>09:12 AM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">192.168.1.42</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08230</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Elena Reyes</span><br><span style="color: #6B7280; font-size: 0.75rem;">@ereyes_cabuyao</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">City Official</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DBEAFE; color: #0C63E4;">UPDATE</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Projects</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">PRJ-CAB-09</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 24, 2023<br>08:45 AM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">10.0.3.155</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08229</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Ricardo Cruz</span><br><span style="color: #6B7280; font-size: 0.75rem;">@rcruz_brgy</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Barangay Official</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DDD6FE; color: #6366F1;">INSERT</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Budget Transactions</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">TXN-99812</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 24, 2023<br>08:20 AM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">192.168.10.12</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08228</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Maria Clara</span><br><span style="color: #6B7280; font-size: 0.75rem;">@mclara_dept</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Dept Personnel</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DBEAFE; color: #0C63E4;">UPDATE</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Projects</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">PRJ-CAB-12</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 24, 2023<br>07:55 AM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">172.16.0.4</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08227</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Juan dela Cruz</span><br><span style="color: #6B7280; font-size: 0.75rem;">@admin_jdc</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">System Admin</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DBEAFE; color: #0C63E4;">UPDATE</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">System Settings</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">SET-GLOBAL</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 23, 2023<br>11:40 PM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">127.0.0.1</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4" style="color: #1F2937; font-weight: 500;">#AL-08226</td>
                        <td class="py-3 px-4"><span style="color: #1F2937;">Ana Bautista</span><br><span style="color: #6B7280; font-size: 0.75rem;">@abautista_officer</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">City Official</span></td>
                        <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: #DDD6FE; color: #6366F1;">INSERT</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">Budget Transactions</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">TXN-99815</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">8 ct 23, 2023<br>10:15 PM</span></td>
                        <td class="py-3 px-4"><span style="color: #6B7280;">192.168.1.102</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between mt-6 pt-4" style="border-top: 1px solid #E5E7EB;">
            <p class="text-sm" style="color: #6B7280;">Showing 6 of 340 Logs</p>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">Prev</button>
                <button class="px-3 py-1 rounded text-sm font-semibold" style="background-color: #2563EB; color: white;">1</button>
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">2</button>
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">3</button>
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">...</button>
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">57</button>
                <button class="px-3 py-1 rounded text-sm" style="background-color: #ffffff; color: #6B7280; border: 1px solid #D1D5DB;">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection

