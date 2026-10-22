<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\IdCard;
use App\Models\KasabutanLostItem;
use App\Models\KasabutanSelling;
use App\Models\Mtop;
use App\Models\Potpot;
use App\Models\PotpotDocumentSubmission;
use App\Models\PotpotMayorsPermit;
use App\Models\Tanod;
use App\Models\TanodId;
use App\Models\Tricycle;
use App\Models\TricycleDocumentSubmission;
use App\Models\TricycleMayorsPermit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isSuperadmin = $user->isSuperadmin();

        $availableModules = $this->availableModules($user);
        abort_if(empty($availableModules), 403, 'Your account has no dashboard modules assigned.');

        // Only the superadmin may switch modules; everyone else is locked to theirs.
        $requested = $request->query('module');
        if ($isSuperadmin) {
            $selectedModule = (is_string($requested) && isset($availableModules[$requested])) ? $requested : 'all';
        } else {
            $selectedModule = array_key_first($availableModules);
        }

        $modules = $selectedModule === 'all' ? array_keys($availableModules) : [$selectedModule];
        $viewLabel = $selectedModule === 'all' ? 'All Modules' : $availableModules[$selectedModule];

        $today = now()->startOfDay();
        $until = now()->addDays(30)->endOfDay();

        $moduleStats = [];
        $sidePanels = [];
        $quickActions = [];
        $expiringSoon = 0;
        $totalRecords = 0;
        $renewals = collect();
        $activity = collect();

        foreach ($modules as $module) {
            $data = match ($module) {
                'potpot' => $this->potpotData($today, $until),
                'tricycle' => $this->tricycleData($today, $until),
                'tanod' => $this->tanodData(),
            };

            $moduleStats[$module] = ['label' => $availableModules[$module], 'cards' => $data['cards']];
            $sidePanels = array_merge($sidePanels, $data['panels']);
            $quickActions = array_merge($quickActions, $data['actions']);
            $expiringSoon += $data['expiring'];
            $totalRecords += $data['total'];
            $renewals = $renewals->merge($data['renewals']);
            $activity = $activity->merge($data['activity']);
        }

        $upcomingRenewals = $renewals->sortBy('date')->take(6)->values();
        $recentActivity = $activity->sortByDesc('time')->take(6)->values();

        // Tanod has no expiry dates, so the alert/renewals only apply to potpot and tricycle.
        $tracksExpiry = collect($modules)->contains(fn ($m) => $m !== 'tanod');
        $expiringHref = route('admin.expiring', array_filter([
            'module' => $selectedModule !== 'all' ? $selectedModule : null,
            'days' => 30,
        ]));

        $monthlyRegistrations = collect(range(0, 7))->map(function ($i) use ($modules) {
            $month = now()->startOfMonth()->subMonths(7 - $i);
            $count = 0;

            foreach ($modules as $module) {
                $count += $this->registrationsFor($module, $month);
            }

            return ['label' => $month->format('M'), 'count' => $count];
        });
        $maxMonthly = max($monthlyRegistrations->max('count'), 1);

        $admins = $isSuperadmin ? User::orderBy('name')->get() : collect();

        return view('admin.dashboard', compact(
            'isSuperadmin',
            'availableModules',
            'selectedModule',
            'modules',
            'viewLabel',
            'moduleStats',
            'sidePanels',
            'quickActions',
            'expiringSoon',
            'expiringHref',
            'tracksExpiry',
            'totalRecords',
            'upcomingRenewals',
            'recentActivity',
            'monthlyRegistrations',
            'maxMonthly',
            'admins'
        ));
    }

    protected function availableModules(User $user): array
    {
        return match ($user->role) {
            'superadmin' => ['potpot' => 'Potpot', 'tricycle' => 'Tricycle', 'tanod' => 'Tanod'],
            'potpot_admin' => ['potpot' => 'Potpot'],
            'tricycle_admin' => ['tricycle' => 'Tricycle'],
            'tanod_admin' => ['tanod' => 'Tanod'],
            default => [],
        };
    }

    protected function potpotData(Carbon $today, Carbon $until): array
    {
        $range = [$today, $until];

        $expiring = Potpot::whereBetween('date_expired', $range)->count()
            + PotpotMayorsPermit::whereBetween('expiry_date', $range)->count()
            + IdCard::whereBetween('expiry_date', $range)->count();

        $potpots = Potpot::count();
        $permits = PotpotMayorsPermit::count();
        $idCards = IdCard::count();
        $kasabutan = KasabutanSelling::count() + KasabutanLostItem::count();

        $renewals = collect()
            ->merge(Potpot::whereBetween('date_expired', $range)->orderBy('date_expired')->limit(6)->get()->map(fn ($p) => [
                'label' => 'Potpot ' . $p->body_number,
                'sub' => $p->name,
                'date' => $p->date_expired,
                'tag' => 'Potpot',
            ]))
            ->merge(PotpotMayorsPermit::whereBetween('expiry_date', $range)->orderBy('expiry_date')->limit(6)->get()->map(fn ($p) => [
                'label' => 'Permit ' . $p->control_no,
                'sub' => $p->name,
                'date' => $p->expiry_date,
                'tag' => 'Potpot',
            ]))
            ->merge(IdCard::whereBetween('expiry_date', $range)->orderBy('expiry_date')->limit(6)->get()->map(fn ($c) => [
                'label' => 'ID Card ' . $c->id_number,
                'sub' => $c->full_name,
                'date' => $c->expiry_date,
                'tag' => 'Potpot',
            ]));

        $activity = collect()
            ->merge(Potpot::latest('updated_at')->take(3)->get()->map(fn ($p) => [
                'text' => "Potpot {$p->body_number} updated",
                'time' => $p->updated_at,
                'tag' => 'Potpot',
            ]))
            ->merge(PotpotMayorsPermit::latest('updated_at')->take(3)->get()->map(fn ($p) => [
                'text' => "Mayor's Permit {$p->control_no} updated",
                'time' => $p->updated_at,
                'tag' => 'Potpot',
            ]))
            ->merge(IdCard::latest('updated_at')->take(3)->get()->map(fn ($c) => [
                'text' => "ID card {$c->id_number} updated",
                'time' => $c->updated_at,
                'tag' => 'Potpot',
            ]))
            ->merge(PotpotDocumentSubmission::latest()->take(3)->get()->map(fn ($d) => [
                'text' => "Documents submitted by {$d->driver_name}",
                'time' => $d->created_at,
                'tag' => 'Potpot',
            ]));

        $statusCounts = Potpot::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'cards' => [
                $this->card('Registered Potpots', $potpots, 'vehicle', 'blue', route('potpot.list')),
                $this->card('Active Permits', PotpotMayorsPermit::whereIn('status', ['active', 'renewed'])->count(), 'document', 'purple', route('potpot.mayors-permit')),
                $this->card('ID Cards Issued', $idCards, 'idcard', 'teal', route('potpot.id-cards')),
                $this->card('Kasabutan Records', $kasabutan, 'tag', 'indigo', route('potpot.kasabutan.selling'), 'Selling + Lost Item'),
                $this->card('Pending Documents', PotpotDocumentSubmission::where('status', 'pending')->count(), 'upload', 'cyan', route('potpot.document-submissions'), 'Awaiting review'),
                $this->card('Expiring Soon', $expiring, 'clock', 'amber', route('admin.expiring', ['module' => 'potpot']), 'Within next 30 days'),
            ],
            'panels' => [
                $this->statusPanel('Potpot Status', $statusCounts),
            ],
            'actions' => [
                $this->action('Potpot List', 'Registered potpot records', route('potpot.list'), 'list', 'blue'),
                $this->action('ID Cards', 'Manage Potpot ID cards', route('potpot.id-cards'), 'idcard', 'red'),
                $this->action("Mayor's Permit – Potpot", 'Issue and track permits', route('potpot.mayors-permit'), 'document', 'purple'),
                $this->action('Kasabutan – Selling', 'Sale agreements', route('potpot.kasabutan.selling'), 'tag', 'indigo'),
                $this->action('Kasabutan – Lost Item', 'Lost item agreements', route('potpot.kasabutan.lost-item'), 'search', 'amber'),
                $this->action('Potpot Documents', 'Review driver submissions', route('potpot.document-submissions'), 'upload', 'cyan'),
            ],
            'expiring' => $expiring,
            'total' => $potpots + $permits + $idCards + $kasabutan,
            'renewals' => $renewals,
            'activity' => $activity,
        ];
    }

    protected function tricycleData(Carbon $today, Carbon $until): array
    {
        $range = [$today, $until];

        $expiring = Tricycle::whereBetween('date_expired', $range)->count()
            + TricycleMayorsPermit::whereBetween('expiry_date', $range)->count()
            + Franchise::whereBetween('valid_until', $range)->count();

        $tricycles = Tricycle::count();
        $permits = TricycleMayorsPermit::count();
        $mtops = Mtop::count();
        $franchises = Franchise::count();

        $renewals = collect()
            ->merge(Tricycle::whereBetween('date_expired', $range)->orderBy('date_expired')->limit(6)->get()->map(fn ($t) => [
                'label' => $t->body_number . ' — ' . $t->plate_no,
                'sub' => $t->name,
                'date' => $t->date_expired,
                'tag' => 'Tricycle',
            ]))
            ->merge(TricycleMayorsPermit::with('tricycle')->whereBetween('expiry_date', $range)->orderBy('expiry_date')->limit(6)->get()->map(fn ($p) => [
                'label' => 'Permit ' . $p->control_no,
                'sub' => $p->tricycle->name ?? '—',
                'date' => $p->expiry_date,
                'tag' => 'Tricycle',
            ]))
            ->merge(Franchise::with('tricycle')->whereBetween('valid_until', $range)->orderBy('valid_until')->limit(6)->get()->map(fn ($f) => [
                'label' => 'Franchise ' . $f->authorized_no,
                'sub' => $f->tricycle->name ?? '—',
                'date' => $f->valid_until,
                'tag' => 'Tricycle',
            ]));

        $activity = collect()
            ->merge(Tricycle::latest('updated_at')->take(3)->get()->map(fn ($t) => [
                'text' => "Tricycle {$t->body_number} updated",
                'time' => $t->updated_at,
                'tag' => 'Tricycle',
            ]))
            ->merge(Franchise::latest('updated_at')->take(3)->get()->map(fn ($f) => [
                'text' => "Franchise {$f->authorized_no} updated",
                'time' => $f->updated_at,
                'tag' => 'Tricycle',
            ]))
            ->merge(TricycleMayorsPermit::latest('updated_at')->take(3)->get()->map(fn ($p) => [
                'text' => "Mayor's Permit {$p->control_no} updated",
                'time' => $p->updated_at,
                'tag' => 'Tricycle',
            ]))
            ->merge(Mtop::latest('updated_at')->take(3)->get()->map(fn ($m) => [
                'text' => "MTOP {$m->case_no} updated",
                'time' => $m->updated_at,
                'tag' => 'Tricycle',
            ]))
            ->merge(TricycleDocumentSubmission::latest()->take(3)->get()->map(fn ($d) => [
                'text' => "Documents submitted by {$d->driver_name}",
                'time' => $d->created_at,
                'tag' => 'Tricycle',
            ]));

        $statusCounts = Tricycle::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $todaCounts = Tricycle::whereNotNull('toda')
            ->selectRaw('toda, count(*) as total')
            ->groupBy('toda')
            ->pluck('total', 'toda');

        $todaRows = collect(Tricycle::TODA_OPTIONS)
            ->map(fn ($label, $value) => ['label' => $label, 'count' => (int) ($todaCounts[$value] ?? 0)])
            ->values()
            ->all();

        return [
            'cards' => [
                $this->card('Registered Tricycles', $tricycles, 'vehicle', 'blue', route('tricycle.list')),
                $this->card('Active Permits', TricycleMayorsPermit::whereIn('status', ['active', 'renewed'])->count(), 'document', 'purple', route('tricycle.mayors-permit')),
                $this->card('MTOP Records', $mtops, 'clipboard', 'amber', route('tricycle.mtop')),
                $this->card('Active Franchises', Franchise::whereIn('status', ['New', 'Renewed'])->count(), 'franchise', 'cyan', route('tricycle.franchise'), 'New + Renewed'),
                $this->card('Pending Documents', TricycleDocumentSubmission::where('status', 'pending')->count(), 'upload', 'teal', route('tricycle.document-submissions'), 'Awaiting review'),
                $this->card('Expiring Soon', $expiring, 'clock', 'red', route('admin.expiring', ['module' => 'tricycle']), 'Within next 30 days'),
            ],
            'panels' => [
                $this->statusPanel('Tricycle Status', $statusCounts),
                ['type' => 'list', 'title' => 'Tricycles by TODA', 'rows' => $todaRows],
            ],
            'actions' => [
                $this->action('Tricycle List', 'Registered tricycle records', route('tricycle.list'), 'vehicle', 'emerald'),
                $this->action("Mayor's Permit – Tricycle", 'Issue and track permits', route('tricycle.mayors-permit'), 'document', 'purple'),
                $this->action('MTOP', 'Route operation records', route('tricycle.mtop'), 'clipboard', 'amber'),
                $this->action('Franchise', 'Franchise authorization records', route('tricycle.franchise'), 'franchise', 'cyan'),
                $this->action('Tricycle Documents', 'Review driver submissions', route('tricycle.document-submissions'), 'upload', 'teal'),
            ],
            'expiring' => $expiring,
            'total' => $tricycles + $permits + $mtops + $franchises,
            'renewals' => $renewals,
            'activity' => $activity,
        ];
    }

    protected function tanodData(): array
    {
        $tanods = Tanod::count();
        $tanodIds = TanodId::count();

        $withoutId = Tanod::whereNotIn('id_number', TanodId::select('id_number'))->count();

        $barangayRows = Tanod::selectRaw('barangay, count(*) as total')
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'barangay')
            ->map(fn ($total, $barangay) => ['label' => $barangay, 'count' => (int) $total])
            ->values()
            ->all();

        $activity = collect()
            ->merge(Tanod::latest('updated_at')->take(3)->get()->map(fn ($t) => [
                'text' => "Tanod {$t->full_name} updated",
                'time' => $t->updated_at,
                'tag' => 'Tanod',
            ]))
            ->merge(TanodId::latest('updated_at')->take(3)->get()->map(fn ($t) => [
                'text' => "Tanod ID {$t->id_number} updated",
                'time' => $t->updated_at,
                'tag' => 'Tanod',
            ]));

        return [
            'cards' => [
                $this->card('Total Tanods', $tanods, 'shield', 'blue', route('tanod.list')),
                $this->card('Tanod IDs', $tanodIds, 'idcard', 'teal', route('tanod.id')),
                $this->card('Barangays Covered', Tanod::distinct()->count('barangay'), 'list', 'purple', route('tanod.list')),
                $this->card('Tanods Without ID', $withoutId, 'users', 'amber', route('tanod.id'), 'No ID record yet'),
            ],
            'panels' => [
                ['type' => 'list', 'title' => 'Tanods by Barangay', 'rows' => $barangayRows],
            ],
            'actions' => [
                $this->action('Tanod List', 'Registered tanod records', route('tanod.list'), 'shield', 'blue'),
                $this->action('Tanod ID', 'Create and print IDs', route('tanod.id'), 'idcard', 'teal'),
            ],
            'expiring' => 0,
            'total' => $tanods + $tanodIds,
            'renewals' => collect(),
            'activity' => $activity,
        ];
    }

    protected function registrationsFor(string $module, Carbon $month): int
    {
        return match ($module) {
            'tricycle' => Tricycle::whereYear('date_registered', $month->year)->whereMonth('date_registered', $month->month)->count(),
            'potpot' => Potpot::whereYear('date_registered', $month->year)->whereMonth('date_registered', $month->month)->count(),
            'tanod' => Tanod::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            default => 0,
        };
    }

    protected function card(string $label, int $value, string $icon, string $tone, string $href, ?string $sub = null): array
    {
        return compact('label', 'value', 'icon', 'tone', 'href', 'sub');
    }

    protected function action(string $label, string $sub, string $href, string $icon, string $tone): array
    {
        return compact('label', 'sub', 'href', 'icon', 'tone');
    }

    protected function statusPanel(string $title, Collection $counts): array
    {
        $total = max((int) $counts->sum(), 1);
        $rows = [];

        foreach (['active' => 'emerald', 'renewed' => 'blue', 'expired' => 'red'] as $status => $tone) {
            $count = (int) ($counts[$status] ?? 0);
            $rows[] = [
                'label' => ucfirst($status),
                'count' => $count,
                'percent' => (int) round(($count / $total) * 100),
                'tone' => $tone,
            ];
        }

        return ['type' => 'status', 'title' => $title, 'rows' => $rows];
    }
}