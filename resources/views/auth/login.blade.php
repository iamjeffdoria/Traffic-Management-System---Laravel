@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="min-h-screen lg:h-screen grid lg:grid-cols-2 lg:grid-rows-[minmax(0,1fr)] neo-bg relative cartoon-bg overflow-hidden">

    {{-- Decorative floating blobs (cartoony background) --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
    </div>

    {{-- Brand panel (desktop only) --}}
    <aside class="relative z-10 hidden lg:flex flex-col justify-between gap-4 px-12 py-8 min-h-0">
        <div class="flex items-center gap-6">
            <div class="neo-raised w-36 h-36 rounded-full flex items-center justify-center shrink-0 scene-pop-in">
                <div class="neo-inset w-28 h-28 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-[6.5rem] h-[6.5rem] object-contain drop-shadow-md scene-float">
                </div>
            </div>
            <span class="font-extrabold text-2xl leading-tight uppercase tracking-wider text-gray-700">Public Safety<br>Office</span>
        </div>

        <div class="max-w-md">
            <div class="neo-inset rounded-3xl p-6 scene-pop-in scene-delay-2">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-700">
                    Permits, franchises &amp; records in <span class="text-gradient-red">one place.</span>
                </h1>
                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Manage tricycle and potpot registrations, mayor's permits, ID cards, and document submissions.
                </p>

                <div class="mt-6 flex flex-wrap gap-2.5">
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Tricycle</span>
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-blue-500"></span>Potpot</span>
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-amber-500"></span>Tanod</span>
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-purple-500"></span>Mayor's Permits</span>
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-red-500"></span>ID Cards</span>
                    <span class="neo-chip"><span class="w-2 h-2 rounded-full bg-cyan-500"></span>QR Verification</span>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="neo-raised-sm rounded-2xl px-4 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Today</p>
                        <p id="login-date" class="mt-0.5 text-sm font-bold text-gray-700">&mdash;</p>
                    </div>
                    <div class="neo-raised-sm rounded-2xl px-4 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Local Time</p>
                        <p id="login-clock" class="mt-0.5 text-sm font-bold text-gray-700 tabular-nums">--:--:--</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 text-xs font-medium text-gray-500">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="status-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    </span>
                    System online
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500">
            &copy; {{ date('Y') }} Public Safety Office
        </p>
    </aside>

    {{-- Form panel --}}
    <main class="relative z-10 flex items-center justify-center px-6 py-6 lg:py-4 min-h-0">
        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex flex-col items-center gap-3 mb-6 scene-pop-in">
                <div class="neo-raised w-32 h-32 rounded-full flex items-center justify-center">
                    <div class="neo-inset w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/csulogo2.png') }}" alt="PSO Logo" class="w-[5.5rem] h-[5.5rem] object-contain drop-shadow-md scene-float">
                    </div>
                </div>
                <span class="text-base font-extrabold uppercase tracking-wider text-gray-700">Public Safety Office</span>
            </div>

            <div class="neo-raised rounded-3xl p-7 login-card-enter relative overflow-hidden">
                <div class="login-accent-bar" aria-hidden="true"></div>
                <span class="neo-badge">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Authorized personnel only
                </span>
                <h2 class="mt-4 text-2xl font-bold text-gray-700 tracking-tight"><span id="login-greeting">Welcome back</span></h2>
                <p class="mt-1 text-sm text-gray-500">Sign in to your account to continue.</p>

                @if ($errors->any())
                    <div class="login-shake neo-inset mt-6 flex items-start gap-2 rounded-xl text-red-600 text-sm px-4 py-3">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                    @csrf

                    <div class="scene-pop-in scene-delay-1">
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <div class="neo-input-wrap relative">
                            <span class="neo-input-icon pointer-events-none absolute left-4 top-1/2 -translate-y-1/2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', request()->cookie('remembered_email')) }}" required autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                                class="neo-input w-full rounded-xl pl-12 pr-4 py-3 text-sm">
                        </div>
                    </div>

                    <div class="scene-pop-in scene-delay-2">
                        <label for="password" class="block text-sm font-medium text-gray-600 mb-2">Password</label>
                        <div class="neo-input-wrap relative">
                            <span class="neo-input-icon pointer-events-none absolute left-4 top-1/2 -translate-y-1/2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="neo-input w-full rounded-xl pl-12 pr-14 py-3 text-sm">
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

                    <p id="caps-warning" class="hidden -mt-3 text-xs font-medium text-amber-600">
                        Caps Lock is on
                    </p>

                    <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer select-none scene-pop-in scene-delay-3">
                        <input type="checkbox" name="remember" @checked(old('remember')) class="neo-check">
                        Remember me
                    </label>

                    <button type="submit" id="login-btn"
                        class="neo-btn btn-shine relative overflow-hidden w-full rounded-xl px-6 py-3 text-sm font-semibold tracking-wide scene-pop-in scene-delay-4 inline-flex items-center justify-center gap-2">
                        <svg id="login-btn-spinner" class="login-spinner hidden w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span id="login-btn-label">Sign In</span>
                        <svg class="btn-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-gray-500">
                    Trouble signing in? <span class="font-medium text-gray-600">Contact your administrator.</span>
                </p>
            </div>

            <p class="lg:hidden mt-4 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Public Safety Office
            </p>
        </div>
    </main>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('login-btn');
        const label = document.getElementById('login-btn-label');
        const spinner = document.getElementById('login-btn-spinner');
        const password = document.getElementById('password');
        const caps = document.getElementById('caps-warning');

        // Live date/time (Philippine time) + time-based greeting
        const clockEl = document.getElementById('login-clock');
        const dateEl = document.getElementById('login-date');
        const greetEl = document.getElementById('login-greeting');

        const tick = () => {
            const now = new Date();
            const tz = { timeZone: 'Asia/Manila' };

            if (clockEl) {
                clockEl.textContent = now.toLocaleTimeString('en-US', { ...tz, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('en-US', { ...tz, weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
            }
            if (greetEl) {
                const hour = parseInt(now.toLocaleString('en-US', { ...tz, hour: 'numeric', hour12: false }), 10) % 24;
                greetEl.textContent = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
            }
        };
        tick();
        setInterval(tick, 1000);

        if (!btn || !password) return;

        // Caps Lock warning while typing the password
        const checkCaps = (event) => {
            if (!event.getModifierState) return;
            caps.classList.toggle('hidden', !event.getModifierState('CapsLock'));
        };
        password.addEventListener('keydown', checkCaps);
        password.addEventListener('keyup', checkCaps);
        password.addEventListener('blur', () => caps.classList.add('hidden'));

        // Loading state on submit
        btn.form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-wait');
            label.textContent = 'Signing in...';
            spinner.classList.remove('hidden');
        });

        // Reset the button if the user returns with the browser's back button
        window.addEventListener('pageshow', (event) => {
            if (!event.persisted) return;
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-wait');
            label.textContent = 'Sign In';
            spinner.classList.add('hidden');
        });
    });
</script>
@endsection