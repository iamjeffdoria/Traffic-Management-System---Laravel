<div id="import-id-card-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('import-id-card-modal')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Import ID Cards</h3>
            <button type="button" onclick="closeModal('import-id-card-modal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="id-card-import-form" method="POST" action="{{ route('potpot.id-cards.import') }}" enctype="multipart/form-data" class="px-6 py-5">
            @csrf

            <p class="text-sm text-gray-500 mb-4">
                Upload a .zip file containing a <code class="bg-gray-100 px-1 rounded">data.csv</code> file (columns: Full Name, ID Number, Gender, Date of Birth, Address, Height, Weight, OR Number, Date Issued, Expiry Date, Photo Filename) and a <code class="bg-gray-100 px-1 rounded">photos/</code> folder with the referenced photo files.
            </p>

            <label id="id-card-import-dropzone" for="id-card-import-input"
                class="flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 px-4 py-8 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-colors">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                <span id="id-card-import-filename" class="text-sm text-gray-600">Click to choose a .zip file, or drag it here</span>
                <input type="file" name="file" id="id-card-import-input" accept=".zip" class="hidden">
            </label>

            <div class="flex gap-3 mt-6">
                <button type="submit" id="id-card-import-submit" disabled
                    class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Import
                </button>
                <button type="button" onclick="closeModal('import-id-card-modal')"
                    class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>