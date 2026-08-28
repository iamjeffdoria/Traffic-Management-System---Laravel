@props(['idCard'])

@php
    $idCardEditData = [
        'id' => $idCard->id,
        'full_name' => $idCard->full_name,
        'id_number' => $idCard->id_number,
        'gender' => $idCard->gender,
        'date_of_birth' => $idCard->date_of_birth->format('Y-m-d'),
        'address' => $idCard->address,
        'height' => $idCard->height,
        'weight' => $idCard->weight,
        'or_number' => $idCard->or_number,
        'date_issued' => $idCard->date_issued->format('Y-m-d'),
        'expiry_date' => $idCard->expiry_date->format('Y-m-d'),
        'photo_url' => $idCard->photo_path ? asset('storage/' . $idCard->photo_path) : null,
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-0.5">
            <button type="button" onclick="openIdCardEditModal({{ Illuminate\Support\Js::from($idCardEditData) }})" title="Edit"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.id-cards.print', $idCard) }}')"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-id-card-form-{{ $idCard->id }}" method="POST" action="{{ route('potpot.id-cards.destroy', $idCard) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmIdCardDelete('delete-id-card-form-{{ $idCard->id }}', '{{ $idCard->full_name }}')" title="Delete"
                class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </td>
    <td class="px-4 py-4 align-top">
        @if ($idCard->photo_path)
            <img src="{{ asset('storage/' . $idCard->photo_path) }}" alt="{{ $idCard->full_name }}"
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
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 font-semibold break-words">{{ $idCard->id_number }}</p>
        <span class="inline-block max-w-full truncate rounded-full {{ $idCard->gender === 'Male' ? 'bg-blue-600' : 'bg-pink-600' }} text-white text-xs font-semibold px-2.5 py-1 mt-1.5">
            {{ $idCard->gender }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 font-medium break-words">{{ $idCard->full_name }}</p>
        <p class="text-gray-500 text-xs break-words mt-1">{{ $idCard->address }}</p>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1">
            {{ $idCard->height }} cm
        </span>
        <span class="inline-block max-w-full truncate rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5">
            {{ $idCard->weight }} kg
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <p class="text-gray-900 text-sm font-semibold">{{ $idCard->date_of_birth->format('M-d-y') }}</p>
        <span class="inline-block max-w-full truncate rounded-full bg-teal-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5" title="OR: {{ $idCard->or_number }}">
            OR: {{ $idCard->or_number }}
        </span>
    </td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
            {{ $idCard->date_issued->format('M-d-y') }}
        </span>
        <span class="inline-block max-w-full truncate rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 mt-1.5 whitespace-nowrap">
            Exp: {{ $idCard->expiry_date->format('M-d-y') }}
        </span>
    </td>
</tr>