@include('admin.partials.tanod-id-tbody')

<div id="tanod-id-pagination-desktop" class="hidden lg:block mt-4">
    @unless ($tanodIds->isEmpty())
        {{ $tanodIds->links() }}
    @endunless
</div>

@include('admin.partials.tanod-id-cards')

<div id="tanod-id-pagination-mobile" class="lg:hidden mt-4">
    @unless ($tanodIds->isEmpty())
        {{ $tanodIds->links() }}
    @endunless
</div>