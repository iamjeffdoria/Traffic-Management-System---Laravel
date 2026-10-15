<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasabutanSelling;
use Illuminate\Http\Request;

class KasabutanController extends Controller
{
    public function selling()
    {
        $sellings = KasabutanSelling::latest('updated_at')->paginate(25);

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