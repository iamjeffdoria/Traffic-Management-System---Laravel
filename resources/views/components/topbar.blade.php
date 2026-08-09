@props(['title' => ''])

<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4 pl-20 lg:pl-6">
        <h1 class="text-lg font-semibold text-gray-900">{{ $title }}</h1>

        <div class="relative">
            <button type="button" onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center text-white font-semibold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </button>

            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-44 rounded-xl border border-gray-200 bg-white shadow-lg py-1 z-30">
                <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>