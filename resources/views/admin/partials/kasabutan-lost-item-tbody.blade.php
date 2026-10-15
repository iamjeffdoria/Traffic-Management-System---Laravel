@php
    $hasActiveLostItemFilters = request()->filled('driver') || request()->filled('body') || request()->filled('passenger') || request()->filled('witness');
@endphp

<tbody id="kasabutan-lost-item-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($lostItems as $item)
        <x-kasabutan-lost-item-table-row :item="$item" />
    @empty
        <tr>
            <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveLostItemFilters)
                    No Kasabutan - Lost Item records match your search.
                    <a href="{{ route('potpot.kasabutan.lost-item') }}" data-ajax-kasabutan-lost-item-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No Kasabutan - Lost Item records yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>