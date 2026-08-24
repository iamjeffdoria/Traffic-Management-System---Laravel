<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}