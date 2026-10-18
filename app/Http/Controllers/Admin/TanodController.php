<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tanod;
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

    public function idCards()
    {
        return view('admin.tanod-id');
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