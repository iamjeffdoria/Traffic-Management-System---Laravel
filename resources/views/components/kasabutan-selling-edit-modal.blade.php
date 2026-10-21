@props(['potpots'])

<div id="edit-kasabutan-selling-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Edit Kasabutan - Selling</h3>
            <button type="button" onclick="closeModal('edit-kasabutan-selling-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="kasabutan-selling-edit-form"
              data-kasabutan-form
              method="POST"
              action=""
              data-update-url-template="{{ route('potpot.kasabutan.selling.update', ['selling' => '__ID__']) }}"
              class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2 relative" data-searchable-select data-remote-url="{{ route('search.potpots') }}" data-on-select="onKasabutanSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Potpot <span class="text-gray-400 font-normal">(search to auto-fill owner, barangay and body number)</span></label>
                <input type="text" data-search-input autocomplete="off" placeholder="Search by body no or operator name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="hidden" data-search-hidden>

                <div data-search-dropdown class="hidden absolute z-20 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                    {{-- options are loaded from the server while typing --}}
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No potpots found.</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                <input type="text" name="owner" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                <input type="text" name="barangay" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                <input type="text" name="municipality" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Body Number</label>
                <input type="text" name="body_number" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buyer</label>
                <input type="text" name="buyer" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buyer Address</label>
                <input type="text" name="buyer_address" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness</label>
                <input type="text" name="witness" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness Type</label>
                <select name="witness_type" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="" disabled hidden>Select type</option>
                    <option value="Traffic Enforcer">Traffic Enforcer</option>
                    <option value="PSO Personnel">PSO Personnel</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Secretariat</label>
                <input type="text" name="secretariat" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="kasabutan-selling-edit-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-kasabutan-selling-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>