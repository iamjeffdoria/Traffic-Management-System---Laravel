@props(['tricycles'])

<div id="create-tricycle-mayors-permit-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('create-tricycle-mayors-permit-modal')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Add Permit</h3>
            <button type="button" onclick="closeModal('create-tricycle-mayors-permit-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="tricycle-mayors-permit-create-form" data-permit-form method="POST" action="{{ route('tricycle.mayors-permit.store') }}" class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf

            <div class="sm:col-span-2 relative" data-searchable-select data-on-select="onTricycleSearchSelect">
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
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors">
                            {{ $tricycle->body_number }} — {{ $tricycle->plate_no }} ({{ $tricycle->name }})
                        </button>
                    @endforeach
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No tricycles found.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" data-permit-name-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" data-permit-address-display readonly
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Control No.</label>
                <input type="text" name="control_no" value="{{ old('control_no') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="active" @selected(old('status') === 'active')>Active</option>
                    <option value="expired" @selected(old('status') === 'expired')>Expired</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                <input type="text" name="business_name" value="{{ old('business_name') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motorized Operation</label>
                <input type="text" name="motorized_operation" value="{{ old('motorized_operation') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OR No.</label>
                <input type="text" name="or_no" value="{{ old('or_no') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date</label>
                <input type="date" name="issue_date" value="{{ old('issue_date') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Issued At</label>
                <input type="text" name="issued_at" value="{{ old('issued_at') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mayor</label>
                <input type="text" name="mayor" value="{{ old('mayor') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                <select name="quarter" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="" disabled selected>Select quarter</option>
                    <option value="First Quarter" @selected(old('quarter') === 'First Quarter')>First Quarter</option>
                    <option value="Second Quarter" @selected(old('quarter') === 'Second Quarter')>Second Quarter</option>
                    <option value="Third Quarter" @selected(old('quarter') === 'Third Quarter')>Third Quarter</option>
                    <option value="Fourth Quarter" @selected(old('quarter') === 'Fourth Quarter')>Fourth Quarter</option>
                </select>
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="tricycle-mayors-permit-create-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Add Permit
            </button>
            <button type="button" onclick="closeModal('create-tricycle-mayors-permit-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>