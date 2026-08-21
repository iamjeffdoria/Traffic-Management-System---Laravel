@php
    $hasActivePotpotPermitFilters = request()->filled('control_no') || request()->filled('name') || request()->filled('business_name') || request()->filled('status');
@endphp

<tbody id="potpot-mayors-permit-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($permits as $permit)
        <x-potpot-mayors-permit-table-row :permit="$permit" />
    @empty
        <tr>
            <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActivePotpotPermitFilters)
                    No permits match your search.
                    <a href="{{ route('potpot.mayors-permit') }}" data-ajax-potpot-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No permits issued yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="potpot-mayors-permit-pagination-desktop" class="mt-4">
    @unless ($permits->isEmpty())
        {{ $permits->links() }}
    @endunless
</div>

<div id="potpot-mayors-permit-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($permits as $permit)
        <x-potpot-mayors-permit-mobile-card :permit="$permit" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            @if ($hasActivePotpotPermitFilters)
                No permits match your search.
                <a href="{{ route('potpot.mayors-permit') }}" data-ajax-potpot-permit-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No permits issued yet.
            @endif
        </div>
    @endforelse
</div>

<div id="potpot-mayors-permit-pagination-mobile" class="lg:hidden mt-4">
    @unless ($permits->isEmpty())
        {{ $permits->links() }}
    @endunless
</div>

<div id="potpot-mayors-permit-edit-modals">
    @foreach ($permits as $permit)
        <x-potpot-mayors-permit-edit-modal :permit="$permit" />
    @endforeach
</div>