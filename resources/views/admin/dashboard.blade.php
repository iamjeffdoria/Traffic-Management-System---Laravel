@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="dashboard" />
    <div class="flex-1 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Dashboard" />

        <div class="max-w-7xl mx-auto px-6 pt-8 pb-8">
            <p class="text-gray-500 text-sm mb-6">Welcome back, {{ auth()->user()->name }}.</p>

            <!-- Stat cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Registered Vehicles</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">2,400+</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9M3 3h18v10H3V3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-600 font-medium mt-3">↑ 4.2% this month</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Active Permits</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">1,100+</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-600 font-medium mt-3">↑ 1.8% this month</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Expiring Soon</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">86</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-amber-600 font-medium mt-3">Within next 30 days</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">ID Cards Issued</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">640+</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-600 font-medium mt-3">↑ 6.5% this month</p>
                </div>
            </div>

            <!-- Quick actions -->
            <h2 class="text-sm font-semibold text-gray-900 mt-8 mb-3">Quick Actions</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('potpot.id-cards') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">ID Cards</p>
                        <p class="text-xs text-gray-500">Manage Potpot ID cards</p>
                    </div>
                </a>

                <a href="{{ route('potpot.mayors-permit') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Mayor's Permit – Potpot</p>
                        <p class="text-xs text-gray-500">Issue and track permits</p>
                    </div>
                </a>

                <a href="{{ route('tricycle.list') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Tricycle List</p>
                        <p class="text-xs text-gray-500">Registered tricycle records</p>
                    </div>
                </a>

                <a href="{{ route('tricycle.mayors-permit') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Mayor's Permit – Tricycle</p>
                        <p class="text-xs text-gray-500">Issue and track permits</p>
                    </div>
                </a>

                <a href="{{ route('tricycle.mtop') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">MTOP</p>
                        <p class="text-xs text-gray-500">Route operation records</p>
                    </div>
                </a>

                <a href="{{ route('tricycle.franchise') }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                    <div class="w-11 h-11 rounded-xl bg-cyan-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Franchise</p>
                        <p class="text-xs text-gray-500">Franchise authorization records</p>
                    </div>
                </a>
            </div>

            <!-- Recent activity + status breakdown -->
            <div class="grid lg:grid-cols-3 gap-4 mt-8">
                <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-gray-900">New tricycle <span class="font-medium">BOD-91125</span> registered</p>
                                <p class="text-xs text-gray-400 mt-0.5">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-gray-900">Franchise <span class="font-medium">FR-84021</span> updated</p>
                                <p class="text-xs text-gray-400 mt-0.5">5 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-gray-900">Mayor's Permit <span class="font-medium">MP-2291</span> is expiring soon</p>
                                <p class="text-xs text-gray-400 mt-0.5">Yesterday</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-gray-900">ID card <span class="font-medium">PID-00456</span> was removed</p>
                                <p class="text-xs text-gray-400 mt-0.5">2 days ago</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Status Breakdown</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600">Active</span>
                                <span class="font-medium text-gray-900">68%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: 68%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600">Renewed</span>
                                <span class="font-medium text-gray-900">21%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: 21%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600">Expired</span>
                                <span class="font-medium text-gray-900">11%</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full" style="width: 11%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming renewals + TODA breakdown -->
            <div class="grid lg:grid-cols-3 gap-4 mt-6">
                <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-900">Upcoming Renewals</h2>
                        <a href="{{ route('tricycle.list') }}" class="text-xs text-red-600 font-medium">View all →</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">BOD-27173 — PL-3138</p>
                                <p class="text-xs text-gray-500">Faye Ziemann</p>
                            </div>
                            <span class="inline-block rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                Expires Sep-03-26
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">BOD-19629 — KW-3102</p>
                                <p class="text-xs text-gray-500">Claudia Roob</p>
                            </div>
                            <span class="inline-block rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                Expires Sep-07-26
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Franchise FR-84021</p>
                                <p class="text-xs text-gray-500">Prof. Pete Stroman</p>
                            </div>
                            <span class="inline-block rounded-full bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                Expires Sep-12-26
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">MTOP Case CN-3305</p>
                                <p class="text-xs text-gray-500">Bernita Erdman</p>
                            </div>
                            <span class="inline-block rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                Expires Sep-18-26
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Tricycles by TODA</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">PTL 001-A</span>
                            <span class="text-xs font-medium text-gray-900">142</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">PSMTL 001-B</span>
                            <span class="text-xs font-medium text-gray-900">98</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">PST 001-C</span>
                            <span class="text-xs font-medium text-gray-900">76</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">PCRT-001-D</span>
                            <span class="text-xs font-medium text-gray-900">54</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">PHC 001-E</span>
                            <span class="text-xs font-medium text-gray-900">30</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection