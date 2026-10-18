@extends('layouts.app')

@section('title', 'Tanod ID')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tanod-id" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Tanod ID" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            {{-- Tanod ID UI goes here --}}
        </div>
    </div>
</div>
@endsection