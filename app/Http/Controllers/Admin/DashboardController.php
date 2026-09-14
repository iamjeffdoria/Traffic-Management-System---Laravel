<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\IdCard;
use App\Models\Mtop;
use App\Models\PotpotMayorsPermit;
use App\Models\Tricycle;
use App\Models\TricycleMayorsPermit;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $in30Days = $now->copy()->addDays(30);

        $totalTricycles = Tricycle::count();
        $activePermits = TricycleMayorsPermit::where('status', 'active')->count()
            + PotpotMayorsPermit::where('status', 'active')->count();
        $idCardsIssued = IdCard::count();

        $expiringSoon = Tricycle::whereBetween('date_expired', [$now, $in30Days])->count()
            + TricycleMayorsPermit::whereBetween('expiry_date', [$now, $in30Days])->count()
            + PotpotMayorsPermit::whereBetween('expiry_date', [$now, $in30Days])->count()
            + Franchise::whereBetween('valid_until', [$now, $in30Days])->count()
            + IdCard::whereBetween('expiry_date', [$now, $in30Days])->count();

        $totalRecords = $totalTricycles
            + TricycleMayorsPermit::count()
            + PotpotMayorsPermit::count()
            + Mtop::count()
            + Franchise::count()
            + $idCardsIssued;

        $statusCounts = Tricycle::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $statusTotal = max($statusCounts->sum(), 1);
        $statusBreakdown = [
            'active' => round((($statusCounts['active'] ?? 0) / $statusTotal) * 100),
            'renewed' => round((($statusCounts['renewed'] ?? 0) / $statusTotal) * 100),
            'expired' => round((($statusCounts['expired'] ?? 0) / $statusTotal) * 100),
        ];

        $todaCounts = Tricycle::whereNotNull('toda')
            ->selectRaw('toda, count(*) as total')
            ->groupBy('toda')
            ->pluck('total', 'toda');

        $upcomingRenewals = collect()
            ->merge(Tricycle::whereBetween('date_expired', [$now, $in30Days])->get()->map(fn ($t) => [
                'label' => $t->body_number . ' — ' . $t->plate_no,
                'sub' => $t->name,
                'date' => $t->date_expired,
            ]))
            ->merge(TricycleMayorsPermit::with('tricycle')->whereBetween('expiry_date', [$now, $in30Days])->get()->map(fn ($p) => [
                'label' => 'Permit ' . $p->control_no,
                'sub' => $p->tricycle->name ?? '—',
                'date' => $p->expiry_date,
            ]))
            ->merge(Franchise::with('tricycle')->whereBetween('valid_until', [$now, $in30Days])->get()->map(fn ($f) => [
                'label' => 'Franchise ' . $f->authorized_no,
                'sub' => $f->municipal_treasurer,
                'date' => $f->valid_until,
            ]))
            ->sortBy('date')
            ->take(4)
            ->values();

        $recentActivity = collect()
            ->merge(Tricycle::latest('updated_at')->take(3)->get()->map(fn ($t) => [
                'text' => "Tricycle {$t->body_number} updated",
                'time' => $t->updated_at,
            ]))
            ->merge(Franchise::latest('updated_at')->take(3)->get()->map(fn ($f) => [
                'text' => "Franchise {$f->authorized_no} updated",
                'time' => $f->updated_at,
            ]))
            ->merge(TricycleMayorsPermit::latest('updated_at')->take(3)->get()->map(fn ($p) => [
                'text' => "Mayor's Permit {$p->control_no} updated",
                'time' => $p->updated_at,
            ]))
            ->merge(IdCard::latest('updated_at')->take(3)->get()->map(fn ($c) => [
                'text' => "ID card {$c->id_number} updated",
                'time' => $c->updated_at,
            ]))
            ->sortByDesc('time')
            ->take(4)
            ->values();

        $monthlyRegistrations = collect(range(0, 7))->map(function ($i) use ($now) {
            $month = $now->copy()->subMonths(7 - $i);
            $count = Tricycle::whereYear('date_registered', $month->year)
                ->whereMonth('date_registered', $month->month)
                ->count();

            return ['label' => $month->format('M'), 'count' => $count];
        });
        $maxMonthly = max($monthlyRegistrations->max('count'), 1);

        $admins = User::orderBy('name')->get();

        return view('admin.dashboard', compact(
            'totalTricycles',
            'activePermits',
            'idCardsIssued',
            'expiringSoon',
            'totalRecords',
            'statusBreakdown',
            'todaCounts',
            'upcomingRenewals',
            'recentActivity',
            'monthlyRegistrations',
            'maxMonthly',
            'admins'
        ));
    }
}