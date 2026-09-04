<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TricycleMayorsPermit;
use App\Models\Tricycle;
use App\Imports\TricycleMayorsPermitsImport;
use App\Exports\TricycleMayorsPermitsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TricycleMayorsPermitController extends Controller
{
    public function index(Request $request)
    {
        $permits = TricycleMayorsPermit::query()
            ->with('tricycle')
            ->when($request->filled('control_no'), fn ($q) =>
                $q->where('control_no', 'like', '%' . $request->input('control_no') . '%'))
            ->when($request->filled('tricycle'), fn ($q) =>
                $q->whereHas('tricycle', fn ($tq) =>
                    $tq->where('body_number', 'like', '%' . $request->input('tricycle') . '%')
                       ->orWhere('name', 'like', '%' . $request->input('tricycle') . '%')))
            ->when($request->filled('business_name'), fn ($q) =>
                $q->where('business_name', 'like', '%' . $request->input('business_name') . '%'))
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->input('status')))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        $tricycles = Tricycle::orderBy('body_number')->get();

        if ($request->ajax()) {
            return view('admin.partials.tricycle-mayors-permit-ajax-results', compact('permits', 'tricycles'));
        }

        return view('admin.tricycle-mayors-permit', compact('permits', 'tricycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'control_no' => 'required|string|max:255|unique:tricycle_mayors_permits,control_no',
            'status' => 'required|in:active,expired',
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

        TricycleMayorsPermit::create($validated);

        return redirect()->route('tricycle.mayors-permit')->with('success', 'Permit added successfully.');
    }

    public function update(Request $request, TricycleMayorsPermit $permit)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'control_no' => 'required|string|max:255|unique:tricycle_mayors_permits,control_no,' . $permit->id,
            'status' => 'required|in:active,expired',
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

        return redirect()->route('tricycle.mayors-permit')->with('success', 'Permit updated successfully.');
    }

    public function destroy(TricycleMayorsPermit $permit)
    {
        $permit->delete();

        return redirect()->route('tricycle.mayors-permit')->with('success', 'Permit removed successfully.');
    }

    public function print(TricycleMayorsPermit $permit)
    {
        $permit->load('tricycle');

        return view('admin.tricycle-mayors-permit-print', compact('permit'));
    }

    public function export()
    {
        return Excel::download(new TricycleMayorsPermitsExport, 'tricycle-mayors-permits-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new TricycleMayorsPermitsImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->getRowFailures();

        if (!empty($failures)) {
            $messages = array_map(
                fn ($f) => 'Row ' . $f['row'] . ': ' . implode(', ', $f['errors']),
                $failures
            );

            return back()->withErrors($messages);
        }

        return redirect()->route('tricycle.mayors-permit')->with('success', 'Permits imported successfully.');
    }
}