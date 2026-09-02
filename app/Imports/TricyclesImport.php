<?php

namespace App\Imports;

use App\Models\Tricycle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TricyclesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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

        $dateRegistered = $this->parseDate($row['date_registered'] ?? null);
        $dateExpired = $this->parseDate($row['date_expired'] ?? null);

        // Some rows in the source sheet have these two swapped (clerical
        // error) — fix the order instead of failing the whole row.
        if ($dateRegistered !== null && $dateExpired !== null && $dateExpired < $dateRegistered) {
            [$dateRegistered, $dateExpired] = [$dateExpired, $dateRegistered];
        }

        $data = [
            'body_number' => $row['body_number'] ?? null,
            'plate_no' => $row['plate_no'] ?? null,
            'name' => $row['name'] ?? null,
            'address' => $row['address'] ?? null,
            'make_kind' => $row['make_kind'] ?? null,
            'status' => $this->normalizeStatus($row['status'] ?? ''),
            'engine_motor_no' => $row['engine_motor_no'] ?? null,
            'chassis_no' => $row['chassis_no'] ?? null,
            'date_registered' => $dateRegistered,
            'date_expired' => $dateExpired,
            'toda' => $this->normalizeToda($row['toda'] ?? null),
            'remarks' => $row['remarks'] ?? null,
        ];

        $validator = Validator::make($data, [
            'body_number' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:tricycles,plate_no',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'make_kind' => 'required|string|max:255',
            'status' => 'required|in:active,renewed,expired',
            'engine_motor_no' => 'nullable|string|max:255',
            'chassis_no' => 'nullable|string|max:255',
            'date_registered' => 'required|date',
            'date_expired' => 'required|date|after_or_equal:date_registered',
        ]);

        if ($validator->fails()) {
            $this->rowFailures[] = [
                'row' => $this->currentRow,
                'errors' => $validator->errors()->all(),
            ];

            return null;
        }

        return new Tricycle($data);
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
     * Collapses any header-to-key formatting quirks (e.g. "Make/Kind" or
     * "Engine/Motor No" producing double underscores or odd separators)
     * into clean, predictable snake_case keys.
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

    private function normalizeToda(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        foreach (array_keys(Tricycle::TODA_OPTIONS) as $option) {
            if (strcasecmp($option, $value) === 0) {
                return $option;
            }
        }

        return $value;
    }

    /**
     * Maps loosely-worded status labels from the spreadsheet (e.g. "New")
     * onto the three statuses the system actually understands.
     */
    private function normalizeStatus($value): string
    {
        $value = strtolower(trim((string) $value));

        $aliases = [
            'new' => 'active',
            'newly registered' => 'active',
            'active' => 'active',
            'renewed' => 'renewed',
            'renew' => 'renewed',
            'expired' => 'expired',
            'expire' => 'expired',
            'inactive' => 'expired',
        ];

        return $aliases[$value] ?? $value;
    }
}