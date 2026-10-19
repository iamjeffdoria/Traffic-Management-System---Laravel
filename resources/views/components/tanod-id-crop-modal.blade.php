<div id="tanod-id-crop-modal" class="hidden fixed inset-0 z-[60] items-center justify-center px-4 py-6 overflow-y-auto">
    <div class="absolute inset-0 bg-black/70"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md flex flex-col">
        <div class="px-6 pt-6 pb-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 text-lg">Crop Photo</h3>
            <p class="text-xs text-gray-500 mt-0.5">Drag to position, zoom to fit. The box matches the ID frame exactly.</p>
        </div>

        <div class="px-6 py-4">
            <div class="bg-gray-900 rounded-lg overflow-hidden" style="height: 340px;">
                <img id="tanod-id-crop-image" src="" alt="" class="block max-w-full">
            </div>

            <div class="flex items-center justify-center gap-2 mt-3">
                <button type="button" onclick="zoomTanodIdCrop(-0.1)" class="rounded-full border border-gray-300 px-4 py-1.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Zoom −</button>
                <button type="button" onclick="zoomTanodIdCrop(0.1)" class="rounded-full border border-gray-300 px-4 py-1.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Zoom +</button>
                <button type="button" onclick="rotateTanodIdCrop(90)" class="rounded-full border border-gray-300 px-4 py-1.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Rotate</button>
            </div>
        </div>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="applyTanodIdCrop()"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Apply Crop
            </button>
            <button type="button" onclick="cancelTanodIdCrop()"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>