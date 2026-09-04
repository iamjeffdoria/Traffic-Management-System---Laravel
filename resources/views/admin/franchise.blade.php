@extends('layouts.app')

@section('title', 'Franchise')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-franchise" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Franchise" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            <x-franchise-toolbar />

            <x-franchise-create-modal :tricycles="$tricycles" />

            <x-franchise-import-modal />

            <x-franchise-delete-confirm-modal />

            @php
                $hasActiveFranchiseFilters = request()->filled('authorized_no') || request()->filled('name') || request()->filled('route') || request()->filled('purpose') || request()->filled('status');
            @endphp

            <form id="franchise-filter-form" method="GET" action="{{ route('tricycle.franchise') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-24">
                        <col class="w-[16%]">
                        <col class="w-[20%]">
                        <col class="w-[20%]">
                        <col class="w-[12%]">
                        <col class="w-[18%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-24">Actions</th>
                            <th class="px-6 py-3 font-bold">Franchise No. / Status</th>
                            <th class="px-6 py-3 font-bold">Owner / Vehicle</th>
                            <th class="px-6 py-3 font-bold">Route / Purpose</th>
                            <th class="px-6 py-3 font-bold">Amount / OR No.</th>
                            <th class="px-6 py-3 font-bold">Registration / Expiry</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="authorized_no" form="franchise-filter-form" data-filter-scope="desktop" value="{{ request('authorized_no') }}" oninput="debouncedFetchFranchiseFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="name" form="franchise-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchFranchiseFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="route" form="franchise-filter-form" data-filter-scope="desktop" value="{{ request('route') }}" oninput="debouncedFetchFranchiseFilter()" placeholder="Search route..."
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-1.5 py-2">
                                <select name="status" form="franchise-filter-form" data-filter-scope="desktop" onchange="debouncedFetchFranchiseFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium pl-1.5 pr-0.5 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                    <option value="">All statuses</option>
                                    <option value="New" @selected(request('status') === 'New')>New</option>
                                    <option value="Renewed" @selected(request('status') === 'Renewed')>Renewed</option>
                                    <option value="Expired" @selected(request('status') === 'Expired')>Expired</option>
                                </select>
                            </th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="franchise-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($franchises as $franchise)
                            <x-franchise-table-row :franchise="$franchise" />
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveFranchiseFilters)
                                        No franchises match your search.
                                        <a href="{{ route('tricycle.franchise') }}" data-ajax-franchise-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No franchise records yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="franchise-pagination-desktop" class="mt-4">
                @unless ($franchises->isEmpty())
                    {{ $franchises->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="authorized_no" form="franchise-filter-form" data-filter-scope="mobile" value="{{ request('authorized_no') }}" oninput="debouncedFetchFranchiseFilter()" placeholder="Search authorized no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="franchise-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchFranchiseFilter()" placeholder="Search owner name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="route" form="franchise-filter-form" data-filter-scope="mobile" value="{{ request('route') }}" oninput="debouncedFetchFranchiseFilter()" placeholder="Search route..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="purpose" form="franchise-filter-form" data-filter-scope="mobile" value="{{ request('purpose') }}" oninput="debouncedFetchFranchiseFilter()" placeholder="Search purpose..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" form="franchise-filter-form" data-filter-scope="mobile" onchange="debouncedFetchFranchiseFilter()"
                    class="w-full max-w-full truncate rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="New" @selected(request('status') === 'New')>New</option>
                    <option value="Renewed" @selected(request('status') === 'Renewed')>Renewed</option>
                    <option value="Expired" @selected(request('status') === 'Expired')>Expired</option>
                </select>
            </div>

            <!-- Mobile cards -->
            <div id="franchise-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($franchises as $franchise)
                    <x-franchise-mobile-card :franchise="$franchise" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveFranchiseFilters)
                            No franchises match your search.
                            <a href="{{ route('tricycle.franchise') }}" data-ajax-franchise-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No franchise records yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="franchise-pagination-mobile" class="lg:hidden mt-4">
                @unless ($franchises->isEmpty())
                    {{ $franchises->links() }}
                @endunless
            </div>

            <div id="franchise-edit-modals">
                <x-franchise-edit-modal :tricycles="$tricycles" />
            </div>
        </div>
    </div>
</div>
@endsection