<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotpotMayorsPermit;
use Illuminate\Http\Request;

class PotpotMayorsPermitController extends Controller
{
    public function index(Request $request)
    {
        $permits = PotpotMayorsPermit::query()
            ->when($request->filled('control_no'), fn ($q) =>
                $q->where('control_no', 'like', '%' . $request->input('control_no') . '%'))
            ->when($request->filled('name'), fn ($q) =>
                $q->where('name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('business_name'), fn ($q) =>
                $q->where('business_name', 'like', '%' . $request->input('business_name') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.potpot-mayors-permit-ajax-results', compact('permits'));
        }

        return view('admin.potpot-mayors-permit', compact('permits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'control_no' => 'required|string|max:255|unique:potpot_mayors_permits,control_no',
            'status' => 'required|in:active,expired',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
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

        PotpotMayorsPermit::create($validated);

        return redirect()->route('potpot.mayors-permit')->with('success', 'Permit added successfully.');
    }

    public function update(Request $request, PotpotMayorsPermit $permit)
    {
        $validated = $request->validate([
            'control_no' => 'required|string|max:255|unique:potpot_mayors_permits,control_no,' . $permit->id,
            'status' => 'required|in:active,expired',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
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

        $permit->update($validated);

        return redirect()->route('potpot.mayors-permit')->with('success', 'Permit updated successfully.');
    }

    public function destroy(PotpotMayorsPermit $permit)
    {
        $permit->delete();

        return redirect()->route('potpot.mayors-permit')->with('success', 'Permit removed successfully.');
    }

    public function print(PotpotMayorsPermit $permit)
    {
        return view('admin.potpot-mayors-permit-print', compact('permit'));
    }
}