<div class="flex flex-col items-end gap-3 mb-6 sm:flex-row sm:items-center sm:justify-end">

    {{-- Print dropdown --}}
    <div class="relative">
        <button type="button" id="tanod-id-bulk-print-btn" data-bulk-print-url="{{ route('tanod.id.bulk-print') }}"
            onclick="toggleDropdown('tanod-id-print-dropdown')" disabled
            class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 text-white px-5 py-2 text-sm font-semibold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
            </svg>
            <span id="tanod-id-bulk-print-label">Print Selected</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="tanod-id-print-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-gray-200 bg-white shadow-lg py-1 z-40">
            <button type="button" onclick="printSelectedTanodIds()"
                class="w-full text-left px-4 py-2.5 hover:bg-gray-50">
                <span class="block text-sm font-medium text-gray-900">Front &amp; Back</span>
                <span class="block text-xs text-gray-500">Side by side, 2 IDs per page</span>
            </button>
            <button type="button" onclick="printSelectedTanodIds('front')"
                class="w-full text-left px-4 py-2.5 hover:bg-gray-50">
                <span class="block text-sm font-medium text-gray-900">Fronts only</span>
                <span class="block text-xs text-gray-500">4 cards per page</span>
            </button>
            <button type="button" onclick="printSelectedTanodIds('back')"
                class="w-full text-left px-4 py-2.5 hover:bg-gray-50">
                <span class="block text-sm font-medium text-gray-900">Backs only</span>
                <span class="block text-xs text-gray-500">4 cards per page</span>
            </button>
        </div>
    </div>

    <div class="inline-flex items-center overflow-hidden rounded-full">
        <button type="button" onclick="openModal('import-tanod-id-modal')"
            class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 text-sm font-semibold hover:bg-emerald-700 transition-colors border-r border-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
            </svg>
            Import File
        </button>

        {{-- TODO: swap for an <a href="{{ route('tanod.id.export') }}"> once that route exists --}}
        <button type="button"
            class="inline-flex items-center gap-1.5 bg-orange-500 text-white px-4 py-2 text-sm font-semibold hover:bg-orange-600 transition-colors border-r border-orange-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12" />
            </svg>
            Export File
        </button>

        <button type="button" onclick="openModal('create-tanod-id-modal')"
            class="inline-flex items-center gap-1.5 bg-red-600 text-white px-5 py-2 text-sm font-semibold hover:bg-red-700 transition-colors">
            + Add Tanod ID
        </button>
    </div>
</div>