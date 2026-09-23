@php
    $hasActiveFilters = request()->filled('driver_name') || request()->filled('status');
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

<div id="document-submission-cards-mobile" class="lg:hidden space-y-2">
    @forelse ($submissions as $submission)
        <div class="rounded-lg border border-gray-200 bg-white p-3">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-gray-900 font-medium text-sm truncate">{{ $submission->driver_name }}</p>
                    <p class="text-gray-400 text-xs truncate">{{ $submission->contact_number }} · {{ $submission->body_number ?? '—' }} · {{ $submission->plate_no ?? '—' }}</p>
                </div>
                <span class="inline-block rounded-full {{ $statusColors[$submission->status] }} text-white text-[10px] font-semibold px-2 py-0.5 whitespace-nowrap shrink-0">
                    {{ ucfirst($submission->status) }}
                </span>
            </div>

            <div class="flex items-center gap-1 mt-2">
                @foreach ($docIcons as $field => $abbr)
                    <button type="button"
                        onclick="openDocModal('{{ asset('storage/' . $submission->$field) }}', '{{ \App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] }} — {{ $submission->driver_name }}')"
                        title="{{ \App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] }}"
                        class="inline-flex items-center justify-center w-6 h-6 rounded border border-gray-200 text-[9px] font-bold text-gray-500">
                        {{ $abbr }}
                    </button>
                @endforeach
            </div>

            <form method="POST" action="{{ route('tricycle.document-submissions.update-status', $submission) }}" class="flex items-center gap-1.5 mt-2">
                @csrf
                @method('PUT')
                <select name="status" class="rounded border border-gray-300 px-1.5 py-1 text-xs">
                    <option value="pending" @selected($submission->status === 'pending')>Pending</option>
                    <option value="approved" @selected($submission->status === 'approved')>Approved</option>
                    <option value="rejected" @selected($submission->status === 'rejected')>Rejected</option>
                </select>
                <input type="text" name="admin_notes" value="{{ $submission->admin_notes }}" placeholder="Notes..."
                    class="flex-1 min-w-0 rounded border border-gray-300 px-2 py-1 text-xs">
                <button type="submit" class="shrink-0 rounded bg-gray-900 text-white px-2.5 py-1 text-xs font-semibold">Save</button>
            </form>

            <form method="POST" action="{{ route('tricycle.document-submissions.destroy', $submission) }}" class="mt-1.5"
                onsubmit="return confirm('Remove this submission?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-[11px] text-red-600 hover:underline">Remove</button>
            </form>
        </div>
    @empty
        <div class="rounded-lg border border-gray-200 p-8 text-center text-gray-400 text-sm">
            @if ($hasActiveFilters)
                No submissions match your search.
                <a href="{{ route('tricycle.document-submissions') }}" data-ajax-document-submission-link class="text-red-600 font-medium ml-1">Clear filters</a>
            @else
                No document submissions yet.
            @endif
        </div>
    @endforelse
</div>