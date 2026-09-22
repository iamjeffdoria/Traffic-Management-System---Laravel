<?php

namespace App\Http\Controllers;

use App\Models\TricycleDocumentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverDocumentSubmissionController extends Controller
{
    public function create()
    {
        return view('driver.document-submission');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'body_number' => 'nullable|string|max:255',
            'plate_no' => 'nullable|string|max:255',
            'endorsement_letter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'toda_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'police_clearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'drivers_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $paths = [];
        foreach (['endorsement_letter', 'toda_certificate', 'police_clearance', 'or_cr', 'drivers_license'] as $field) {
            $paths[$field . '_path'] = $request->file($field)->store('document-submissions', 'public');
        }

        TricycleDocumentSubmission::create([
            'driver_name' => $validated['driver_name'],
            'contact_number' => $validated['contact_number'],
            'body_number' => $validated['body_number'] ?? null,
            'plate_no' => $validated['plate_no'] ?? null,
            ...$paths,
        ]);

        return back()->with('success', 'Your documents were submitted successfully. The tricycle admin will review them shortly.');
    }
}