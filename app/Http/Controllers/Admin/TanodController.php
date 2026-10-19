<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tanod;
use App\Models\TanodId;
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
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:50',
            'emergency_contact_relationship' => 'required|string|max:255',
        ]);

        TanodId::create($validated);

return redirect()->route('tanod.id')->with('success', 'Tanod ID added successfully.');
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