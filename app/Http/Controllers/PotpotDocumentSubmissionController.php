<?php

namespace App\Http\Controllers;

use App\Models\PotpotDocumentSubmission;
use Illuminate\Http\Request;

class PotpotDocumentSubmissionController extends Controller
{
    /**
     * Potpot requirements. key => label shown on the form.
     * To change a requirement later, edit here AND the model's
     * DOCUMENT_LABELS/$fillable AND add a migration for the column.
     */
    protected function requiredDocs(): array
    {
        return [
            'drivers_license' => "Driver's License",
            'id_card' => 'ID Card',
            'mayors_permit' => "Mayor's Permit",
            'police_clearance' => 'Police Clearance',
            'medical_certificate' => 'Medical Certificate',
        ];
    }

    public function create()
    {
        return view('driver.document-submission', [
            'heading' => 'Potpot Document Submission',
            'reviewer' => 'potpot admin',
            'showPlateNo' => false,
            'formAction' => route('driver.potpot.documents.store'),
            'requiredDocs' => $this->requiredDocs(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'driver_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'body_number' => 'nullable|string|max:255',
        ];

        foreach (array_keys($this->requiredDocs()) as $field) {
            $rules[$field] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $validated = $request->validate($rules);

        $paths = [];
        foreach (array_keys($this->requiredDocs()) as $field) {
            $paths[$field . '_path'] = $request->file($field)->store('potpot-document-submissions', 'public');
        }

        PotpotDocumentSubmission::create([
            'driver_name' => $validated['driver_name'],
            'contact_number' => $validated['contact_number'],
            'body_number' => $validated['body_number'] ?? null,
            ...$paths,
        ]);

        return back()->with('success', 'Your documents were submitted successfully. The potpot admin will review them shortly.');
    }
}