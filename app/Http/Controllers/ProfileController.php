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
        $prefs = $user->preferences ?? [];

        return view('profile', [
            'viewer' => $user,
            'profile' => [
                'name' => $user->name,
                'nim' => $user->nim,
                'email' => $user->email,
                'phone' => $user->phone,
                'faculty' => $prefs['faculty'] ?? null,
                'prodi' => $prefs['prodi'] ?? null,
                'role_label' => $user->isAdmin() ? 'Administrator Platform' : 'Mahasiswa',
                'initials' => $user->initials(),
                'avatar_url' => $user->avatar_path
                    ? \App\Support\FindumbStorage::publicUrl($user->avatar_path)
                    : null,
                'meta' => $user->reporterMeta(),
                'verified' => $user->hasVerifiedEmail(),
            ],
            'stats' => [
                'reports' => $user->reports()->count(),
                'claims' => $user->claims()->count(),
                'resolved' => $user->reports()->where('status', 'resolved')->count(),
                'active_claims' => $user->claims()->whereIn('status', ClaimStatus::activeValues())->count(),
                'saved' => \App\Models\Report::query()
                    ->whereHas('savedBy', fn ($q) => $q->where('users.id', $user->id))
                    ->count(),
                'conversations' => \App\Models\Claim::query()
                    ->where(function ($q) use ($user) {
                        $q->where('claimant_id', $user->id)
                            ->orWhereHas('report', fn ($r) => $r->where('user_id', $user->id));
                    })
                    ->count(),
                'unread_notifications' => \App\Models\AppNotification::query()
                    ->where('user_id', $user->id)
                    ->whereNull('read_at')
                    ->count(),
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
