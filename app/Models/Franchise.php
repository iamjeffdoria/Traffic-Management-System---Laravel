<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    use HasFactory;

    protected $fillable = [
        'tricycle_id',
        'name',
        'valid_until',
        'plate_no',
        'denomination',
        'status',
        'authorized_no',
        'motor_no',
        'chassis_no',
        'authorized_route',
        'purpose',
        'official_receipt_no',
        'amount_paid',
        'date',
        'municipal_treasurer',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function tricycle()
    {
        return $this->belongsTo(Tricycle::class);
    }
}