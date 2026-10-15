@php
    $hasActivePotpotFilters = request()->filled('body') || request()->filled('name') || request()->filled('address') || request()->filled('status');
    $potpotStatusColors = ['active' => 'bg-teal-500', 'renewed' => 'bg-green-600', 'expired' => 'bg-red-600'];
@endphp

<div id="potpot-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($potpots as $potpot)
        @php
            $potpotEditData = [
                'id' => $potpot->id,
                'body_number' => $potpot->body_number,
                'name' => $potpot->name,
                'driver' => $potpot->driver,
                'address' => $potpot->address,
                'contact_number' => $potpot->contact_number,
                'or_number' => $potpot->or_number,
                'status' => $potpot->status,
                'date_registered' => $potpot->date_registered->format('Y-m-d'),
                'date_expired' => $potpot->date_expired->format('Y-m-d'),
                'remarks' => $potpot->remarks,
            ];
        @endphp
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <p class="text-gray-900 font-medium truncate min-w-0">{{ $potpot->body_number }} — {{ $potpot->name }}</p>
                <div class="flex items-center gap-1 shrink-0">
                    <button type="button" onclick="openPotpotEditModal({{ Illuminate\Support\Js::from($potpotEditData) }})" title="Edit"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form id="delete-potpot-form-mobile-{{ $potpot->id }}" method="POST" action="{{ route('potpot.destroy', $potpot) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" onclick="confirmPotpotDelete('delete-potpot-form-mobile-{{ $potpot->id }}', {{ Illuminate\Support\Js::from('Potpot ' . $potpot->body_number . ' (' . $potpot->name . ')') }})" title="Delete"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            @if ($potpot->address)
                <p class="text-gray-500 text-sm truncate">{{ $potpot->address }}</p>
            @endif
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full {{ $potpotStatusColors[$potpot->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                    {{ ucfirst($potpot->status) }}
                </span>
                @if ($potpot->driver)
                    <span class="inline-block max-w-full truncate rounded-full bg-cyan-600 text-white text-xs font-semibold px-2.5 py-1" title="Driver: {{ $potpot->driver }}">
                        Driver: {{ $potpot->driver }}
                    </span>
                @endif
                @if ($potpot->or_number)
                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">{{ $potpot->or_number }}</span>
                @endif
                @if ($potpot->contact_number)
                    <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1">{{ $potpot->contact_number }}</span>
                @endif
            </div>
            <div class="flex items-center gap-1 mt-1.5">
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $potpot->date_registered->format('M-d-y') }}</span>
                <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $potpot->date_expired->format('M-d-y') }}</span>
            </div>
            @if ($potpot->remarks)
                <p class="text-gray-500 text-xs mt-1.5">{{ $potpot->remarks }}</p>
            @endif
        </div>
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActivePotpotFilters)
                No potpots match your search.
                <a href="{{ route('potpot.list') }}" data-ajax-potpot-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No potpots registered yet.
            @endif
        </div>
    @endforelse
</div>