<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = AppNotification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(100)
            ->get();

        return response()->json([
            'data' => $notifications->map(fn (AppNotification $n) => $n->toUiArray()),
            'unread_count' => $notifications->whereNull('read_at')->count(),
        ]);
    }

    public function markRead(Request $request, AppNotification $notification): JsonResponse
    {
        abort_unless((int) $notification->user_id === (int) $request->user()->id, 403);

        if (! $notification->isRead()) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['message' => 'Notifikasi ditandai terbaca.']);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $count = AppNotification::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Semua notifikasi ditandai terbaca.',
            'updated' => $count,
        ]);
    }
}
