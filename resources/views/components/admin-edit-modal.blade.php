<div id="edit-admin-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <div>
                <h3 class="font-semibold text-gray-900 text-lg">Edit Admin</h3>
                <p class="text-xs text-gray-500 mt-0.5">Update details, photo, or role.</p>
            </div>
            <button type="button" onclick="closeModal('edit-admin-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="admin-edit-form"
              method="POST"
              action=""
              enctype="multipart/form-data"
              data-update-url-template="{{ route('admin.users.update', ['user' => '__ID__']) }}"
              class="px-6 py-5 space-y-5 overflow-y-auto">
            @csrf
            @method('PUT')

            {{-- Photo --}}
            <div data-avatar-upload class="flex flex-col items-center gap-2">
                <label for="edit-photo" class="relative group cursor-pointer">
                    <span class="w-24 h-24 rounded-full overflow-hidden bg-gray-50 border-2 border-gray-200 group-hover:border-red-400 transition-colors flex items-center justify-center">
                        <img data-avatar-img src="" alt="" class="hidden w-full h-full object-cover">
                        <span data-avatar-placeholder class="w-full h-full bg-red-600 text-white text-3xl font-semibold flex items-center justify-center"></span>
                    </span>
                    <span class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center border-2 border-white shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </label>
                <input type="file" id="edit-photo" name="photo" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                <p data-avatar-filename class="text-xs text-gray-400 max-w-full truncate">Click to change photo · max 2 MB</p>
            </div>

            {{-- Details --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="edit-password" placeholder="Leave blank to keep current" minlength="8"
                            oninput="checkPasswordLength(this, 'edit-password-hint')"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-11 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600">
                        <button type="button" onclick="togglePassword('edit-password', 'edit-password-eye-open', 'edit-password-eye-closed')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="edit-password-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="edit-password-eye-closed" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <p id="edit-password-hint" class="text-xs mt-1 text-gray-400">Leave blank to keep current, or enter at least 8 characters</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                        <option value="superadmin">Super Admin</option>
                        <option value="potpot_admin">Potpot Admin</option>
                        <option value="tricycle_admin">Tricycle Admin</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="admin-edit-form"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-admin-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>