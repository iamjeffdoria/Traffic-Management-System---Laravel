@props(['mtop'])

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            @if ($mtop->tricycle)
                <p class="text-gray-900 font-medium truncate">{{ $mtop->tricycle->name }}</p>
                <p class="text-gray-500 text-sm truncate">{{ $mtop->case_no }}</p>
            @else
                <p class="text-gray-900 font-medium truncate">{{ $mtop->case_no }}</p>
            @endif
            <p class="text-gray-700 text-sm mt-1.5">{{ $mtop->route_operation }}</p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full bg-orange-600 text-white text-xs font-semibold px-2.5 py-1">
                    {{ $mtop->no_of_units }} {{ Str::plural('unit', $mtop->no_of_units) }}
                </span>
                @if ($mtop->tricycle)
                    <span class="inline-block rounded-full bg-indigo-600 text-white text-xs font-mono px-2.5 py-1">
                        {{ $mtop->tricycle->body_number }}
                    </span>
                    <span class="inline-block rounded-full bg-indigo-600 text-white text-xs font-mono px-2.5 py-1">
                        {{ $mtop->tricycle->plate_no }}
                    </span>
                @endif
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $mtop->date->format('M-d-y') }}
                </span>
            </div>
            <p class="text-gray-500 text-xs mt-1.5">{{ $mtop->mayor }}</p>
            <p class="text-gray-500 text-xs">Treasurer: {{ $mtop->municipal_treasurer }} · Officer: {{ $mtop->officer_in_charge }}</p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" title="Edit"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" title="Delete"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>