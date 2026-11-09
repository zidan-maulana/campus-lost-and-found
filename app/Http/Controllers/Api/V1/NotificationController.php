<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Modul notifikasi in-app — docs/API_CONTRACT.md §8.
 */
class NotificationController extends Controller
{
    use V1Response;

    /**
     * GET /api/v1/notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) min(50, max(1, (int) $request->query('per_page', 20)));

        $notifications = AppNotification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);

        $unread = AppNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return $this->paginated(
            $notifications,
            fn (AppNotification $notification) => ApiPresenter::notification($notification),
            'Daftar notifikasi berhasil diambil.',
            ['unread_count' => $unread],
        );
    }

    /**
     * PATCH /api/v1/notifications/{notification}/read
     */
    public function markRead(Request $request, AppNotification $notification): JsonResponse
    {
        abort_unless((int) $notification->user_id === (int) $request->user()->id, 403);

        if (! $notification->isRead()) {
            $notification->update(['read_at' => now()]);
        }

        return $this->ok(
            ApiPresenter::notification($notification->refresh()),
            'Notifikasi ditandai terbaca.',
        );
    }

    /**
     * POST /api/v1/notifications/read-all
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $count = AppNotification::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->ok(['updated' => $count], 'Semua notifikasi ditandai terbaca.');
    }
}
