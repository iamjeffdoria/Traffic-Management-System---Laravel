@props(['selling'])

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $selling->owner }}</p>
        <p class="text-gray-500 text-xs break-words mt-1">{{ $selling->barangay }}, {{ $selling->municipality }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $selling->body_number }}">
            {{ $selling->body_number }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $selling->buyer }}</p>
        <p class="text-gray-500 text-xs break-words mt-1">{{ $selling->buyer_address }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
            ₱{{ number_format($selling->amount, 2) }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-sm break-words">{{ $selling->witness }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
            {{ $selling->date->format('M-d-y') }}
        </span>
    </td>
</tr>