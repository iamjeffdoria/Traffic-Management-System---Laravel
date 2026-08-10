@php
    $hasActiveFilters = request()->filled('body') || request()->filled('plate') || request()->filled('name') || request()->filled('status');
    $statusColors = [
        'active' => 'bg-teal-500',
        'renewed' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];
@endphp

<tbody id="tricycle-tbody-desktop" class="divide-y divide-gray-100">
    @forelse ($tricycles as $tricycle)
        <tr class="divide-x divide-gray-100 hover:bg-gray-50/60 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                    <button type="button" onclick="openModal('edit-tricycle-modal-{{ $tricycle->id }}')" title="Edit"
                        class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form id="delete-tricycle-form-{{ $tricycle->id }}" method="POST" action="{{ route('tricycle.destroy', $tricycle) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" onclick="confirmDelete('delete-tricycle-form-{{ $tricycle->id }}', '{{ $tricycle->body_number }} ({{ $tricycle->plate_no }})')" title="Delete"
                        class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-gray-900 font-medium">{{ $tricycle->body_number }}</p>
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5">
                    {{ $tricycle->chassis_no }}
                </span>
            </td>
            <td class="px-6 py-4">
                <p class="text-gray-900 font-semibold">{{ $tricycle->plate_no }}</p>
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5">
                    {{ $tricycle->engine_motor_no }}
                </span>
            </td>
            <td class="px-6 py-4">
                <p class="text-gray-900 font-medium">{{ $tricycle->name }}</p>
            </td>
            <td class="px-6 py-4">
                @if ($tricycle->address)
                    <p class="text-gray-800 text-xs font-semibold mb-1">{{ $tricycle->address }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-1">
                    <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">
                        {{ $tricycle->make_kind }}
                    </span>
                    @if ($tricycle->toda)
                        <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1">
                            {{ $tricycle->toda }}
                        </span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-block rounded-full {{ $statusColors[$tricycle->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                    {{ ucfirst($tricycle->status) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex flex-col gap-1">
                    <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                        {{ $tricycle->date_registered->format('M-d-y') }}
                    </span>
                    <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                        {{ $tricycle->date_expired->format('M-d-y') }}
                    </span>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveFilters)
                    No tricycles match your search.
                    <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No tricycles registered yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="tricycle-pagination-desktop" class="mt-4">
    @unless ($tricycles->isEmpty())
        {{ $tricycles->links() }}
    @endunless
</div>

<div id="tricycle-cards-mobile" class="lg:hidden space-y-3">
    @forelse ($tricycles as $tricycle)
        <div class="rounded-2xl border border-gray-200 p-4">
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
                    <button type="button" onclick="openModal('edit-tricycle-modal-{{ $tricycle->id }}')" title="Edit"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form id="delete-tricycle-form-mobile-{{ $tricycle->id }}" method="POST" action="{{ route('tricycle.destroy', $tricycle) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" onclick="confirmDelete('delete-tricycle-form-mobile-{{ $tricycle->id }}', '{{ $tricycle->body_number }} ({{ $tricycle->plate_no }})')" title="Delete"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-gray-200 p-10 text-center text-gray-500 text-sm">
            @if ($hasActiveFilters)
                No tricycles match your search.
                <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No tricycles registered yet.
            @endif
        </div>
    @endforelse
</div>

<div id="tricycle-pagination-mobile" class="lg:hidden mt-4">
    @unless ($tricycles->isEmpty())
        {{ $tricycles->links() }}
    @endunless
</div>

<div id="tricycle-edit-modals">
    @foreach ($tricycles as $tricycle)
        <x-tricycle-edit-modal :tricycle="$tricycle" />
    @endforeach
</div>