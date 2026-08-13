@props(['permit'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-6 py-4">
        <span class="inline-block rounded-full {{ $statusColors[$permit->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
            {{ ucfirst($permit->status) }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-semibold break-words">{{ $permit->control_no }}</p>
        <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5">
            {{ $permit->or_no }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        @if ($permit->tricycle)
            <p class="text-gray-900 font-medium break-words">{{ $permit->tricycle->body_number }}</p>
            <p class="text-gray-500 text-xs break-words">{{ $permit->tricycle->plate_no }}</p>
            <p class="text-gray-700 text-xs mt-1 break-words">{{ $permit->tricycle->name }}</p>
        @else
            <p class="text-gray-400 text-xs italic">Tricycle removed</p>
        @endif
    </td>
    <td class="px-6 py-4 align-top">
        @if ($permit->business_name && $permit->business_name !== 'none')
            <p class="text-gray-800 text-xs font-semibold mb-1 break-words">{{ $permit->business_name }}</p>
        @endif
        <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 max-w-full truncate" title="{{ $permit->motorized_operation }}">
            {{ $permit->motorized_operation }}
        </span>
    </td>
    <td class="px-6 py-4">
        <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">
            ₱{{ number_format($permit->amount_paid, 2) }}
        </span>
    </td>
    <td class="px-6 py-4">
        <div class="flex flex-col gap-1">
            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ $permit->issue_date->format('M-d-y') }}
            </span>
            <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ $permit->expiry_date->format('M-d-y') }}
            </span>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-xs font-semibold break-words">{{ $permit->mayor }}</p>
        <p class="text-gray-500 text-xs break-words">{{ $permit->issued_at }}</p>
        <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1 mt-1">
            {{ $permit->quarter }}
        </span>
    </td>
</tr>