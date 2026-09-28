<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotpotDocumentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PotpotDocumentSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = PotpotDocumentSubmission::query()
            ->when($request->filled('driver_name'), fn ($q) =>
                $q->where('driver_name', 'like', '%' . $request->input('driver_name') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $config = $this->pageConfig();

        if ($request->ajax()) {
            return view('admin.partials.document-submission-ajax-results', compact('submissions', 'config'));
        }

        return view('admin.document-submissions', compact('submissions', 'config'));
    }

    public function updateStatus(Request $request, PotpotDocumentSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $submission->update($validated);

        return back()->with('success', 'Submission status updated.');
    }

    public function destroy(PotpotDocumentSubmission $submission)
    {
        foreach (array_keys(PotpotDocumentSubmission::DOCUMENT_LABELS) as $field) {
            if ($submission->$field) {
                Storage::disk('public')->delete($submission->$field);
            }
        }

        $submission->delete();

        return back()->with('success', 'Submission removed.');
    }

    protected function pageConfig(): array
    {
        return [
            'title' => 'Potpot Document Submissions',
            'sidebar' => 'potpot-document-submissions',
            'indexRoute' => 'potpot.document-submissions',
            'updateRoute' => 'potpot.document-submissions.update-status',
            'destroyRoute' => 'potpot.document-submissions.destroy',
            'showPlate' => false,
            'labels' => PotpotDocumentSubmission::DOCUMENT_LABELS,
            'shortLabels' => [
                'drivers_license_path' => 'License',
                'id_card_path' => 'ID Card',
                'mayors_permit_path' => 'Permit',
                'police_clearance_path' => 'Police',
                'medical_certificate_path' => 'Medical',
            ],
        ];
    }
}