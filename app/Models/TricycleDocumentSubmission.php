<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TricycleDocumentSubmission extends Model
{
    protected $fillable = [
        'driver_name',
        'contact_number',
        'body_number',
        'plate_no',
        'endorsement_letter_path',
        'toda_certificate_path',
        'police_clearance_path',
        'or_cr_path',
        'drivers_license_path',
        'status',
        'admin_notes',
    ];

    public const DOCUMENT_LABELS = [
        'endorsement_letter_path' => 'Endorsement Letter',
        'toda_certificate_path' => 'TODA Certificate',
        'police_clearance_path' => 'Police Clearance',
        'or_cr_path' => 'OR/CR Photocopy',
        'drivers_license_path' => "Driver's License Photocopy",
    ];
}