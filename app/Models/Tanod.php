<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanod extends Model
{
    use HasFactory;

    protected $table = 'tanods';

    public const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    protected $fillable = [
        'id_number',
        'full_name',
        'position',
        'barangay',
        'barangay_chairman',
        'home_address',
        'weight',
        'date_of_birth',
        'place_of_birth',
        'height',
        'blood_type',
        'mobile_no',
        'emergency_contact_name',
        'emergency_contact_address',
        'emergency_contact_relationship',
        'emergency_contact_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];
}