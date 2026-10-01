@props(['user', 'size' => 'w-9 h-9', 'text' => 'text-sm', 'color' => 'bg-red-600'])

@if ($user->photo_url)
    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"
        class="{{ $size }} rounded-full object-cover shrink-0 border border-gray-200">
@else
    <div class="{{ $size }} {{ $text }} {{ $color }} rounded-full flex items-center justify-center text-white font-semibold shrink-0">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
@endif