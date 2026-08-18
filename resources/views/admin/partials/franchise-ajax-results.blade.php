@php
    $hasActiveFranchiseFilters = request()->filled('authorized_no') || request()->filled('name') || request()->filled('plate') || request()->filled('status');
@endphp

<tbody id="franchise-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($franchises as $franchise)
        <x-franchise-table-row :franchise="$franchise" />
    @empty
        <tr>
            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveFranchiseFilters)
                    No franchises match your search.
                    <a href="{{ route('tricycle.franchise') }}" data-ajax-franchise-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No franchise records yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="franchise-pagination-desktop" class="mt-4">
    @unless ($franchises->isEmpty())
        {{ $franchises->links() }}
    @endunless
</div>

<div id="franchise-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($franchises as $franchise)
        <x-franchise-mobile-card :franchise="$franchise" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActiveFranchiseFilters)
                No franchises match your search.
                <a href="{{ route('tricycle.franchise') }}" data-ajax-franchise-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No franchise records yet.
            @endif
        </div>
    @endforelse
</div>

<div id="franchise-pagination-mobile" class="lg:hidden mt-4">
    @unless ($franchises->isEmpty())
        {{ $franchises->links() }}
    @endunless
</div>