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

            <div id="document-submission-results">
                @include('admin.partials.document-submission-ajax-results')
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

        <div class="flex-1 min-h-0 overflow-hidden bg-gray-100 flex items-center justify-center p-3">
            <img id="doc-preview-image" src="" alt="" class="hidden max-w-full max-h-full rounded-lg shadow-sm object-contain">
            <iframe id="doc-preview-pdf" src="" class="hidden w-full h-[75vh] rounded-lg bg-white"></iframe>
        </div>
    </div>
</div>

<!-- Delete confirmation modal -->
<div id="delete-document-submission-confirm-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('delete-document-submission-confirm-modal')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="font-semibold text-gray-900 text-lg">Remove submission?</h3>
        <p class="text-sm text-gray-500 mt-1">
            Are you sure you want to remove <span id="delete-document-submission-confirm-name" class="font-medium text-gray-700"></span>? This can't be undone.
        </p>
        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeModal('delete-document-submission-confirm-modal')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="button" onclick="submitPendingDocumentSubmissionDelete()"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Remove
            </button>
        </div>
    </div>
</div>
@endsection