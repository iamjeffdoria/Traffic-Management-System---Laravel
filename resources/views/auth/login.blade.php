@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="min-h-screen grid lg:grid-cols-2 neo-bg">

    {{-- Brand panel (desktop only) --}}
    <aside class="hidden lg:flex flex-col justify-between p-14">
        <div class="flex items-center gap-4">
            <div class="neo-raised w-16 h-16 rounded-full flex items-center justify-center">
                <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-10 h-10 object-contain">
            </div>
            <span class="font-bold text-sm leading-tight uppercase tracking-wide text-gray-600">Public Safety<br>Office</span>
        </div>

        <div class="max-w-md">
            <div class="neo-inset rounded-3xl p-8">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-700">
                    Permits, franchises &amp; records in one place.
                </h1>
                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Manage tricycle and potpot registrations, mayor's permits, ID cards, and document submissions.
                </p>
            </div>
        </div>

        <p class="text-xs text-gray-500">
            &copy; {{ date('Y') }} Public Safety Office
        </p>
    </aside>

    {{-- Form panel --}}
    <main class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex flex-col items-center gap-3 mb-8">
                <div class="neo-raised w-20 h-20 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-12 h-12 object-contain">
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Public Safety Office</span>
            </div>

            <div class="neo-raised rounded-3xl p-8 login-card-enter">
                <h2 class="text-2xl font-bold text-gray-700 tracking-tight">Welcome back</h2>
                <p class="mt-1 text-sm text-gray-500">Sign in to your account to continue.</p>

                @if ($errors->any())
                    <div class="neo-inset mt-6 flex items-start gap-2 rounded-xl text-red-600 text-sm px-4 py-3">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', request()->cookie('remembered_email')) }}" required autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                            class="neo-input w-full rounded-xl px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-600 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="neo-input w-full rounded-xl px-4 py-3 pr-14 text-sm">
                            <button type="button" onclick="togglePassword('password', 'eye-open', 'eye-closed')"
                                class="neo-raised-sm absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:text-gray-700">
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

                    <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" @checked(old('remember')) class="neo-check">
                        Remember me
                    </label>

                    <button type="submit"
                        class="neo-btn w-full rounded-xl px-6 py-3 text-sm font-semibold tracking-wide">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="lg:hidden mt-8 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Public Safety Office
            </p>
        </div>
    </main>
</section>
@endsection