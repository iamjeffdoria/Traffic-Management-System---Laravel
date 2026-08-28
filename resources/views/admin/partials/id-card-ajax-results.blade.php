@php
    $hasActiveIdCardFilters = request()->filled('full_name') || request()->filled('id_number');
@endphp

<tbody id="id-card-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($idCards as $idCard)
        <x-id-card-table-row :idCard="$idCard" />
    @empty
        <tr>
            <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveIdCardFilters)
                    No ID cards match your search.
                    <a href="{{ route('potpot.id-cards') }}" data-ajax-id-card-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No ID cards registered yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="id-card-pagination-desktop" class="mt-4">
    @unless ($idCards->isEmpty())
        {{ $idCards->links() }}
    @endunless
</div>

<div id="id-card-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($idCards as $idCard)
        <x-id-card-mobile-card :idCard="$idCard" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActiveIdCardFilters)
                No ID cards match your search.
                <a href="{{ route('potpot.id-cards') }}" data-ajax-id-card-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No ID cards registered yet.
            @endif
        </div>
    @endforelse
</div>

<div id="id-card-pagination-mobile" class="lg:hidden mt-4">
    @unless ($idCards->isEmpty())
        {{ $idCards->links() }}
    @endunless
</div>

<div id="id-card-edit-modals">
    <x-id-card-edit-modal />
</div>