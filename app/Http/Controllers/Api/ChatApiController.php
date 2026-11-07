<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatApiController extends Controller
{
    public function __construct(private readonly ChatService $chat)
    {
    }

    public function conversations(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->chat->conversationsFor($request->user()),
            'unread_count' => $this->chat->unreadCount($request->user()),
        ]);
    }

    public function messages(Request $request, Claim $claim): JsonResponse
    {
        $afterId = $request->query('after_id');

        $messages = $this->chat->messagesFor(
            $claim,
            $request->user(),
            $afterId !== null ? (int) $afterId : null,
        );

        return response()->json([
            'data' => $messages->map(fn ($m) => $m->toUiArray($request->user())),
            'can_send_message' => $claim->status->allowsChat(),
            'claim' => $claim->toUiArray($request->user()),
            'poll_seconds' => (int) config('findumb.chat.poll_seconds', 5),
        ]);
    }

    public function send(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:'.(int) config('findumb.chat.max_length', 2000)],
        ]);

        $message = $this->chat->send($claim, $request->user(), $validated['message']);

        return response()->json([
            'message' => 'Pesan terkirim.',
            'data' => $message->toUiArray($request->user()),
        ], 201);
    }
}
