@props(['potpots'])

<div id="create-kasabutan-selling-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Add Kasabutan - Selling</h3>
            <button type="button" onclick="closeModal('create-kasabutan-selling-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="kasabutan-selling-create-form" data-kasabutan-form method="POST" action="{{ route('potpot.kasabutan.selling.store') }}" class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf

            <div class="sm:col-span-2 relative" data-searchable-select data-on-select="onKasabutanSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Potpot <span class="text-gray-400 font-normal">(search to auto-fill owner, barangay and body number)</span></label>
                <input type="text" data-search-input autocomplete="off" placeholder="Search by body no or operator name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="hidden" data-search-hidden>

                <div data-search-dropdown class="hidden absolute z-20 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                    @foreach ($potpots as $potpot)
                        <button type="button" data-option
                            data-id="{{ $potpot->id }}"
                            data-label="{{ $potpot->body_number }} — {{ $potpot->name }}"
                            data-search="{{ strtolower($potpot->body_number . ' ' . $potpot->name) }}"
                            data-body="{{ $potpot->body_number }}"
                            data-name="{{ $potpot->name }}"
                            data-address="{{ $potpot->address }}"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors">
                            {{ $potpot->body_number }} — {{ $potpot->name }}
                        </button>
                    @endforeach
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No potpots found.</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                <input type="text" name="owner" value="{{ old('owner') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                <input type="text" name="barangay" value="{{ old('barangay') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                <input type="text" name="municipality" value="{{ old('municipality') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Body Number</label>
                <input type="text" name="body_number" value="{{ old('body_number') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buyer</label>
                <input type="text" name="buyer" value="{{ old('buyer') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buyer Address</label>
                <input type="text" name="buyer_address" value="{{ old('buyer_address') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ old('date') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness</label>
                <input type="text" name="witness" value="{{ old('witness') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="kasabutan-selling-create-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Add Kasabutan
            </button>
            <button type="button" onclick="closeModal('create-kasabutan-selling-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>