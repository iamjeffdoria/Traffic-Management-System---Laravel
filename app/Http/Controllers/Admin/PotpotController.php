<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Potpot;
use Illuminate\Http\Request;

class PotpotController extends Controller
{
    public function index(Request $request)
    {
        $potpots = Potpot::query()
            ->when($request->filled('body'), fn ($q) =>
                $q->where('body_number', 'like', '%' . $request->input('body') . '%'))
            ->when($request->filled('name'), fn ($q) =>
                $q->where('name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('address'), fn ($q) =>
                $q->where('address', 'like', '%' . $request->input('address') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.potpot-ajax-results', compact('potpots'));
        }

        return view('admin.potpots', compact('potpots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_number' => 'required|string|max:255|unique:potpots,body_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'or_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,renewed,expired',
            'date_registered' => 'required|date',
            'date_expired' => 'required|date|after_or_equal:date_registered',
            'remarks' => 'nullable|string',
        ]);

        Potpot::create($validated);

        return redirect()->route('potpot.list')->with('success', 'Potpot added successfully.');
    }

    public function update(Request $request, Potpot $potpot)
    {
        $validated = $request->validate([
            'body_number' => 'required|string|max:255|unique:potpots,body_number,' . $potpot->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'or_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,renewed,expired',
            'date_registered' => 'required|date',
            'date_expired' => 'required|date|after_or_equal:date_registered',
            'remarks' => 'nullable|string',
        ]);

        $potpot->update($validated);

        return redirect()->route('potpot.list')->with('success', 'Potpot updated successfully.');
    }
}