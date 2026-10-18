@php
    $hasActiveTanodFilters = request()->filled('id_number') || request()->filled('name') || request()->filled('barangay');
@endphp

<tbody id="tanod-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($tanods as $tanod)
        @php
            $tanodEditData = array_merge(
                $tanod->only([
                    'id', 'id_number', 'full_name', 'position', 'barangay', 'barangay_chairman',
                    'home_address', 'place_of_birth', 'height', 'weight', 'blood_type', 'mobile_no',
                    'emergency_contact_name', 'emergency_contact_address',
                    'emergency_contact_relationship', 'emergency_contact_number',
                ]),
                ['date_of_birth' => $tanod->date_of_birth->format('Y-m-d')]
            );
        @endphp
        <tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
            <td class="px-4 py-4 align-top">
                <div class="flex items-center gap-1">
                    <button type="button" title="Edit" onclick="openTanodEditModal({{ Illuminate\Support\Js::from($tanodEditData) }})"
                        class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form id="delete-tanod-form-{{ $tanod->id }}" method="POST" action="{{ route('tanod.destroy', $tanod) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" title="Delete"
                        onclick="confirmTanodDelete('delete-tanod-form-{{ $tanod->id }}', {{ Illuminate\Support\Js::from($tanod->full_name . ' (' . $tanod->id_number . ')') }})"
                        class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
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
            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
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