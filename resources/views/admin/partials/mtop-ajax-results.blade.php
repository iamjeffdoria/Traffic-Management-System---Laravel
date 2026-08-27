@php
    $hasActiveMtopFilters = request()->filled('case_no') || request()->filled('tricycle') || request()->filled('route_operation');
@endphp

<tbody id="mtop-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($mtops as $mtop)
        <x-mtop-table-row :mtop="$mtop" />
    @empty
        <tr>
            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveMtopFilters)
                    No MTOP records match your search.
                    <a href="{{ route('tricycle.mtop') }}" data-ajax-mtop-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No MTOP records yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="mtop-pagination-desktop" class="mt-4">
    @unless ($mtops->isEmpty())
        {{ $mtops->links() }}
    @endunless
</div>

<div id="mtop-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($mtops as $mtop)
        <x-mtop-mobile-card :mtop="$mtop" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActiveMtopFilters)
                No MTOP records match your search.
                <a href="{{ route('tricycle.mtop') }}" data-ajax-mtop-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No MTOP records yet.
            @endif
        </div>
    @endforelse
</div>

<div id="mtop-pagination-mobile" class="lg:hidden mt-4">
    @unless ($mtops->isEmpty())
        {{ $mtops->links() }}
    @endunless
</div>

<div id="mtop-edit-modals">
    <x-mtop-edit-modal :tricycles="$tricycles" />
</div>