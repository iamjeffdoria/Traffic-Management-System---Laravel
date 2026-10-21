@props(['selling'])

@php
    $sellingEditData = array_merge(
        $selling->only(['id', 'owner', 'barangay', 'municipality', 'body_number', 'buyer', 'buyer_address', 'amount', 'witness', 'witness_type', 'secretariat']),
        ['date' => $selling->date->format('Y-m-d')]
    );
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" title="Edit" onclick="openKasabutanSellingEditModal({{ Illuminate\Support\Js::from($sellingEditData) }})"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.kasabutan.selling.print', $selling) }}')"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-kasabutan-selling-form-{{ $selling->id }}" method="POST" action="{{ route('potpot.kasabutan.selling.destroy', $selling) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" title="Delete"
                onclick="confirmKasabutanSellingDelete('delete-kasabutan-selling-form-{{ $selling->id }}', {{ Illuminate\Support\Js::from('Kasabutan - ' . $selling->owner . ' (' . $selling->body_number . ')') }})"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $selling->owner }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5" title="{{ $selling->barangay }}, {{ $selling->municipality }}">
            {{ $selling->barangay }}, {{ $selling->municipality }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $selling->body_number }}">
            {{ $selling->body_number }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $selling->buyer }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-indigo-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5" title="{{ $selling->buyer_address }}">
            {{ $selling->buyer_address }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap" title="₱{{ number_format($selling->amount, 2) }}">
            ₱{{ number_format($selling->amount, 2) }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-sm break-words">{{ $selling->witness }}</p>
        @if ($selling->witness_type)
            <span class="inline-block max-w-full truncate rounded-full {{ $selling->witness_type === 'PSO Personnel' ? 'bg-blue-600' : 'bg-amber-500' }} text-white text-xs font-semibold px-2.5 py-1 mt-1" title="{{ $selling->witness_type }}">
                {{ $selling->witness_type }}
            </span>
        @endif
        @if ($selling->secretariat)
            <p class="text-gray-500 text-xs break-words mt-1">Secretariat: {{ $selling->secretariat }}</p>
        @endif
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
            {{ $selling->date->format('M-d-y') }}
        </span>
    </td>
</tr>