<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Halaman pesan & proses klaim (PR-05, UI_UX 5.4).
 */
class MessageController extends Controller
{
    public function __construct(private readonly ChatService $chat)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        return view('messages', [
            'viewer' => $user,
            'conversations' => $this->chat->conversationsFor($user),
            'activeClaimId' => $request->query('claim'),
            'pollSeconds' => (int) config('findumb.chat.poll_seconds', 5),
        ]);
    }
}
