<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasabutanLostItem;
use App\Models\KasabutanSelling;
use App\Models\Potpot;
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

        $potpots = Potpot::orderBy('body_number')->get(['id', 'body_number', 'name', 'address']);

        return view('admin.kasabutan-selling', compact('sellings', 'potpots'));
    }

    public function printSelling(KasabutanSelling $selling)
    {
        return view('admin.kasabutan-selling-print', compact('selling'));
    }

    public function storeSelling(Request $request)
    {
        $validated = $request->validate([
            'secretariat' => 'required|string|max:255',
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

    public function updateSelling(Request $request, KasabutanSelling $selling)
    {
        $validated = $request->validate([
            'secretariat' => 'required|string|max:255',
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

        $selling->update($validated);

        return redirect()->route('potpot.kasabutan.selling')->with('success', 'Kasabutan record updated successfully.');
    }

    public function destroySelling(KasabutanSelling $selling)
    {
        $selling->delete();

        return redirect()->route('potpot.kasabutan.selling')->with('success', 'Kasabutan record removed successfully.');
    }

    public function lostItem(Request $request)
    {
        $lostItems = KasabutanLostItem::query()
            ->when($request->filled('driver'), fn ($q) =>
                $q->where('driver', 'like', '%' . $request->input('driver') . '%'))
            ->when($request->filled('body'), fn ($q) =>
                $q->where('body_number', 'like', '%' . $request->input('body') . '%'))
            ->when($request->filled('passenger'), fn ($q) =>
                $q->where('passenger', 'like', '%' . $request->input('passenger') . '%'))
            ->when($request->filled('witness'), fn ($q) =>
                $q->where(fn ($w) =>
                    $w->where('witness_1', 'like', '%' . $request->input('witness') . '%')
                      ->orWhere('witness_2', 'like', '%' . $request->input('witness') . '%')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.kasabutan-lost-item-ajax-results', compact('lostItems'));
        }

        $potpots = Potpot::orderBy('body_number')->get(['id', 'body_number', 'name', 'driver', 'address']);

        return view('admin.kasabutan-lost-item', compact('lostItems', 'potpots'));
    }

    public function printLostItem(KasabutanLostItem $lostItem)
    {
        return view('admin.kasabutan-lost-item-print', compact('lostItem'));
    }

    public function storeLostItem(Request $request)
    {
        $validated = $request->validate([
            'driver' => 'required|string|max:255',
            'body_number' => 'required|string|max:255',
            'driver_age' => 'required|integer|min:1|max:120',
            'driver_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'passenger' => 'required|string|max:255',
            'passenger_age' => 'required|integer|min:1|max:120',
            'passenger_address' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'issued_at' => 'required|string|max:255',
            'witness_1' => 'required|string|max:255',
            'witness_2' => 'required|string|max:255',
            'it_section' => 'required|string|max:255',
            'security_officer' => 'required|string|max:255',
        ]);

        KasabutanLostItem::create($validated);

        return redirect()->route('potpot.kasabutan.lost-item')->with('success', 'Kasabutan - Lost Item record added successfully.');
    }

    public function updateLostItem(Request $request, KasabutanLostItem $lostItem)
    {
        // Named error bag so a failed update doesn't reopen the "Add" modal.
        $validated = $request->validateWithBag('lostItemUpdate', [
            'driver' => 'required|string|max:255',
            'body_number' => 'required|string|max:255',
            'driver_age' => 'required|integer|min:1|max:120',
            'driver_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'passenger' => 'required|string|max:255',
            'passenger_age' => 'required|integer|min:1|max:120',
            'passenger_address' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'issued_at' => 'required|string|max:255',
            'witness_1' => 'required|string|max:255',
            'witness_2' => 'required|string|max:255',
            'it_section' => 'required|string|max:255',
            'security_officer' => 'required|string|max:255',
        ]);

        $lostItem->update($validated);

        return redirect()->route('potpot.kasabutan.lost-item')->with('success', 'Kasabutan - Lost Item record updated successfully.');
    }

    public function destroyLostItem(KasabutanLostItem $lostItem)
    {
        $lostItem->delete();

        return redirect()->route('potpot.kasabutan.lost-item')->with('success', 'Kasabutan - Lost Item record removed successfully.');
    }
}