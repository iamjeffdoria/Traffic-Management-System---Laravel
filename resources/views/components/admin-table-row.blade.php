@props(['admin'])

@php
    $roleColors = [
        'superadmin' => 'bg-red-600',
        'potpot_admin' => 'bg-blue-600',
        'tricycle_admin' => 'bg-teal-600',
    ];

    $adminEditData = [
        'id' => $admin->id,
        'name' => $admin->name,
        'email' => $admin->email,
        'role' => $admin->role,
        'photo_url' => $admin->photo_url,
    ];
@endphp

<tr class="divide-x divide-gray-300 border-b border-gray-200 hover:bg-gray-50/60 transition-colors">
    <td class="px-4 py-4 align-top">
        <div class="flex items-center gap-1">
            <button type="button" onclick="openAdminEditModal({{ Illuminate\Support\Js::from($adminEditData) }})" title="Edit"
                class="p-1.5 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>

            @if ($admin->id !== auth()->id())
                <form id="delete-form-{{ $admin->id }}" method="POST" action="{{ route('admin.users.destroy', $admin) }}">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" onclick="confirmDelete('delete-form-{{ $admin->id }}', {{ Illuminate\Support\Js::from($admin->name) }})" title="Delete"
                    class="p-1.5 rounded-lg text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            @endif
        </div>
    </td>
    <td class="px-4 py-3 align-top">
        <x-avatar :user="$admin" size="w-10 h-10" />
    </td>
    <td class="px-4 py-4 align-top text-gray-900 font-semibold break-words">{{ $admin->name }}</td>
    <td class="px-4 py-4 align-top text-gray-700 text-sm break-words">{{ $admin->email }}</td>
    <td class="px-4 py-4 align-top">
        <span class="inline-block max-w-full truncate rounded-full {{ $roleColors[$admin->role] ?? 'bg-slate-600' }} text-white text-xs font-semibold px-3 py-1">
            {{ str_replace('_', ' ', ucfirst($admin->role)) }}
        </span>
    </td>
</tr>