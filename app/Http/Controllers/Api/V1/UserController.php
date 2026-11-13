<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ClaimStatus;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Report;
use App\Models\SavedReport;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use App\Support\FindumbStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Aktivitas & profil civitas — docs/API_CONTRACT.md §10 (my-reports, profile, settings).
 */
class UserController extends Controller
{
    use V1Response;

    /**
     * GET /api/v1/user/activities
     */
    public function activities(Request $request): JsonResponse
    {
        $user = $request->user();

        $reports = Report::query()
            ->where('user_id', $user->id)
            ->with(['user', 'category'])
            ->withCount('claims')
            ->latest()
            ->get();

        $claims = Claim::query()
            ->where('claimant_id', $user->id)
            ->with(['report.category', 'claimant', 'confirmations'])
            ->latest()
            ->get();

        return $this->ok([
            'summary' => [
                'reports_total' => $reports->count(),
                'reports_lost' => $reports->filter(fn (Report $r) => $r->type->value === 'lost')->count(),
                'reports_found' => $reports->filter(fn (Report $r) => $r->type->value === 'found')->count(),
                'reports_active' => $reports->filter(fn (Report $r) => in_array($r->status->value, ['open', 'in_handover'], true))->count(),
                'reports_resolved' => $reports->filter(fn (Report $r) => $r->status->value === 'resolved')->count(),
                'claims_total' => $claims->count(),
                'claims_active' => $claims->filter(fn (Claim $c) => in_array($c->status->value, ClaimStatus::activeValues(), true))->count(),
                'bookmarks_total' => DB::table('saved_reports')->where('user_id', $user->id)->count(),
            ],
            'reports' => $reports->map(fn (Report $r) => ApiPresenter::report($r, $user))->values()->all(),
            'claims' => $claims->map(fn (Claim $c) => array_merge(ApiPresenter::claim($c, $user), [
                'report' => $c->report ? ApiPresenter::report($c->report, $user) : null,
            ]))->values()->all(),
        ], 'Aktivitas pengguna berhasil diambil.');
    }

    /**
     * GET /api/v1/user/profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->ok([
            'user' => ApiPresenter::user($user),
            'preferences' => $user->preferences ?? [],
        ], 'Profil berhasil diambil.');
    }

    /**
     * PUT /api/v1/user/profile
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'nim' => ['sometimes', 'nullable', 'string', 'max:20'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:25'],
            'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.max' => 'Nama maksimal 120 karakter.',
            'avatar.image' => 'Foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil harus JPG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                FindumbStorage::delete($user->avatar_path);
            }

            $validated['avatar_path'] = $request->file('avatar')->store('avatars', FindumbStorage::disk());
        }

        unset($validated['avatar']);

        if (! empty($validated)) {
            $user->update($validated);
        }

        return $this->ok(ApiPresenter::user($user->refresh()), 'Profil berhasil diperbarui.');
    }

    /**
     * PUT /api/v1/user/settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'preferences' => ['required', 'array'],
            'preferences.*' => ['boolean'],
        ], [
            'preferences.required' => 'Preferensi pengaturan wajib dikirim.',
        ]);

        $user = $request->user();
        $user->update([
            'preferences' => array_merge($user->preferences ?? [], $validated['preferences']),
        ]);

        return $this->ok(['preferences' => $user->preferences], 'Pengaturan berhasil disimpan.');
    }
}
