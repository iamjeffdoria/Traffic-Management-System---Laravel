@extends('layouts.app')

@section('title', 'Document Submissions')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-document-submissions" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Document Submissions" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            <form id="document-submission-filter-form" method="GET" action="{{ route('tricycle.document-submissions') }}" class="flex flex-col sm:flex-row gap-2 mb-4">
                <input type="text" name="driver_name" value="{{ request('driver_name') }}" oninput="debouncedFetchDocumentSubmissionFilter()" placeholder="Search driver name..."
                    class="w-full sm:w-64 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" onchange="debouncedFetchDocumentSubmissionFilter()" class="w-full sm:w-40 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </form>

            @php
                $statusColors = [
                    'pending' => 'bg-amber-500',
                    'approved' => 'bg-green-600',
                    'rejected' => 'bg-red-600',
                ];
                $docIcons = [
                    'endorsement_letter_path' => 'EL',
                    'toda_certificate_path' => 'TC',
                    'police_clearance_path' => 'PC',
                    'or_cr_path' => 'OR',
                    'drivers_license_path' => 'DL',
                ];
            @endphp

            <!-- Desktop table -->
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

            <!-- Mobile compact cards -->
            @include('admin.partials.document-submission-cards')

            <div id="document-submission-pagination-mobile" class="lg:hidden mt-4">
                @unless ($submissions->isEmpty())
                    {{ $submissions->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>

<!-- Document preview modal -->
<div id="doc-preview-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6">
    <div onclick="closeDocModal()" class="absolute inset-0 bg-black/70"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 shrink-0">
            <h3 id="doc-preview-title" class="font-semibold text-gray-900 text-sm truncate pr-4"></h3>
            <div class="flex items-center gap-1 shrink-0">
                <a id="doc-preview-download" href="#" download
                    class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Download">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                </a>
                <button type="button" onclick="closeDocModal()"
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-auto bg-gray-100 flex items-center justify-center p-3">
            <img id="doc-preview-image" src="" alt="" class="hidden max-w-full max-h-full rounded-lg shadow-sm object-contain">
            <iframe id="doc-preview-pdf" src="" class="hidden w-full h-[75vh] rounded-lg bg-white"></iframe>
        </div>
    </div>
</div>
@endsection