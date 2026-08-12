@extends('layouts.app')

@section('title', "Mayor's Permit - Tricycle")

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-mayors-permit" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Mayor's Permit - Tricycle" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="rounded-2xl border border-gray-200 p-10 text-center text-gray-500 text-sm">
                Mayor's Permit module coming soon.
            </div>
        </div>
    </div>
</div>
@endsection