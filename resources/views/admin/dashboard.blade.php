@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $tones = [
        'blue' => ['bg-blue-50', 'text-blue-600'],
        'purple' => ['bg-purple-50', 'text-purple-600'],
        'amber' => ['bg-amber-50', 'text-amber-600'],
        'teal' => ['bg-teal-50', 'text-teal-600'],
        'emerald' => ['bg-emerald-50', 'text-emerald-600'],
        'red' => ['bg-red-50', 'text-red-600'],
        'cyan' => ['bg-cyan-50', 'text-cyan-600'],
        'indigo' => ['bg-indigo-50', 'text-indigo-600'],
    ];

    $barColors = [
        'emerald' => 'bg-emerald-500',
        'blue' => 'bg-blue-500',
        'red' => 'bg-red-500',
    ];

    $icons = [
        'vehicle' => 'M3 7h11v9H3zM14 10h4l3 3v3h-7M7 19a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z',
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'idcard' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
        'tag' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z',
        'upload' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'shield' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
        'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
        'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'franchise' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z',
        'users' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'search' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
    ];

    $roleAvatarColors = [
        'superadmin' => 'bg-red-600',
        'potpot_admin' => 'bg-blue-600',
        'tricycle_admin' => 'bg-emerald-600',
        'tanod_admin' => 'bg-amber-600',
    ];
    $roleBadgeColors = [
        'superadmin' => 'bg-gray-900 text-white',
        'potpot_admin' => 'bg-blue-100 text-blue-700',
        'tricycle_admin' => 'bg-emerald-100 text-emerald-700',
        'tanod_admin' => 'bg-amber-100 text-amber-700',
    ];
@endphp

<div class="lg:flex group/layout">
    <x-sidebar active="dashboard" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Dashboard" />

        <div class="max-w-7xl mx-auto px-6 pt-8 pb-8">
            <!-- Header + module filter (superadmin only) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <p class="text-gray-500 text-sm">Welcome back, {{ auth()->user()->name }}.</p>
                    <p class="text-xs text-gray-400 mt-0.5">Showing: <span class="font-medium text-gray-600">{{ $viewLabel }}</span></p>
                </div>

                @if ($isSuperadmin)
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="w-full sm:w-auto">
                        <select name="module" onchange="showPageLoading(); this.form.submit()"
                            class="w-full sm:w-48 rounded-full border-2 border-gray-300 bg-white text-gray-900 font-medium px-3 py-2 text-sm focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            <option value="all" @selected($selectedModule === 'all')>All Modules</option>
                            @foreach ($availableModules as $key => $label)
                                <option value="{{ $key }}" @selected($selectedModule === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>

            <!-- Alerts banner (potpot / tricycle only, since tanod has no expiry dates) -->
            @if ($tracksExpiry)
                <div class="rounded-2xl border {{ $expiringSoon > 0 ? 'border-amber-200 bg-amber-50' : 'border-emerald-200 bg-emerald-50' }} px-5 py-4 flex items-center gap-3 mb-6">
                    <svg class="w-5 h-5 {{ $expiringSoon > 0 ? 'text-amber-600' : 'text-emerald-600' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    @if ($expiringSoon > 0)
                        <p class="text-sm text-amber-800">
                            <span class="font-semibold">{{ $expiringSoon }} {{ Str::plural('record', $expiringSoon) }}</span>
                            {{ $expiringSoon === 1 ? 'is' : 'are' }} expiring within the next 30 days{{ count($modules) > 1 ? ' across all modules' : '' }}.
                        </p>
                        <a href="{{ $expiringHref }}" class="ml-auto text-xs font-semibold text-amber-800 whitespace-nowrap hover:underline">Review now →</a>
                    @else
                        <p class="text-sm text-emerald-800">Nothing is expiring within the next 30 days.</p>
                    @endif
                </div>
            @endif

            <!-- Stat cards, grouped per module -->
            @foreach ($moduleStats as $key => $section)
                <div class="{{ $loop->first ? '' : 'mt-8' }}">
                    @if (count($moduleStats) > 1)
                        <h2 class="text-sm font-semibold text-gray-900 mb-3">{{ $section['label'] }}</h2>
                    @endif

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($section['cards'] as $card)
                            @php $tone = $tones[$card['tone']] ?? $tones['blue']; @endphp
                            <a href="{{ $card['href'] }}" class="rounded-2xl border border-gray-200 bg-white p-6 hover:border-red-300 hover:shadow-sm transition-all">
                                <div class="flex items-start justify-between">
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($card['value']) }}</p>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl {{ $tone[0] }} flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 {{ $tone[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$card['icon']] ?? $icons['list'] }}" />
                                        </svg>
                                    </div>
                                </div>
                                @if ($card['sub'])
                                    <p class="text-xs {{ $tone[1] }} font-medium mt-3">{{ $card['sub'] }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Quick actions -->
            <h2 class="text-sm font-semibold text-gray-900 mt-8 mb-3">Quick Actions</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($quickActions as $action)
                    @php $tone = $tones[$action['tone']] ?? $tones['blue']; @endphp
                    <a href="{{ $action['href'] }}" class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center gap-4 hover:border-red-300 hover:shadow-sm transition-all">
                        <div class="w-11 h-11 rounded-xl {{ $tone[0] }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 {{ $tone[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$action['icon']] ?? $icons['list'] }}" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $action['label'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $action['sub'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Recent activity + side panels (status breakdown / distributions) -->
            <div class="grid lg:grid-cols-3 gap-4 mt-8">
                <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        @forelse ($recentActivity as $activity)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-900">{{ $activity['text'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $activity['time']->diffForHumans() }}</p>
                                </div>
                                @if (count($modules) > 1)
                                    <span class="inline-block rounded-full bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 shrink-0">{{ $activity['tag'] }}</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No recent activity yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($sidePanels as $panel)
                        <div class="rounded-2xl border border-gray-200 bg-white p-6">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4">{{ $panel['title'] }}</h2>

                            @if ($panel['type'] === 'status')
                                <div class="space-y-4">
                                    @foreach ($panel['rows'] as $row)
                                        <div>
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="text-gray-600">{{ $row['label'] }}</span>
                                                <span class="font-medium text-gray-900">{{ $row['count'] }} ({{ $row['percent'] }}%)</span>
                                            </div>
                                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                                <div class="h-full {{ $barColors[$row['tone']] ?? 'bg-gray-400' }} rounded-full" style="width: {{ $row['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-3">
                                    @forelse ($panel['rows'] as $row)
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs text-gray-600 truncate">{{ $row['label'] }}</span>
                                            <span class="text-xs font-medium text-gray-900">{{ $row['count'] }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-400">No data yet.</p>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Upcoming renewals (potpot / tricycle only) -->
            @if ($tracksExpiry)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-900">Upcoming Renewals</h2>
                        <a href="{{ $expiringHref }}" class="text-xs text-red-600 font-medium">View all →</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($upcomingRenewals as $renewal)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $renewal['label'] }}</p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $renewal['sub'] }}@if (count($modules) > 1) · {{ $renewal['tag'] }}@endif
                                    </p>
                                </div>
                                <span class="inline-block rounded-full {{ $renewal['date']->lte(now()->addDays(7)) ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }} text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                    Expires {{ $renewal['date']->format('M-d-y') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 py-3">Nothing expiring in the next 30 days.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Monthly registrations trend -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-semibold text-gray-900">Registrations — Last 8 Months</h2>
                    <span class="text-xs text-gray-400">{{ $viewLabel }}</span>
                </div>
                <div class="flex items-end gap-3 h-40">
                    @foreach ($monthlyRegistrations as $index => $month)
                        <div class="flex-1 h-full flex flex-col items-center justify-end gap-2">
                            <span class="text-[10px] text-gray-500">{{ $month['count'] }}</span>
                            <div class="w-full {{ $index === $monthlyRegistrations->count() - 1 ? 'bg-red-600' : 'bg-red-100' }} rounded-t-lg" style="height: {{ max(($month['count'] / $maxMonthly) * 100, 4) }}%"></div>
                            <span class="text-[10px] {{ $index === $monthlyRegistrations->count() - 1 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Admin team (superadmin only) + system snapshot -->
            <div class="grid lg:grid-cols-3 gap-4 mt-6">
                @if ($isSuperadmin)
                    <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-semibold text-gray-900">Admin Team</h2>
                            <a href="{{ route('admin.users') }}" class="text-xs text-red-600 font-medium">Manage →</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($admins->take(5) as $admin)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-3">
                                        <x-avatar :user="$admin" size="w-8 h-8" text="text-xs" :color="$roleAvatarColors[$admin->role] ?? 'bg-slate-600'" />
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $admin->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-block rounded-full {{ $roleBadgeColors[$admin->role] ?? 'bg-slate-100 text-slate-700' }} text-xs font-semibold px-2.5 py-1">
                                        {{ str_replace('_', ' ', ucfirst($admin->role)) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="rounded-2xl border border-gray-200 bg-white p-6 {{ $isSuperadmin ? '' : 'lg:col-span-3' }}">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">System Snapshot</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Total records ({{ $viewLabel }})</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($totalRecords) }}</span>
                        </div>
                        @if ($isSuperadmin)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Admin accounts</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $admins->count() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection