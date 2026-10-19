<div id="edit-tanod-id-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Edit Tanod ID</h3>
            <button type="button" onclick="closeModal('edit-tanod-id-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="tanod-id-edit-form"
              method="POST"
              action=""
              enctype="multipart/form-data"
              data-update-url-template="{{ route('tanod.id.update', ['tanodId' => '__ID__']) }}"
              class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2" data-avatar-upload>
                <p class="block text-sm font-medium text-gray-700 mb-2">2x2 Photo</p>
                <div class="flex items-center gap-4">
                    <label for="edit-tanod-id-photo" class="relative group cursor-pointer shrink-0">
                        <span class="w-24 h-24 rounded-lg overflow-hidden bg-gray-50 border-2 border-gray-200 group-hover:border-red-400 transition-colors flex items-center justify-center">
                            <img data-avatar-img src="" alt="Current photo" class="hidden w-full h-full object-cover">
                            <svg data-avatar-placeholder class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                    </label>
                    <input type="file" id="edit-tanod-id-photo" name="photo" accept="image/*" class="hidden" onchange="openTanodIdCropper(this)">
                    <p data-avatar-filename class="text-xs text-gray-400 min-w-0 truncate">Click the photo to change it · max 5 MB</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm font-semibold text-gray-900">Personal Details</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                <input type="text" name="id_number" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="address" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="date_of_birth" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                <p class="text-sm font-semibold text-gray-900">Emergency Contact Person</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="emergency_contact_name" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                <input type="text" name="emergency_contact_relationship" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="tel" inputmode="tel" name="emergency_contact_number" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="emergency_contact_address" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="tanod-id-edit-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-tanod-id-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>