<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pusat notifikasi dalam aplikasi (PR-07, FR-10).
 */
class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = AppNotification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(100)
            ->get();

        return view('notifications', [
            'viewer' => $user,
            'notifications' => $notifications,
            'unreadCount' => $notifications->whereNull('read_at')->count(),
            'savedIds' => $user->savedReports()->pluck('reports.id')->map(fn ($id) => (string) $id)->all(),
        ]);
    }
}
