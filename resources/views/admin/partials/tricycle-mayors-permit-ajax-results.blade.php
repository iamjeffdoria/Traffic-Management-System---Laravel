@php
    $hasActivePermitFilters = request()->filled('control_no') || request()->filled('tricycle') || request()->filled('business_name') || request()->filled('status');
@endphp

<tbody id="tricycle-mayors-permit-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($permits as $permit)
        <x-tricycle-mayors-permit-table-row :permit="$permit" />
    @empty
        <tr>
            <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActivePermitFilters)
                    No permits match your search.
                    <a href="{{ route('tricycle.mayors-permit') }}" data-ajax-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No permits issued yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="tricycle-mayors-permit-pagination-desktop" class="mt-4">
    @unless ($permits->isEmpty())
        {{ $permits->links() }}
    @endunless
</div>

<div id="tricycle-mayors-permit-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($permits as $permit)
        <x-tricycle-mayors-permit-mobile-card :permit="$permit" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-10 text-center text-gray-500 text-sm">
            @if ($hasActivePermitFilters)
                No permits match your search.
                <a href="{{ route('tricycle.mayors-permit') }}" data-ajax-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No permits issued yet.
            @endif
        </div>
    @endforelse
</div>

<div id="tricycle-mayors-permit-pagination-mobile" class="lg:hidden mt-4">
    @unless ($permits->isEmpty())
        {{ $permits->links() }}
    @endunless
</div>

<div id="tricycle-mayors-permit-edit-modals">
    <x-tricycle-mayors-permit-edit-modal :tricycles="$tricycles" />
</div>