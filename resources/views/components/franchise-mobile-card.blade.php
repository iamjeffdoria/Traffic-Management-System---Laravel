@props(['franchise'])

@php
    $statusColors = [
        'New' => 'bg-blue-600',
        'Renewed' => 'bg-green-600',
        'Expired' => 'bg-red-600',
    ];
    $statusColor = $statusColors[$franchise->status] ?? 'bg-slate-600';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            @if ($franchise->tricycle)
                <p class="text-gray-900 font-medium truncate">{{ $franchise->tricycle->name }}</p>
            @else
                <p class="text-gray-400 text-sm italic">Tricycle removed</p>
            @endif
            <p class="text-gray-500 text-sm truncate">{{ $franchise->authorized_no }}</p>
            <p class="text-gray-700 text-sm mt-1.5">{{ $franchise->authorized_route }}</p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full {{ $statusColor }} text-white text-xs font-semibold px-2.5 py-1">
                    {{ $franchise->status }}
                </span>
                @if ($franchise->tricycle)
                    <span class="inline-block rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $franchise->tricycle->body_number }}">
                        {{ $franchise->tricycle->body_number }}
                    </span>
                    <span class="inline-block rounded-full bg-indigo-600 text-white text-xs font-mono px-2.5 py-1">
                        {{ $franchise->tricycle->plate_no }}
                    </span>
                @endif
                <span class="inline-block rounded-full bg-teal-600 text-white text-xs font-mono px-2.5 py-1">
                    OR: {{ $franchise->official_receipt_no }}
                </span>
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $franchise->date->format('M-d-y') }}
                </span>
                <span class="inline-block rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    Valid until: {{ $franchise->valid_until->format('M-d-y') }}
                </span>
            </div>
            <p class="text-gray-500 text-xs mt-1.5">₱{{ number_format($franchise->amount_paid, 2) }}</p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" onclick="openModal('edit-franchise-modal-{{ $franchise->id }}')" title="Edit"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" onclick="printFromUrl('{{ route('tricycle.franchise.print', $franchise) }}')" title="Print"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-franchise-form-mobile-{{ $franchise->id }}" method="POST" action="{{ route('tricycle.franchise.destroy', $franchise) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmFranchiseDelete('delete-franchise-form-mobile-{{ $franchise->id }}', 'Franchise {{ $franchise->authorized_no }}')" title="Delete"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>