@extends('layouts.app')

@section('title', 'Tricycle List')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-list" />
    <<div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Tricycle List" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <x-tricycle-toolbar />

            <x-tricycle-create-modal />

            <x-tricycle-delete-confirm-modal />

            @php
                $hasActiveFilters = request()->filled('body') || request()->filled('plate') || request()->filled('name') || request()->filled('address') || request()->filled('status');
            @endphp

           <form id="tricycle-filter-form" method="GET" action="{{ route('tricycle.list') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-24">
                        <col class="w-[13%]">
                        <col class="w-[13%]">
                        <col class="w-[11%]">
                        <col class="w-[21%]">
                        <col class="w-[13%]">
                        <col class="w-[16%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                            <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                                <th class="px-6 py-3 font-bold w-24">Actions</th>
                                <th class="px-6 py-3 font-bold">Body No.</th>
                                <th class="px-6 py-3 font-bold">Plate No.</th>
                                <th class="px-6 py-3 font-bold">Owner</th>
                                <th class="px-6 py-3 font-bold">Vehicle / Address</th>
                                <th class="px-6 py-3 font-bold">Status</th>
                                <th class="px-6 py-3 font-bold">Registration / Expiry</th>
                            </tr>
                            <tr class="border-t border-gray-300 divide-x divide-gray-300">
                                <th class="px-6 py-2"></th>
                                <th class="px-2 py-2">
                                    <input type="text" name="body" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('body') }}" oninput="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-4 py-2">
                                    <input type="text" name="plate" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('plate') }}" oninput="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1.5 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-2 py-2">
                                    <input type="text" name="name" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-4 py-2">
                                    <input type="text" name="address" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('address') }}" oninput="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-1.5 py-2">
                                    <select name="status" form="tricycle-filter-form" data-filter-scope="desktop" onchange="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium pl-1.5 pr-0.5 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                        <option value="">All statuses</option>
                                        <option value="active" @selected(request('status') === 'active')>Active</option>
                                        <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                                        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                                    </select>
                                </th>
                                <th class="px-6 py-2"></th>
                            </tr>
                    </thead>
                    <tbody id="tricycle-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($tricycles as $tricycle)
                            <x-tricycle-table-row :tricycle="$tricycle" />
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveFilters)
                                        No tricycles match your search.
                                        <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No tricycles registered yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="tricycle-pagination-desktop" class="mt-4">
                {{ $tricycles->links() }}
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="body" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('body') }}" oninput="debouncedFetchFilter()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="plate" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('plate') }}" oninput="debouncedFetchFilter()" placeholder="Search plate no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchFilter()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="address" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('address') }}" oninput="debouncedFetchFilter()" placeholder="Search address..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" form="tricycle-filter-form" data-filter-scope="mobile" onchange="debouncedFetchFilter()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                </select>
            </div>

           <div id="tricycle-cards-mobile" class="lg:hidden space-y-3">
                @forelse ($tricycles as $tricycle)
                    <x-tricycle-mobile-card :tricycle="$tricycle" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveFilters)
                            No tricycles match your search.
                            <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No tricycles registered yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="tricycle-pagination-mobile" class="lg:hidden mt-4">
                {{ $tricycles->links() }}
            </div>

            <div id="tricycle-edit-modals">
                @foreach ($tricycles as $tricycle)
                    <x-tricycle-edit-modal :tricycle="$tricycle" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection