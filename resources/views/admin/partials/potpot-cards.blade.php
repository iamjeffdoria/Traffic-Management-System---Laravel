@php
    $hasActivePotpotFilters = request()->filled('body') || request()->filled('name') || request()->filled('address') || request()->filled('status');
    $potpotStatusColors = ['active' => 'bg-teal-500', 'renewed' => 'bg-green-600', 'expired' => 'bg-red-600'];
@endphp

<div id="potpot-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($potpots as $potpot)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <p class="text-gray-900 font-medium truncate">{{ $potpot->body_number }} — {{ $potpot->name }}</p>
            @if ($potpot->address)
                <p class="text-gray-500 text-sm truncate">{{ $potpot->address }}</p>
            @endif
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full {{ $potpotStatusColors[$potpot->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                    {{ ucfirst($potpot->status) }}
                </span>
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