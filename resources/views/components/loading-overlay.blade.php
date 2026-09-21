<div id="page-loading-overlay" class="hidden fixed inset-0 z-[9999] items-center justify-center bg-white/80 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-3">
        <!-- Minimal horizontal traffic light (uses its own loader-* keyframes) -->
        <div class="flex flex-row items-center gap-2 bg-gray-900 rounded-full px-3 py-2 shadow-lg">
            <span class="w-3 h-3 rounded-full bg-red-500" style="animation: loader-red 1.8s ease-in-out infinite;"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-400" style="animation: loader-yellow 1.8s ease-in-out infinite;"></span>
            <span class="w-3 h-3 rounded-full bg-green-500" style="animation: loader-green 1.8s ease-in-out infinite;"></span>
        </div>

        <p class="text-xs text-gray-500 font-medium tracking-wide">Loading</p>
    </div>
</div>