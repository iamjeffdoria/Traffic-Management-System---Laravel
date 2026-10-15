<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasabutanSelling;
use Illuminate\Http\Request;

class KasabutanController extends Controller
{
    public function selling(Request $request)
    {
        $sellings = KasabutanSelling::query()
            ->when($request->filled('owner'), fn ($q) =>
                $q->where('owner', 'like', '%' . $request->input('owner') . '%'))
            ->when($request->filled('body'), fn ($q) =>
                $q->where('body_number', 'like', '%' . $request->input('body') . '%'))
            ->when($request->filled('buyer'), fn ($q) =>
                $q->where('buyer', 'like', '%' . $request->input('buyer') . '%'))
            ->when($request->filled('witness'), fn ($q) =>
                $q->where('witness', 'like', '%' . $request->input('witness') . '%'))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.kasabutan-selling-ajax-results', compact('sellings'));
        }

        return view('admin.kasabutan-selling', compact('sellings'));
    }

    public function storeSelling(Request $request)
    {
        $validated = $request->validate([
            'owner' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'body_number' => 'required|string|max:255',
            'buyer' => 'required|string|max:255',
            'buyer_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'witness' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        KasabutanSelling::create($validated);

        return redirect()->route('potpot.kasabutan.selling')->with('success', 'Kasabutan record added successfully.');
    }

    public function lostItem()
    {
        return view('admin.kasabutan-lost-item');
    }
}