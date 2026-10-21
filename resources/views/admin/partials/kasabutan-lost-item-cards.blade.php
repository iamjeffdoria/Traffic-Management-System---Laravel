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

            <p class="text-gray-500 text-xs mt-1.5">Witnesses: {{ $item->witness_1 }} ({{ $item->witness_1_type }}), {{ $item->witness_2 }} ({{ $item->witness_2_type }})</p>
            <p class="text-gray-500 text-xs">{{ $item->issued_at }}</p>
            <p class="text-gray-400 text-xs">Verified: {{ $item->it_section }} · Noted: {{ $item->security_officer }}</p>

            <div class="flex items-center gap-1 mt-2">
                <button type="button" title="Edit" onclick="openKasabutanLostItemEditModal({{ Illuminate\Support\Js::from(array_merge($item->only(['id', 'driver', 'body_number', 'driver_age', 'driver_address', 'amount', 'passenger', 'passenger_age', 'passenger_address', 'issued_at', 'witness_1', 'witness_2', 'witness_1_type', 'witness_2_type', 'it_section', 'security_officer']), ['date' => $item->date->format('Y-m-d'), 'time' => \Carbon\Carbon::parse($item->time)->format('H:i')])) }})" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.kasabutan.lost-item.print', $item) }}')" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                    </svg>
                </button>
                <form id="delete-kasabutan-lost-item-form-mobile-{{ $item->id }}" method="POST" action="{{ route('potpot.kasabutan.lost-item.destroy', $item) }}">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" title="Delete" onclick="confirmKasabutanLostItemDelete('delete-kasabutan-lost-item-form-mobile-{{ $item->id }}', {{ Illuminate\Support\Js::from('Kasabutan - ' . $item->driver . ' (' . $item->body_number . ')') }})" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
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