<?php

namespace App\Exports;

use App\Models\Franchise;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FranchisesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Franchise::query()->with('tricycle')->orderBy('authorized_no');
    }

    public function headings(): array
    {
        return [
            'Body Number', 'Valid Until', 'Denomination', 'Status', 'Authorized No',
            'Authorized Route', 'Purpose', 'Official Receipt No', 'Amount Paid', 'Date', 'Municipal Treasurer',
        ];
    }

    public function map($franchise): array
    {
        return [
            optional($franchise->tricycle)->body_number,
            optional($franchise->valid_until)->format('Y-m-d'),
            $franchise->denomination,
            $franchise->status,
            $franchise->authorized_no,
            $franchise->authorized_route,
            $franchise->purpose,
            $franchise->official_receipt_no,
            $franchise->amount_paid,
            optional($franchise->date)->format('Y-m-d'),
            $franchise->municipal_treasurer,
        ];
    }
}