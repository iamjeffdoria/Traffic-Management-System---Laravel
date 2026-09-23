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

<tbody id="document-submission-tbody-desktop" class="divide-y divide-gray-100">
    @forelse ($submissions as $submission)
        <tr class="hover:bg-gray-50/60 transition-colors align-top">
            <td class="px-3 py-1.5">
                <p class="text-gray-900 font-medium truncate">{{ $submission->driver_name }}</p>
                <span class="inline-block mt-1 rounded-full bg-slate-600 text-white text-[10px] font-mono px-2 py-0.5 truncate max-w-full" title="{{ $submission->contact_number }}">{{ $submission->contact_number }}</span>
            </td>
            <td class="px-3 py-1.5">
                <div class="flex flex-wrap gap-1">
                    <span class="inline-block rounded-full bg-cyan-600 text-white text-[10px] font-mono px-2 py-0.5" title="{{ $submission->body_number ?? '—' }}">{{ $submission->body_number ?? '—' }}</span>
                    <span class="inline-block rounded-full bg-indigo-600 text-white text-[10px] font-mono px-2 py-0.5" title="{{ $submission->plate_no ?? '—' }}">{{ $submission->plate_no ?? '—' }}</span>
                </div>
            </td>
            <td class="px-3 py-1.5">
                <div class="flex items-center gap-1 flex-wrap">
                    @foreach ($docIcons as $field => $abbr)
                        <button type="button"
                            onclick="openDocModal('{{ asset('storage/' . $submission->$field) }}', '{{ \App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] }} — {{ $submission->driver_name }}')"
                            title="{{ \App\Models\TricycleDocumentSubmission::DOCUMENT_LABELS[$field] }}"
                            class="inline-flex items-center justify-center w-6 h-6 rounded border border-gray-200 text-[9px] font-bold text-gray-500 hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-colors">
                            {{ $abbr }}
                        </button>
                    @endforeach
                </div>
            </td>
            <td class="px-3 py-1.5">
                <span class="inline-block rounded-full {{ $statusColors[$submission->status] }} text-white text-[10px] font-semibold px-2 py-0.5 whitespace-nowrap">
                    {{ ucfirst($submission->status) }}
                </span>
            </td>
            <td class="px-3 py-1.5">
                <form method="POST" action="{{ route('tricycle.document-submissions.update-status', $submission) }}" class="flex items-center gap-1.5">
                    @csrf
                    @method('PUT')
                    <select name="status" class="rounded border border-gray-300 px-1.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="pending" @selected($submission->status === 'pending')>Pending</option>
                        <option value="approved" @selected($submission->status === 'approved')>Approved</option>
                        <option value="rejected" @selected($submission->status === 'rejected')>Rejected</option>
                    </select>
                    <input type="text" name="admin_notes" value="{{ $submission->admin_notes }}" placeholder="Notes..."
                        class="flex-1 min-w-0 rounded border border-gray-300 px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-red-600">
                    <button type="submit" title="Save"
                        class="shrink-0 rounded bg-gray-900 text-white w-6 h-6 flex items-center justify-center hover:bg-gray-800 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
            </td>
            <td class="px-3 py-1.5 text-right">
                <form method="POST" action="{{ route('tricycle.document-submissions.destroy', $submission) }}"
                    onsubmit="return confirm('Remove this submission?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remove" class="text-gray-300 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-3 py-10 text-center text-gray-400">
                @if ($hasActiveFilters)
                    No submissions match your search.
                    <a href="{{ route('tricycle.document-submissions') }}" data-ajax-document-submission-link class="text-red-600 font-medium ml-1">Clear filters</a>
                @else
                    No document submissions yet.
                @endif
            </td>
        </tr>
    @endforelse
</tbody>