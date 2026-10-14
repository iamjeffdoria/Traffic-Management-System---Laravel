<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Potpot extends Model
{
    use HasFactory;

    protected $table = 'potpots';

    protected $fillable = [
        'body_number',
        'name',
        'address',
        'contact_number',
        'or_number',
        'status',
        'date_registered',
        'date_expired',
        'remarks',
    ];

    protected $casts = [
        'date_registered' => 'date',
        'date_expired' => 'date',
    ];
}