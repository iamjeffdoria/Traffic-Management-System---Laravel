<?php

namespace App\Imports;

use App\Models\Tricycle;
use App\Models\Franchise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class FranchisesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
            'valid_until' => $this->parseDate($row['valid_until'] ?? null),
            'denomination' => $this->toStringOrNull($row['denomination'] ?? null),
            'status' => $this->normalizeStatus($row['status'] ?? ''),
            'authorized_no' => $this->toStringOrNull($row['authorized_no'] ?? null),
            'authorized_route' => $this->toStringOrNull($row['authorized_route'] ?? null),
            'purpose' => $this->toStringOrNull($row['purpose'] ?? null),
            'official_receipt_no' => $this->toStringOrNull($row['official_receipt_no'] ?? null),
            'amount_paid' => $row['amount_paid'] ?? null,
            'date' => $this->parseDate($row['date'] ?? null),
            'municipal_treasurer' => $this->toStringOrNull($row['municipal_treasurer'] ?? null),
        ];

        $validator = Validator::make($data, [
            'tricycle_id' => 'required|exists:tricycles,id',
            'valid_until' => 'required|date',
            'denomination' => 'nullable|string|max:255',
            'status' => 'required|in:New,Renewed,Expired',
            'authorized_no' => 'required|string|max:255|unique:franchises,authorized_no',
            'authorized_route' => 'required|string',
            'purpose' => 'nullable|string',
            'official_receipt_no' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|min:0',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->rowFailures[] = [
                'row' => $this->currentRow,
                'errors' => $validator->errors()->all(),
            ];

            return null;
        }

        return new Franchise($data);
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
     * Maps loosely-worded status labels from the spreadsheet onto the
     * three statuses the system actually understands.
     */
    private function normalizeStatus($value): string
    {
        $value = strtolower(trim((string) $value));

        $aliases = [
            'new' => 'New',
            'newly registered' => 'New',
            'renewed' => 'Renewed',
            'renew' => 'Renewed',
            'expired' => 'Expired',
            'expire' => 'Expired',
            'inactive' => 'Expired',
        ];

        return $aliases[$value] ?? $value;
    }

    /**
     * Excel returns numeric-looking cells (e.g. a purely numeric
     * authorized/receipt number) as int/float instead of string, which
     * fails the "string" validation rule. Cast them back to plain strings.
     */
    private function toStringOrNull($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_float($value) && floor($value) === $value) {
            // Avoid "1024.0" style artifacts from Excel float cells.
            return (string) (int) $value;
        }

        return trim((string) $value);
    }
}