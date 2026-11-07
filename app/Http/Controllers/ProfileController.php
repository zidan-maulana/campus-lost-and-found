<?php

namespace App\Http\Controllers;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('profile', [
            'viewer' => $user,
            'stats' => [
                'reports' => $user->reports()->count(),
                'claims' => $user->claims()->count(),
                'resolved' => $user->reports()->where('status', 'resolved')->count(),
                'active_claims' => $user->claims()->whereIn('status', ClaimStatus::activeValues())->count(),
            ],
            'activeClaim' => $user->activeClaim()?->load('report'),
            'recentClaims' => Claim::query()
                ->where('claimant_id', $user->id)
                ->with('report')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
