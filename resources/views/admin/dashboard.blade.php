@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="dashboard" />
    <div class="flex-1 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Dashboard" />

        <div class="max-w-7xl mx-auto px-6 pt-8 pb-8">
            <p class="text-gray-500 text-sm mb-6">Welcome back, {{ auth()->user()->name }}.</p>

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
                    <a href="{{ route('potpot.index') }}" class="text-sm text-red-600 font-medium mt-2 inline-block">Manage →</a>
                </div>
                <div class="rounded-2xl border border-gray-200 p-6">
                    <p class="text-sm text-gray-500">Tricycle Registration</p>
                    <a href="{{ route('tricycle.index') }}" class="text-sm text-red-600 font-medium mt-2 inline-block">Manage →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection