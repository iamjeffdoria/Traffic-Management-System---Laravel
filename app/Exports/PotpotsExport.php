<?php

namespace App\Exports;

use App\Models\Potpot;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PotpotsExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    public function query()
    {
        return Potpot::query()->orderBy('body_number');
    }

    public function headings(): array
    {
        return [
            'Body Number', 'Operator', 'Address', 'Contact Number', 'OR Number',
            'Status', 'Date Registered', 'Date Expired', 'Remarks',
        ];
    }

    public function map($potpot): array
    {
        return [
            $potpot->body_number,
            $potpot->name,
            $potpot->address,
            $potpot->contact_number,
            $potpot->or_number,
            $potpot->status,
            optional($potpot->date_registered)->format('Y-m-d'),
            optional($potpot->date_expired)->format('Y-m-d'),
            $potpot->remarks,
        ];
    }

    public function columnFormats(): array
    {
        // Text, so leading zeros (001, 0912...) survive.
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }
}