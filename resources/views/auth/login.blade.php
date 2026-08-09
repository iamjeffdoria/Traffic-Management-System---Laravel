@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex">

    <!-- Left: Branding panel -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 overflow-hidden">
        <!-- Signature route-line motif -->
        <svg class="absolute inset-0 w-full h-full opacity-[0.15]" viewBox="0 0 600 800" fill="none" preserveAspectRatio="xMidYMid slice">
            <path d="M-50 650 C 100 650, 150 500, 280 480 S 450 350, 480 200 S 400 50, 550 -50"
                stroke="white" stroke-width="2" stroke-dasharray="1 14" stroke-linecap="round" />
            <path d="M-50 750 C 120 720, 200 600, 320 560 S 480 420, 500 280 S 550 100, 650 20"
                stroke="white" stroke-width="2" stroke-dasharray="1 14" stroke-linecap="round" />
            <circle cx="280" cy="480" r="5" fill="white" />
            <circle cx="480" cy="200" r="5" fill="white" />
            <circle cx="320" cy="560" r="5" fill="white" />
            <circle cx="500" cy="280" r="5" fill="white" />
        </svg>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                    TMS
                </div>
                <span class="font-semibold text-white text-lg">Traffic Management System</span>
            </div>

            <div class="max-w-md">
                <span class="inline-block rounded-full bg-white/10 text-red-400 text-xs font-semibold px-4 py-1.5 mb-6">
                    Municipal admin access
                </span>
                <h1 class="text-4xl font-bold text-white tracking-tight leading-tight">
                    Every permit,
                    <br />
                    <span class="text-red-500">every route, one system.</span>
                </h1>
                <p class="mt-5 text-gray-400 leading-relaxed">
                    Sign in to manage tricycle and pedicab registrations, issue permits,
                    and keep your municipality's records up to date.
                </p>
            </div>

            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Traffic Management System. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Right: Form panel -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-white">
        <div class="w-full max-w-sm">

            <!-- Mobile-only logo (hidden on lg since the left panel shows it there) -->
            <div class="lg:hidden flex items-center gap-2 mb-10">
                <div class="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                    TMS
                </div>
                <span class="font-semibold text-gray-900 text-lg">Traffic Management System</span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
            <p class="mt-2 text-sm text-gray-500">Sign in with your admin credentials to continue.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="you@municipality.gov"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-shadow">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-11 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-shadow">
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
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                    class="w-full rounded-full bg-red-600 text-white px-6 py-3 text-sm font-semibold shadow-lg shadow-red-600/25 hover:bg-red-700 transition-colors">
                    Log In
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-gray-400">
                Trouble signing in? Contact your system administrator.
            </p>
        </div>
    </div>

</div>
@endsection