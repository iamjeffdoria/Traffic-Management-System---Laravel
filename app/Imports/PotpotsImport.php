<?php

namespace App\Imports;

use App\Models\Potpot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PotpotsImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    protected array $rowFailures = [];
    protected int $created = 0;
    protected int $updated = 0;

    private const MONTHS = [
        'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
        'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12,
    ];

    public function __construct()
    {
        HeadingRowFormatter::default('none');
    }

    /**
     * Only the first sheet is imported; extra sheets (Cleanup Log,
     * Instructions, ...) are ignored.
     */
    public function sheets(): array
    {
        return [0 => $this];
    }

    public function collection(Collection $rows): void
    {
        $seen = [];
        $position = 0;

        foreach ($rows as $rawRow) {
            $rowNumber = ++$position + 1; // +1 for the heading row

            $row = $this->normalizeRow($rawRow instanceof Collection ? $rawRow->toArray() : (array) $rawRow);

            if ($this->isBlank($row)) {
                continue;
            }

            $errors = [];

            $registeredRaw = $this->pick($row, ['date_registered', 'date_of_registration', 'registration_date']);
            $expiredRaw = $this->pick($row, ['date_expired', 'expiration_date', 'expiry_date', 'date_of_expiration']);

            $registered = $this->parseDate($registeredRaw);
            $expired = $this->parseDate($expiredRaw);

            if ($registeredRaw !== null && $registered === null) {
                $errors[] = 'Date Registered "' . $this->display($registeredRaw) . '" is not a valid date.';
            }
            if ($expiredRaw !== null && $expired === null) {
                $errors[] = 'Date Expired "' . $this->display($expiredRaw) . '" is not a valid date.';
            }
            if ($registered && $expired && $expired < $registered) {
                $errors[] = 'Date Expired is earlier than Date Registered.';
            }

            $remarks = $this->toStringOrNull($this->pick($row, ['remarks', 'remark']));

            $data = [
                'body_number' => $this->toStringOrNull($this->pick($row, ['body_number', 'body_no', 'body'])),
                'name' => $this->toStringOrNull($this->pick($row, ['operator', 'name'])),
                'address' => $this->toStringOrNull($this->pick($row, ['address'])),
                'contact_number' => $this->normalizeContact($this->pick($row, ['contact_number', 'contact_no', 'contact'])),
                'or_number' => $this->toStringOrNull($this->pick($row, ['or_number', 'or_no', 'or'])),
                'status' => $this->resolveStatus($this->pick($row, ['status']), $expired, $remarks),
                'date_registered' => $registered,
                'date_expired' => $expired,
                'remarks' => $remarks,
            ];

            $validator = Validator::make($data, [
                'body_number' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:50',
                'or_number' => 'nullable|string|max:255',
                'status' => 'required|in:active,renewed,expired',
                'date_registered' => 'nullable|date',
                'date_expired' => 'nullable|date',
                'remarks' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
            }

            if (empty($errors) && isset($seen[$data['body_number']])) {
                $errors[] = 'Body Number appears twice in this file (first on row ' . $seen[$data['body_number']] . ').';
            }

            if (!empty($errors)) {
                $this->rowFailures[] = [
                    'row' => $rowNumber,
                    'body' => $data['body_number'],
                    'errors' => $errors,
                ];
                continue;
            }

            $seen[$data['body_number']] = $rowNumber;

            try {
                $potpot = Potpot::updateOrCreate(
                    ['body_number' => $data['body_number']],
                    collect($data)->except('body_number')->all()
                );

                $potpot->wasRecentlyCreated ? $this->created++ : $this->updated++;
            } catch (\Throwable $e) {
                $this->rowFailures[] = [
                    'row' => $rowNumber,
                    'body' => $data['body_number'],
                    'errors' => ['Could not be saved: ' . $e->getMessage()],
                ];
            }
        }
    }

    public function getRowFailures(): array
    {
        return $this->rowFailures;
    }

    public function getCreatedCount(): int
    {
        return $this->created;
    }

    public function getUpdatedCount(): int
    {
        return $this->updated;
    }

    /**
     * Turns any header spelling ("CONTACT No.", "Date of Registration") into
     * a clean snake_case key and tidies every cell value.
     */
    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $cleanKey = trim(preg_replace('/[^a-z0-9]+/', '_', strtolower((string) $key)), '_');

            if ($cleanKey === '') {
                continue;
            }

            if (is_string($value)) {
                $value = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $value));
                if ($value === '') {
                    $value = null;
                }
            }

            $normalized[$cleanKey] = $value;
        }

        return $normalized;
    }

    private function isBlank(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null) {
                return false;
            }
        }

        return true;
    }

    private function pick(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null) {
                return $row[$key];
            }
        }

        return null;
    }

    private function display($value): string
    {
        return is_scalar($value) ? (string) $value : 'unreadable value';
    }

    private function toStringOrNull($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_float($value) && floor($value) === $value) {
            return (string) (int) $value;
        }

        return trim((string) $value);
    }

    /**
     * Excel drops the leading 0 of numeric phone numbers (09123456789 -> 9123456789).
     * Put it back for 10-digit mobile numbers.
     */
    private function normalizeContact($value): ?string
    {
        $contact = $this->toStringOrNull($value);

        if ($contact !== null && preg_match('/^9\d{9}$/', $contact)) {
            return '0' . $contact;
        }

        return $contact;
    }

    private function resolveStatus($status, ?string $expired, ?string $remarks): string
    {
        $value = strtolower(trim((string) $status));

        $aliases = [
            'active' => 'active',
            'new' => 'active',
            'newly registered' => 'active',
            'renewed' => 'renewed',
            'renew' => 'renewed',
            'expired' => 'expired',
            'expire' => 'expired',
            'inactive' => 'expired',
        ];

        if ($value !== '') {
            return $aliases[$value] ?? $value; // unknown values fail validation and get reported
        }

        // Blank status: work it out from the dates / remarks.
        if ($expired === null || $expired < now()->toDateString()) {
            return 'expired';
        }

        if ($remarks !== null && stripos($remarks, 'renew') !== false) {
            return 'renewed';
        }

        return 'active';
    }

    /**
     * Accepts Excel date cells, Excel serial numbers, 2026-04-22, 4/22/2026,
     * 22-Apr-26, 5-Sept-25, "April 22, 2026" ... Returns null if unreadable.
     */
    private function parseDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            $serial = (float) $value;

            if ($serial >= 1 && $serial < 2958466) {
                try {
                    return ExcelDate::excelToDateTimeObject($serial)->format('Y-m-d');
                } catch (\Throwable $e) {
                    return null;
                }
            }

            return null;
        }

        $value = trim((string) $value);

        // 22-Apr-26, 5-Sept-25, 4-March-26, 3-Aug.-22
        if (preg_match('/^(\d{1,2})[\s\-\/\.]+([A-Za-z]+)\.?[\s\-\/\.,]*(\d{2,4})$/', $value, $m)) {
            $month = self::MONTHS[strtolower(substr($m[2], 0, 3))] ?? null;

            if ($month) {
                $day = (int) $m[1];
                $year = (int) $m[3];
                if ($year < 100) {
                    $year += 2000;
                }

                return checkdate($month, $day, $year)
                    ? sprintf('%04d-%02d-%02d', $year, $month, $day)
                    : null;
            }
        }

        $formats = ['Y-m-d', 'Y/m/d', 'm/d/Y', 'm-d-Y', 'd/m/Y', 'd-m-Y', 'm/d/y', 'm-d-y', 'M d, Y', 'F d, Y', 'd M Y', 'd F Y'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $errors = \DateTime::getLastErrors();

            if ($date && (!$errors || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }
}