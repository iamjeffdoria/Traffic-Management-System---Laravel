@php
    $hasActiveLostItemFilters = request()->filled('driver') || request()->filled('body') || request()->filled('passenger') || request()->filled('witness');
@endphp

<div id="kasabutan-lost-item-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($lostItems as $item)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <p class="text-gray-900 font-medium truncate min-w-0">{{ $item->driver }}</p>
                <span class="inline-block rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1 shrink-0">{{ $item->body_number }}</span>
            </div>
            <div class="flex flex-wrap gap-1 mt-1">
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $item->driver_age }} yrs</span>
                <span class="inline-block max-w-full truncate rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $item->driver_address }}">{{ $item->driver_address }}</span>
            </div>

            <p class="text-gray-700 text-sm mt-2">Passenger: <span class="font-medium">{{ $item->passenger }}</span></p>
            <div class="flex flex-wrap gap-1 mt-1">
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $item->passenger_age }} yrs</span>
                <span class="inline-block max-w-full truncate rounded-full bg-indigo-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $item->passenger_address }}">{{ $item->passenger_address }}</span>
            </div>

            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">₱{{ number_format($item->amount, 2) }}</span>
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $item->date->format('M-d-y') }}</span>
                <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->time)->format('g:i A') }}</span>
            </div>

            <p class="text-gray-500 text-xs mt-1.5">Witnesses: {{ $item->witness_1 }}, {{ $item->witness_2 }}</p>
            <p class="text-gray-500 text-xs">{{ $item->issued_at }}</p>
            <p class="text-gray-400 text-xs">Verified: {{ $item->it_section }} · Noted: {{ $item->security_officer }}</p>
        </div>
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActiveLostItemFilters)
                No Kasabutan - Lost Item records match your search.
                <a href="{{ route('potpot.kasabutan.lost-item') }}" data-ajax-kasabutan-lost-item-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No Kasabutan - Lost Item records yet.
            @endif
        </div>
    @endforelse
</div>