@extends('layouts.app')

@section('title', 'Potpot List')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-list" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Potpot List" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3 max-h-64 overflow-y-auto">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-potpot-toolbar />

            <x-potpot-create-modal />

            <x-potpot-import-modal />

            <x-potpot-edit-modal />

            <x-potpot-delete-confirm-modal />

            <form id="potpot-filter-form" method="GET" action="{{ route('potpot.list') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-24">
                        <col class="w-[11%]">
                        <col class="w-[18%]">
                        <col class="w-[16%]">
                        <col class="w-[13%]">
                        <col class="w-[14%]">
                        <col class="w-[18%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-24">Actions</th>
                            <th class="px-6 py-3 font-bold">Body No.</th>
                            <th class="px-6 py-3 font-bold">Operator</th>
                            <th class="px-6 py-3 font-bold">Address / Numbers</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold">Registration / Expiry</th>
                            <th class="px-6 py-3 font-bold">OR No. / Remarks</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="body" form="potpot-filter-form" data-filter-scope="desktop" value="{{ request('body') }}" oninput="debouncedFetchPotpotFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="name" form="potpot-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchPotpotFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="address" form="potpot-filter-form" data-filter-scope="desktop" value="{{ request('address') }}" oninput="debouncedFetchPotpotFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-1.5 py-2">
                                <select name="status" form="potpot-filter-form" data-filter-scope="desktop" onchange="debouncedFetchPotpotFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium pl-1.5 pr-0.5 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                    <option value="">All</option>
                                    <option value="active" @selected(request('status') === 'active')>Active</option>
                                    <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                                </select>
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    @include('admin.partials.potpot-tbody')
                </table>
            </div>

            <div id="potpot-pagination-desktop" class="hidden lg:block mt-4">
                @unless ($potpots->isEmpty())
                    {{ $potpots->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="body" form="potpot-filter-form" data-filter-scope="mobile" value="{{ request('body') }}" oninput="debouncedFetchPotpotFilter()" placeholder="Search body no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="potpot-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchPotpotFilter()" placeholder="Search operator..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="address" form="potpot-filter-form" data-filter-scope="mobile" value="{{ request('address') }}" oninput="debouncedFetchPotpotFilter()" placeholder="Search address..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" form="potpot-filter-form" data-filter-scope="mobile" onchange="debouncedFetchPotpotFilter()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                </select>
            </div>

            @include('admin.partials.potpot-cards')

            <div id="potpot-pagination-mobile" class="lg:hidden mt-4">
                @unless ($potpots->isEmpty())
                    {{ $potpots->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection