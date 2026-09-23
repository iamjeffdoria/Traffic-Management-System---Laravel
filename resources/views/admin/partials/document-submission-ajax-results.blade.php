@include('admin.partials.document-submission-tbody')

<div id="document-submission-pagination-desktop" class="mt-4">
    @unless ($submissions->isEmpty())
        {{ $submissions->links() }}
    @endunless
</div>

@include('admin.partials.document-submission-cards')

<div id="document-submission-pagination-mobile" class="lg:hidden mt-4">
    @unless ($submissions->isEmpty())
        {{ $submissions->links() }}
    @endunless
</div>