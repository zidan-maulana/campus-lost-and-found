<?php

namespace App\Http\Controllers\Api;

use App\Enums\ClaimStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileApiController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nim' => $user->nim,
                'phone' => $user->phone,
                'role' => $user->role->value,
                'status' => $user->status->value,
                'initials' => $user->initials(),
                'avatar_url' => $user->avatar_path ? \App\Support\FindumbStorage::publicUrl($user->avatar_path) : null,
                'email_verified' => $user->hasVerifiedEmail(),
                'meta' => $user->reporterMeta(),
                'stats' => [
                    'reports' => $user->reports()->count(),
                    'claims' => $user->claims()->count(),
                    'resolved' => $user->reports()->where('status', 'resolved')->count(),
                    'active_claims' => $user->claims()->whereIn('status', ClaimStatus::activeValues())->count(),
                ],
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'nim' => ['sometimes', 'nullable', 'string', 'max:20'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:25'],
            'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                \App\Support\FindumbStorage::delete($user->avatar_path);
            }

            $validated['avatar_path'] = $request->file('avatar')->store('avatars', \App\Support\FindumbStorage::disk());
        }

        unset($validated['avatar']);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'name' => $user->name,
                'nim' => $user->nim,
                'phone' => $user->phone,
                'meta' => $user->reporterMeta(),
            ],
        ]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'preferences' => ['required', 'array'],
            'preferences.*' => ['boolean'],
        ]);

        $user = $request->user();
        $user->update([
            'preferences' => array_merge($user->preferences ?? [], $validated['preferences']),
        ]);

        return response()->json([
            'message' => 'Pengaturan disimpan.',
            'data' => $user->preferences,
        ]);
    }
}
