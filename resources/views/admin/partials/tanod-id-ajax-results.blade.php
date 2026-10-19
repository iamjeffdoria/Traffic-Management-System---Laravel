@include('admin.partials.tanod-id-tbody')

<div id="tanod-id-pagination" class="mt-4">
    @unless ($tanodIds->isEmpty())
        {{ $tanodIds->links() }}
    @endunless
</div>