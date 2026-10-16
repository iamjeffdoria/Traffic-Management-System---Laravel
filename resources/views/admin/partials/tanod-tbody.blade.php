@php
    $hasActiveTanodFilters = request()->filled('id_number') || request()->filled('name') || request()->filled('barangay');
@endphp

<tbody id="tanod-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($tanods as $tanod)
        <tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
            <td class="px-6 py-4 align-top">
                <p class="text-gray-900 font-semibold break-words">{{ $tanod->id_number }}</p>
                @if ($tanod->blood_type)
                    <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5">{{ $tanod->blood_type }}</span>
                @endif
            </td>
            <td class="px-6 py-4 align-top">
                <p class="text-gray-900 font-medium break-words">{{ $tanod->full_name }}</p>
                <span class="inline-block max-w-full truncate rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5" title="{{ $tanod->position }}">{{ $tanod->position }}</span>
            </td>
            <td class="px-6 py-4 align-top">
                <p class="text-gray-900 text-sm font-semibold break-words">{{ $tanod->barangay }}</p>
                <p class="text-gray-500 text-xs break-words">Chairman: {{ $tanod->barangay_chairman }}</p>
            </td>
            <td class="px-6 py-4 align-top">
                @if ($tanod->mobile_no)
                    <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1">{{ $tanod->mobile_no }}</span>
                @else
                    <span class="text-gray-400 text-xs">—</span>
                @endif
            </td>
            <td class="px-6 py-4 align-top">
                <p class="text-gray-900 text-sm break-words">{{ $tanod->emergency_contact_name }}</p>
                <p class="text-gray-500 text-xs break-words">{{ $tanod->emergency_contact_relationship }} · {{ $tanod->emergency_contact_number }}</p>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveTanodFilters)
                    No tanods match your search.
                    <a href="{{ route('tanod.list') }}" data-ajax-tanod-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No tanods registered yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>