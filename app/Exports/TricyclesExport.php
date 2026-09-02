<?php

namespace App\Exports;

use App\Models\Tricycle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TricyclesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Tricycle::query()->orderBy('body_number');
    }

    public function headings(): array
    {
        return [
            'Body Number', 'Plate No', 'Name', 'Address', 'Make Kind', 'Status',
            'Engine Motor No', 'Chassis No', 'Date Registered', 'Date Expired', 'Toda', 'Remarks',
        ];
    }

    public function map($tricycle): array
    {
        return [
            $tricycle->body_number,
            $tricycle->plate_no,
            $tricycle->name,
            $tricycle->address,
            $tricycle->make_kind,
            $tricycle->status,
            $tricycle->engine_motor_no,
            $tricycle->chassis_no,
            optional($tricycle->date_registered)->format('Y-m-d'),
            optional($tricycle->date_expired)->format('Y-m-d'),
            $tricycle->toda,
            $tricycle->remarks,
        ];
    }
}