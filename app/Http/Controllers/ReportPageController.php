<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Halaman formulir laporan dan detail laporan (UI_UX 5.2 & 5.3).
 */
class ReportPageController extends Controller
{
    public function createLost(Request $request): View
    {
        return $this->form($request, 'lost');
    }

    public function createFound(Request $request): View
    {
        // "Saya Menemukan Barang Ini" pada laporan HILANG mengarah ke sini dengan
        // ?ref={id} → formulir temuan diisi otomatis dari laporan hilang tersebut,
        // dan pemilik laporan hilang dinotifikasi setelah laporan temuan dibuat.
        $reference = null;

        if ($ref = $request->query('ref')) {
            $reference = Report::query()
                ->whereKey($ref)
                ->where('type', \App\Enums\ReportType::Lost)
                ->first();
        }

        return view('report-found', [
            'viewer' => $request->user(),
            'reportType' => 'found',
            'categories' => ItemCategory::cachedActive(),
            'referenceReport' => $reference,
        ]);
    }

    private function form(Request $request, string $type): View
    {
        return view($type === 'lost' ? 'report-lost' : 'report-found', [
            'viewer' => $request->user(),
            'reportType' => $type,
            'categories' => ItemCategory::cachedActive(),
        ]);
    }

    public function show(Request $request, Report $report): View
    {
        $viewer = $request->user();

        // Laporan tersembunyi/dibatalkan hanya boleh dilihat pemilik dan admin.
        if (! $report->status->isPubliclyVisible()) {
            $allowed = $viewer && ($report->isOwnedBy($viewer) || $viewer->isAdmin());

            abort_unless($allowed, 404);
        }

        $report->load(['user', 'category', 'claims.claimant', 'claims.confirmations']);

        return view('report-detail', [
            'viewer' => $viewer,
            'id' => (string) $report->id,
            'reportData' => $report->toUiArray($viewer),
            'report' => $report,
            'categories' => ItemCategory::cachedActive(),
            'savedIds' => $viewer
                ? $viewer->savedReports()->pluck('reports.id')->map(fn ($id) => (string) $id)->all()
                : [],
            'isOwner' => $report->isOwnedBy($viewer),
            'canClaim' => $viewer
                && $report->canReceiveClaim()
                && ! $report->isOwnedBy($viewer)
                && ! $viewer->hasActiveClaim(),
            'myClaim' => $viewer
                ? $report->claims->firstWhere('claimant_id', $viewer->id)
                : null,
        ]);
    }
}
