@php
    $statusColors = [
        'pending' => 'bg-amber-500',
        'approved' => 'bg-green-600',
        'rejected' => 'bg-red-600',
    ];
    $docIcons = [
        'endorsement_letter_path' => 'Endorsement',
        'toda_certificate_path' => 'TODA Cert',
        'police_clearance_path' => 'Police',
        'or_cr_path' => 'OR/CR',
        'drivers_license_path' => 'License',
    ];
@endphp

<div class="hidden lg:block rounded-xl border border-gray-200 overflow-x-auto isolate" style="max-height: 680px; overflow-y: auto;">
    <table class="w-full text-xs border-separate border-spacing-0 table-fixed">
        <colgroup>
            <col class="w-[16%]">
            <col class="w-[10%]">
            <col class="w-[13%]">
            <col class="w-[9%]">
            <col class="w-[36%]">
            <col class="w-[6%]">
        </colgroup>
        <thead class="text-left text-gray-700 bg-gray-50 sticky top-0 z-10">
            <tr class="border-b border-gray-200">
                <th class="px-3 py-2 font-semibold">Driver</th>
                <th class="px-3 py-2 font-semibold">Vehicle</th>
                <th class="px-3 py-2 font-semibold">Docs</th>
                <th class="px-3 py-2 font-semibold">Status</th>
                <th class="px-3 py-2 font-semibold">Notes</th>
                <th class="px-3 py-2 font-semibold text-right">—</th>
            </tr>
        </thead>
        @include('admin.partials.document-submission-tbody')
    </table>
</div>
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