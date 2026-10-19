@props(['tanods'])

<div id="create-tanod-id-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Add Tanod ID</h3>
            <button type="button" onclick="closeModal('create-tanod-id-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="tanod-id-create-form" data-tanod-id-form method="POST" action="{{ route('tanod.id.store') }}" class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf

            <div class="sm:col-span-2 relative" data-searchable-select data-on-select="onTanodIdSearchSelect">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanod <span class="text-gray-400 font-normal">(search to auto-fill all fields from the Tanod List)</span></label>
                <input type="text" data-search-input autocomplete="off" placeholder="Search by ID number or name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="hidden" data-search-hidden>

                <div data-search-dropdown class="hidden absolute z-20 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                    @foreach ($tanods as $tanod)
                        <button type="button" data-option
                            data-id="{{ $tanod->id }}"
                            data-label="{{ $tanod->id_number }} — {{ $tanod->full_name }}"
                            data-search="{{ strtolower($tanod->id_number . ' ' . $tanod->full_name) }}"
                            data-id-number="{{ $tanod->id_number }}"
                            data-full-name="{{ $tanod->full_name }}"
                            data-address="{{ $tanod->home_address }}"
                            data-date-of-birth="{{ $tanod->date_of_birth->format('Y-m-d') }}"
                            data-ec-name="{{ $tanod->emergency_contact_name }}"
                            data-ec-address="{{ $tanod->emergency_contact_address }}"
                            data-ec-number="{{ $tanod->emergency_contact_number }}"
                            data-ec-relationship="{{ $tanod->emergency_contact_relationship }}"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors">
                            {{ $tanod->id_number }} — {{ $tanod->full_name }}
                        </button>
                    @endforeach
                    <p data-no-results class="hidden px-4 py-2.5 text-sm text-gray-400">No tanods found.</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm font-semibold text-gray-900">Personal Details</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                <input type="text" name="id_number" value="{{ old('id_number') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Emergency Contact Person</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="tel" inputmode="tel" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" required placeholder="e.g. 09123456789"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="emergency_contact_address" value="{{ old('emergency_contact_address') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="tanod-id-create-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Add Tanod ID
            </button>
            <button type="button" onclick="closeModal('create-tanod-id-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>