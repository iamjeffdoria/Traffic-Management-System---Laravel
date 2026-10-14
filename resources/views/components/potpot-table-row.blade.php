@props(['potpot'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'renewed' => 'bg-green-600',
        'expired' => 'bg-red-600',
    ];

    $potpotEditData = [
        'id' => $potpot->id,
        'body_number' => $potpot->body_number,
        'name' => $potpot->name,
        'address' => $potpot->address,
        'contact_number' => $potpot->contact_number,
        'or_number' => $potpot->or_number,
        'status' => $potpot->status,
        'date_registered' => $potpot->date_registered->format('Y-m-d'),
        'date_expired' => $potpot->date_expired->format('Y-m-d'),
        'remarks' => $potpot->remarks,
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" onclick="openPotpotEditModal({{ Illuminate\Support\Js::from($potpotEditData) }})" title="Edit"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <form id="delete-potpot-form-{{ $potpot->id }}" method="POST" action="{{ route('potpot.destroy', $potpot) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmPotpotDelete('delete-potpot-form-{{ $potpot->id }}', {{ Illuminate\Support\Js::from('Potpot ' . $potpot->body_number . ' (' . $potpot->name . ')') }})" title="Delete"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
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