@props(['franchise'])

@php
    $statusColors = [
        'New' => 'bg-blue-600',
        'Renewed' => 'bg-green-600',
        'Expired' => 'bg-red-600',
    ];
    $statusColor = $statusColors[$franchise->status] ?? 'bg-slate-600';
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" onclick="openModal('edit-franchise-modal-{{ $franchise->id }}')" title="Edit"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" onclick="printFromUrl('{{ route('tricycle.franchise.print', $franchise) }}')" title="Print"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-franchise-form-{{ $franchise->id }}" method="POST" action="{{ route('tricycle.franchise.destroy', $franchise) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmFranchiseDelete('delete-franchise-form-{{ $franchise->id }}', 'Franchise {{ $franchise->authorized_no }}')" title="Delete"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 font-semibold break-words">{{ $franchise->authorized_no }}</p>
        <span class="inline-block max-w-full truncate rounded-full {{ $statusColor }} text-white text-xs font-semibold px-2.5 py-1 mt-1.5">
            {{ $franchise->status }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        @if ($franchise->tricycle)
            <p class="text-gray-900 font-medium break-words">{{ $franchise->tricycle->name }}</p>
            <div class="flex flex-wrap gap-1 mt-1.5">
                <span class="inline-block max-w-full truncate rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $franchise->tricycle->body_number }}">
                    {{ $franchise->tricycle->body_number }}
                </span>
                <span class="inline-block max-w-full truncate rounded-full bg-indigo-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $franchise->tricycle->plate_no }}">
                    {{ $franchise->tricycle->plate_no }}
                </span>
            </div>
        @else
            <p class="text-gray-400 text-xs italic">Tricycle removed</p>
        @endif
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 text-sm break-words">{{ $franchise->authorized_route }}</p>
        @if ($franchise->purpose)
            <span class="inline-block max-w-full truncate rounded-full bg-amber-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5" title="{{ $franchise->purpose }}">
                {{ $franchise->purpose }}
            </span>
        @endif
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 text-sm font-semibold">₱{{ number_format($franchise->amount_paid, 2) }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-teal-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5" title="OR: {{ $franchise->official_receipt_no }}">
            OR: {{ $franchise->official_receipt_no }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
            {{ $franchise->date->format('M-d-y') }}
        </span>
        <span class="inline-block max-w-full truncate rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5 whitespace-nowrap">
            Valid until: {{ $franchise->valid_until->format('M-d-y') }}
        </span>
    </td>
</tr>