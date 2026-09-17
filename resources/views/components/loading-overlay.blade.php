<div id="page-loading-overlay" class="hidden fixed inset-0 z-[9999] items-center justify-center bg-white/80 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-5">
        <!-- Traffic light -->
        <div class="flex flex-col items-center gap-2 bg-gray-900 rounded-2xl px-3 py-4 shadow-lg">
            <span class="w-4 h-4 rounded-full bg-red-500" style="animation: tl-red 2.4s ease-in-out infinite;"></span>
            <span class="w-4 h-4 rounded-full bg-yellow-400" style="animation: tl-yellow 2.4s ease-in-out infinite;"></span>
            <span class="w-4 h-4 rounded-full bg-green-500" style="animation: tl-green 2.4s ease-in-out infinite;"></span>
        </div>

        <!-- Moving road dashes -->
        <div class="w-24 h-1.5 rounded-full overflow-hidden bg-gray-200 relative">
            <div class="absolute inset-0"
                style="background-image: repeating-linear-gradient(90deg, #dc2626 0 10px, transparent 10px 20px); animation: road-move 0.8s linear infinite;">
            </div>
        </div>

        <p class="flex items-center gap-1 text-sm text-gray-600 font-medium">
            Loading
            <span class="flex gap-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-bounce" style="animation-delay: 0ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-bounce" style="animation-delay: 150ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-bounce" style="animation-delay: 300ms"></span>
            </span>
        </p>
    </div>
</div>