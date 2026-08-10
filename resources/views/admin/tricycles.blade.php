@extends('layouts.app')

@section('title', 'Tricycle List')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-list" />
    <div class="flex-1 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Tricycle List" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <div class="inline-flex items-center overflow-hidden rounded-full">
                    <button type="button"
                        class="inline-flex items-center gap-1 bg-emerald-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-emerald-700 transition-colors border-r border-emerald-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                        </svg>
                        Import File
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-1 bg-orange-500 text-white px-3 py-1.5 text-xs font-semibold hover:bg-orange-600 transition-colors border-r border-orange-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12" />
                        </svg>
                        Export File
                    </button>
                    <button type="button" onclick="openModal('create-tricycle-modal')"
                        class="inline-flex items-center gap-1 bg-red-600 text-white px-4 py-1.5 text-xs font-semibold hover:bg-red-700 transition-colors">
                        + Add Tricycle
                    </button>
                </div>
            </div>

            <x-tricycle-create-modal />

            <!-- Delete confirmation modal -->
            <div id="delete-confirm-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
                <div onclick="closeModal('delete-confirm-modal')" class="absolute inset-0 bg-black/50"></div>

                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-lg">Remove tricycle?</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Are you sure you want to remove <span id="delete-confirm-name" class="font-medium text-gray-700"></span>? This can't be undone.
                    </p>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeModal('delete-confirm-modal')"
                            class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="submitPendingDelete()"
                            class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            @php
                $hasActiveFilters = request()->filled('body') || request()->filled('plate') || request()->filled('name') || request()->filled('address') || request()->filled('status');
            @endphp

           <form id="tricycle-filter-form" method="GET" action="{{ route('tricycle.list') }}"></form>

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-50 text-left text-gray-900">
                            <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                                <th class="px-6 py-3 font-bold w-24">Actions</th>
                                <th class="px-6 py-3 font-bold">Body No.</th>
                                <th class="px-6 py-3 font-bold">Plate No.</th>
                                <th class="px-6 py-3 font-bold">Owner</th>
                                <th class="px-6 py-3 font-bold">Vehicle / Address</th>
                                <th class="px-6 py-3 font-bold">Status</th>
                                <th class="px-6 py-3 font-bold">Registration / Expiry</th>
                            </tr>
                            <tr class="border-t border-gray-300 divide-x divide-gray-300">
                                <th class="px-6 py-2"></th>
                                <th class="px-2 py-2">
                                    <input type="text" name="body" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('body') }}" oninput="debouncedFetchFilter()" placeholder="Body no..."
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-4 py-2">
                                    <input type="text" name="plate" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('plate') }}" oninput="debouncedFetchFilter()" placeholder="Search plate no..."
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1.5 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-2 py-2">
                                    <input type="text" name="name" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchFilter()" placeholder="Owner..."
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-4 py-2">
                                    <input type="text" name="address" form="tricycle-filter-form" data-filter-scope="desktop" value="{{ request('address') }}" oninput="debouncedFetchFilter()" placeholder="Search address..."
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-2 py-2">
                                    <select name="status" form="tricycle-filter-form" data-filter-scope="desktop" onchange="debouncedFetchFilter()"
                                        class="w-full rounded-lg border-2 border-gray-400 text-gray-900 font-medium px-2 py-1 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                        <option value="">All statuses</option>
                                        <option value="active" @selected(request('status') === 'active')>Active</option>
                                        <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                                        <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                                    </select>
                                </th>
                                <th class="px-6 py-2"></th>
                            </tr>
                    </thead>
                    <tbody id="tricycle-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($tricycles as $tricycle)
                            <tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="openModal('edit-tricycle-modal-{{ $tricycle->id }}')" title="Edit"
                                            class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form id="delete-tricycle-form-{{ $tricycle->id }}" method="POST" action="{{ route('tricycle.destroy', $tricycle) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" onclick="confirmDelete('delete-tricycle-form-{{ $tricycle->id }}', '{{ $tricycle->body_number }} ({{ $tricycle->plate_no }})')" title="Delete"
                                            class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 font-medium">{{ $tricycle->body_number }}</p>
                                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5">
                                        {{ $tricycle->chassis_no }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 font-semibold">{{ $tricycle->plate_no }}</p>
                                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1 mt-1.5">
                                        {{ $tricycle->engine_motor_no }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 font-medium">{{ $tricycle->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($tricycle->address)
                                        <p class="text-gray-800 text-xs font-semibold mb-1">{{ $tricycle->address }}</p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-1">
                                        <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">
                                            {{ $tricycle->make_kind }}
                                        </span>
                                        @if ($tricycle->toda)
                                            <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1">
                                                {{ $tricycle->toda }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-teal-500',
                                            'renewed' => 'bg-teal-500',
                                            'expired' => 'bg-red-600',
                                        ];
                                    @endphp
                                    <span class="inline-block rounded-full {{ $statusColors[$tricycle->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                                        {{ ucfirst($tricycle->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                                            {{ $tricycle->date_registered->format('M-d-y') }}
                                        </span>
                                        <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 w-fit whitespace-nowrap">
                                            {{ $tricycle->date_expired->format('M-d-y') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    @if ($hasActiveFilters)
                                        No tricycles match your search.
                                        <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                    @else
                                        No tricycles registered yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="tricycle-pagination-desktop" class="mt-4">
                {{ $tricycles->links() }}
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" name="body" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('body') }}" oninput="debouncedFetchFilter()" placeholder="Search body no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="plate" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('plate') }}" oninput="debouncedFetchFilter()" placeholder="Search plate no..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="name" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchFilter()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" name="address" form="tricycle-filter-form" data-filter-scope="mobile" value="{{ request('address') }}" oninput="debouncedFetchFilter()" placeholder="Search address..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" form="tricycle-filter-form" data-filter-scope="mobile" onchange="debouncedFetchFilter()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="renewed" @selected(request('status') === 'renewed')>Renewed</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                </select>
            </div>

            <!-- Mobile stacked cards -->
            <div id="tricycle-cards-mobile" class="lg:hidden space-y-3">
                @forelse ($tricycles as $tricycle)
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-gray-900 font-medium truncate">{{ $tricycle->body_number }} — {{ $tricycle->plate_no }}</p>
                                <p class="text-gray-500 text-sm truncate">{{ $tricycle->name }}</p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-teal-500',
                                            'renewed' => 'bg-teal-500',
                                            'expired' => 'bg-red-600',
                                        ];
                                    @endphp
                                    <span class="inline-block rounded-full {{ $statusColors[$tricycle->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                                        {{ ucfirst($tricycle->status) }}
                                    </span>
                                    <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">
                                        {{ $tricycle->make_kind }}
                                    </span>
                                    @if ($tricycle->toda)
                                        <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1">
                                            {{ $tricycle->toda }}
                                        </span>
                                    @endif
                                    @if ($tricycle->address)
                                        <span class="inline-block rounded-full bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1">
                                            {{ $tricycle->address }}
                                        </span>
                                    @endif
                                </div>
                               <div class="flex flex-wrap gap-1 mt-1.5">
                                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">
                                        {{ $tricycle->engine_motor_no }}
                                    </span>
                                    <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">
                                        {{ $tricycle->chassis_no }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 mt-1.5">
                                    <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                        {{ $tricycle->date_registered->format('M-d-y') }}
                                    </span>
                                    <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                                        {{ $tricycle->date_expired->format('M-d-y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" onclick="openModal('edit-tricycle-modal-{{ $tricycle->id }}')" title="Edit"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form id="delete-tricycle-form-mobile-{{ $tricycle->id }}" method="POST" action="{{ route('tricycle.destroy', $tricycle) }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete('delete-tricycle-form-mobile-{{ $tricycle->id }}', '{{ $tricycle->body_number }} ({{ $tricycle->plate_no }})')" title="Delete"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        @if ($hasActiveFilters)
                            No tricycles match your search.
                            <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                        @else
                            No tricycles registered yet.
                        @endif
                    </div>
                @endforelse
            </div>

            <div id="tricycle-pagination-mobile" class="lg:hidden mt-4">
                {{ $tricycles->links() }}
            </div>

            <div id="tricycle-edit-modals">
                @foreach ($tricycles as $tricycle)
                    <x-tricycle-edit-modal :tricycle="$tricycle" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection