@extends('layouts.app')

@section('title', 'Kasabutan - Lost Item')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-kasabutan-lost-item" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Kasabutan - Lost Item" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center text-gray-500 text-sm">
                Kasabutan - Lost Item records will appear here.
            </div>
        </div>
    </div>
</div>
@endsection