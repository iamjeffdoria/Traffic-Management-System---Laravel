@extends('layouts.app')

@section('title', 'Kasabutan - Selling')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-kasabutan-selling" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Kasabutan - Selling" />

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
                    document.addEventListener('DOMContentLoaded', () => openModal('create-kasabutan-selling-modal'));
                </script>
            @endif

            <x-kasabutan-selling-toolbar />

            <x-kasabutan-selling-create-modal :potpots="$potpots" />

            <x-kasabutan-selling-edit-modal :potpots="$potpots" />

            <x-kasabutan-selling-delete-confirm-modal />

            @php
                $hasActiveSellingFilters = request()->filled('owner') || request()->filled('body') || request()->filled('buyer') || request()->filled('witness');
            @endphp

            <form id="kasabutan-selling-filter-form" method="GET" action="{{ route('potpot.kasabutan.selling') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-28">
                        <col class="w-[18%]">
                        <col class="w-[10%]">
                        <col class="w-[20%]">
                        <col class="w-[15%]">
                        <col class="w-[14%]">
                        <col class="w-[12%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Actions</th>
                            <th class="px-6 py-3 font-bold">Owner / Address</th>
                            <th class="px-6 py-3 font-bold">Body No.</th>
                            <th class="px-6 py-3 font-bold">Buyer / Address</th>
                            <th class="px-6 py-3 font-bold">Amount</th>
                            <th class="px-6 py-3 font-bold">Witness</th>
                            <th class="px-6 py-3 font-bold">Date</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="owner" form="kasabutan-selling-filter-form" data-filter-scope="desktop" value="{{ request('owner') }}" oninput="debouncedFetchKasabutanSellingFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="body" form="kasabutan-selling-filter-form" data-filter-scope="desktop" value="{{ request('body') }}" oninput="debouncedFetchKasabutanSellingFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="buyer" form="kasabutan-selling-filter-form" data-filter-scope="desktop" value="{{ request('buyer') }}" oninput="debouncedFetchKasabutanSellingFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="witness" form="kasabutan-selling-filter-form" data-filter-scope="desktop" value="{{ request('witness') }}" oninput="debouncedFetchKasabutanSellingFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="kasabutan-selling-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($sellings as $selling)
                            <x-kasabutan-selling-table-row :selling="$selling" />
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveSellingFilters)
                                        No Kasabutan - Selling records match your search.
                                        <a href="{{ route('potpot.kasabutan.selling') }}" data-ajax-kasabutan-selling-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No Kasabutan - Selling records yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="kasabutan-selling-pagination-desktop" class="hidden lg:block mt-4">
                @unless ($sellings->isEmpty())
                    {{ $sellings->links() }}
                @endunless
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="owner" form="kasabutan-selling-filter-form" data-filter-scope="mobile" value="{{ request('owner') }}" oninput="debouncedFetchKasabutanSellingFilter()" placeholder="Search owner..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="body" form="kasabutan-selling-filter-form" data-filter-scope="mobile" value="{{ request('body') }}" oninput="debouncedFetchKasabutanSellingFilter()" placeholder="Search body no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="buyer" form="kasabutan-selling-filter-form" data-filter-scope="mobile" value="{{ request('buyer') }}" oninput="debouncedFetchKasabutanSellingFilter()" placeholder="Search buyer..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="witness" form="kasabutan-selling-filter-form" data-filter-scope="mobile" value="{{ request('witness') }}" oninput="debouncedFetchKasabutanSellingFilter()" placeholder="Search witness..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <!-- Mobile cards -->
            <div id="kasabutan-selling-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($sellings as $selling)
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
                        <p class="text-gray-900 font-medium truncate">{{ $selling->owner }}</p>
                        <span class="inline-block max-w-full truncate rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1 mt-1" title="{{ $selling->barangay }}, {{ $selling->municipality }}">{{ $selling->barangay }}, {{ $selling->municipality }}</span>
                        <p class="text-gray-700 text-sm mt-1.5">Sold to <span class="font-medium">{{ $selling->buyer }}</span></p>
                        <span class="inline-block max-w-full truncate rounded-full bg-indigo-600 text-white text-xs font-semibold px-2.5 py-1 mt-1" title="{{ $selling->buyer_address }}">{{ $selling->buyer_address }}</span>
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="inline-block rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1">{{ $selling->body_number }}</span>
                            <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">₱{{ number_format($selling->amount, 2) }}</span>
                            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $selling->date->format('M-d-y') }}</span>
                        </div>
                        <p class="text-gray-500 text-xs mt-1.5">Witness: {{ $selling->witness }}@if ($selling->secretariat) · Secretariat: {{ $selling->secretariat }}@endif</p>
                        <div class="flex items-center gap-1 mt-2">
                            <button type="button" title="Edit" onclick="openKasabutanSellingEditModal({{ Illuminate\Support\Js::from(array_merge($selling->only(['id', 'owner', 'barangay', 'municipality', 'body_number', 'buyer', 'buyer_address', 'amount', 'witness', 'secretariat']), ['date' => $selling->date->format('Y-m-d')])) }})" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.kasabutan.selling.print', $selling) }}')" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                                </svg>
                            </button>
                            <form id="delete-kasabutan-selling-form-mobile-{{ $selling->id }}" method="POST" action="{{ route('potpot.kasabutan.selling.destroy', $selling) }}">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" title="Delete" onclick="confirmKasabutanSellingDelete('delete-kasabutan-selling-form-mobile-{{ $selling->id }}', {{ Illuminate\Support\Js::from('Kasabutan - ' . $selling->owner . ' (' . $selling->body_number . ')') }})" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveSellingFilters)
                            No Kasabutan - Selling records match your search.
                            <a href="{{ route('potpot.kasabutan.selling') }}" data-ajax-kasabutan-selling-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No Kasabutan - Selling records yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="kasabutan-selling-pagination-mobile" class="lg:hidden mt-4">
                @unless ($sellings->isEmpty())
                    {{ $sellings->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection