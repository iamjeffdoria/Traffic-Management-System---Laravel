@php
    $hasActiveSellingFilters = request()->filled('owner') || request()->filled('body') || request()->filled('buyer') || request()->filled('witness');
@endphp

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

<div id="kasabutan-selling-pagination-desktop" class="hidden lg:block mt-4">
    @unless ($sellings->isEmpty())
        {{ $sellings->links() }}
    @endunless
</div>

<div id="kasabutan-selling-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($sellings as $selling)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <p class="text-gray-900 font-medium truncate">{{ $selling->owner }}</p>
            <p class="text-gray-500 text-sm truncate">{{ $selling->barangay }}, {{ $selling->municipality }}</p>
            <p class="text-gray-700 text-sm mt-1.5">Sold to <span class="font-medium">{{ $selling->buyer }}</span> — {{ $selling->buyer_address }}</p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1">{{ $selling->body_number }}</span>
                <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">₱{{ number_format($selling->amount, 2) }}</span>
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $selling->date->format('M-d-y') }}</span>
            </div>
            <p class="text-gray-500 text-xs mt-1.5">Witness: {{ $selling->witness }}</p>
            <div class="flex items-center gap-1 mt-2">
                <button type="button" title="Edit" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button type="button" title="Print" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                    </svg>
                </button>
                <button type="button" title="Delete" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
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