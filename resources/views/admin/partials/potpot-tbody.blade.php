@php
    $hasActivePotpotFilters = request()->filled('body') || request()->filled('name') || request()->filled('address') || request()->filled('status');
@endphp

<tbody id="potpot-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($potpots as $potpot)
        <x-potpot-table-row :potpot="$potpot" />
    @empty
        <tr>
            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActivePotpotFilters)
                    No potpots match your search.
                    <a href="{{ route('potpot.list') }}" data-ajax-potpot-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No potpots registered yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>