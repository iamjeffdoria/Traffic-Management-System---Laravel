<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Potpot;
use App\Models\Tricycle;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Returns at most 20 tricycles matching the typed text.
    public function tricycles(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $tricycles = Tricycle::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('body_number', 'like', '%' . $q . '%')
                  ->orWhere('plate_no', 'like', '%' . $q . '%')
                  ->orWhere('name', 'like', '%' . $q . '%');
            }))
            ->orderBy('body_number')
            ->limit(20)
            ->get();

        return response()->json($tricycles->map(fn ($t) => [
            'id' => $t->id,
            'label' => $t->body_number . ' — ' . $t->plate_no . ' (' . $t->name . ')',
            'data' => [
                'name' => $t->name,
                'address' => $t->address,
                'make' => $t->make_kind,
                'motor' => $t->engine_motor_no,
                'chassis' => $t->chassis_no,
                'plate' => $t->plate_no,
            ],
        ])->values());
    }

    // Returns at most 20 potpots matching the typed text.
    public function potpots(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $potpots = Potpot::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('body_number', 'like', '%' . $q . '%')
                  ->orWhere('name', 'like', '%' . $q . '%')
                  ->orWhere('driver', 'like', '%' . $q . '%');
            }))
            ->orderBy('body_number')
            ->limit(20)
            ->get();

        return response()->json($potpots->map(fn ($p) => [
            'id' => $p->id,
            'label' => $p->body_number . ' — ' . ($p->driver ?: $p->name),
            'data' => [
                'body' => $p->body_number,
                'name' => $p->name,
                'driver' => $p->driver,
                'address' => $p->address,
            ],
        ])->values());
    }
}