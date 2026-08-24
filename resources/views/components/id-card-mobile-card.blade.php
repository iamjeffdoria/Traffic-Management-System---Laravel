@props(['idCard'])

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-start gap-3 min-w-0">
            @if ($idCard->photo_path)
                <img src="{{ asset('storage/' . $idCard->photo_path) }}" alt="{{ $idCard->full_name }}"
                    class="w-14 h-14 rounded-lg object-cover border border-gray-200 shrink-0">
            @else
                <div class="w-14 h-14 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    </svg>
                </div>
            @endif
            <div class="min-w-0">
                <p class="text-gray-900 font-medium truncate">{{ $idCard->full_name }}</p>
                <p class="text-gray-500 text-sm truncate">{{ $idCard->id_number }} · {{ $idCard->address }}</p>
                <div class="flex flex-wrap gap-1 mt-2">
                    <span class="inline-block rounded-full {{ $idCard->gender === 'Male' ? 'bg-blue-600' : 'bg-pink-600' }} text-white text-xs font-semibold px-2.5 py-1">
                        {{ $idCard->gender }}
                    </span>
                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1">
                        {{ $idCard->height }} cm
                    </span>
                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-semibold px-2.5 py-1">
                        {{ $idCard->weight }} kg
                    </span>
                </div>
                <p class="text-gray-500 text-xs mt-1.5">DOB: {{ $idCard->date_of_birth->format('M-d-y') }} · OR: {{ $idCard->or_number }}</p>
                <p class="text-gray-500 text-xs">Issued: {{ $idCard->date_issued->format('M-d-y') }} · Exp: {{ $idCard->expiry_date->format('M-d-y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" title="Edit"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
            <button type="button" title="Print" onclick="printFromUrl('{{ route('potpot.id-cards.print', $idCard) }}')"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                </svg>
            </button>
            <form id="delete-id-card-form-mobile-{{ $idCard->id }}" method="POST" action="{{ route('potpot.id-cards.destroy', $idCard) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmIdCardDelete('delete-id-card-form-mobile-{{ $idCard->id }}', '{{ $idCard->full_name }}')" title="Delete"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>