<?php

namespace App\Imports;

use App\Models\Tricycle;
use App\Models\TricycleMayorsPermit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TricycleMayorsPermitsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
            'control_no' => $this->toStringOrNull($row['control_no'] ?? null),
            'status' => $this->normalizeStatus($row['status'] ?? ''),
            'business_name' => $this->toStringOrNull($row['business_name'] ?? null),
            'motorized_operation' => $this->toStringOrNull($row['motorized_operation'] ?? null),
            'or_no' => $this->toStringOrNull($row['or_no'] ?? null),
            'amount_paid' => $row['amount_paid'] ?? null,
            'issue_date' => $this->parseDate($row['issue_date'] ?? null),
            'expiry_date' => $this->parseDate($row['expiry_date'] ?? null),
            'issued_at' => $this->toStringOrNull($row['issued_at'] ?? null),
            'mayor' => $this->toStringOrNull($row['mayor'] ?? null),
            'quarter' => $this->toStringOrNull($row['quarter'] ?? null),
        ];

        $validator = Validator::make($data, [
            'tricycle_id' => 'required|exists:tricycles,id',
            'control_no' => 'required|string|max:255|unique:tricycle_mayors_permits,control_no',
            'status' => 'required|in:active,expired',
            'business_name' => 'nullable|string|max:255',
            'motorized_operation' => 'required|string|max:255',
            'or_no' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:issue_date',
            'issued_at' => 'required|string|max:255',
            'mayor' => 'required|string|max:255',
            'quarter' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->rowFailures[] = [
                'row' => $this->currentRow,
                'errors' => $validator->errors()->all(),
            ];

            return null;
        }

        return new TricycleMayorsPermit($data);
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
     * Excel returns numeric-looking cells (e.g. a purely numeric control
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
     * Maps loosely-worded status labels from the spreadsheet onto the
     * two statuses the system actually understands.
     */
    private function normalizeStatus($value): string
    {
        $value = strtolower(trim((string) $value));

        $aliases = [
            'active' => 'active',
            'new' => 'active',
            'renewed' => 'active',
            'expired' => 'expired',
            'expire' => 'expired',
            'inactive' => 'expired',
        ];

        return $aliases[$value] ?? $value;
    }
}