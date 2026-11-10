<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AbuseReportStatus;
use App\Http\Controllers\Controller;
use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\ModerationAction;
use App\Models\Report;
use App\Models\User;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Dashboard administrator (PR-08, FR-12, UI_UX 5.6).
 *
 * Administrator tidak menentukan kepemilikan barang (BR-06); hanya menangani
 * laporan penyalahgunaan, penyembunyian konten, dan status akun.
 */
class AdminDashboardController extends Controller
{
    public function __construct(private readonly ModerationService $moderation)
    {
    }

    public function index(Request $request): View
    {
        $abuseReports = AbuseReport::query()
            ->with(['reporter', 'reviewer'])
            ->pending()
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.dashboard', [
            'viewer' => $request->user(),
            'abuseReports' => $abuseReports,
            'adminData' => $this->dashboardPayload($abuseReports),
            'overview' => $this->overviewPayload(),
            'stats' => [
                'users' => User::count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'reports_total' => Report::count(),
                'reports_open' => Report::where('status', 'open')->count(),
                'reports_hidden' => Report::where('status', 'hidden')->count(),
                'claims_active' => Claim::whereIn('status', ['submitted', 'in_handover'])->count(),
                'abuse_pending' => $abuseReports->count(),
                'reports_found' => Report::where('type', 'found')->count(),
                'reports_lost' => Report::where('type', 'lost')->count(),
                'reports_found_open' => Report::where('type', 'found')->where('status', 'open')->count(),
                'reports_found_handover' => Report::where('type', 'found')->where('status', 'in_handover')->count(),
                'reports_lost_open' => Report::where('type', 'lost')->where('status', 'open')->count(),
                'reports_resolved' => Report::where('status', 'resolved')->count(),
            ],
            'recentActions' => ModerationAction::query()
                ->with(['admin', 'targetUser'])
                ->latest('created_at')
                ->limit(10)
                ->get(),
            'hiddenReports' => Report::query()
                ->where('status', 'hidden')
                ->with('user')
                ->latest()
                ->limit(20)
                ->get(),
            'suspendedUsers' => User::query()
                ->where('status', 'suspended')
                ->latest()
                ->limit(20)
                ->get(),
        ]);
    }

    public function showReport(Request $request, Report $report): View
    {
        $report->load(['user', 'category', 'claims.claimant']);

        return view('admin.report', [
            'viewer' => $request->user(),
            'report' => $report,
        ]);
    }

    /**
     * Data dashboard dalam bentuk yang dipakai skrip front-end
     * (resources/views/admin/dashboard.blade.php) — menggantikan data contoh
     * yang sebelumnya ditulis langsung di dalam view.
     *
     * @param  \Illuminate\Support\Collection<int, AbuseReport>  $abuseReports
     * @return array<string, mixed>
     */
    private function dashboardPayload($abuseReports): array
    {
        $reasonLabels = [
            'fake_report' => 'Laporan Palsu / Spam',
            'inappropriate_chat' => 'Pelecehan / Kata Kasar',
            'fraud_claim' => 'Klaim Fiktif Berulang',
            'other' => 'Pelanggaran Lain',
        ];

        return [
            'abuseReports' => $abuseReports->map(fn (AbuseReport $a) => [
                'id' => 'ABUSE-'.str_pad((string) $a->id, 4, '0', STR_PAD_LEFT),
                'raw_id' => (int) $a->id,
                'reporter_name' => $a->reporter?->name ?? 'Civitas UMB',
                'reporter_nim' => $a->reporter?->nim ?? '-',
                'target_type' => (string) $a->target_type,
                'target_id' => (string) $a->target_id,
                'target_title' => $this->abuseTargetTitle($a),
                'reason_code' => (string) $a->reason,
                'reason_label' => $reasonLabels[(string) $a->reason] ?? ucfirst(str_replace('_', ' ', (string) $a->reason)),
                'severity' => in_array((string) $a->reason, ['fake_report', 'fraud_claim'], true) ? 'high' : 'medium',
                'description' => (string) ($a->details ?? ''),
                'date' => $a->created_at?->translatedFormat('d M Y, H:i').' WIB',
                'status' => $a->status instanceof \BackedEnum ? $a->status->value : (string) $a->status,
            ])->values()->all(),

            'users' => User::query()
                ->latest()
                ->limit(200)
                ->get()
                ->map(fn (User $u) => [
                    'id' => (string) $u->id,
                    'name' => $u->name,
                    'nim' => (string) ($u->nim ?? '-'),
                    'faculty' => '-',
                    'prodi' => '-',
                    'email' => $u->email,
                    'role' => $u->role instanceof \BackedEnum ? $u->role->value : (string) $u->role,
                    'status' => $u->status instanceof \BackedEnum ? $u->status->value : (string) $u->status,
                ])->values()->all(),

            'auditLogs' => ModerationAction::query()
                ->with(['admin', 'targetUser'])
                ->latest('created_at')
                ->limit(100)
                ->get()
                ->map(fn (ModerationAction $m) => [
                    'id' => 'AUD-'.str_pad((string) $m->id, 3, '0', STR_PAD_LEFT),
                    'timestamp' => $m->created_at?->translatedFormat('d M Y, H:i').' WIB',
                    'admin_name' => $m->admin?->name ?? 'Administrator',
                    'admin_email' => $m->admin?->email ?? '-',
                    'action_type' => $m->action_type instanceof \BackedEnum ? $m->action_type->value : (string) $m->action_type,
                    'action_label' => ucfirst(str_replace('_', ' ', (string) ($m->action_type instanceof \BackedEnum ? $m->action_type->value : $m->action_type))),
                    'target' => $m->targetUser?->name ?? ('#'.$m->target_id),
                    'reason' => (string) ($m->reason ?? '-'),
                    'impact' => $m->action_type instanceof \BackedEnum && $m->action_type->value === 'suspend_user'
                        ? 'Akun status suspended (DEC-026).'
                        : 'Perubahan tersimpan di server.',
                ])->values()->all(),

            'categories' => \App\Models\ItemCategory::query()
                ->withCount('reports')
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($c) => [
                    'id' => (string) $c->id,
                    'name' => $c->name,
                    'icon' => (string) ($c->icon ?? 'package'),
                    'count' => (int) $c->reports_count,
                    'desc' => (string) ($c->description ?? ''),
                ])->values()->all(),

            'reports' => Report::query()
                ->with(['user', 'category'])
                ->latest()
                ->limit(300)
                ->get()
                ->map(fn (Report $r) => [
                    'id' => (string) $r->id,
                    'title' => $r->title,
                    'type' => $r->type instanceof \BackedEnum ? $r->type->value : (string) $r->type,
                    'reporter' => trim(($r->user?->name ?? 'Civitas UMB').' ('.($r->user?->nim ?? '-').')'),
                    'location' => trim((string) $r->campus_location.' • '.(string) $r->location, ' •'),
                    'date' => $r->incident_date?->translatedFormat('d M Y') ?? '-',
                    'status' => $r->status instanceof \BackedEnum ? $r->status->value : (string) $r->status,
                ])->values()->all(),
        ];
    }

    /**
     * Judul ringkas dari objek yang dilaporkan (laporan / pesan chat / akun).
     */
    private function abuseTargetTitle(AbuseReport $abuse): string
    {
        $type = (string) $abuse->target_type;

        if ($type === 'report') {
            $title = Report::whereKey($abuse->target_id)->value('title');
            if ($title) {
                return (string) $title;
            }
        }

        if ($type === 'chat_message') {
            $message = \App\Models\ChatMessage::whereKey($abuse->target_id)->value('body');

            return $message ? 'Pesan: '.mb_strimwidth((string) $message, 0, 60, '…') : 'Pesan chat #'.$abuse->target_id;
        }

        if ($type === 'user') {
            $user = User::find($abuse->target_id);

            return $user ? 'Akun Civitas: '.$user->name.' ('.($user->nim ?? '-').')' : 'Akun #'.$abuse->target_id;
        }

        return ucfirst($type).' #'.$abuse->target_id;
    }

    /**
     * Tambah akun administrator baru dari dashboard.
     *
     * Akun langsung aktif & terverifikasi karena dibuat oleh administrator
     * yang sudah login (tidak lewat alur OTP email kampus).
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'unit' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'email.unique' => 'Email tersebut sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => \App\Enums\UserRole::Admin,
            'status' => \App\Enums\UserStatus::Active,
            'preferences' => $validated['unit'] ? ['unit' => $validated['unit']] : null,
        ]);

        // email_verified_at tidak masuk $fillable, jadi diisi terpisah.
        $admin->forceFill(['email_verified_at' => now()])->save();

        return back()->with('status', 'Akun administrator baru berhasil dibuat.');
    }

    /**
     * Angka & deret waktu untuk tab Overview — semuanya dihitung dari database.
     *
     * @return array<string, mixed>
     */
    private function overviewPayload(): array
    {
        $totalReports = Report::count();
        $resolved = Report::where('status', 'resolved')->count();

        // ---------------------------------------------------------- funnel status
        $funnel = [
            'open' => Report::where('status', 'open')->count(),
            'in_handover' => Report::where('status', 'in_handover')->count(),
            'resolved' => $resolved,
            'hidden' => Report::where('status', 'hidden')->count(),
            'cancelled' => Report::where('status', 'cancelled')->count(),
        ];
        $funnelTotal = max(array_sum($funnel), 1);
        $pct = fn (int $n) => round($n / $funnelTotal * 100, 1);

        // ------------------------------------------------------- kategori teratas
        $topCategories = \App\Models\ItemCategory::query()
            ->withCount('reports')
            ->orderByDesc('reports_count')
            ->limit(4)
            ->get()
            ->map(fn ($c) => [
                'name' => $c->name,
                'count' => (int) $c->reports_count,
                'pct' => $totalReports > 0 ? round($c->reports_count / $totalReports * 100, 1) : 0,
            ])->values()->all();

        // --------------------------------------------------------- seri waktu chart
        $trend = [
            '7d' => $this->trendSeries(7, 1, 'd M'),
            '30d' => $this->trendSeries(30, 3, 'd M'),
            '90d' => $this->trendSeries(90, 8, 'd M'),
        ];

        // Rata-rata hari dari laporan dibuat sampai klaim selesai.
        $avgDays = Claim::query()
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at'])
            ->avg(fn (Claim $c) => $c->created_at->diffInHours($c->resolved_at) / 24);

        // Rasio aduan terhadap total laporan.
        $abuseTotal = AbuseReport::count();

        return [
            'funnel' => [
                'total' => $totalReports,
                'open' => $funnel['open'],
                'in_handover' => $funnel['in_handover'],
                'resolved' => $funnel['resolved'],
                'hidden' => $funnel['hidden'],
                'pct_open' => $pct($funnel['open']),
                'pct_handover' => $pct($funnel['in_handover']),
                'pct_resolved' => $pct($funnel['resolved']),
            ],
            'top_categories' => $topCategories,
            'trend' => $trend,
            'kpi' => [
                'avg_days' => $avgDays ? round($avgDays, 1) : null,
                'return_rate' => $totalReports > 0 ? round($resolved / $totalReports * 100, 1) : 0,
                'complaint_ratio' => $totalReports > 0 ? round($abuseTotal / $totalReports * 100, 1) : 0,
            ],
        ];
    }

    /**
     * Deret jumlah laporan temuan & hilang per rentang hari, dikelompokkan
     * menjadi beberapa titik agar chart tetap terbaca.
     *
     * @return array<string, mixed>
     */
    private function trendSeries(int $days, int $bucketDays, string $labelFormat): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $rows = Report::query()
            ->where('created_at', '>=', $start)
            ->get(['type', 'created_at']);

        $buckets = (int) ceil($days / $bucketDays);
        $labels = [];
        $temuan = [];
        $hilang = [];

        for ($i = 0; $i < $buckets; $i++) {
            $from = $start->copy()->addDays($i * $bucketDays);
            $to = $from->copy()->addDays($bucketDays);

            $labels[] = $from->translatedFormat($labelFormat);

            $inBucket = $rows->filter(fn ($r) => $r->created_at->gte($from) && $r->created_at->lt($to));

            $temuan[] = $inBucket->filter(fn ($r) => $r->type->value === 'found')->count();
            $hilang[] = $inBucket->filter(fn ($r) => $r->type->value === 'lost')->count();
        }

        $totals = array_map(fn ($t, $h) => $t + $h, $temuan, $hilang);
        $total = array_sum($totals);

        // Bandingkan dengan periode sebelumnya yang sama panjangnya.
        $prevStart = $start->copy()->subDays($days);
        $prevTotal = Report::query()
            ->where('created_at', '>=', $prevStart)
            ->where('created_at', '<', $start)
            ->count();

        $trendText = 'belum ada pembanding';
        if ($prevTotal > 0) {
            $delta = round(($total - $prevTotal) / $prevTotal * 100, 1);
            $trendText = ($delta >= 0 ? '+' : '').$delta.'% vs '.$days.' hari sebelumnya';
        } elseif ($total > 0) {
            $trendText = $total.' laporan baru';
        }

        $resolvedInRange = Report::query()
            ->where('status', 'resolved')
            ->where('created_at', '>=', $start)
            ->count();

        return [
            'labels' => $labels,
            'temuan' => $temuan,
            'hilang' => $hilang,
            'headline' => $total.' Laporan',
            'trendText' => $trendText,
            'high' => $totals ? (string) max($totals) : '0',
            'low' => $totals ? (string) min($totals) : '0',
            'rate' => $total > 0 ? round($resolvedInRange / $total * 100, 1).'%' : '0%',
            // Titik yang diberi penanda pada chart: puncak dan titik terendah.
            'keyPoints' => $totals ? array_values(array_unique([
                (int) array_search(max($totals), $totals, true),
                (int) array_search(min($totals), $totals, true),
            ])) : [],
        ];
    }

    public function hideReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->hideReport($request->user(), $report, $validated['reason']);

        return back()->with('status', 'Laporan disembunyikan dari katalog publik.');
    }

    public function unhideReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->unhideReport($request->user(), $report, $validated['reason']);

        return back()->with('status', 'Laporan ditampilkan kembali.');
    }

    public function warnUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->warn($request->user(), $user, $validated['reason']);

        return back()->with('status', 'Peringatan dikirim ke pengguna.');
    }

    public function suspendUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->suspendUser($request->user(), $user, $validated['reason']);

        return back()->with('status', 'Akun dinonaktifkan sesuai kebijakan DEC-026.');
    }

    public function reactivateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->reactivateUser($request->user(), $user, $validated['reason']);

        return back()->with('status', 'Akun diaktifkan kembali. Status laporan lama tidak berubah otomatis.');
    }

    public function resolveAbuseReport(Request $request, AbuseReport $abuseReport): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:resolved,dismissed'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->moderation->resolveAbuseReport(
            $request->user(),
            $abuseReport,
            $validated['decision'] === 'resolved' ? AbuseReportStatus::Resolved->value : AbuseReportStatus::Dismissed->value,
            $validated['reason'],
        );

        return back()->with('status', 'Laporan penyalahgunaan ditinjau.');
    }
}
