<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Tricycle;
use Illuminate\Http\Request;

class FranchiseController extends Controller
{
    public function index(Request $request)
    {
        $franchises = Franchise::query()
            ->with('tricycle')
            ->when($request->filled('name'), fn ($q) =>
                $q->where('name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('plate'), fn ($q) =>
                $q->where('plate_no', 'like', '%' . $request->input('plate') . '%'))
            ->when($request->filled('authorized_no'), fn ($q) =>
                $q->where('authorized_no', 'like', '%' . $request->input('authorized_no') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        $tricycles = Tricycle::orderBy('body_number')->get();

        if ($request->ajax()) {
            return view('admin.partials.franchise-ajax-results', compact('franchises', 'tricycles'));
        }

        return view('admin.franchise', compact('franchises', 'tricycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'name' => 'required|string|max:255',
            'valid_until' => 'required|date',
            'plate_no' => 'required|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'status' => 'required|in:New,Renewed,Expired',
            'authorized_no' => 'required|string|max:255|unique:franchises,authorized_no',
            'motor_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
            'authorized_route' => 'required|string',
            'purpose' => 'nullable|string',
            'official_receipt_no' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|min:0',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
        ]);

        Franchise::create($validated);

        return redirect()->route('tricycle.franchise')->with('success', 'Franchise added successfully.');
    }

    public function update(Request $request, Franchise $franchise)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'name' => 'required|string|max:255',
            'valid_until' => 'required|date',
            'plate_no' => 'required|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'status' => 'required|in:New,Renewed,Expired',
            'authorized_no' => 'required|string|max:255|unique:franchises,authorized_no,' . $franchise->id,
            'motor_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
            'authorized_route' => 'required|string',
            'purpose' => 'nullable|string',
            'official_receipt_no' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|min:0',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
        ]);

        $franchise->update($validated);

        return redirect()->route('tricycle.franchise')->with('success', 'Franchise updated successfully.');
    }

    public function destroy(Franchise $franchise)
    {
        $franchise->delete();

        return redirect()->route('tricycle.franchise')->with('success', 'Franchise removed successfully.');
    }

    public function print(Franchise $franchise)
    {
        $franchise->load('tricycle');

        return view('admin.franchise-print', compact('franchise'));
    }
}