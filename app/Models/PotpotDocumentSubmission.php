<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotpotDocumentSubmission extends Model
{
    protected $fillable = [
        'driver_name',
        'contact_number',
        'body_number',
        'drivers_license_path',
        'id_card_path',
        'mayors_permit_path',
        'police_clearance_path',
        'medical_certificate_path',
        'status',
        'admin_notes',
    ];

    public const DOCUMENT_LABELS = [
        'drivers_license_path' => "Driver's License",
        'id_card_path' => 'ID Card',
        'mayors_permit_path' => "Mayor's Permit",
        'police_clearance_path' => 'Police Clearance',
        'medical_certificate_path' => 'Medical Certificate',
    ];
}