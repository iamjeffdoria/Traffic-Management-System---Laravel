@extends('layouts.app')

@section('title', 'Kasabutan - Lost Item')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-kasabutan-lost-item" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Kasabutan - Lost Item" />

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

                {{-- Reopen the modal so the user can fix the fields (old values are kept) --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => openModal('create-kasabutan-lost-item-modal'));
                </script>
            @endif

            @if ($errors->lostItemUpdate->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3 max-h-64 overflow-y-auto">
                    <p class="font-semibold mb-1">The record could not be updated:</p>
                    <ul class="space-y-1">
                        @foreach ($errors->lostItemUpdate->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-kasabutan-lost-item-toolbar />

            <x-kasabutan-lost-item-create-modal />

            <x-kasabutan-lost-item-edit-modal />

            <x-kasabutan-lost-item-delete-confirm-modal />

            <form id="kasabutan-lost-item-filter-form" method="GET" action="{{ route('potpot.kasabutan.lost-item') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-28">
                        <col class="w-[17%]">
                        <col class="w-[9%]">
                        <col class="w-[17%]">
                        <col class="w-[10%]">
                        <col class="w-[12%]">
                        <col class="w-[14%]">
                        <col class="w-[13%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Actions</th>
                            <th class="px-6 py-3 font-bold">Driver / Address</th>
                            <th class="px-6 py-3 font-bold">Body No.</th>
                            <th class="px-6 py-3 font-bold">Passenger / Address</th>
                            <th class="px-6 py-3 font-bold">Amount</th>
                            <th class="px-6 py-3 font-bold">Date / Time</th>
                            <th class="px-6 py-3 font-bold">Witnesses</th>
                            <th class="px-6 py-3 font-bold">Issued At / Signatories</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="driver" form="kasabutan-lost-item-filter-form" data-filter-scope="desktop" value="{{ request('driver') }}" oninput="debouncedFetchKasabutanLostItemFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="body" form="kasabutan-lost-item-filter-form" data-filter-scope="desktop" value="{{ request('body') }}" oninput="debouncedFetchKasabutanLostItemFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="passenger" form="kasabutan-lost-item-filter-form" data-filter-scope="desktop" value="{{ request('passenger') }}" oninput="debouncedFetchKasabutanLostItemFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="witness" form="kasabutan-lost-item-filter-form" data-filter-scope="desktop" value="{{ request('witness') }}" oninput="debouncedFetchKasabutanLostItemFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    @include('admin.partials.kasabutan-lost-item-tbody')
                </table>
            </div>

            <div id="kasabutan-lost-item-pagination-desktop" class="hidden lg:block mt-4">
                @unless ($lostItems->isEmpty())
                    {{ $lostItems->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="driver" form="kasabutan-lost-item-filter-form" data-filter-scope="mobile" value="{{ request('driver') }}" oninput="debouncedFetchKasabutanLostItemFilter()" placeholder="Search driver..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="body" form="kasabutan-lost-item-filter-form" data-filter-scope="mobile" value="{{ request('body') }}" oninput="debouncedFetchKasabutanLostItemFilter()" placeholder="Search body no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="passenger" form="kasabutan-lost-item-filter-form" data-filter-scope="mobile" value="{{ request('passenger') }}" oninput="debouncedFetchKasabutanLostItemFilter()" placeholder="Search passenger..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="witness" form="kasabutan-lost-item-filter-form" data-filter-scope="mobile" value="{{ request('witness') }}" oninput="debouncedFetchKasabutanLostItemFilter()" placeholder="Search witness..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            @include('admin.partials.kasabutan-lost-item-cards')

            <div id="kasabutan-lost-item-pagination-mobile" class="lg:hidden mt-4">
                @unless ($lostItems->isEmpty())
                    {{ $lostItems->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection