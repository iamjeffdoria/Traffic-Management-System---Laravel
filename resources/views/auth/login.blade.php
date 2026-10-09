@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="min-h-screen grid lg:grid-cols-2 bg-white">

    {{-- Brand panel (desktop only) --}}
    <aside class="hidden lg:flex relative flex-col justify-between bg-gray-900 text-white p-12 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-red-600/20"></div>
        <div class="absolute -bottom-32 -left-20 w-96 h-96 rounded-full bg-red-600/10"></div>

        <div class="relative flex items-center gap-3">
            <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-11 h-11 object-contain">
            <span class="font-bold text-sm leading-tight uppercase tracking-wide">Public Safety<br>Office</span>
        </div>

        <div class="relative max-w-md">
            <h1 class="text-4xl font-bold leading-tight tracking-tight">
                Permits, franchises &amp; records in one place.
            </h1>
            <p class="mt-4 text-gray-400 text-sm leading-relaxed">
                Manage tricycle and potpot registrations, mayor's permits, ID cards, and document submissions.
            </p>
        </div>

        <p class="relative text-xs text-gray-500">
            &copy; {{ date('Y') }} Public Safety Office
        </p>
    </aside>

    {{-- Form panel --}}
    <main class="flex items-center justify-center bg-gray-50 lg:bg-white px-6 py-12">
        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex flex-col items-center gap-3 mb-8">
                <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-16 h-16 object-contain">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Public Safety Office</span>
            </div>

            <div class="bg-white lg:bg-transparent rounded-2xl border border-gray-200 lg:border-0 shadow-sm lg:shadow-none p-8 lg:p-0">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
                <p class="mt-1 text-sm text-gray-500">Sign in to your account to continue.</p>

                @if ($errors->any())
                    <div class="mt-6 flex items-start gap-2 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', request()->cookie('remembered_email')) }}" required autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm placeholder:text-gray-400 focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/20 transition">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-11 text-sm placeholder:text-gray-400 focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/20 transition">
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

                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" @checked(old('remember'))
                            class="rounded border-gray-300 text-red-600 focus:ring-red-600/30">
                        Remember me
                    </label>

                    <button type="submit"
                        class="w-full rounded-lg bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600/40 focus:ring-offset-2 transition-colors">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="lg:hidden mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Public Safety Office
            </p>
        </div>
    </main>
</section>
@endsection