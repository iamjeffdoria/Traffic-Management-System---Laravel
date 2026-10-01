@props(['title' => ''])

<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4 pl-20 lg:pl-6">
        <h1 class="text-lg font-semibold text-gray-900">{{ $title }}</h1>

        <div class="relative">
            <button type="button" onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                </div>
                <x-avatar :user="auth()->user()" />
            </button>

            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-gray-200 bg-white shadow-lg py-1 z-30">
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                    <x-avatar :user="auth()->user()" size="w-10 h-10" />
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</p>
                    </div>
                </div>
                <button type="button" onclick="toggleDropdown('profile-dropdown'); openModal('profile-edit-modal')"
                    class="w-full flex items-center gap-2 text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <x-profile-edit-modal />
</header>