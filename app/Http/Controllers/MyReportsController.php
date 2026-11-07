<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "Laporan Saya" — laporan milik pengguna beserta klaim terkait (UI_UX 5.5).
 */
class MyReportsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $reports = Report::query()
            ->where('user_id', $user->id)
            ->with(['category'])
            ->withCount(['claims', 'claims as active_claims_count' => function ($q) {
                $q->whereIn('status', ClaimStatus::activeValues());
            }])
            ->latest()
            ->get();

        $myClaims = Claim::query()
            ->where('claimant_id', $user->id)
            ->with(['report.category', 'report.user'])
            ->latest()
            ->get();

        return view('my-reports', [
            'viewer' => $user,
            'reports' => $reports,
            'myClaims' => $myClaims,
            'activeClaim' => $user->activeClaim()?->load('report'),
        ]);
    }
}
