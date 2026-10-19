@extends('layouts.app')

@section('title', 'Tanod ID')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tanod-id" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Tanod ID" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3 max-h-64 overflow-y-auto">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Reopen the modal so the user can fix the fields (old values are kept) --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => openModal('create-tanod-id-modal'));
                </script>
            @endif

            <x-tanod-id-toolbar />

            <x-tanod-id-create-modal :tanods="$tanods" />

            <form id="tanod-id-filter-form" method="GET" action="{{ route('tanod.id') }}"></form>

            <div class="rounded-2xl border border-gray-200 bg-white overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-gray-900 bg-gray-50">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-4 py-3 font-bold w-10">
                                <input type="checkbox" class="rounded border-gray-300 text-red-600 focus:ring-red-600" onchange="toggleAllTanodIdCheckboxes(this)" title="Select up to 4">
                            </th>
                            <th class="px-6 py-3 font-bold w-28">Actions</th>
                            <th class="px-6 py-3 font-bold">ID No.</th>
                            <th class="px-6 py-3 font-bold">Full Name</th>
                            <th class="px-6 py-3 font-bold">Address</th>
                            <th class="px-6 py-3 font-bold">Date of Birth</th>
                            <th class="px-6 py-3 font-bold">Emergency Contact</th>
                        </tr>
                        <tr class="border-t border-gray-300 divide-x divide-gray-300">
                            <th class="px-4 py-2"></th>
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="id_number" form="tanod-id-filter-form" value="{{ request('id_number') }}" oninput="debouncedFetchTanodIdFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="name" form="tanod-id-filter-form" value="{{ request('name') }}" oninput="debouncedFetchTanodIdFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-2 py-2">
                                <input type="text" name="address" form="tanod-id-filter-form" value="{{ request('address') }}" oninput="debouncedFetchTanodIdFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2"></th>
                            <th class="px-2 py-2">
                                <input type="text" name="contact" form="tanod-id-filter-form" value="{{ request('contact') }}" oninput="debouncedFetchTanodIdFilter()"
                                    class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                            </th>
                        </tr>
                    </thead>
                    @include('admin.partials.tanod-id-tbody')
                </table>
            </div>

            <div id="tanod-id-pagination" class="mt-4">
                @unless ($tanodIds->isEmpty())
                    {{ $tanodIds->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection