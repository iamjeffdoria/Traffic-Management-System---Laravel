@php
    $hasActiveFilters = request()->filled('body') || request()->filled('plate') || request()->filled('name') || request()->filled('status') || request()->filled('toda');
    $statusColors = [
        'active' => 'bg-teal-500',
        'renewed' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];
@endphp

<tbody id="tricycle-tbody-desktop" class="divide-y divide-gray-300">
    @forelse ($tricycles as $tricycle)
        <x-tricycle-table-row :tricycle="$tricycle" />
    @empty
        <tr>
            <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                @if ($hasActiveFilters)
                    No tricycles match your search.
                    <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No tricycles registered yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>

<div id="tricycle-pagination-desktop" class="mt-4">
    @unless ($tricycles->isEmpty())
        {{ $tricycles->links() }}
    @endunless
</div>

<div id="tricycle-cards-mobile" class="lg:hidden space-y-3">
    @forelse ($tricycles as $tricycle)
        <x-tricycle-mobile-card :tricycle="$tricycle" />
    @empty
        <div class="rounded-2xl border border-gray-200 p-10 text-center text-gray-500 text-sm">
            @if ($hasActiveFilters)
                No tricycles match your search.
                <a href="{{ route('tricycle.list') }}" data-ajax-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No tricycles registered yet.
            @endif
        </div>
    @endforelse
</div>

<div id="tricycle-pagination-mobile" class="lg:hidden mt-4">
    @unless ($tricycles->isEmpty())
        {{ $tricycles->links() }}
    @endunless
</div>

<div id="tricycle-edit-modals">
    @foreach ($tricycles as $tricycle)
        <x-tricycle-edit-modal :tricycle="$tricycle" />
    @endforeach
</div>