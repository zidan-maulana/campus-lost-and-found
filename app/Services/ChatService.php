<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\ChatMessage;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Chat privat per klaim (PR-05, FR-08, DEC-009/010/011).
 *
 * - Hanya penemu dan pengklaim yang dapat membaca/mengirim.
 * - Read-only setelah klaim mencapai status terminal.
 * - Pesan dihapus 30 hari setelah serah terima selesai (BR-09).
 */
class ChatService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function send(Claim $claim, User $sender, string $message): ChatMessage
    {
        $message = trim($message);

        if ($message === '') {
            throw new BusinessRuleException('Pesan tidak boleh kosong.');
        }

        if (mb_strlen($message) > (int) config('findumb.chat.max_length', 2000)) {
            throw new BusinessRuleException('Pesan terlalu panjang (maksimal '.config('findumb.chat.max_length', 2000).' karakter).');
        }

        if (! $claim->isParticipant($sender)) {
            throw new BusinessRuleException('Anda bukan bagian dari percakapan ini.', 403);
        }

        if (! $claim->status->allowsChat()) {
            throw new BusinessRuleException('Percakapan ini sudah ditutup karena proses klaim telah berakhir.');
        }

        $chatMessage = DB::transaction(function () use ($claim, $sender, $message) {
            $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

            if (! $locked->status->allowsChat()) {
                throw new BusinessRuleException('Percakapan ini sudah ditutup.');
            }

            $created = ChatMessage::create([
                'claim_id' => $locked->id,
                'sender_id' => $sender->id,
                'message' => $message,
            ]);

            $this->notifications->newMessage($locked, $sender);

            return $created;
        });

        return $chatMessage->load('sender');
    }

    /**
     * @return Collection<int, ChatMessage>
     */
    public function messagesFor(Claim $claim, User $viewer, ?int $afterId = null): Collection
    {
        $this->assertParticipant($claim, $viewer);

        $query = $claim->messages()->with('sender')->orderBy('id');

        if ($afterId) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get();

        // Tandai pesan dari pihak lain sebagai terbaca.
        $claim->messages()
            ->where('sender_id', '!=', $viewer->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $messages;
    }

    public function unreadCount(User $user): int
    {
        return ChatMessage::query()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->whereHas('claim', function ($q) use ($user) {
                $q->where('claimant_id', $user->id)
                    ->orWhereHas('report', fn ($r) => $r->where('user_id', $user->id));
            })
            ->count();
    }

    /**
     * Daftar percakapan milik pengguna (sebagai penemu maupun pengklaim).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function conversationsFor(User $user): Collection
    {
        $claims = Claim::query()
            ->with(['report.category', 'report.user', 'claimant', 'messages' => fn ($q) => $q->latest('id')->limit(1)])
            ->where(function ($q) use ($user) {
                $q->where('claimant_id', $user->id)
                    ->orWhereHas('report', fn ($r) => $r->where('user_id', $user->id));
            })
            ->orderByDesc('updated_at')
            ->get();

        return $claims->map(function (Claim $claim) use ($user) {
            $lastMessage = $claim->messages->first();
            $counterpart = $claim->isClaimant($user) ? $claim->report?->user : $claim->claimant;

            return [
                'claim_id' => (string) $claim->id,
                'report_id' => (string) $claim->report_id,
                'item_title' => $claim->report?->title,
                'item_image' => $claim->report?->imageUrl(),
                'counterpart_name' => $counterpart?->name,
                'counterpart_initials' => $counterpart?->initials(),
                'counterpart_meta' => $counterpart?->reporterMeta(),
                'status' => $claim->status->value,
                'status_label' => $claim->status->label(),
                'is_active' => $claim->isActive(),
                'can_send_message' => $claim->status->allowsChat(),
                'snippet' => $lastMessage?->message,
                'time_display' => $lastMessage?->created_at?->diffForHumans(short: true),
                'unread' => $claim->messages()->where('sender_id', '!=', $user->id)->whereNull('read_at')->exists(),
                'i_am_claimant' => $claim->isClaimant($user),
                'confirmations_count' => $claim->confirmations()->count(),
                'i_confirmed' => $claim->confirmations()->where('user_id', $user->id)->exists(),
            ];
        })->values();
    }

    /**
     * Scheduler: hapus pesan 30 hari setelah serah terima selesai,
     * kecuali pesan yang ditahan untuk peninjauan penyalahgunaan (BR-09, DEC-019).
     *
     * @return int jumlah pesan terhapus
     */
    public function purgeExpired(): int
    {
        $cutoff = now()->subDays((int) config('findumb.chat.retention_days', 30));

        $claimIds = Claim::query()
            ->where('status', ClaimStatus::Completed->value)
            ->where('resolved_at', '<=', $cutoff)
            ->pluck('id');

        if ($claimIds->isEmpty()) {
            return 0;
        }

        return ChatMessage::query()
            ->whereIn('claim_id', $claimIds)
            ->where('is_retained', false)
            ->delete();
    }

    private function assertParticipant(Claim $claim, User $viewer): void
    {
        if (! $claim->isParticipant($viewer)) {
            throw new BusinessRuleException('Anda tidak berhak membuka percakapan ini.', 403);
        }
    }
}
