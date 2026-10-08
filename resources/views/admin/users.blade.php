@extends('layouts.app')

@section('title', 'Admin Management')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="admins" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Admin Management" />

        <div class="max-w-5xl mx-auto px-6 py-6">
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

            <!-- Add admin trigger -->
            <div class="flex justify-end">
                <button type="button" onclick="openModal('create-modal')"
                    class="rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                    + Add New Admin
                </button>
            </div>

            <x-admin-create-modal />

            <!-- Delete confirmation modal -->
            <div id="delete-confirm-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
                <div class="absolute inset-0 bg-black/50"></div>

                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-lg">Remove admin?</h3>
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
                $hasActiveAdminFilters = request()->filled('name') || request()->filled('email') || request()->filled('role');
            @endphp

            <form id="admin-filter-form" method="GET" action="{{ route('admin.users') }}"></form>

            <div class="mt-6">
                <!-- Desktop table -->
                <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                    <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                        <colgroup>
                            <col class="w-24">
                            <col class="w-20">
                            <col class="w-[28%]">
                            <col class="w-[34%]">
                            <col class="w-[20%]">
                        </colgroup>
                        <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                            <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                                <th class="px-6 py-3 font-bold w-24">Actions</th>
                                <th class="px-4 py-3 font-bold w-20">Photo</th>
                                <th class="px-6 py-3 font-bold">Name</th>
                                <th class="px-6 py-3 font-bold">Email</th>
                                <th class="px-6 py-3 font-bold">Role</th>
                            </tr>
                            <tr class="border-t border-gray-300 divide-x divide-gray-300">
                                <th class="px-6 py-2"></th>
                                <th class="px-4 py-2"></th>
                                <th class="px-2 py-2">
                                    <input type="text" name="name" form="admin-filter-form" data-filter-scope="desktop" value="{{ request('name') }}" oninput="debouncedFetchAdminFilter()"
                                        class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-2 py-2">
                                    <input type="text" name="email" form="admin-filter-form" data-filter-scope="desktop" value="{{ request('email') }}" oninput="debouncedFetchAdminFilter()"
                                        class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium placeholder-gray-500 px-2 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                </th>
                                <th class="px-1.5 py-2">
                                    <select name="role" form="admin-filter-form" data-filter-scope="desktop" onchange="debouncedFetchAdminFilter()"
                                        class="w-full max-w-full truncate rounded-lg border-2 border-gray-400 text-gray-900 font-medium pl-1.5 pr-0.5 py-2 text-xs focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
                                        <option value="">All roles</option>
                                        <option value="superadmin" @selected(request('role') === 'superadmin')>Super Admin</option>
                                        <option value="potpot_admin" @selected(request('role') === 'potpot_admin')>Potpot Admin</option>
                                        <option value="tricycle_admin" @selected(request('role') === 'tricycle_admin')>Tricycle Admin</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="admin-tbody-desktop" class="divide-y divide-gray-300">
                            @forelse ($admins as $admin)
                                <x-admin-table-row :admin="$admin" />
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                                        @if ($hasActiveAdminFilters)
                                            No admins match your search.
                                            <a href="{{ route('admin.users') }}" data-ajax-admin-link class="text-red-600 font-medium ml-1">Clear filters</a>
                                        @else
                                            No admin accounts yet.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="admin-pagination-desktop" class="mt-4">
                    @unless ($admins->isEmpty())
                        {{ $admins->links() }}
                    @endunless
                </div>

                <!-- Mobile search filters -->
                <div class="lg:hidden space-y-2 mb-4">
                    <input type="text" name="name" form="admin-filter-form" data-filter-scope="mobile" value="{{ request('name') }}" oninput="debouncedFetchAdminFilter()" placeholder="Search name..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <input type="text" name="email" form="admin-filter-form" data-filter-scope="mobile" value="{{ request('email') }}" oninput="debouncedFetchAdminFilter()" placeholder="Search email..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <select name="role" form="admin-filter-form" data-filter-scope="mobile" onchange="debouncedFetchAdminFilter()"
                        class="w-full max-w-full truncate rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                        <option value="">All roles</option>
                        <option value="superadmin" @selected(request('role') === 'superadmin')>Super Admin</option>
                        <option value="potpot_admin" @selected(request('role') === 'potpot_admin')>Potpot Admin</option>
                        <option value="tricycle_admin" @selected(request('role') === 'tricycle_admin')>Tricycle Admin</option>
                    </select>
                </div>

                <!-- Mobile stacked cards -->
                <div id="admin-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                    @forelse ($admins as $admin)
                        <x-admin-mobile-card :admin="$admin" />
                    @empty
                        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                            @if ($hasActiveAdminFilters)
                                No admins match your search.
                                <a href="{{ route('admin.users') }}" data-ajax-admin-link class="text-red-600 font-medium ml-1">Clear filters</a>
                            @else
                                No admin accounts yet.
                            @endif
                        </div>
                    @endforelse
                </div>

                <div id="admin-pagination-mobile" class="lg:hidden mt-4">
                    @unless ($admins->isEmpty())
                        {{ $admins->links() }}
                    @endunless
                </div>

                <div id="admin-edit-modals">
                    <x-admin-edit-modal />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection