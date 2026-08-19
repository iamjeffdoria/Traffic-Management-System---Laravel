@extends('layouts.app')

@section('title', 'MTOP')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-mtop" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="MTOP" />

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

            <x-mtop-toolbar />

            <x-mtop-create-modal :tricycles="$tricycles" />

            <x-mtop-delete-confirm-modal />

            @php
                $hasActiveMtopFilters = request()->filled('case_no') || request()->filled('tricycle') || request()->filled('route_operation');
            @endphp

            <form id="mtop-filter-form" method="GET" action="{{ route('tricycle.mtop') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-24">
                        <col class="w-[16%]">
                        <col class="w-[18%]">
                        <col class="w-[18%]">
                        <col class="w-[12%]">
                        <col class="w-[24%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-24">Actions</th>
                            <th class="px-6 py-3 font-bold">Case No.</th>
                            <th class="px-6 py-3 font-bold">Tricycle / Owner</th>
                            <th class="px-6 py-3 font-bold">Route Operation</th>
                            <th class="px-6 py-3 font-bold">Date</th>
                            <th class="px-6 py-3 font-bold">Treasurer / Officer / Mayor</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="case_no" form="mtop-filter-form" data-filter-scope="desktop" value="{{ request('case_no') }}" oninput="debouncedFetchMtopFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="tricycle" form="mtop-filter-form" data-filter-scope="desktop" value="{{ request('tricycle') }}" oninput="debouncedFetchMtopFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="route_operation" form="mtop-filter-form" data-filter-scope="desktop" value="{{ request('route_operation') }}" oninput="debouncedFetchMtopFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="mtop-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($mtops as $mtop)
                            <x-mtop-table-row :mtop="$mtop" />
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveMtopFilters)
                                        No MTOP records match your search.
                                        <a href="{{ route('tricycle.mtop') }}" data-ajax-mtop-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No MTOP records yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="mtop-pagination-desktop" class="mt-4">
                {{ $mtops->links() }}
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="case_no" form="mtop-filter-form" data-filter-scope="mobile" value="{{ request('case_no') }}" oninput="debouncedFetchMtopFilter()" placeholder="Search case no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="tricycle" form="mtop-filter-form" data-filter-scope="mobile" value="{{ request('tricycle') }}" oninput="debouncedFetchMtopFilter()" placeholder="Search tricycle or owner..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="route_operation" form="mtop-filter-form" data-filter-scope="mobile" value="{{ request('route_operation') }}" oninput="debouncedFetchMtopFilter()" placeholder="Search route operation..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <!-- Mobile stacked cards -->
            <div id="mtop-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($mtops as $mtop)
                    <x-mtop-mobile-card :mtop="$mtop" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveMtopFilters)
                            No MTOP records match your search.
                            <a href="{{ route('tricycle.mtop') }}" data-ajax-mtop-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No MTOP records yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="mtop-pagination-mobile" class="lg:hidden mt-4">
                {{ $mtops->links() }}
            </div>

            <div id="mtop-edit-modals">
                @foreach ($mtops as $mtop)
                    <x-mtop-edit-modal :mtop="$mtop" :tricycles="$tricycles" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection