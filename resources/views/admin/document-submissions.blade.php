@extends('layouts.app')

@section('title', 'Document Submissions')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-document-submissions" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Document Submissions" />

        <div class="max-w-6xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            <form method="GET" action="{{ route('tricycle.document-submissions') }}" class="flex flex-col sm:flex-row gap-2 mb-6">
                <input type="text" name="driver_name" value="{{ request('driver_name') }}" placeholder="Search driver name..."
                    class="w-full sm:w-64 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <select name="status" class="w-full sm:w-48 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
                <button type="submit" class="rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                    Filter
                </button>
            </form>

            <div class="space-y-4">
                @forelse ($submissions as $submission)
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-500',
                            'approved' => 'bg-green-600',
                            'rejected' => 'bg-red-600',
                        ];
                    @endphp
                    <div class="rounded-2xl border border-gray-200 bg-white p-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <p class="text-gray-900 font-semibold">{{ $submission->driver_name }}</p>
                                <p class="text-gray-500 text-sm">{{ $submission->contact_number }}</p>
                                @if ($submission->body_number || $submission->plate_no)
                                    <p class="text-gray-500 text-xs mt-1">
                                        {{ $submission->body_number ?? '—' }} · {{ $submission->plate_no ?? '—' }}
                                    </p>
                                @endif
                            </div>
                            <span class="inline-block rounded-full {{ $statusColors[$submission->status] }} text-white text-xs font-semibold px-3 py-1">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>

                        <div class="grid sm:grid-cols-3 lg:grid-cols-5 gap-2 mt-4">
                            @foreach (\App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS as $field => $label)
                                <a href="{{ asset('storage/' . $submission->$field) }}" target="_blank"
                                    class="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('tricycle.document-submissions.update-status', $submission) }}" class="flex flex-wrap items-end gap-2 mt-4 pt-4 border-t border-gray-100">
                            @csrf
                            @method('PUT')
                            <div class="flex-1 min-w-[160px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                                    <option value="pending" @selected($submission->status === 'pending')>Pending</option>
                                    <option value="approved" @selected($submission->status === 'approved')>Approved</option>
                                    <option value="rejected" @selected($submission->status === 'rejected')>Rejected</option>
                                </select>
                            </div>
                            <div class="flex-[2] min-w-[220px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                                <input type="text" name="admin_notes" value="{{ $submission->admin_notes }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                            </div>
                            <button type="submit" class="rounded-full bg-gray-900 text-white px-5 py-2 text-sm font-semibold hover:bg-gray-800 transition-colors">
                                Save
                            </button>
                        </form>

                        <form method="POST" action="{{ route('tricycle.document-submissions.destroy', $submission) }}" class="mt-2"
                            onsubmit="return confirm('Remove this submission?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Remove submission</button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 p-10 text-center text-gray-500 text-sm">
                        No document submissions yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection