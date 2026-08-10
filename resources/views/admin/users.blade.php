@extends('layouts.app')

@section('title', 'Admin Management')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="admins" />
    <div class="flex-1 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
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
            <div onclick="closeModal('delete-confirm-modal')" class="absolute inset-0 bg-black/50"></div>

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

        <!-- Admin list: table on desktop, stacked cards on mobile -->
        <div class="mt-6">

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium w-24">Actions</th>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Email</th>
                            <th class="px-6 py-3 font-medium">Role</th>
                        </tr>
                        <tr class="border-t border-gray-200">
                            <th class="px-6 py-2"></th>
                            <th class="px-6 py-2">
                                <input type="text" id="filter-name" oninput="filterAdminTable()" placeholder="Search name..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-normal focus:outline-none focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2">
                                <input type="text" id="filter-email" oninput="filterAdminTable()" placeholder="Search email..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-normal focus:outline-none focus:ring-2 focus:ring-red-600">
                            </th>
                            <th class="px-6 py-2">
                                <select id="filter-role" onchange="filterAdminTable()"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-normal focus:outline-none focus:ring-2 focus:ring-red-600">
                                    <option value="">All roles</option>
                                    <option value="superadmin">Super Admin</option>
                                    <option value="potpot_admin">Potpot Admin</option>
                                    <option value="tricycle_admin">Tricycle Admin</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($admins as $admin)
                            <tr data-admin-row data-name="{{ $admin->name }}" data-email="{{ $admin->email }}" data-role="{{ $admin->role }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="openModal('edit-modal-{{ $admin->id }}')" title="Edit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        @if ($admin->id !== auth()->id())
                                            <form id="delete-form-{{ $admin->id }}" method="POST" action="{{ route('admin.users.destroy', $admin) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" onclick="confirmDelete('delete-form-{{ $admin->id }}', '{{ $admin->name }}')" title="Delete"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-900 font-medium">{{ $admin->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $admin->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-full bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1">
                                        {{ str_replace('_', ' ', ucfirst($admin->role)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile search filters -->
            <div class="lg:hidden space-y-2 mb-4">
                <input type="text" id="filter-name-mobile" oninput="filterAdminTable()" placeholder="Search name..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <input type="text" id="filter-email-mobile" oninput="filterAdminTable()" placeholder="Search email..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select id="filter-role-mobile" onchange="filterAdminTable()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All roles</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="potpot_admin">Potpot Admin</option>
                    <option value="tricycle_admin">Tricycle Admin</option>
                </select>
            </div>

            <!-- Mobile stacked cards -->
            <div class="lg:hidden space-y-3">
                @foreach ($admins as $admin)
                    <div data-admin-row data-name="{{ $admin->name }}" data-email="{{ $admin->email }}" data-role="{{ $admin->role }}"
                        class="rounded-2xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-gray-900 font-medium truncate">{{ $admin->name }}</p>
                                <p class="text-gray-500 text-sm truncate">{{ $admin->email }}</p>
                                <span class="inline-block mt-2 rounded-full bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1">
                                    {{ str_replace('_', ' ', ucfirst($admin->role)) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" onclick="openModal('edit-modal-{{ $admin->id }}')" title="Edit"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                @if ($admin->id !== auth()->id())
                                    <form id="delete-form-mobile-{{ $admin->id }}" method="POST" action="{{ route('admin.users.destroy', $admin) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDelete('delete-form-mobile-{{ $admin->id }}', '{{ $admin->name }}')" title="Delete"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        @foreach ($admins as $admin)
            <x-admin-edit-modal :admin="$admin" />
        @endforeach
    </div>
    </div>
</div>
@endsection