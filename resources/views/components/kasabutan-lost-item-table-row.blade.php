@props(['item'])

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
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
        <p class="text-gray-900 text-sm break-words mt-0.5">2. {{ $item->witness_2 }}</p>
    </td>
    <td class="px-6 py-4 align-top">
        <p class="text-gray-900 text-xs font-semibold break-words">{{ $item->issued_at }}</p>
        <p class="text-gray-500 text-xs break-words mt-1">Verified: {{ $item->it_section }}</p>
        <p class="text-gray-500 text-xs break-words">Noted: {{ $item->security_officer }}</p>
    </td>
</tr>