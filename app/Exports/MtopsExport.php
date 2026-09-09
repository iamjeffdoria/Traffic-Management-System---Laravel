<?php

namespace App\Exports;

use App\Models\Mtop;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MtopsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Mtop::query()->with('tricycle')->orderBy('case_no');
    }

    public function headings(): array
    {
        return [
            'Body Number', 'Case No', 'No Of Units', 'Route Operation',
            'Date', 'Municipal Treasurer', 'Officer In Charge', 'Mayor',
        ];
    }

    public function map($mtop): array
    {
        return [
            optional($mtop->tricycle)->body_number,
            $mtop->case_no,
            $mtop->no_of_units,
            $mtop->route_operation,
            optional($mtop->date)->format('Y-m-d'),
            $mtop->municipal_treasurer,
            $mtop->officer_in_charge,
            $mtop->mayor,
        ];
    }
}