<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Potpot;
use Illuminate\Http\Request;

class PotpotController extends Controller
{
    public function index()
    {
        $potpots = Potpot::query()
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

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
}