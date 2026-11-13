<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Claim;
use App\Services\ChatService;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Modul pesan privat — docs/API_CONTRACT.md §7.
 */
class ChatController extends Controller
{
    use V1Response;

    public function __construct(private readonly ChatService $chat)
    {
    }

    /**
     * GET /api/v1/chats (§7.1)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $conversations = $this->chat->conversationsFor($user);

        // Jumlah pesan belum dibaca per klaim (kontrak §7.1).
        $unread = ChatMessage::query()
            ->selectRaw('claim_id, COUNT(*) as total')
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->whereIn('claim_id', $conversations->pluck('claim_id')->map(fn ($id) => (int) $id)->all())
            ->groupBy('claim_id')
            ->pluck('total', 'claim_id');

        $data = $conversations->map(function (array $conversation) use ($unread) {
            // Kontrak memakai id numerik; ChatService mengembalikan string untuk kebutuhan Blade lama.
            $conversation['claim_id'] = (int) $conversation['claim_id'];
            $conversation['report_id'] = (int) $conversation['report_id'];
            $conversation['unread_count'] = (int) ($unread[(int) $conversation['claim_id']] ?? 0);

            return $conversation;
        })->values()->all();

        return $this->ok($data, 'Daftar percakapan berhasil diambil.', [
            'unread_total' => $this->chat->unreadCount($user),
        ]);
    }

    /**
     * GET /api/v1/claims/{claim}/messages (§7.2)
     */
    public function messages(Request $request, Claim $claim): JsonResponse
    {
        $user = $request->user();
        $afterId = $request->query('after_id');

        $messages = $this->chat->messagesFor(
            $claim,
            $user,
            $afterId !== null && $afterId !== '' ? (int) $afterId : null,
        );

        $claim->loadMissing('report');

        return $this->ok([
            'claim_id' => (int) $claim->id,
            'item_title' => $claim->report?->title,
            'status' => $claim->status->value,
            'is_readonly' => ! $claim->status->allowsChat(),
            'messages' => $messages->map(fn (ChatMessage $message) => ApiPresenter::message($message, $user))->values()->all(),
            'poll_seconds' => (int) config('findumb.chat.poll_seconds', 5),
        ], 'Riwayat pesan berhasil diambil.');
    }

    /**
     * POST /api/v1/claims/{claim}/messages (§7.3)
     */
    public function send(Request $request, Claim $claim): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:'.(int) config('findumb.chat.max_length', 2000)],
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan terlalu panjang (maksimal '.(int) config('findumb.chat.max_length', 2000).' karakter).',
        ]);

        $message = $this->chat->send($claim, $request->user(), $validated['message']);

        return $this->created(
            ApiPresenter::message($message, $request->user()),
            'Pesan terkirim.',
        );
    }
}
