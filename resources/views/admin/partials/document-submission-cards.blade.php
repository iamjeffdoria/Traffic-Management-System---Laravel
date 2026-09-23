@php
    $hasActiveFilters = request()->filled('driver_name') || request()->filled('status');
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

<div id="document-submission-cards-mobile" class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
    @forelse ($submissions as $submission)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-gray-900 font-medium text-sm truncate">{{ $submission->driver_name }}</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        <span class="inline-block rounded-full bg-slate-600 text-white text-[10px] font-mono px-2 py-0.5">{{ $submission->contact_number }}</span>
                        <span class="inline-block rounded-full bg-cyan-600 text-white text-[10px] font-mono px-2 py-0.5">{{ $submission->body_number ?? '—' }}</span>
                        <span class="inline-block rounded-full bg-indigo-600 text-white text-[10px] font-mono px-2 py-0.5">{{ $submission->plate_no ?? '—' }}</span>
                    </div>
                </div>
                <span class="inline-block rounded-full {{ $statusColors[$submission->status] }} text-white text-[10px] font-semibold px-2 py-0.5 whitespace-nowrap shrink-0">
                    {{ ucfirst($submission->status) }}
                </span>
            </div>

            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($docIcons as $field => $label)
                    <button type="button"
                        onclick="openDocModal('{{ asset('storage/' . $submission->$field) }}', '{{ addslashes(\App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] . ' — ' . $submission->driver_name) }}')"
                        title="{{ \App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] }}"
                        class="inline-flex items-center rounded-full border border-gray-200 px-2 py-0.5 text-[9px] font-semibold text-gray-500 whitespace-nowrap">
                        {{ $label }}
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

            <form id="delete-document-submission-form-mobile-{{ $submission->id }}" method="POST" action="{{ route('tricycle.document-submissions.destroy', $submission) }}">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" onclick="confirmDocumentSubmissionDelete('delete-document-submission-form-mobile-{{ $submission->id }}', '{{ addslashes($submission->driver_name) }}')"
                class="mt-1.5 text-[11px] text-red-600 hover:underline">
                Remove
            </button>
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