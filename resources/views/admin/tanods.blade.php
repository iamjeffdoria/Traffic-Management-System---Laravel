@extends('layouts.app')

@section('title', 'Tanod List')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tanod-list" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Tanod List" />

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
                    document.addEventListener('DOMContentLoaded', () => openModal('create-tanod-modal'));
                </script>
            @endif

            @if ($errors->tanodUpdate->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3 max-h-64 overflow-y-auto">
                    <p class="font-semibold mb-1">The record could not be updated:</p>
                    <ul class="space-y-1">
                        @foreach ($errors->tanodUpdate->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-tanod-toolbar />

            <x-tanod-create-modal />

            <x-tanod-edit-modal />

            <x-tanod-delete-confirm-modal />

            <form id="tanod-filter-form" method="GET" action="{{ route('tanod.list') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-24">
                        <col class="w-[13%]">
                        <col class="w-[22%]">
                        <col class="w-[20%]">
                        <col class="w-[15%]">
                        <col class="w-[22%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold w-24">Actions</th>
                            <th class="px-6 py-3 font-bold">ID No.</th>
                            <th class="px-6 py-3 font-bold">Name / Position</th>
                            <th class="px-6 py-3 font-bold">Barangay / Chairman</th>
                            <th class="px-6 py-3 font-bold">Contact</th>
                            <th class="px-6 py-3 font-bold">Emergency Contact</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="id_number" form="tanod-filter-form" data-filter-scope="desktop" value="{{ request('id_number') }}" oninput="debouncedFetchTanodFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="name" form="tanod-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchTanodFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="barangay" form="tanod-filter-form" data-filter-scope="desktop" value="{{ request('barangay') }}" oninput="debouncedFetchTanodFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    @include('admin.partials.tanod-tbody')
                </table>
            </div>

            <div id="tanod-pagination-desktop" class="hidden lg:block mt-4">
                @unless ($tanods->isEmpty())
                    {{ $tanods->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="id_number" form="tanod-filter-form" data-filter-scope="mobile" value="{{ request('id_number') }}" oninput="debouncedFetchTanodFilter()" placeholder="Search ID number..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="tanod-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchTanodFilter()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="barangay" form="tanod-filter-form" data-filter-scope="mobile" value="{{ request('barangay') }}" oninput="debouncedFetchTanodFilter()" placeholder="Search barangay..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            @include('admin.partials.tanod-cards')

            <div id="tanod-pagination-mobile" class="lg:hidden mt-4">
                @unless ($tanods->isEmpty())
                    {{ $tanods->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection