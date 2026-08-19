@props(['permit'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" onclick="openModal('edit-tricycle-mayors-permit-modal-{{ $permit->id }}')" title="Edit"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <form id="delete-permit-form-{{ $permit->id }}" method="POST" action="{{ route('tricycle.mayors-permit.destroy', $permit) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmPermitDelete('delete-permit-form-{{ $permit->id }}', 'Permit {{ $permit->control_no }}')" title="Delete"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full {{ $statusColors[$permit->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
            {{ ucfirst($permit->status) }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 font-semibold break-words">{{ $permit->control_no }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5" title="{{ $permit->or_no }}">
            {{ $permit->or_no }}
        </span>
    </td>
        <td class="px-4 py-4 align-top">
        @if ($permit->tricycle)
            <p class="text-gray-900 font-medium break-words">{{ $permit->tricycle->name }}</p>
            <div class="flex flex-wrap gap-1 mt-1.5">
                <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $permit->tricycle->body_number }}">
                    {{ $permit->tricycle->body_number }}
                </span>
                <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $permit->tricycle->plate_no }}">
                    {{ $permit->tricycle->plate_no }}
                </span>
            </div>
        @else
            <p class="text-gray-400 text-xs italic">Tricycle removed</p>
        @endif
    </td>
    <td class="px-4 py-4 align-top">
        @if ($permit->business_name && $permit->business_name !== 'none')
            <p class="text-gray-800 text-xs font-semibold mb-1 break-words">{{ $permit->business_name }}</p>
        @endif
        <span class="inline-block max-w-full truncate rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $permit->motorized_operation }}">
            {{ $permit->motorized_operation }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">
            ₱{{ number_format($permit->amount_paid, 2) }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <div class="flex flex-col gap-1 max-w-full">
            <span class="inline-block max-w-full truncate rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit">
                {{ $permit->issue_date->format('M-d-y') }}
            </span>
            <span class="inline-block max-w-full truncate rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 w-fit">
                {{ $permit->expiry_date->format('M-d-y') }}
            </span>
        </div>
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 text-xs font-semibold break-words">{{ $permit->mayor }}</p>
        <p class="text-gray-500 text-xs break-words">{{ $permit->issued_at }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1 mt-1">
            {{ $permit->quarter }}
        </span>
    </td>
</tr>