@php
    $hasActiveTanodIdFilters = request()->filled('id_number') || request()->filled('name') || request()->filled('address') || request()->filled('contact');
@endphp

<tbody id="tanod-id-tbody" class="divide-y divide-gray-300">
    @forelse ($tanodIds as $tanodId)
        @php
            $tanodIdEditData = array_merge(
                $tanodId->only([
                    'id', 'id_number', 'full_name', 'address',
                    'emergency_contact_name', 'emergency_contact_address',
                    'emergency_contact_number', 'emergency_contact_relationship',
                ]),
                [
                    'date_of_birth' => $tanodId->date_of_birth->format('Y-m-d'),
                    'photo_url' => $tanodId->photo_path ? asset('storage/' . $tanodId->photo_path) : null,
                ]
            );
        @endphp
        <tr class="divide-x divide-gray-300 hover:bg-gray-50/60 transition-colors">
            <td class="px-4 py-4 align-top">
                <input type="checkbox" class="tanod-id-checkbox rounded border-gray-300 text-red-600 focus:ring-red-600" data-id="{{ $tanodId->id }}">
            </td>
            <td class="px-4 py-4 align-top">
                <div class="flex items-center gap-1">
                    <button type="button" title="Edit" onclick="openTanodIdEditModal({{ Illuminate\Support\Js::from($tanodIdEditData) }})"
                        class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    {{-- TODO: wire up to printFromUrl(route(...)) once the print route exists --}}
                    <button type="button" title="Print" onclick="printFromUrl('{{ route('tanod.id.print', $tanodId) }}')"
                        class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                        </svg>
                    </button>
                    {{-- TODO: wire up to confirmTanodIdDelete(...) once the delete route and modal exist --}}
                    <button type="button" title="Delete"
                        class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
            <td class="px-4 py-4 align-top">
                @if ($tanodId->photo_path)
                    <img src="{{ asset('storage/' . $tanodId->photo_path) }}" alt="{{ $tanodId->full_name }}"
                        class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                @else
                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                    </div>
                @endif
            </td>
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
            <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
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