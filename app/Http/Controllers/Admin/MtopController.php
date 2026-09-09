<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mtop;
use App\Models\Tricycle;
use App\Imports\MtopsImport;
use App\Exports\MtopsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MtopController extends Controller
{
    public function index(Request $request)
    {
        $mtops = Mtop::query()
            ->with('tricycle')
            ->when($request->filled('case_no'), fn ($q) =>
                $q->where('case_no', 'like', '%' . $request->input('case_no') . '%'))
            ->when($request->filled('tricycle'), fn ($q) =>
                $q->whereHas('tricycle', fn ($tq) =>
                    $tq->where('body_number', 'like', '%' . $request->input('tricycle') . '%')
                       ->orWhere('name', 'like', '%' . $request->input('tricycle') . '%')))
            ->when($request->filled('route_operation'), fn ($q) =>
                $q->where('route_operation', 'like', '%' . $request->input('route_operation') . '%'))
            ->latest('updated_at')
            ->paginate(25)
            ->withQueryString();

        $tricycles = Tricycle::orderBy('body_number')->get();

        if ($request->ajax()) {
            return view('admin.partials.mtop-ajax-results', compact('mtops', 'tricycles'));
        }

        return view('admin.mtop', compact('mtops', 'tricycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'case_no' => 'required|string|max:255|unique:mtops,case_no',
            'no_of_units' => 'required|integer|min:1',
            'route_operation' => 'required|string|max:255',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
            'officer_in_charge' => 'required|string|max:255',
            'mayor' => 'required|string|max:255',
        ]);

        Mtop::create($validated);

        return redirect()->route('tricycle.mtop')->with('success', 'MTOP record added successfully.');
    }

    public function update(Request $request, Mtop $mtop)
    {
        $validated = $request->validate([
            'tricycle_id' => 'required|exists:tricycles,id',
            'case_no' => 'required|string|max:255|unique:mtops,case_no,' . $mtop->id,
            'no_of_units' => 'required|integer|min:1',
            'route_operation' => 'required|string|max:255',
            'date' => 'required|date',
            'municipal_treasurer' => 'required|string|max:255',
            'officer_in_charge' => 'required|string|max:255',
            'mayor' => 'required|string|max:255',
        ]);

        $mtop->update($validated);

        return redirect()->route('tricycle.mtop')->with('success', 'MTOP record updated successfully.');
    }

    public function destroy(Mtop $mtop)
    {
        $mtop->delete();

        return redirect()->route('tricycle.mtop')->with('success', 'MTOP record removed successfully.');
    }

    public function print(Mtop $mtop)
    {
        $mtop->load('tricycle');

        return view('admin.mtop-print', compact('mtop'));
    }

    public function export()
    {
        return Excel::download(new MtopsExport, 'mtops-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new MtopsImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->getRowFailures();

        if (!empty($failures)) {
            $messages = array_map(
                fn ($f) => 'Row ' . $f['row'] . ': ' . implode(', ', $f['errors']),
                $failures
            );

            return back()->withErrors($messages);
        }

        return redirect()->route('tricycle.mtop')->with('success', 'MTOP records imported successfully.');
    }
}