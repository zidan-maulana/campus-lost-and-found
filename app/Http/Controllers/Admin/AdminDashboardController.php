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
            'stats' => [
                'users' => User::count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'reports_open' => Report::where('status', 'open')->count(),
                'reports_hidden' => Report::where('status', 'hidden')->count(),
                'claims_active' => Claim::whereIn('status', ['submitted', 'in_handover'])->count(),
                'abuse_pending' => $abuseReports->count(),
                'reports_found' => Report::where('type', 'found')->where('status', 'open')->count(),
                'reports_lost' => Report::where('type', 'lost')->where('status', 'open')->count(),
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
