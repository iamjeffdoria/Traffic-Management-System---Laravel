@include('admin.partials.tanod-tbody')

<div id="tanod-pagination-desktop" class="hidden lg:block mt-4">
    @unless ($tanods->isEmpty())
        {{ $tanods->links() }}
    @endunless
</div>

@include('admin.partials.tanod-cards')

<div id="tanod-pagination-mobile" class="lg:hidden mt-4">
    @unless ($tanods->isEmpty())
        {{ $tanods->links() }}
    @endunless
</div>