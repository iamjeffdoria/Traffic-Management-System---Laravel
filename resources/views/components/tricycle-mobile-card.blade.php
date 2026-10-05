@props(['tricycle'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'renewed' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];

    $tricycleEditData = [
        'id' => $tricycle->id,
        'body_number' => $tricycle->body_number,
        'plate_no' => $tricycle->plate_no,
        'name' => $tricycle->name,
        'address' => $tricycle->address,
        'owner_number' => $tricycle->owner_number,
        'driver' => $tricycle->driver,
        'driver_number' => $tricycle->driver_number,
        'make_kind' => $tricycle->make_kind,
        'status' => $tricycle->status,
        'engine_motor_no' => $tricycle->engine_motor_no,
        'chassis_no' => $tricycle->chassis_no,
        'date_registered' => $tricycle->date_registered->format('Y-m-d'),
        'date_expired' => $tricycle->date_expired->format('Y-m-d'),
        'toda' => $tricycle->toda,
        'remarks' => $tricycle->remarks,
    ];
@endphp

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-gray-900 font-medium truncate">{{ $tricycle->body_number }} — {{ $tricycle->plate_no }}</p>
            <p class="text-gray-500 text-sm truncate">{{ $tricycle->name }}</p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full {{ $statusColors[$tricycle->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                    {{ ucfirst($tricycle->status) }}
                </span>
                <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">
                    {{ $tricycle->make_kind }}
                </span>
                @if ($tricycle->toda)
                    <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1">
                        {{ $tricycle->toda }}
                    </span>
                @endif
                @if ($tricycle->address)
                    <span class="inline-block rounded-full bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1">
                        {{ $tricycle->address }}
                    </span>
                @endif
                @if ($tricycle->owner_number)
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $tricycle->owner_number }}
                    </span>
                @endif
            </div>
            <div class="flex flex-wrap gap-1 mt-1.5">
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">
                    {{ $tricycle->engine_motor_no }}
                </span>
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">
                    {{ $tricycle->chassis_no }}
                </span>
            </div>
            <div class="flex items-center gap-1 mt-1.5">
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $tricycle->date_registered->format('M-d-y') }}
                </span>
                <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $tricycle->date_expired->format('M-d-y') }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button"
                onclick="openTricycleEditModal({{ Illuminate\Support\Js::from($tricycleEditData) }})"
                title="Edit"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <form id="delete-tricycle-form-mobile-{{ $tricycle->id }}" method="POST" action="{{ route('tricycle.destroy', $tricycle) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmDelete('delete-tricycle-form-mobile-{{ $tricycle->id }}', {{ Illuminate\Support\Js::from($tricycle->body_number . ' (' . $tricycle->plate_no . ')') }})" title="Delete"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>