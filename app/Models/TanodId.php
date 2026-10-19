<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanodId extends Model
{
    use HasFactory;

    protected $table = 'tanod_ids';

    protected $fillable = [
        'id_number',
        'full_name',
        'address',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_address',
        'emergency_contact_number',
        'emergency_contact_relationship',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
}