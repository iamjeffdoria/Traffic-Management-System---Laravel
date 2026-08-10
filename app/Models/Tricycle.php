<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tricycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'body_number',
        'plate_no',
        'name',
        'address',
        'make_kind',
        'status',
        'engine_motor_no',
        'chassis_no',
        'date_registered',
        'date_expired',
        'toda',
        'remarks',
    ];

    protected $casts = [
        'date_registered' => 'date',
        'date_expired' => 'date',
    ];
}