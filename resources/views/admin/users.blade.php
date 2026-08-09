@extends('layouts.app')

@section('title', 'Admin Management')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="admins" />
    <div class="flex-1 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Admin Management" />

        <div class="max-w-5xl mx-auto px-6 pt-8 pb-8">
            <p class="text-gray-500">Create and manage admin accounts for each role.</p>

            @if (session('success'))
            <div class="mt-6 rounded-lg bg-green-50 text-green-700 text-sm px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Add admin trigger -->
        <div class="mt-8 flex justify-end">
            <button type="button" onclick="openModal('create-modal')"
                class="rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                + Add New Admin
            </button>
        </div>

        <x-admin-create-modal />

        <!-- Admin list: table on desktop, stacked cards on mobile -->
        <div class="mt-8">

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
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($admins as $admin)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="openModal('edit-modal-{{ $admin->id }}')" title="Edit"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        @if ($admin->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $admin) }}"
                                                onsubmit="return confirm('Remove {{ $admin->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
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

            <!-- Mobile stacked cards -->
            <div class="lg:hidden space-y-3">
                @foreach ($admins as $admin)
                    <div class="rounded-2xl border border-gray-200 p-4">
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
                                    <form method="POST" action="{{ route('admin.users.destroy', $admin) }}"
                                        onsubmit="return confirm('Remove {{ $admin->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
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