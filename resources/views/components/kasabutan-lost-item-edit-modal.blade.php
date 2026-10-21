@props(['potpots'])

<div id="edit-kasabutan-lost-item-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Edit Kasabutan - Lost Item</h3>
            <button type="button" onclick="closeModal('edit-kasabutan-lost-item-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="kasabutan-lost-item-edit-form"
              data-kasabutan-lost-item-form
              method="POST"
              action=""
              data-update-url-template="{{ route('potpot.kasabutan.lost-item.update', ['lostItem' => '__ID__']) }}"
              class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2 relative" data-searchable-select data-remote-url="{{ route('search.potpots') }}" data-on-select="onKasabutanLostItemSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Potpot <span class="text-gray-400 font-normal">(search to auto-fill driver, body number and address)</span></label>
                <input type="text" data-search-input autocomplete="off" placeholder="Search by body no, operator or driver name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="hidden" data-search-hidden>

                <div data-search-dropdown class="hidden absolute z-20 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                    {{-- options are loaded from the server while typing --}}
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No potpots found.</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm font-semibold text-gray-900">Potpot Driver</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Potpot Driver</label>
                <input type="text" name="driver" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Body Number</label>
                <input type="text" name="body_number" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Age of Driver</label>
                <input type="number" min="1" max="120" name="driver_age" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address of Driver</label>
                <input type="text" name="driver_address" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Potpot Passenger</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Potpot Passenger</label>
                <input type="text" name="passenger" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Age of Passenger</label>
                <input type="number" min="1" max="120" name="passenger_age" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address of Passenger</label>
                <input type="text" name="passenger_address" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Date, Time &amp; Place</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <input type="time" name="time" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Issued At</label>
                <input type="text" name="issued_at" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Witnesses</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness 1</label>
                <input type="text" name="witness_1" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness 1 Type</label>
                <select name="witness_1_type" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="Traffic Enforcer">Traffic Enforcer</option>
                    <option value="PSO Personnel">PSO Personnel</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness 2</label>
                <input type="text" name="witness_2" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Witness 2 Type</label>
                <select name="witness_2_type" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="Traffic Enforcer">Traffic Enforcer</option>
                    <option value="PSO Personnel">PSO Personnel</option>
                </select>
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Signatories</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IT Section <span class="text-gray-400 font-normal">(Verified by)</span></label>
                <input type="text" name="it_section" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Security Officer <span class="text-gray-400 font-normal">(Noted by)</span></label>
                <input type="text" name="security_officer" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="kasabutan-lost-item-edit-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-kasabutan-lost-item-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>