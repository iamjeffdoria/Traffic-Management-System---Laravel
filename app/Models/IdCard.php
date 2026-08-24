<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCard extends Model
{
    use HasFactory;

    protected $table = 'id_cards';

    protected $fillable = [
        'full_name',
        'id_number',
        'gender',
        'date_of_birth',
        'address',
        'height',
        'weight',
        'or_number',
        'date_issued',
        'expiry_date',
        'photo_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_issued' => 'date',
        'expiry_date' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];
}