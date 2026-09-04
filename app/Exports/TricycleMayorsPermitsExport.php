<?php

namespace App\Exports;

use App\Models\TricycleMayorsPermit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TricycleMayorsPermitsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return TricycleMayorsPermit::query()->with('tricycle')->orderBy('control_no');
    }

    public function headings(): array
    {
        return [
            'Body Number', 'Control No', 'Status', 'Business Name', 'Motorized Operation',
            'OR No', 'Amount Paid', 'Issue Date', 'Expiry Date', 'Issued At', 'Mayor', 'Quarter',
        ];
    }

    public function map($permit): array
    {
        return [
            optional($permit->tricycle)->body_number,
            $permit->control_no,
            $permit->status,
            $permit->business_name,
            $permit->motorized_operation,
            $permit->or_no,
            $permit->amount_paid,
            optional($permit->issue_date)->format('Y-m-d'),
            optional($permit->expiry_date)->format('Y-m-d'),
            $permit->issued_at,
            $permit->mayor,
            $permit->quarter,
        ];
    }
}