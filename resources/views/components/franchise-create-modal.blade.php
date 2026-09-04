@props(['tricycles'])

<div id="create-franchise-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('create-franchise-modal')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Add Franchise Record</h3>
            <button type="button" onclick="closeModal('create-franchise-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="franchise-create-form" data-franchise-form method="POST" action="{{ route('tricycle.franchise.store') }}" class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf

            <div class="sm:col-span-2 relative" data-searchable-select data-on-select="onFranchiseSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Body Number (Tricycle)</label>
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
                            data-plate="{{ $tricycle->plate_no }}"
                            data-motor="{{ $tricycle->engine_motor_no }}"
                            data-chassis="{{ $tricycle->chassis_no }}"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors">
                            {{ $tricycle->body_number }} — {{ $tricycle->plate_no }} ({{ $tricycle->name }})
                        </button>
                    @endforeach
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No tricycles found.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" data-franchise-name-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                <input type="date" name="valid_until" value="{{ old('valid_until') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plate No.</label>
                <input type="text" data-franchise-plate-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Denomination</label>
                <input type="text" name="denomination" value="{{ old('denomination') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="New" selected>New</option>
                    <option value="Renewed">Renewed</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized No.</label>
                <input type="text" name="authorized_no" value="{{ old('authorized_no') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motor No.</label>
                <input type="text" data-franchise-motor-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chassis No.</label>
                <input type="text" data-franchise-chassis-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized Route</label>
                <textarea name="authorized_route" rows="2" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">{{ old('authorized_route') }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                <textarea name="purpose" rows="2"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">{{ old('purpose') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Official Receipt No.</label>
                <input type="text" name="official_receipt_no" value="{{ old('official_receipt_no') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                <input type="number" step="0.01" min="0" name="amount_paid" value="{{ old('amount_paid') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ old('date') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipal Treasurer</label>
                <input type="text" name="municipal_treasurer" value="{{ old('municipal_treasurer') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="franchise-create-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Franchise
            </button>
            <button type="button" onclick="closeModal('create-franchise-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>