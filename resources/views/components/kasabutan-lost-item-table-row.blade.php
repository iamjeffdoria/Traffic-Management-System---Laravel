@props(['item'])

@php
    $lostItemEditData = array_merge(
        $item->only([
            'id', 'driver', 'body_number', 'driver_age', 'driver_address', 'amount',
            'passenger', 'passenger_age', 'passenger_address', 'issued_at',
            'witness_1', 'witness_2', 'witness_1_type', 'witness_2_type', 'it_section', 'security_officer',
        ]),
        [
            'date' => $item->date->format('Y-m-d'),
            'time' => \Carbon\Carbon::parse($item->time)->format('H:i'),
        ]
    );
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" title="Edit" onclick="openKasabutanLostItemEditModal({{ Illuminate\Support\Js::from($lostItemEditData) }})"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.kasabutan.lost-item.print', $item) }}')"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-kasabutan-lost-item-form-{{ $item->id }}" method="POST" action="{{ route('potpot.kasabutan.lost-item.destroy', $item) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" title="Delete"
                onclick="confirmKasabutanLostItemDelete('delete-kasabutan-lost-item-form-{{ $item->id }}', {{ Illuminate\Support\Js::from('Kasabutan - ' . $item->driver . ' (' . $item->body_number . ')') }})"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $item->driver }}</p>
        <div class="flex flex-wrap gap-1 mt-1.5">
            <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                {{ $item->driver_age }} yrs
            </span>
            <span class="inline-block max-w-full truncate rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $item->driver_address }}">
                {{ $item->driver_address }}
            </span>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $item->body_number }}">
            {{ $item->body_number }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $item->passenger }}</p>
        <div class="flex flex-wrap gap-1 mt-1.5">
            <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                {{ $item->passenger_age }} yrs
            </span>
            <span class="inline-block max-w-full truncate rounded-full bg-indigo-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $item->passenger_address }}">
                {{ $item->passenger_address }}
            </span>
        </div>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap" title="₱{{ number_format($item->amount, 2) }}">
            ₱{{ number_format($item->amount, 2) }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <div class="flex flex-col gap-1">
            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ $item->date->format('M-d-y') }}
            </span>
            <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ \Carbon\Carbon::parse($item->time)->format('g:i A') }}
            </span>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-sm break-words">1. {{ $item->witness_1 }}</p>
        <span class="inline-block max-w-full truncate rounded-full {{ $item->witness_1_type === 'PSO Personnel' ? 'bg-blue-600' : 'bg-amber-500' }} text-white text-xs font-semibold px-2.5 py-1 mt-1" title="{{ $item->witness_1_type }}">
            {{ $item->witness_1_type }}
        </span>
        <p class="text-gray-900 text-sm break-words mt-2">2. {{ $item->witness_2 }}</p>
        <span class="inline-block max-w-full truncate rounded-full {{ $item->witness_2_type === 'PSO Personnel' ? 'bg-blue-600' : 'bg-amber-500' }} text-white text-xs font-semibold px-2.5 py-1 mt-1" title="{{ $item->witness_2_type }}">
            {{ $item->witness_2_type }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-xs font-semibold break-words">{{ $item->issued_at }}</p>
        <p class="text-gray-500 text-xs break-words mt-1">Verified: {{ $item->it_section }}</p>
        <p class="text-gray-500 text-xs break-words">Noted: {{ $item->security_officer }}</p>
    </td>
</tr>