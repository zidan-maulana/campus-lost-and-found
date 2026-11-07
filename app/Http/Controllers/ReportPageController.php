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
        return $this->form($request, 'found');
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
