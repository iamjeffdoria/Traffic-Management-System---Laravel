<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mtop extends Model
{
    use HasFactory;

    protected $table = 'mtops';

    protected $fillable = [
        'tricycle_id',
        'case_no',
        'no_of_units',
        'route_operation',
        'date',
        'municipal_treasurer',
        'officer_in_charge',
        'mayor',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tricycle(): BelongsTo
    {
        return $this->belongsTo(Tricycle::class);
    }
}