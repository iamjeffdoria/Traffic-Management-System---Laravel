@extends('layouts.app')

@section('title', 'ID Cards')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-id-cards" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="ID Cards" />

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

            <x-id-card-toolbar />

            <x-id-card-create-modal />

            <x-id-card-delete-confirm-modal />

            @php
                $hasActiveIdCardFilters = request()->filled('full_name') || request()->filled('id_number');
            @endphp

            <form id="id-card-filter-form" method="GET" action="{{ route('potpot.id-cards') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-32">
                        <col class="w-[10%]">
                        <col class="w-[16%]">
                        <col class="w-[20%]">
                        <col class="w-[12%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-32">Actions</th>
                            <th class="px-6 py-3 font-bold">Photo</th>
                            <th class="px-6 py-3 font-bold">ID No. / Gender</th>
                            <th class="px-6 py-3 font-bold">Name / Address</th>
                            <th class="px-6 py-3 font-bold">Height / Weight</th>
                            <th class="px-6 py-3 font-bold">DOB / OR No.</th>
                            <th class="px-6 py-3 font-bold">Issued / Expiry</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="id_number" form="id-card-filter-form" data-filter-scope="desktop" value="{{ request('id_number') }}" oninput="debouncedFetchIdCardFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="full_name" form="id-card-filter-form" data-filter-scope="desktop" value="{{ request('full_name') }}" oninput="debouncedFetchIdCardFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="id-card-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($idCards as $idCard)
                            <x-id-card-table-row :idCard="$idCard" />
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveIdCardFilters)
                                        No ID cards match your search.
                                        <a href="{{ route('potpot.id-cards') }}" data-ajax-id-card-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No ID cards registered yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="id-card-pagination-desktop" class="mt-4">
                @unless ($idCards->isEmpty())
                    {{ $idCards->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="full_name" form="id-card-filter-form" data-filter-scope="mobile" value="{{ request('full_name') }}" oninput="debouncedFetchIdCardFilter()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="id_number" form="id-card-filter-form" data-filter-scope="mobile" value="{{ request('id_number') }}" oninput="debouncedFetchIdCardFilter()" placeholder="Search ID number..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <!-- Mobile stacked cards -->
            <div id="id-card-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($idCards as $idCard)
                    <x-id-card-mobile-card :idCard="$idCard" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveIdCardFilters)
                            No ID cards match your search.
                            <a href="{{ route('potpot.id-cards') }}" data-ajax-id-card-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No ID cards registered yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="id-card-pagination-mobile" class="lg:hidden mt-4">
                @unless ($idCards->isEmpty())
                    {{ $idCards->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection