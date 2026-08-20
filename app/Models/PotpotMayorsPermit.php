<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotpotMayorsPermit extends Model
{
    use HasFactory;

    protected $table = 'potpot_mayors_permits';

    protected $fillable = [
        'control_no',
        'status',
        'name',
        'address',
        'business_name',
        'motorized_operation',
        'or_no',
        'amount_paid',
        'issue_date',
        'expiry_date',
        'issued_at',
        'mayor',
        'quarter',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];
}