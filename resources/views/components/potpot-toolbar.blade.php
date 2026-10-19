<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div class="w-full sm:w-auto">
        <select name="sort" form="potpot-filter-form" onchange="debouncedFetchPotpotFilter()"
            class="w-full sm:w-52 rounded-full border-2 border-gray-300 bg-white text-gray-900 font-medium px-3 py-2 text-xs sm:text-sm focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
            <option value="" @selected(!request('sort'))>Sort: Latest updated</option>
            <option value="body_asc" @selected(request('sort') === 'body_asc')>Body No. (low to high)</option>
            <option value="body_desc" @selected(request('sort') === 'body_desc')>Body No. (high to low)</option>
            <option value="name_asc" @selected(request('sort') === 'name_asc')>Operator (A to Z)</option>
            <option value="name_desc" @selected(request('sort') === 'name_desc')>Operator (Z to A)</option>
            <option value="expiring" @selected(request('sort') === 'expiring')>Expiring soonest</option>
            <option value="registered" @selected(request('sort') === 'registered')>Newest registered</option>
        </select>
    </div>

    <div class="inline-flex items-center overflow-hidden rounded-full self-end sm:self-auto">
        <button type="button" onclick="openModal('import-potpot-modal')"
            class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 text-sm font-semibold hover:bg-emerald-700 transition-colors border-r border-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
            </svg>
            Import File
        </button>

        <a href="{{ route('potpot.export') }}"
            class="inline-flex items-center gap-1.5 bg-orange-500 text-white px-4 py-2 text-sm font-semibold hover:bg-orange-600 transition-colors border-r border-orange-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12" />
            </svg>
            Export File
        </a>

        <button type="button" onclick="openModal('create-potpot-modal')"
            class="inline-flex items-center gap-1.5 bg-red-600 text-white px-5 py-2 text-sm font-semibold hover:bg-red-700 transition-colors">
            + Add Potpot
        </button>
    </div>
</div>