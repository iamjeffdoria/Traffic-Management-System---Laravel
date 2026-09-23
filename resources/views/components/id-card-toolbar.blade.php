<div class="flex flex-col gap-3 mb-6">
    <p class="text-xs text-gray-500">Check up to 4 ID cards to print together on one sheet.</p>

    <div class="flex flex-col items-end gap-3 sm:flex-row sm:items-center sm:justify-end">
        <button type="button" id="id-card-bulk-print-btn" data-bulk-print-url="{{ route('potpot.id-cards.bulk-print') }}"
            onclick="printSelectedIdCards()" disabled
            class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 text-white px-5 py-2 text-sm font-semibold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
            </svg>
            Print Selected
        </button>

        <div class="inline-flex items-center overflow-hidden rounded-full">
            <button type="button" onclick="openModal('import-id-card-modal')"
                class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 text-sm font-semibold hover:bg-emerald-700 transition-colors border-r border-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                Import ZIP
            </button>
            <a href="{{ route('potpot.id-cards.export') }}"
                class="inline-flex items-center gap-1.5 bg-orange-500 text-white px-4 py-2 text-sm font-semibold hover:bg-orange-600 transition-colors border-r border-orange-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12" />
                </svg>
                Export ZIP
            </a>
            <button type="button" onclick="openModal('create-id-card-modal')"
                class="inline-flex items-center gap-1.5 bg-red-600 text-white px-5 py-2 text-sm font-semibold hover:bg-red-700 transition-colors">
                + Add ID Card
            </button>
        </div>
    </div>
</div>