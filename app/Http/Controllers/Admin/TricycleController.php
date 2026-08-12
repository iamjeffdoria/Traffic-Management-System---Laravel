<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tricycle;
use Illuminate\Http\Request;

class TricycleController extends Controller
{
    public function index(Request $request)
    {
        $tricycles = Tricycle::query()
            ->when($request->filled('body'), fn ($q) =>
                $q->where('body_number', 'like', '%' . $request->input('body') . '%'))
            ->when($request->filled('plate'), fn ($q) =>
                $q->where('plate_no', 'like', '%' . $request->input('plate') . '%'))
            ->when($request->filled('name'), fn ($q) =>
                $q->where('name', 'like', '%' . $request->input('name') . '%'))
            ->when($request->filled('address'), fn ($q) =>
                $q->where('address', 'like', '%' . $request->input('address') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->when($request->filled('toda'), fn ($q) =>
                $q->where('toda', $request->input('toda')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.partials.tricycle-ajax-results', compact('tricycles'));
        }

        return view('admin.tricycles', compact('tricycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_number' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:tricycles,plate_no',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'make_kind' => 'required|string|max:255',
            'status' => 'required|in:active,renewed,expired',
            'engine_motor_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
            'date_registered' => 'required|date',
            'date_expired' => 'required|date|after_or_equal:date_registered',
            'toda' => 'nullable|in:' . implode(',', array_keys(Tricycle::TODA_OPTIONS)),
            'remarks' => 'nullable|string',
        ]);

        Tricycle::create($validated);

        return redirect()->route('tricycle.list')->with('success', 'Tricycle added successfully.');
    }

    public function update(Request $request, Tricycle $tricycle)
    {
        $validated = $request->validate([
            'body_number' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255|unique:tricycles,plate_no,' . $tricycle->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'make_kind' => 'required|string|max:255',
            'status' => 'required|in:active,renewed,expired',
            'engine_motor_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
            'date_registered' => 'required|date',
            'date_expired' => 'required|date|after_or_equal:date_registered',
            'toda' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $tricycle->update($validated);

        return redirect()->route('tricycle.list')->with('success', 'Tricycle updated successfully.');
    }

    public function destroy(Tricycle $tricycle)
    {
        $tricycle->delete();

        return redirect()->route('tricycle.list')->with('success', 'Tricycle removed successfully.');
    }
}