<!-- Mobile open/close state -->
<input type="checkbox" id="sidebar-toggle" class="peer/mobile hidden" />

<!-- Floating hamburger button (mobile only) -->
<label for="sidebar-toggle"
    class="lg:hidden fixed top-4 left-4 z-50 cursor-pointer bg-gray-900 text-white p-2.5 rounded-lg shadow-lg peer-checked/mobile:hidden">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</label>

<!-- Overlay (mobile only, shown when open) -->
<label for="sidebar-toggle"
    class="hidden peer-checked/mobile:block lg:hidden fixed inset-0 bg-black/50 z-30">
</label>

<!-- Sidebar -->
<aside class="group/collapse fixed top-0 left-0 z-40 h-screen bg-gray-900 text-white flex flex-col overflow-hidden
    w-56 -translate-x-full peer-checked/mobile:translate-x-0 lg:translate-x-0
    lg:has-[#sidebar-collapse:checked]:w-16
    transition-all duration-300 ease-in-out">

    <!-- Desktop collapse state now lives INSIDE the sidebar -->
    <input type="checkbox" id="sidebar-collapse" class="hidden" />

    <div class="flex items-center justify-between px-5 py-4 border-b border-white/10 lg:group-has-[#sidebar-collapse:checked]/collapse:px-3">
        <span class="font-semibold text-lg lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">TMS Admin</span>

        <!-- Mobile close button -->
        <label for="sidebar-toggle" class="lg:hidden cursor-pointer p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </label>

        <!-- Desktop collapse toggle -->
        <label for="sidebar-collapse" class="hidden lg:flex cursor-pointer p-1 text-gray-400 hover:text-white">
            <svg class="w-5 h-5 transition-transform lg:group-has-[#sidebar-collapse:checked]/collapse:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </label>
    </div>

    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto overflow-x-hidden">
        <a href="{{ route('admin.dashboard') }}" title="Dashboard"
        class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
        {{ $active === 'dashboard' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Dashboard</span>
        </a>

        @if (auth()->user()->isPotpotAdmin())
            <a href="{{ route('potpot.index') }}" title="Potpot Registration"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'potpot' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4M4 17h12m0 0l-4 4m4-4l-4-4" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Potpot Registration</span>
            </a>
        @endif

        @if (auth()->user()->isTricycleAdmin())
            <a href="{{ route('tricycle.index') }}" title="Tricycle Registration"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'tricycle' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17h2v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2h2m12 0a2 2 0 11-4 0m4 0a2 2 0 10-4 0m-8 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Tricycle Registration</span>
            </a>

            <a href="{{ route('tricycle.list') }}" title="Tricycle List"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'tricycle-list' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Tricycle List</span>
            </a>

                        <a href="{{ route('tricycle.mayors-permit') }}" title="Mayor's Permit - Tricycle"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'tricycle-mayors-permit' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Mayor's Permit</span>
            </a>

            <a href="{{ route('tricycle.mtop') }}" title="MTOP"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'tricycle-mtop' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">MTOP</span>
            </a>

            <a href="{{ route('tricycle.franchise') }}" title="Franchise"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'tricycle-franchise' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Franchise</span>
            </a>
        @endif

        @if (auth()->user()->isSuperadmin())
            <a href="{{ route('admin.users') }}" title="Admin Management"
            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0
            {{ $active === 'admins' ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="truncate lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Admin Management</span>
            </a>
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Logout"
                class="w-full flex items-center gap-3 text-sm text-gray-400 hover:text-white px-4 py-2 lg:group-has-[#sidebar-collapse:checked]/collapse:justify-center lg:group-has-[#sidebar-collapse:checked]/collapse:px-0">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="lg:group-has-[#sidebar-collapse:checked]/collapse:hidden">Logout</span>
            </button>
        </form>
    </div>
</aside>