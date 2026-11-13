<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Claim;
use App\Models\HandoverConfirmation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Konfirmasi serah terima dua arah (PR-06, FR-09, BR-07, BR-08).
 *
 * Selesai hanya bila penemu DAN pengklaim sama-sama mengonfirmasi.
 * Konfirmasi ganda dicegah oleh unique index (claim_id, user_id).
 */
class HandoverService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * @return array{claim: Claim, completed: bool}
     */
    public function confirm(Claim $claim, User $user): array
    {
        try {
            return DB::transaction(function () use ($claim, $user) {
                $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

                if (! $locked->isParticipant($user)) {
                    throw new BusinessRuleException('Hanya penemu dan pengklaim yang dapat mengonfirmasi serah terima ini.', 403);
                }

                if ($locked->status !== ClaimStatus::InHandover) {
                    throw new BusinessRuleException('Klaim ini tidak sedang dalam proses serah terima.');
                }

                // Idempoten: konfirmasi berulang tidak mengubah apa pun.
                $already = HandoverConfirmation::query()
                    ->where('claim_id', $locked->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($already) {
                    $confirmations = $this->confirmationCount($locked);

                    return ['claim' => $locked->fresh(), 'completed' => $confirmations >= 2];
                }

                HandoverConfirmation::create([
                    'claim_id' => $locked->id,
                    'user_id' => $user->id,
                    'confirmed_at' => now(),
                ]);

                $confirmations = $this->confirmationCount($locked);

                if ($confirmations >= 2) {
                    $this->complete($locked);

                    return ['claim' => $locked->fresh(), 'completed' => true];
                }

                $this->notifications->handoverAwaitingOtherParty($locked, $user);

                return ['claim' => $locked->fresh(), 'completed' => false];
            });
        } catch (QueryException $e) {
            if (in_array($e->errorInfo[0] ?? null, ['23505', '23000'], true)) {
                throw new BusinessRuleException('Konfirmasi Anda sudah tercatat. Menunggu konfirmasi pihak lain.');
            }

            throw $e;
        }
    }

    /**
     * Dua pihak sudah konfirmasi: klaim selesai, laporan resolved,
     * klaim antre dibatalkan tanpa sanksi, chat otomatis read-only (BR-08).
     */
    private function complete(Claim $claim): void
    {
        $claim->update([
            'status' => ClaimStatus::Completed,
            'resolved_at' => now(),
        ]);

        $claim->histories()->create([
            'from_status' => ClaimStatus::InHandover->value,
            'to_status' => ClaimStatus::Completed->value,
            'changed_by' => null,
            'reason' => 'Serah terima dikonfirmasi oleh kedua pihak.',
            'created_at' => now(),
        ]);

        $report = Report::whereKey($claim->report_id)->lockForUpdate()->first();

        if ($report) {
            $report->update(['status' => ReportStatus::Resolved]);

            // Klaim mengantre diakhiri tanpa sanksi (DEC-025 poin 6).
            $queued = Claim::query()
                ->where('report_id', $report->id)
                ->where('id', '!=', $claim->id)
                ->where('status', ClaimStatus::Submitted->value)
                ->get();

            foreach ($queued as $other) {
                $other->update([
                    'status' => ClaimStatus::Cancelled,
                    'resolved_at' => now(),
                    'cancellation_reason' => 'Barang telah diserahterimakan kepada pengklaim lain.',
                ]);

                $other->histories()->create([
                    'from_status' => ClaimStatus::Submitted->value,
                    'to_status' => ClaimStatus::Cancelled->value,
                    'changed_by' => null,
                    'reason' => 'Laporan selesai dengan klaim lain.',
                    'created_at' => now(),
                ]);

                $this->notifications->claimCancelled($other, null);
            }
        }

        $this->notifications->handoverCompleted($claim);
    }

    private function confirmationCount(Claim $claim): int
    {
        return HandoverConfirmation::query()->where('claim_id', $claim->id)->count();
    }
}
