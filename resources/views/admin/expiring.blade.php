@extends('layouts.app')

@section('title', 'Expiring Records')

@section('content')
@php
    $statusColors = ['active' => 'bg-teal-500', 'renewed' => 'bg-green-600', 'new' => 'bg-blue-600', 'expired' => 'bg-red-600'];

    $badge = function (int $d) {
        if ($d < 0) return ['bg-red-700', 'Expired ' . abs($d) . 'd ago'];
        if ($d === 0) return ['bg-red-600', 'Expires today'];
        if ($d <= 7) return ['bg-red-500', $d . 'd left'];
        if ($d <= 30) return ['bg-amber-500', $d . 'd left'];
        return ['bg-teal-500', $d . 'd left'];
    };

    $windowLabel = $showExpired ? 'already expired' : "within the next {$days} days";
@endphp

<div class="lg:flex group/layout">
    <x-sidebar active="dashboard" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Expiring Records" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-900 mb-4">← Back to dashboard</a>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ number_format($total) }} {{ \Illuminate\Support\Str::plural('record', $total) }} {{ $windowLabel }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $module === 'all' ? 'All modules' : $modules[$module] }} · soonest first
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.expiring') }}" class="flex flex-col sm:flex-row gap-2">
                    @if ($isSuperadmin)
                        <select name="module" onchange="showPageLoading(); this.form.submit()"
                            class="rounded-full border-2 border-gray-300 bg-white text-gray-900 font-medium px-3 py-2 text-sm focus:outline-none focus:border-red-600">
                            <option value="all" @selected($module === 'all')>All Modules</option>
                            @foreach ($modules as $key => $label)
                                <option value="{{ $key }}" @selected($module === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                    <select name="days" onchange="showPageLoading(); this.form.submit()"
                        class="rounded-full border-2 border-gray-300 bg-white text-gray-900 font-medium px-3 py-2 text-sm focus:outline-none focus:border-red-600">
                        @foreach ($windows as $w)
                            <option value="{{ $w }}" @selected(!$showExpired && $days === $w)>Next {{ $w }} days</option>
                        @endforeach
                        <option value="expired" @selected($showExpired)>Already expired</option>
                    </select>
                </form>
            </div>

            {{-- Type chips --}}
            @if ($typeCounts->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-5">
                    <a href="{{ request()->fullUrlWithQuery(['type' => null, 'page' => null]) }}"
                        class="rounded-full px-3.5 py-1.5 text-xs font-semibold border transition-colors {{ $type === null ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-500' }}">
                        All ({{ $total }})
                    </a>
                    @foreach ($typeCounts as $name => $count)
                        <a href="{{ request()->fullUrlWithQuery(['type' => $name, 'page' => null]) }}"
                            class="rounded-full px-3.5 py-1.5 text-xs font-semibold border transition-colors {{ $type === $name ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-500' }}">
                            {{ $name }} ({{ $count }})
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Desktop table --}}
            <div class="hidden lg:block rounded-2xl border border-gray-200 bg-white overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="text-left text-gray-900 bg-gray-50">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Record</th>
                            <th class="px-6 py-3 font-bold">Owner / Name</th>
                            <th class="px-6 py-3 font-bold">Type</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold">Expiry</th>
                            <th class="px-6 py-3 font-bold w-28"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse ($records as $r)
                            @php [$color, $text] = $badge($r['days']); @endphp
                            <tr class="divide-x divide-gray-300 hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 break-words">{{ $r['label'] }}</td>
                                <td class="px-6 py-4 text-gray-700 break-words">{{ $r['sub'] ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $r['type'] }}</span>
                                    @if ($module === 'all')
                                        <p class="text-[11px] text-gray-400 mt-1">{{ $r['module'] }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($r['status'])
                                        <span class="inline-block rounded-full {{ $statusColors[strtolower($r['status'])] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2.5 py-1">{{ ucfirst($r['status']) }}</span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 text-sm font-semibold whitespace-nowrap">{{ $r['date']->format('M-d-y') }}</p>
                                    <span class="inline-block rounded-full {{ $color }} text-white text-xs font-semibold px-2.5 py-1 mt-1 whitespace-nowrap">{{ $text }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ $r['href'] }}" class="inline-flex items-center rounded-full border border-gray-300 text-gray-700 px-3.5 py-1.5 text-xs font-semibold hover:bg-gray-50 whitespace-nowrap">Open →</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                    Nothing {{ $windowLabel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="lg:hidden space-y-3">
                @forelse ($records as $r)
                    @php [$color, $text] = $badge($r['days']); @endphp
                    <a href="{{ $r['href'] }}" class="block rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-gray-900 font-semibold truncate">{{ $r['label'] }}</p>
                                <p class="text-gray-500 text-sm truncate">{{ $r['sub'] ?: '—' }}</p>
                            </div>
                            <span class="inline-block rounded-full {{ $color }} text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap shrink-0">{{ $text }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1">{{ $r['type'] }}</span>
                            @if ($r['status'])
                                <span class="inline-block rounded-full {{ $statusColors[strtolower($r['status'])] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2.5 py-1">{{ ucfirst($r['status']) }}</span>
                            @endif
                            <span class="inline-block rounded-full bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $r['date']->format('M-d-y') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-gray-500 text-sm">
                        Nothing {{ $windowLabel }}.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                @if ($records->hasPages())
                    {{ $records->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection