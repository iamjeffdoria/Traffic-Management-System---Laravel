@include('admin.partials.potpot-tbody')

<div id="potpot-pagination-desktop" class="hidden lg:block mt-4">
    @unless ($potpots->isEmpty())
        {{ $potpots->links() }}
    @endunless
</div>

@include('admin.partials.potpot-cards')

<div id="potpot-pagination-mobile" class="lg:hidden mt-4">
    @unless ($potpots->isEmpty())
        {{ $potpots->links() }}
    @endunless
</div>