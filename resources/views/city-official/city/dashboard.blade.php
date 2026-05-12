@extends('layouts.city')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">City Operations</h1>
                <p class="mt-2 text-sm text-slate-500">Monitor city programs, approve local requests, and coordinate with barangay leaders.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">View Projects</button>
                <button class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">New Initiative</button>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-4 lg:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="rounded-2xl bg-indigo-100 p-3 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase tracking-[0.25em] text-emerald-600">Active</span>
                </div>
                <div class="mt-6">
                    <p class="text-sm text-slate-500">Active Projects</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">28</p>
                    <p class="text-xs text-emerald-600 mt-2">+2 this week</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="rounded-2xl bg-teal-100 p-3 text-teal-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase tracking-[0.25em] text-emerald-600">Coordinated</span>
                </div>
                <div class="mt-6">
                    <p class="text-sm text-slate-500">Barangay Partners</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">45</p>
                    <p class="text-xs text-slate-500 mt-2">Active barangays</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="rounded-2xl bg-cyan-100 p-3 text-cyan-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase tracking-[0.25em] text-emerald-600">Pending</span>
                </div>
                <div class="mt-6">
                    <p class="text-sm text-slate-500">Community Requests</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">12</p>
                    <p class="text-xs text-slate-500 mt-2">Awaiting review</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <div class="rounded-2xl bg-orange-100 p-3 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase tracking-[0.25em] text-amber-600">Trending</span>
                </div>
                <div class="mt-6">
                    <p class="text-sm text-slate-500">Service Delivery</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">94%</p>
                    <p class="text-xs text-slate-500 mt-2">Completion rate</p>
                </div>
            </div>
        </div>
    </div>
@endsection
