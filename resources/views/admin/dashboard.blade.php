@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="lg:flex">
    <x-sidebar active="dashboard" />
    <div class="flex-1 max-w-7xl mx-auto px-6 pt-20 pb-8 lg:pt-12 lg:pb-12">
        <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->email }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded-full border border-gray-300 text-gray-700 px-5 py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors">
                Logout
            </button>
        </form>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Registered Vehicles</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">2,400+</p>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Active Permits</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">1,100+</p>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Potpot Registration</p>
            <a href="#" class="text-sm text-red-600 font-medium mt-2 inline-block">Manage →</a>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Tricycle Registration</p>
            <a href="#" class="text-sm text-red-600 font-medium mt-2 inline-block">Manage →</a>
        </div>
    </div>
</div>
@endsection