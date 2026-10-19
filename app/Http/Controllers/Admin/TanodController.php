<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tanod;
use App\Models\TanodId;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Illuminate\Http\Request;

class TanodController extends Controller
{
    public function index(Request $request)
    {
        $tanods = Tanod::query()
            ->when($request->filled('id_number'), fn ($q) =>
                $q->where('id_number', 'like', '%' . $request->input('id_number') . '%'))
            ->when($request->filled('name'), fn ($q) =>
                $q->where('full_name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('barangay'), fn ($q) =>
                $q->where('barangay', 'like', '%' . $request->input('barangay') . '%'))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.tanod-ajax-results', compact('tanods'));
        }

        return view('admin.tanods', compact('tanods'));
    }

    public function idCards(Request $request)
    {
        $tanodIds = TanodId::query()
            ->when($request->filled('id_number'), fn ($q) =>
                $q->where('id_number', 'like', '%' . $request->input('id_number') . '%'))
            ->when($request->filled('name'), fn ($q) =>
                $q->where('full_name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('address'), fn ($q) =>
                $q->where('address', 'like', '%' . $request->input('address') . '%'))
            ->when($request->filled('contact'), fn ($q) =>
                $q->where('emergency_contact_name', 'like', '%' . $request->input('contact') . '%'))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.tanod-id-ajax-results', compact('tanodIds'));
        }

        $tanods = Tanod::orderBy('full_name')->get([
            'id', 'id_number', 'full_name', 'home_address', 'date_of_birth',
            'emergency_contact_name', 'emergency_contact_address',
            'emergency_contact_number', 'emergency_contact_relationship',
        ]);

        return view('admin.tanod-id', compact('tanodIds', 'tanods'));
    }

    public function storeId(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|string|max:255|unique:tanod_ids,id_number',
            'photo' => 'nullable|image|max:5120',
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
            'emergency_contact_relationship' => 'required|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('tanod-ids', 'public');
        }
        unset($validated['photo']);

        TanodId::create($validated);

return redirect()->route('tanod.id')->with('success', 'Tanod ID added successfully.');
    }

    public function updateId(Request $request, TanodId $tanodId)
    {
        // Named error bag so a failed update doesn't reopen the "Add" modal.
        $validated = $request->validateWithBag('tanodIdUpdate', [
            'id_number' => 'required|string|max:255|unique:tanod_ids,id_number,' . $tanodId->id,
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
            'emergency_contact_relationship' => 'required|string|max:255',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            if ($tanodId->photo_path) {
                Storage::disk('public')->delete($tanodId->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('tanod-ids', 'public');
        }
        unset($validated['photo']);

        $tanodId->update($validated);

        return redirect()->route('tanod.id')->with('success', 'Tanod ID updated successfully.');
    }

    public function importIds(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip',
        ]);

        $tempDir = storage_path('app/temp/tanod-ids-import-' . uniqid());
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

            if ($row === [null]) {
                continue; // blank line
            }

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
                    $storedName = 'tanod-ids/' . uniqid() . '_' . $photoFilename;
                    Storage::disk('public')->put($storedName, file_get_contents($sourcePhoto));
                    $photoPath = $storedName;
                }
            }

            $data = [
                'id_number' => trim((string) ($row['id_number'] ?? '')) ?: null,
                'full_name' => trim((string) ($row['full_name'] ?? '')) ?: null,
                'address' => trim((string) ($row['address'] ?? '')) ?: null,
                'date_of_birth' => trim((string) ($row['date_of_birth'] ?? '')) ?: null,
                'emergency_contact_name' => trim((string) ($row['emergency_contact_name'] ?? '')) ?: null,
                'emergency_contact_address' => trim((string) ($row['emergency_contact_address'] ?? '')) ?: null,
                'emergency_contact_number' => trim((string) ($row['emergency_contact_number'] ?? '')) ?: null,
                'emergency_contact_relationship' => trim((string) ($row['emergency_contact_relationship'] ?? '')) ?: null,
                'photo_path' => $photoPath,
            ];

            $validator = Validator::make($data, [
                'id_number' => 'required|string|max:255|unique:tanod_ids,id_number',
                'full_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'emergency_contact_name' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:50',
                'emergency_contact_relationship' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                $rowFailures[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());

                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                continue;
            }

            TanodId::create($data);
        }

        fclose($handle);
        File::deleteDirectory($tempDir);

        if (!empty($rowFailures)) {
            return back()->withErrors($rowFailures);
        }

        return redirect()->route('tanod.id')->with('success', 'Tanod IDs imported successfully.');
    }

    public function printId(TanodId $tanodId)
    {
        return $this->renderIdSheet(collect([$tanodId]));
    }

    public function bulkPrintIds(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->take(4)
            ->values();

        abort_if($ids->isEmpty(), 404, 'No Tanod IDs selected for printing.');

        $tanodIds = TanodId::whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($t) => $ids->search($t->id))
            ->values();

        return $this->renderIdSheet($tanodIds);
    }

    protected function renderIdSheet($tanodIds)
    {
        // Barangay + Punong Barangay live on the Tanod List record, matched by ID number.
        $tanods = Tanod::whereIn('id_number', $tanodIds->pluck('id_number'))
            ->get()
            ->keyBy('id_number');

        return view('admin.tanod-id-print', compact('tanodIds', 'tanods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|string|max:255|unique:tanods,id_number',
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'barangay_chairman' => 'required|string|max:255',
            'home_address' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'blood_type' => 'nullable|in:' . implode(',', Tanod::BLOOD_TYPES),
            'mobile_no' => 'nullable|string|max:50',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
        ]);

        Tanod::create($validated);

        return redirect()->route('tanod.list')->with('success', 'Tanod added successfully.');
    }

    public function update(Request $request, Tanod $tanod)
    {
        // Named error bag so a failed update doesn't reopen the "Add" modal.
        $validated = $request->validateWithBag('tanodUpdate', [
            'id_number' => 'required|string|max:255|unique:tanods,id_number,' . $tanod->id,
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'barangay_chairman' => 'required|string|max:255',
            'home_address' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'blood_type' => 'nullable|in:' . implode(',', Tanod::BLOOD_TYPES),
            'mobile_no' => 'nullable|string|max:50',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
        ]);

        $tanod->update($validated);

        return redirect()->route('tanod.list')->with('success', 'Tanod updated successfully.');
    }

    public function destroy(Tanod $tanod)
    {
        $tanod->delete();

        return redirect()->route('tanod.list')->with('success', 'Tanod removed successfully.');
    }
}