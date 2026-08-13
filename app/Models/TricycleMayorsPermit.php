<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TricycleMayorsPermit extends Model
{
    use HasFactory;

    protected $table = 'tricycle_mayors_permits';

    protected $fillable = [
        'tricycle_id',
        'control_no',
        'status',
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

    public function tricycle(): BelongsTo
    {
        return $this->belongsTo(Tricycle::class);
    }
}