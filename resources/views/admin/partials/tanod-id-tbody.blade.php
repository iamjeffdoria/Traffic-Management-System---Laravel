@php
    $hasActiveTanodIdFilters = request()->filled('id_number') || request()->filled('name') || request()->filled('address') || request()->filled('contact');
@endphp

<tbody id="tanod-id-tbody" class="divide-y divide-gray-300">
    @forelse ($tanodIds as $tanodId)
        <tr class="divide-x divide-gray-300 hover:bg-gray-50/60 transition-colors">
            <td class="px-6 py-4 align-top font-semibold text-gray-900 break-words">{{ $tanodId->id_number }}</td>
            <td class="px-6 py-4 align-top text-gray-900 font-medium break-words">{{ $tanodId->full_name }}</td>
            <td class="px-6 py-4 align-top text-gray-700 break-words">{{ $tanodId->address }}</td>
            <td class="px-6 py-4 align-top">
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $tanodId->date_of_birth->format('M-d-y') }}
                </span>
            </td>
            <td class="px-6 py-4 align-top">
                <p class="text-gray-900 font-medium break-words">{{ $tanodId->emergency_contact_name }}</p>
                <div class="flex flex-wrap gap-1 mt-1.5">
                    <span class="inline-block max-w-full truncate rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1" title="{{ $tanodId->emergency_contact_relationship }}">
                        {{ $tanodId->emergency_contact_relationship }}
                    </span>
                    <span class="inline-block max-w-full truncate rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1" title="{{ $tanodId->emergency_contact_number }}">
                        {{ $tanodId->emergency_contact_number }}
                    </span>
                </div>
                <p class="text-gray-500 text-xs break-words mt-1.5">{{ $tanodId->emergency_contact_address }}</p>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveTanodIdFilters)
                    No Tanod IDs match your search.
                    <a href="{{ route('tanod.id') }}" data-ajax-tanod-id-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No Tanod IDs yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>