<?php

namespace App\Imports;

use App\Models\Tricycle;
use App\Models\Mtop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MtopsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected array $rowFailures = [];
    protected int $currentRow = 1;

    public function __construct()
    {
        HeadingRowFormatter::default('none');
    }

    public function model(array $row)
    {
        $this->currentRow++;

        $row = $this->normalizeRow($row);

        $tricycleId = $this->resolveTricycleId($row['body_number'] ?? null);

        $data = [
            'tricycle_id' => $tricycleId,
            'case_no' => $this->toStringOrNull($row['case_no'] ?? null),
            'no_of_units' => $row['no_of_units'] ?? null,
            'route_operation' => $this->toStringOrNull($row['route_operation'] ?? null),
            'date' => $this->parseDate($row['date'] ?? null),
            'municipal_treasurer' => $this->toStringOrNull($row['municipal_treasurer'] ?? null),
            'officer_in_charge' => $this->toStringOrNull($row['officer_in_charge'] ?? null),
            'mayor' => $this->toStringOrNull($row['mayor'] ?? null),
        ];

        $validator = Validator::make($data, [
            'tricycle_id' => 'required|exists:tricycles,id',
            'case_no' => 'required|string|max:255|unique:mtops,case_no',
            'no_of_units' => 'required|integer|min:1',
            'route_operation' => 'required|string|max:255',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
            'officer_in_charge' => 'required|string|max:255',
            'mayor' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->rowFailures[] = [
                'row' => $this->currentRow,
                'errors' => $validator->errors()->all(),
            ];

            return null;
        }

        return new Mtop($data);
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getRowFailures(): array
    {
        return $this->rowFailures;
    }

    /**
     * Collapses any header-to-key formatting quirks into clean,
     * predictable snake_case keys.
     */
    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $cleanKey = strtolower((string) $key);
            $cleanKey = preg_replace('/[^a-z0-9]+/', '_', $cleanKey);
            $cleanKey = trim($cleanKey, '_');

            $normalized[$cleanKey] = is_string($value) ? trim($value) : $value;
        }

        return $normalized;
    }

    /**
     * Looks up the tricycle by body number so the spreadsheet doesn't
     * need to know the raw database id.
     */
    private function resolveTricycleId($bodyNumber): ?int
    {
        if (empty($bodyNumber)) {
            return null;
        }

        return Tricycle::where('body_number', trim((string) $bodyNumber))->value('id');
    }

    /**
     * Parses dates from any common format, including Excel's numeric
     * date serials (when the source cell is formatted as a date).
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                // fall through to string parsing below
            }
        }

        $value = trim((string) $value);

        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'm-d-Y', 'd-m-Y', 'Y/m/d', 'M d, Y', 'd M Y', 'M-d-y', 'M/d/y'];
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Excel returns numeric-looking cells (e.g. a purely numeric case
     * number) as int/float instead of string, which fails the "string"
     * validation rule. Cast them back to plain strings here.
     */
    private function toStringOrNull($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_float($value) && floor($value) === $value) {
            // Avoid "24001.0" style artifacts from Excel float cells.
            return (string) (int) $value;
        }

        return trim((string) $value);
    }
}