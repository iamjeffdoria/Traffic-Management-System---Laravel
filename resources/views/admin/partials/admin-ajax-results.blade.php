@php
    $hasActiveAdminFilters = request()->filled('name') || request()->filled('email') || request()->filled('role');
@endphp

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

<div id="admin-pagination-desktop" class="mt-4">
    @unless ($admins->isEmpty())
        {{ $admins->links() }}
    @endunless
</div>

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