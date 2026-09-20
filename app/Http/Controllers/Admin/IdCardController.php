<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        $idCards = IdCard::query()
            ->when($request->filled('full_name'), fn ($q) =>
                $q->where('full_name', 'like', '%' . $request->input('full_name') . '%'))
            ->when($request->filled('id_number'), fn ($q) =>
                $q->where('id_number', 'like', '%' . $request->input('id_number') . '%'))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.id-card-ajax-results', compact('idCards'));
        }

        return view('admin.potpot-id-cards', compact('idCards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:id_cards,id_number',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'or_number' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:date_issued',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('id-cards', 'public');
        }
        unset($validated['photo']);

        IdCard::create($validated);

        return redirect()->route('potpot.id-cards')->with('success', 'ID card added successfully.');
    }

    public function update(Request $request, IdCard $idCard)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:id_cards,id_number,' . $idCard->id,
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'or_number' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:date_issued',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            if ($idCard->photo_path) {
                Storage::disk('public')->delete($idCard->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('id-cards', 'public');
        }
        unset($validated['photo']);

        $idCard->update($validated);

        return redirect()->route('potpot.id-cards')->with('success', 'ID card updated successfully.');
    }

    public function destroy(IdCard $idCard)
    {
        if ($idCard->photo_path) {
            Storage::disk('public')->delete($idCard->photo_path);
        }

        $idCard->delete();

        return redirect()->route('potpot.id-cards')->with('success', 'ID card removed successfully.');
    }

    public function print(IdCard $idCard)
    {
        return view('admin.id-card-print', compact('idCard'));
    }

    public function export()
    {
        $idCards = IdCard::orderBy('full_name')->get();

        $tempDir = storage_path('app/temp/id-cards-export-' . uniqid());
        mkdir($tempDir, 0755, true);
        mkdir($tempDir . '/photos', 0755, true);

        $csvPath = $tempDir . '/data.csv';
        $csv = fopen($csvPath, 'w');
        fputcsv($csv, [
            'Full Name', 'ID Number', 'Gender', 'Date of Birth', 'Address',
            'Height', 'Weight', 'OR Number', 'Date Issued', 'Expiry Date', 'Photo Filename',
        ]);

        foreach ($idCards as $idCard) {
            $photoFilename = '';

            if ($idCard->photo_path && Storage::disk('public')->exists($idCard->photo_path)) {
                $photoFilename = $idCard->id_number . '_' . basename($idCard->photo_path);
                copy(Storage::disk('public')->path($idCard->photo_path), $tempDir . '/photos/' . $photoFilename);
            }

            fputcsv($csv, [
                $idCard->full_name,
                $idCard->id_number,
                $idCard->gender,
                optional($idCard->date_of_birth)->format('Y-m-d'),
                $idCard->address,
                $idCard->height,
                $idCard->weight,
                $idCard->or_number,
                optional($idCard->date_issued)->format('Y-m-d'),
                optional($idCard->expiry_date)->format('Y-m-d'),
                $photoFilename,
            ]);
        }

        fclose($csv);

        $zipName = 'id-cards-' . now()->format('Y-m-d') . '-' . uniqid() . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($csvPath, 'data.csv');

        foreach (glob($tempDir . '/photos/*') as $photoFile) {
            $zip->addFile($photoFile, 'photos/' . basename($photoFile));
        }

        $zip->close();

        File::deleteDirectory($tempDir);

        return response()->download($zipPath, 'id-cards-' . now()->format('Y-m-d') . '.zip')
            ->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip',
        ]);

        $tempDir = storage_path('app/temp/id-cards-import-' . uniqid());
        mkdir($tempDir, 0755, true);

        $zip = new ZipArchive;

        if ($zip->open($request->file('file')->getRealPath()) !== true) {
            File::deleteDirectory($tempDir);

            return back()->withErrors(['file' => 'Could not open the uploaded zip file.']);
        }

        $zip->extractTo($tempDir);
        $zip->close();

        $csvPath = $tempDir . '/data.csv';

        if (!file_exists($csvPath)) {
            File::deleteDirectory($tempDir);

            return back()->withErrors(['file' => 'The zip file must contain a data.csv file at its root.']);
        }

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle);
        $header = array_map(fn ($h) => strtolower(str_replace(' ', '_', trim((string) $h))), $header);

        $rowFailures = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count($row) !== count($header)) {
                $rowFailures[] = "Row {$rowNumber}: column count does not match header.";
                continue;
            }

            $row = array_combine($header, $row);
            $photoPath = null;
            $photoFilename = basename(trim((string) ($row['photo_filename'] ?? '')));

            if ($photoFilename !== '') {
                $sourcePhoto = $tempDir . '/photos/' . $photoFilename;

                if (is_file($sourcePhoto) && in_array(strtolower(pathinfo($photoFilename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    $storedName = 'id-cards/' . uniqid() . '_' . $photoFilename;
                    Storage::disk('public')->put($storedName, file_get_contents($sourcePhoto));
                    $photoPath = $storedName;
                }
            }

            $data = [
                'full_name' => trim((string) ($row['full_name'] ?? '')) ?: null,
                'id_number' => trim((string) ($row['id_number'] ?? '')) ?: null,
                'gender' => trim((string) ($row['gender'] ?? '')) ?: null,
                'date_of_birth' => trim((string) ($row['date_of_birth'] ?? '')) ?: null,
                'address' => trim((string) ($row['address'] ?? '')) ?: null,
                'height' => $row['height'] ?? null,
                'weight' => $row['weight'] ?? null,
                'or_number' => trim((string) ($row['or_number'] ?? '')) ?: null,
                'date_issued' => trim((string) ($row['date_issued'] ?? '')) ?: null,
                'expiry_date' => trim((string) ($row['expiry_date'] ?? '')) ?: null,
                'photo_path' => $photoPath,
            ];

            $validator = Validator::make($data, [
                'full_name' => 'required|string|max:255',
                'id_number' => 'required|string|max:255|unique:id_cards,id_number',
                'gender' => 'required|in:Male,Female',
                'date_of_birth' => 'required|date',
                'address' => 'required|string|max:255',
                'height' => 'required|numeric|min:0',
                'weight' => 'required|numeric|min:0',
                'or_number' => 'required|string|max:255',
                'date_issued' => 'required|date',
                'expiry_date' => 'required|date|after_or_equal:date_issued',
            ]);

            if ($validator->fails()) {
                $rowFailures[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());

                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                continue;
            }

            IdCard::create($data);
        }

        fclose($handle);
        File::deleteDirectory($tempDir);

        if (!empty($rowFailures)) {
            return back()->withErrors($rowFailures);
        }

        return redirect()->route('potpot.id-cards')->with('success', 'ID cards imported successfully.');
    }
}