<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\IdCard;
use App\Models\Potpot;
use App\Models\PotpotMayorsPermit;
use App\Models\Tricycle;
use App\Models\TricycleMayorsPermit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExpiringController extends Controller
{
    private const WINDOWS = [7, 14, 30, 60, 90];

    public function index(Request $request)
    {
        $user = $request->user();
        $modules = $this->availableModules($user);

        if (empty($modules)) {
            return redirect()->route('admin.dashboard');
        }

        // Only the superadmin can switch modules; everyone else is locked to theirs.
        $module = $request->query('module');
        if (!$user->isSuperadmin()) {
            $module = array_key_first($modules);
        } elseif (!is_string($module) || !isset($modules[$module])) {
            $module = 'all';
        }

        $showExpired = $request->query('days') === 'expired';
        $days = (int) $request->query('days', 30);
        if (!in_array($days, self::WINDOWS, true)) {
            $days = 30;
        }

        $today = now()->startOfDay();
        $until = now()->addDays($days)->endOfDay();

        $active = $module === 'all' ? array_keys($modules) : [$module];

        $rows = collect();
        foreach ($active as $m) {
            $rows = $rows->merge($m === 'potpot'
                ? $this->potpotRows($showExpired, $today, $until)
                : $this->tricycleRows($showExpired, $today, $until));
        }

        // Soonest first; for "already expired", most recently expired first.
        $rows = $showExpired
            ? $rows->sortByDesc(fn ($r) => $r['date']->timestamp)
            : $rows->sortBy(fn ($r) => $r['date']->timestamp);

        $typeCounts = $rows->countBy('type');

        $type = $request->query('type');
        if (is_string($type) && $type !== '' && $typeCounts->has($type)) {
            $rows = $rows->where('type', $type);
        } else {
            $type = null;
        }

        $rows = $rows->values();
        $perPage = 25;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $records = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.expiring', [
            'records' => $records,
            'modules' => $modules,
            'module' => $module,
            'isSuperadmin' => $user->isSuperadmin(),
            'days' => $days,
            'windows' => self::WINDOWS,
            'showExpired' => $showExpired,
            'type' => $type,
            'typeCounts' => $typeCounts,
            'total' => $typeCounts->sum(),
        ]);
    }

    protected function availableModules(User $user): array
    {
        // Tanod has no expiry dates, so it isn't included here.
        return match ($user->role) {
            'superadmin' => ['potpot' => 'Potpot', 'tricycle' => 'Tricycle'],
            'potpot_admin' => ['potpot' => 'Potpot'],
            'tricycle_admin' => ['tricycle' => 'Tricycle'],
            default => [],
        };
    }

    /** Applies either "already expired" or "expiring within the window" to a query. */
    protected function scope($query, string $column, bool $showExpired, $today, $until)
    {
        return $showExpired
            ? $query->whereNotNull($column)->where($column, '<', $today)
            : $query->whereBetween($column, [$today, $until]);
    }

    protected function row(string $type, string $label, ?string $sub, $date, ?string $status, string $href, $today): array
    {
        return [
            'type' => $type,
            'label' => $label,
            'sub' => $sub,
            'date' => $date,
            'days' => (int) $today->diffInDays($date->copy()->startOfDay(), false),
            'status' => $status,
            'href' => $href,
        ];
    }

    protected function potpotRows(bool $showExpired, $today, $until): Collection
    {
        $rows = collect();

        $this->scope(Potpot::query(), 'date_expired', $showExpired, $today, $until)->get()
            ->each(fn ($p) => $rows->push($this->row(
                'Potpot', 'Body ' . $p->body_number, $p->name, $p->date_expired, $p->status,
                route('potpot.list', ['body' => $p->body_number]), $today
            )));

        $this->scope(PotpotMayorsPermit::query(), 'expiry_date', $showExpired, $today, $until)->get()
            ->each(fn ($p) => $rows->push($this->row(
                "Mayor's Permit", 'Permit ' . $p->control_no, $p->name, $p->expiry_date, $p->status,
                route('potpot.mayors-permit', ['control_no' => $p->control_no]), $today
            )));

        $this->scope(IdCard::query(), 'expiry_date', $showExpired, $today, $until)->get()
            ->each(fn ($c) => $rows->push($this->row(
                'ID Card', 'ID ' . $c->id_number, $c->full_name, $c->expiry_date, null,
                route('potpot.id-cards', ['id_number' => $c->id_number]), $today
            )));

        return $rows->map(fn ($r) => $r + ['module' => 'Potpot']);
    }

    protected function tricycleRows(bool $showExpired, $today, $until): Collection
    {
        $rows = collect();

        $this->scope(Tricycle::query(), 'date_expired', $showExpired, $today, $until)->get()
            ->each(fn ($t) => $rows->push($this->row(
                'Tricycle', $t->body_number . ' — ' . $t->plate_no, $t->name, $t->date_expired, $t->status,
                route('tricycle.list', ['plate' => $t->plate_no]), $today
            )));

        $this->scope(TricycleMayorsPermit::with('tricycle'), 'expiry_date', $showExpired, $today, $until)->get()
            ->each(fn ($p) => $rows->push($this->row(
                "Mayor's Permit", 'Permit ' . $p->control_no, $p->tricycle->name ?? '—', $p->expiry_date, $p->status,
                route('tricycle.mayors-permit', ['control_no' => $p->control_no]), $today
            )));

        $this->scope(Franchise::with('tricycle'), 'valid_until', $showExpired, $today, $until)->get()
            ->each(fn ($f) => $rows->push($this->row(
                'Franchise', 'Franchise ' . $f->authorized_no, $f->tricycle->name ?? '—', $f->valid_until, $f->status,
                route('tricycle.franchise', ['authorized_no' => $f->authorized_no]), $today
            )));

        return $rows->map(fn ($r) => $r + ['module' => 'Tricycle']);
    }
}