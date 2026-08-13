<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tricycle extends Model
{
    use HasFactory;

    public const TODA_OPTIONS = [
        'PTL 001-A' => 'PTL 001-A',
        'PSMTL 001-B' => 'PSMTL 001-B',
        'PST 001-C' => 'PST 001-C',
        'PCRT-001-D' => 'PCRT-001-D',
        'PHC 001-E' => 'PHC 001-E',
    ];

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

    public function mayorsPermits()
    {
        return $this->hasMany(TricycleMayorsPermit::class);
    }
}