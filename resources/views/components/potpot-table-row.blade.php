@props(['potpot'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'renewed' => 'bg-green-600',
        'expired' => 'bg-red-600',
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-semibold break-words">{{ $potpot->body_number }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $potpot->name }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-800 text-xs font-semibold break-words">{{ $potpot->address ?: '—' }}</p>
        @if ($potpot->contact_number)
            <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5" title="{{ $potpot->contact_number }}">
                {{ $potpot->contact_number }}
            </span>
        @endif
    </td>
    <td class="px-6 py-4 align-top">
        <span class="inline-block rounded-full {{ $statusColors[$potpot->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
            {{ ucfirst($potpot->status) }}
        </span>
    </td>
    <td class="px-6 py-4 align-top">
        <div class="flex flex-col gap-1">
            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ $potpot->date_registered->format('M-d-y') }}
            </span>
            <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                {{ $potpot->date_expired->format('M-d-y') }}
            </span>
        </div>
    </td>
    <td class="px-6 py-4 align-top">
        @if ($potpot->or_number)
            <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $potpot->or_number }}">
                {{ $potpot->or_number }}
            </span>
        @endif
        @if ($potpot->remarks)
            <p class="text-gray-500 text-xs break-words mt-1.5">{{ $potpot->remarks }}</p>
        @endif
    </td>
</tr>