<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasabutanLostItem extends Model
{
    use HasFactory;

    protected $table = 'kasabutan_lost_items';

    protected $fillable = [
        'driver',
        'body_number',
        'driver_age',
        'driver_address',
        'amount',
        'passenger',
        'passenger_age',
        'passenger_address',
        'date',
        'time',
        'issued_at',
        'witness_1',
        'witness_2',
        'it_section',
        'security_officer',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'driver_age' => 'integer',
        'passenger_age' => 'integer',
    ];
}