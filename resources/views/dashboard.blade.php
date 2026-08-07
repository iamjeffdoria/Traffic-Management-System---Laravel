@extends('layouts.app')

@section('title', 'Traffic Management System')

@section('content')

<!-- Navbar -->
<nav class="border-b border-gray-200 bg-white/80 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                TMS
            </div>
            <span class="font-semibold text-gray-900 text-lg">Traffic Management System</span>
        </div>
        <a href="{{ route('login') }}" class="rounded-full bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
            Admin Login
        </a>
    </div>
</nav>

<!-- Hero -->
<section class="max-w-7xl mx-auto px-6 pt-20 pb-24 text-center">
    <span class="inline-block rounded-full bg-red-50 text-red-600 text-xs font-semibold px-4 py-1.5 mb-6">
        Now serving your municipality
    </span>
    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 tracking-tight leading-tight">
        Permits and registrations,
        <br class="hidden sm:block" />
        <span class="text-red-600">without the paperwork.</span>
    </h1>
    <p class="mt-6 text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
        A single system for tricycle and pedicab registration, mayor's permits,
        and ID card issuance — built for local government units to serve residents faster.
    </p>
    <div class="mt-10">
        <a href="{{ route('login') }}" class="inline-block rounded-full bg-red-600 text-white px-7 py-3.5 text-sm font-semibold shadow-lg shadow-red-600/25 hover:bg-red-700 transition-colors">
            Admin Login
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Traffic Management System. All rights reserved.</p>
        <div class="flex items-center gap-6 text-sm text-gray-500">
            <a href="#" class="hover:text-gray-900 transition-colors">Privacy</a>
            <a href="#" class="hover:text-gray-900 transition-colors">Terms</a>
        </div>
    </div>
</footer>

@endsection