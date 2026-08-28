@extends('layouts.app')

@section('title', "Mayor's Permit - Potpot")

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-mayors-permit" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Mayor's Permit - Potpot" />

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

            <x-potpot-mayors-permit-toolbar />

            <x-potpot-mayors-permit-create-modal />

            <x-potpot-mayors-permit-delete-confirm-modal />

            @php
                $hasActivePotpotPermitFilters = request()->filled('control_no') || request()->filled('name') || request()->filled('business_name') || request()->filled('status');
            @endphp

            <form id="potpot-mayors-permit-filter-form" method="GET" action="{{ route('potpot.mayors-permit') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-32">
                        <col class="w-[12%]">
                        <col class="w-[12%]">
                        <col class="w-[13%]">
                        <col class="w-[11%]">
                        <col class="w-[11%]">
                        <col class="w-[16%]">
                        <col class="w-[15%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-32">Actions</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold">Control No.</th>
                            <th class="px-6 py-3 font-bold">Name / Address</th>
                            <th class="px-6 py-3 font-bold">Business / Operation</th>
                            <th class="px-6 py-3 font-bold">Amount Paid</th>
                            <th class="px-6 py-3 font-bold">Issue / Expiry</th>
                            <th class="px-6 py-3 font-bold">Mayor / Issued At / Quarter</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-1.5 py-2">
                                <select name="status" form="potpot-mayors-permit-filter-form" data-filter-scope="desktop" onchange="debouncedFetchPotpotPermitFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium pl-1.5 pr-0.5 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                    <option value="">All statuses</option>
                                    <option value="active" @selected(request('status') === 'active')>Active</option>
                                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                                </select>
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="control_no" form="potpot-mayors-permit-filter-form" data-filter-scope="desktop" value="{{ request('control_no') }}" oninput="debouncedFetchPotpotPermitFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="name" form="potpot-mayors-permit-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchPotpotPermitFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="business_name" form="potpot-mayors-permit-filter-form" data-filter-scope="desktop" value="{{ request('business_name') }}" oninput="debouncedFetchPotpotPermitFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="potpot-mayors-permit-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($permits as $permit)
                            <x-potpot-mayors-permit-table-row :permit="$permit" />
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActivePotpotPermitFilters)
                                        No permits match your search.
                                        <a href="{{ route('potpot.mayors-permit') }}" data-ajax-potpot-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No permits issued yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="potpot-mayors-permit-pagination-desktop" class="mt-4">
                {{ $permits->links() }}
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="control_no" form="potpot-mayors-permit-filter-form" data-filter-scope="mobile" value="{{ request('control_no') }}" oninput="debouncedFetchPotpotPermitFilter()" placeholder="Search control no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="potpot-mayors-permit-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchPotpotPermitFilter()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="business_name" form="potpot-mayors-permit-filter-form" data-filter-scope="mobile" value="{{ request('business_name') }}" oninput="debouncedFetchPotpotPermitFilter()" placeholder="Search business name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" form="potpot-mayors-permit-filter-form" data-filter-scope="mobile" onchange="debouncedFetchPotpotPermitFilter()"
                    class="w-full max-w-full truncate rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                </select>
            </div>

            <!-- Mobile stacked cards -->
            <div id="potpot-mayors-permit-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($permits as $permit)
                    <x-potpot-mayors-permit-mobile-card :permit="$permit" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActivePotpotPermitFilters)
                            No permits match your search.
                            <a href="{{ route('potpot.mayors-permit') }}" data-ajax-potpot-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No permits issued yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="potpot-mayors-permit-pagination-mobile" class="lg:hidden mt-4">
                {{ $permits->links() }}
            </div>

            <div id="potpot-mayors-permit-edit-modals">
                <x-potpot-mayors-permit-edit-modal />
            </div>
        </div>
    </div>
</div>
@endsection