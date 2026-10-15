@include('admin.partials.kasabutan-lost-item-tbody')

<div id="kasabutan-lost-item-pagination-desktop" class="hidden lg:block mt-4">
    @unless ($lostItems->isEmpty())
        {{ $lostItems->links() }}
    @endunless
</div>

@include('admin.partials.kasabutan-lost-item-cards')

<div id="kasabutan-lost-item-pagination-mobile" class="lg:hidden mt-4">
    @unless ($lostItems->isEmpty())
        {{ $lostItems->links() }}
    @endunless
</div>