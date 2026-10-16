@php
    $hasActiveTanodFilters = request()->filled('id_number') || request()->filled('name') || request()->filled('barangay');
@endphp

<div id="tanod-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($tanods as $tanod)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <p class="text-gray-900 font-medium truncate">{{ $tanod->full_name }}</p>
            <p class="text-gray-500 text-sm truncate">{{ $tanod->id_number }}</p>
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">{{ $tanod->position }}</span>
                <span class="inline-block rounded-full bg-purple-600 text-white text-xs font-semibold px-2.5 py-1">{{ $tanod->barangay }}</span>
                @if ($tanod->blood_type)
                    <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1">{{ $tanod->blood_type }}</span>
                @endif
                @if ($tanod->mobile_no)
                    <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1">{{ $tanod->mobile_no }}</span>
                @endif
            </div>
            <p class="text-gray-500 text-xs mt-1.5">Chairman: {{ $tanod->barangay_chairman }}</p>
            <p class="text-gray-500 text-xs">Emergency: {{ $tanod->emergency_contact_name }} ({{ $tanod->emergency_contact_relationship }}) · {{ $tanod->emergency_contact_number }}</p>
        </div>
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActiveTanodFilters)
                No tanods match your search.
                <a href="{{ route('tanod.list') }}" data-ajax-tanod-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No tanods registered yet.
            @endif
        </div>
    @endforelse
</div>