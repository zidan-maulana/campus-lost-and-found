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
            'stats' => [
                'users' => User::count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'reports_open' => Report::where('status', 'open')->count(),
                'reports_hidden' => Report::where('status', 'hidden')->count(),
                'claims_active' => Claim::whereIn('status', ['submitted', 'in_handover'])->count(),
                'abuse_pending' => $abuseReports->count(),
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
