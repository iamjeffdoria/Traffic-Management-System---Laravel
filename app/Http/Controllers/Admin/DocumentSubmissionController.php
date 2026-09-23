<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TricycleDocumentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = TricycleDocumentSubmission::query()
            ->when($request->filled('driver_name'), fn ($q) =>
                $q->where('driver_name', 'like', '%' . $request->input('driver_name') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.document-submission-ajax-results', compact('submissions'));
        }

        return view('admin.document-submissions', compact('submissions'));
    }

    public function updateStatus(Request $request, TricycleDocumentSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Submission status updated.');
    }

    public function destroy(TricycleDocumentSubmission $submission)
    {
        foreach (['endorsement_letter_path', 'toda_certificate_path', 'police_clearance_path', 'or_cr_path', 'drivers_license_path'] as $field) {
            if ($submission->$field) {
                Storage::disk('public')->delete($submission->$field);
            }
        }

        $submission->delete();

        return back()->with('success', 'Submission removed.');
    }
}