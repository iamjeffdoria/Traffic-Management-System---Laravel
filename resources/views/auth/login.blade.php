@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#0b1130] px-6 py-12">

    {{-- Animated traffic scene --}}
    <div class="absolute inset-0 scene-fade-in" aria-hidden="true">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0b1130] via-[#161d45] to-[#232a5c]"></div>

        @for ($i = 0; $i < 50; $i++)
            <span class="absolute rounded-full bg-white star"
                style="top: {{ rand(0, 55) }}%; left: {{ rand(0, 100) }}%; width: {{ rand(1, 2) }}px; height: {{ rand(1, 2) }}px; opacity: {{ rand(30, 90) / 100 }}; animation-delay: {{ rand(0, 40) / 10 }}s;"></span>
        @endfor

        {{-- Bottom highway strip with real moving traffic --}}
        <div class="absolute inset-x-0 bottom-0 h-24 sm:h-32 bg-[#12162e] border-t border-white/10 overflow-hidden highway-rise">
            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-[3px] lane-dashes"></div>

            {{-- Traffic light standing on the road --}}
            <div class="absolute right-10 sm:right-24 bottom-0 flex flex-col items-center z-10 pole-drop">
                <div class="flex flex-col items-center gap-1.5 bg-gray-900 rounded-md px-1.5 py-2 shadow-[0_0_25px_rgba(0,0,0,0.6)]">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500" style="animation: tl-red 3s ease-in-out infinite;"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400" style="animation: tl-yellow 3s ease-in-out infinite;"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500" style="animation: tl-green 3s ease-in-out infinite;"></span>
                </div>
                <div class="w-1.5 h-16 sm:h-20 bg-gray-700"></div>
                <div class="w-6 h-1.5 bg-gray-800 rounded-full"></div>
            </div>

            @php $carColors = ['#ef4444', '#3b82f6', '#eab308', '#22c55e', '#f97316', '#a855f7']; @endphp

            {{-- Lane 1: right to left --}}
            @for ($i = 0; $i < 4; $i++)
                <div class="car car-rtl" style="
                    top: {{ rand(8, 30) }}%;
                    --car-color: {{ $carColors[array_rand($carColors)] }};
                    animation-duration: {{ rand(45, 75) / 10 }}s;
                    animation-delay: {{ $i * 1.8 }}s;">
                    <span class="car-cabin"></span>
                    <span class="car-body"></span>
                    <span class="car-wheel left"></span>
                    <span class="car-wheel right"></span>
                    <span class="car-light front"></span>
                    <span class="car-light back"></span>
                </div>
            @endfor

            {{-- Lane 2: left to right --}}
            @for ($i = 0; $i < 4; $i++)
                <div class="car car-ltr" style="
                    top: {{ rand(60, 82) }}%;
                    --car-color: {{ $carColors[array_rand($carColors)] }};
                    animation-duration: {{ rand(45, 75) / 10 }}s;
                    animation-delay: {{ $i * 2 }}s;">
                    <span class="car-cabin"></span>
                    <span class="car-body"></span>
                    <span class="car-wheel left"></span>
                    <span class="car-wheel right"></span>
                    <span class="car-light front"></span>
                    <span class="car-light back"></span>
                </div>
            @endfor
        </div>

        {{-- Jet flying past --}}
        <div class="jet">
            <span class="jet-trail"></span>
            <span class="jet-wing top"></span>
            <span class="jet-wing bottom"></span>
            <span class="jet-body"></span>
            <span class="jet-tail-light"></span>
        </div>

        <div class="absolute inset-0 bg-gradient-to-t from-transparent via-transparent to-[#0b1130]/40"></div>
    </div>

    <div class="relative z-10 w-full max-w-sm">

        <div class="bg-white rounded-2xl px-8 py-10 shadow-2xl login-card-enter">
            <div class="flex flex-col items-center gap-3 mb-8">
                <img src="{{ asset('images/csulogo2.png') }}" alt="Logo" class="w-14 h-14 object-contain">
                <span class="font-medium text-gray-900 text-sm">Traffic Management System</span>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 tracking-tight text-center">Welcome back</h2>
            <p class="mt-1.5 text-sm text-gray-400 text-center">Sign in to continue</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                @csrf
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Email"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-shadow">
                </div>

                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Password"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 pr-11 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-shadow">
                    <button type="button" onclick="togglePassword('password', 'eye-open', 'eye-closed')"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                        <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-500">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                    Remember me
                </label>

                <button type="submit"
                    class="w-full rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                    Log In
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Traffic Management System
            </p>
        </div>
    </div>
</section>
@endsection