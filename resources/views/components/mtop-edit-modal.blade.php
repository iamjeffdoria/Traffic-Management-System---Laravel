@props(['tricycles'])

<div id="edit-mtop-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('edit-mtop-modal')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Edit MTOP Record</h3>
            <button type="button" onclick="closeModal('edit-mtop-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="mtop-edit-form"
              data-mtop-form
              method="POST"
              action=""
              data-update-url-template="{{ route('tricycle.mtop.update', ['mtop' => '__ID__']) }}"
              class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2 relative" data-searchable-select data-on-select="onMtopSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tricycle</label>
                <input type="text" data-search-input autocomplete="off" placeholder="Search by body no, plate no, or owner name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="hidden" name="tricycle_id" data-search-hidden required>

                <div data-search-dropdown class="hidden absolute z-20 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                    @foreach ($tricycles as $tricycle)
                        <button type="button" data-option
                            data-id="{{ $tricycle->id }}"
                            data-label="{{ $tricycle->body_number }} — {{ $tricycle->plate_no }} ({{ $tricycle->name }})"
                            data-search="{{ strtolower($tricycle->body_number . ' ' . $tricycle->plate_no . ' ' . $tricycle->name) }}"
                            data-name="{{ $tricycle->name }}"
                            data-address="{{ $tricycle->address }}"
                            data-make="{{ $tricycle->make_kind }}"
                            data-motor="{{ $tricycle->engine_motor_no }}"
                            data-chassis="{{ $tricycle->chassis_no }}"
                            data-plate="{{ $tricycle->plate_no }}"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors">
                            {{ $tricycle->body_number }} — {{ $tricycle->plate_no }} ({{ $tricycle->name }})
                        </button>
                    @endforeach
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No tricycles found.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" data-mtop-name-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" data-mtop-address-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                <input type="text" data-mtop-make-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motor No.</label>
                <input type="text" data-mtop-motor-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chassis No.</label>
                <input type="text" data-mtop-chassis-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plate No.</label>
                <input type="text" data-mtop-plate-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Case No.</label>
                <input type="text" name="case_no" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Units</label>
                <input type="number" min="1" name="no_of_units" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Route Operation</label>
                <input type="text" name="route_operation" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipal Treasurer</label>
                <input type="text" name="municipal_treasurer" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Officer in Charge</label>
                <input type="text" name="officer_in_charge" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mayor</label>
                <input type="text" name="mayor" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="mtop-edit-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-mtop-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>