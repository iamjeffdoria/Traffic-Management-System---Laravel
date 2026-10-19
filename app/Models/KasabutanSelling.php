<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasabutanSelling extends Model
{
    use HasFactory;

    protected $table = 'kasabutan_sellings';

    protected $fillable = [
        'owner',
        'barangay',
        'municipality',
        'body_number',
        'buyer',
        'buyer_address',
        'amount',
        'witness',
        'secretariat',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}