<?php

namespace App\Support\Api;

use App\Models\AppNotification;
use App\Models\ChatMessage;
use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use App\Support\FindumbStorage;

/**
 * Pemetaan model -> bentuk JSON publik API v1 (docs/API_CONTRACT.md).
 *
 * Dipisahkan dari `toUiArray()` milik model karena `toUiArray()` mengikuti
 * kebutuhan markup Blade lama (string id, label bahasa Indonesia, dll),
 * sedangkan kontrak v1 memakai id numerik dan nama field baku.
 */
class ApiPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function user(User $user): array
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nim' => $user->nim,
            'phone' => $user->phone,
            'role' => $user->role->value,
            'status' => $user->status->value,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'initials' => $user->initials(),
            'avatar_url' => $user->avatar_path ? FindumbStorage::publicUrl($user->avatar_path) : null,
            'created_at' => $user->created_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function report(Report $report, ?User $viewer = null, bool $withRelations = false): array
    {
        $payload = [
            'id' => (int) $report->id,
            'type' => $report->type->value,
            'title' => $report->title,
            'description' => $report->description,
            'status' => $report->status->value,
            'category' => $report->category ? [
                'id' => (int) $report->category->id,
                'name' => $report->category->name,
                'slug' => $report->category->slug,
            ] : null,
            'campus' => self::campusSlug($report->campus_location),
            'campus_label' => config('findumb.campuses.'.self::campusSlug($report->campus_location), $report->campus_location),
            'location' => $report->location,
            'incident_date' => $report->incident_date?->format('Y-m-d'),
            'incident_time' => $report->incident_time,
            'storage_location' => $report->storage_location,
            'security_post_id' => $report->security_post_id !== null ? (int) $report->security_post_id : null,
            'image_url' => $report->imageUrl(),
            'reporter' => $report->user ? [
                'id' => (int) $report->user->id,
                'name' => $report->user->name,
                'initials' => $report->user->initials(),
            ] : null,
            'claims_count' => $report->claims_count !== null ? (int) $report->claims_count : $report->claims()->count(),
            'is_mine' => $report->isOwnedBy($viewer),
            'created_at' => $report->created_at?->toIso8601String(),
            'updated_at' => $report->updated_at?->toIso8601String(),
        ];

        // Blind information (kontrak §5.2): pertanyaan rahasia hanya untuk pemilik laporan.
        if ($report->isOwnedBy($viewer) && $report->proof_question !== null) {
            $payload['proof_question'] = $report->proof_question;
        }

        if ($withRelations) {
            $payload['claims'] = $report->claims
                ->map(fn (Claim $claim) => self::claim($claim, $viewer))
                ->values()
                ->all();
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public static function claim(Claim $claim, ?User $viewer = null): array
    {
        $payload = [
            'id' => (int) $claim->id,
            'report_id' => (int) $claim->report_id,
            'claimant' => $claim->claimant ? [
                'id' => (int) $claim->claimant->id,
                'name' => $claim->claimant->name,
                'initials' => $claim->claimant->initials(),
            ] : null,
            'status' => $claim->status->value,
            'submitted_at' => $claim->submitted_at?->toIso8601String(),
            'resolved_at' => $claim->resolved_at?->toIso8601String(),
            'expires_at' => $claim->expiresAt()?->toIso8601String(),
            'cancellation_reason' => $claim->cancellation_reason,
            'rejection_reason' => $claim->rejection_reason,
            'confirmations_count' => (int) ($claim->confirmations_count ?? $claim->confirmations()->count()),
            'i_am_claimant' => $claim->isClaimant($viewer),
            'i_am_finder' => $claim->isFinder($viewer),
            'can_send_message' => $claim->status->allowsChat(),
        ];

        // Bukti klaim hanya untuk dua pihak yang terlibat (kontrak §5.2 blind information).
        if ($viewer !== null && $claim->isParticipant($viewer)) {
            $payload['proof_answer'] = $claim->proof_answer;
            $payload['claim_reason'] = $claim->claim_reason;
            $payload['proof_attachment_url'] = $claim->proof_attachment
                ? FindumbStorage::publicUrl($claim->proof_attachment)
                : null;
            $payload['i_confirmed'] = $claim->confirmations()->where('user_id', $viewer->id)->exists();
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public static function message(ChatMessage $message, User $viewer): array
    {
        return [
            'id' => (int) $message->id,
            'sender_id' => (int) $message->sender_id,
            'sender_name' => $message->sender?->name,
            'message' => $message->message,
            'is_me' => (int) $message->sender_id === (int) $viewer->id,
            'created_at' => $message->created_at?->toIso8601String(),
            'read_at' => $message->read_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function notification(AppNotification $notification): array
    {
        return [
            'id' => (int) $notification->id,
            'type' => $notification->type,
            'title' => $notification->title,
            'body' => $notification->body,
            'target_url' => $notification->target_url,
            'data' => $notification->data,
            'read' => $notification->isRead(),
            'read_at' => $notification->read_at?->toIso8601String(),
            'created_at' => $notification->created_at?->toIso8601String(),
        ];
    }

    /**
     * Normalisasi nilai kolom `campus_location` (bisa slug atau label lama) menjadi slug enum.
     */
    public static function campusSlug(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $needle = strtolower(trim($value));

        foreach ((array) config('findumb.campuses', []) as $slug => $label) {
            if ($needle === $slug || $needle === strtolower($label)) {
                return $slug;
            }
        }

        return $needle;
    }
}
