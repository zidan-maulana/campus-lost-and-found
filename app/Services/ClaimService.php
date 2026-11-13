<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Siklus hidup klaim (PR-04, FR-06, FR-07, DEC-006/007/017/024/025).
 *
 * Seluruh perubahan status dibungkus transaksi + row lock untuk mencegah
 * race condition (SYSTEM_DESIGN 10, baris matriks 16 & 17).
 */
class ClaimService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * Mengajukan klaim baru pada laporan barang temuan.
     *
     * @param  array{proof_answer?: string|null, claim_reason?: string|null, proof_attachment?: string|null}  $payload
     */
    public function submit(User $claimant, Report $report, array $payload = []): Claim
    {
        if (! $claimant->isActive()) {
            throw new BusinessRuleException('Akun Anda sedang ditangguhkan sehingga tidak dapat mengajukan klaim.');
        }

        if (! $report->isFound()) {
            throw new BusinessRuleException('Klaim hanya dapat diajukan pada laporan barang temuan.');
        }

        if ($report->isOwnedBy($claimant)) {
            throw new BusinessRuleException('Anda tidak dapat mengklaim barang temuan milik Anda sendiri.');
        }

        if (! $report->status->acceptsClaims()) {
            throw new BusinessRuleException('Laporan ini sudah tidak menerima klaim baru.');
        }

        $this->assertNoActiveClaim($claimant);
        $this->assertNotBlockedFromReclaiming($claimant, $report);

        try {
            return DB::transaction(function () use ($claimant, $report, $payload) {
                // Lock laporan agar tidak ada klaim ganda saat bersamaan.
                $lockedReport = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

                if ($lockedReport->status !== ReportStatus::Open) {
                    throw new BusinessRuleException('Laporan ini sudah tidak menerima klaim baru.');
                }

                // Cek ulang di dalam transaksi (double-submit / klik ganda).
                $this->assertNoActiveClaim($claimant);

                $claim = Claim::create([
                    'report_id' => $lockedReport->id,
                    'claimant_id' => $claimant->id,
                    'status' => ClaimStatus::Submitted,
                    'proof_answer' => $payload['proof_answer'] ?? null,
                    'claim_reason' => $payload['claim_reason'] ?? null,
                    'proof_attachment' => $payload['proof_attachment'] ?? null,
                    'submitted_at' => now(),
                ]);

                $this->recordHistory($claim, null, ClaimStatus::Submitted, $claimant, 'Klaim diajukan.');

                $this->notifications->claimSubmitted($claim);

                return $claim->fresh(['report.user', 'claimant']);
            });
        } catch (QueryException $e) {
            // Unique index claims_one_active_per_claimant / claims_one_handover_per_report
            if ($this->isUniqueViolation($e)) {
                throw new BusinessRuleException('Anda masih memiliki klaim aktif atau klaim ini sudah diproses pihak lain.');
            }

            throw $e;
        }
    }

    /**
     * Penemu menolak klaim sebelum tahap serah terima (FR-07).
     */
    public function reject(Claim $claim, User $finder, ?string $reason = null): Claim
    {
        return DB::transaction(function () use ($claim, $finder, $reason) {
            $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isFinder($finder)) {
                throw new BusinessRuleException('Hanya penemu barang yang dapat menolak klaim ini.', 403);
            }

            if ($locked->status !== ClaimStatus::Submitted) {
                throw new BusinessRuleException('Klaim ini sudah diproses sehingga tidak dapat ditolak.');
            }

            $locked->update([
                'status' => ClaimStatus::Rejected,
                'rejection_reason' => $reason,
                'resolved_at' => now(),
            ]);

            $this->recordHistory($locked, ClaimStatus::Submitted, ClaimStatus::Rejected, $finder, $reason);
            $this->notifications->claimRejected($locked);

            return $locked->fresh();
        });
    }

    /**
     * Pengklaim membatalkan klaimnya sendiri (sukarela), kapan pun selama aktif.
     */
    public function cancelByClaimant(Claim $claim, User $claimant, ?string $reason = null): Claim
    {
        return DB::transaction(function () use ($claim, $claimant, $reason) {
            $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isClaimant($claimant)) {
                throw new BusinessRuleException('Hanya pengklaim yang dapat membatalkan klaim ini.', 403);
            }

            if (! $locked->isActive()) {
                throw new BusinessRuleException('Klaim ini sudah berakhir dan tidak dapat dibatalkan.');
            }

            $previous = $locked->status;
            $wasInHandover = $previous === ClaimStatus::InHandover;

            $locked->update([
                'status' => ClaimStatus::Cancelled,
                'cancelled_by' => $claimant->id,
                'cancellation_reason' => $reason,
                'resolved_at' => now(),
            ]);

            // Jika serah terima dibatalkan, laporan kembali terbuka (DEC-018).
            if ($wasInHandover) {
                $this->reopenReport($locked);
            }

            $this->recordHistory($locked, $previous, ClaimStatus::Cancelled, $claimant, $reason ?? 'Dibatalkan oleh pengklaim.');
            $this->notifications->claimCancelled($locked, $claimant);

            return $locked->fresh();
        });
    }

    /**
     * Penemu membatalkan proses serah terima yang terhenti (DEC-018).
     * Alasan wajib diisi dan dicatat sebagai audit.
     */
    public function cancelByFinder(Claim $claim, User $finder, string $reason): Claim
    {
        if (trim($reason) === '') {
            throw new BusinessRuleException('Alasan pembatalan serah terima wajib diisi.');
        }

        return DB::transaction(function () use ($claim, $finder, $reason) {
            $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isFinder($finder)) {
                throw new BusinessRuleException('Hanya penemu barang yang dapat membatalkan serah terima ini.', 403);
            }

            if ($locked->status !== ClaimStatus::InHandover) {
                throw new BusinessRuleException('Pembatalan oleh penemu hanya berlaku pada proses serah terima yang berjalan.');
            }

            $locked->update([
                'status' => ClaimStatus::Cancelled,
                'cancelled_by' => $finder->id,
                'cancellation_reason' => $reason,
                'resolved_at' => now(),
            ]);

            $this->reopenReport($locked);
            $this->recordHistory($locked, ClaimStatus::InHandover, ClaimStatus::Cancelled, $finder, $reason);
            $this->notifications->claimCancelled($locked, $finder);

            return $locked->fresh();
        });
    }

    /**
     * Penemu memilih satu klaim untuk dilanjutkan ke serah terima (BR-04).
     * Klaim lain tetap `submitted` (mengantre) — DEC-025.
     */
    public function selectForHandover(Claim $claim, User $finder): Claim
    {
        return DB::transaction(function () use ($claim, $finder) {
            $locked = Claim::whereKey($claim->id)->lockForUpdate()->firstOrFail();
            $report = Report::whereKey($locked->report_id)->lockForUpdate()->firstOrFail();

            if (! $locked->isFinder($finder)) {
                throw new BusinessRuleException('Hanya penemu barang yang dapat menyetujui klaim ini.', 403);
            }

            if ($locked->status !== ClaimStatus::Submitted) {
                throw new BusinessRuleException('Hanya klaim yang menunggu respons dapat diproses ke serah terima.');
            }

            if ($report->status !== ReportStatus::Open) {
                throw new BusinessRuleException('Laporan ini sedang dalam proses serah terima dengan klaim lain.');
            }

            $locked->update(['status' => ClaimStatus::InHandover]);
            $report->update(['status' => ReportStatus::InHandover]);

            $this->recordHistory($locked, ClaimStatus::Submitted, ClaimStatus::InHandover, $finder, 'Klaim disetujui untuk serah terima.');
            $this->notifications->handoverStarted($locked);

            return $locked->fresh();
        });
    }

    /**
     * Scheduler: kirim pengingat 72 jam untuk klaim `submitted` tanpa respons.
     *
     * @return int jumlah pengingat terkirim
     */
    public function sendReminders(): int
    {
        $reminderHours = (int) config('findumb.claim.reminder_hours', 72);
        $expiryHours = (int) config('findumb.claim.expiry_hours', 120);

        $claims = Claim::query()
            ->where('status', ClaimStatus::Submitted->value)
            ->whereNull('response_notified_at')
            ->where('submitted_at', '<=', now()->subHours($reminderHours))
            ->where('submitted_at', '>', now()->subHours($expiryHours))
            ->with(['report', 'claimant'])
            ->get();

        foreach ($claims as $claim) {
            DB::transaction(function () use ($claim) {
                $locked = Claim::whereKey($claim->id)->lockForUpdate()->first();

                if (! $locked || $locked->status !== ClaimStatus::Submitted || $locked->response_notified_at) {
                    return;
                }

                $locked->update(['response_notified_at' => now()]);
                $this->notifications->claimReminder($locked);
            });
        }

        return $claims->count();
    }

    /**
     * Scheduler: klaim `submitted` yang melewati batas 120 jam menjadi `expired`.
     * Idempoten dan aman terhadap konflik transaksi (matriks baris 16).
     *
     * @return int jumlah klaim yang kedaluwarsa
     */
    public function expireStale(): int
    {
        $expiryHours = (int) config('findumb.claim.expiry_hours', 120);

        $ids = Claim::query()
            ->where('status', ClaimStatus::Submitted->value)
            ->where('submitted_at', '<=', now()->subHours($expiryHours))
            ->pluck('id');

        $expired = 0;

        foreach ($ids as $id) {
            $changed = DB::transaction(function () use ($id) {
                $locked = Claim::whereKey($id)->lockForUpdate()->first();

                // Bila status sudah berubah (ditolak/dibatalkan/handover), batalkan aman.
                if (! $locked || $locked->status !== ClaimStatus::Submitted) {
                    return false;
                }

                $locked->update([
                    'status' => ClaimStatus::Expired,
                    'resolved_at' => now(),
                ]);

                $this->recordHistory($locked, ClaimStatus::Submitted, ClaimStatus::Expired, null, 'Kedaluwarsa otomatis setelah '.config('findumb.claim.expiry_hours', 120).' jam tanpa respons.');
                $this->notifications->claimExpired($locked);

                return true;
            });

            if ($changed) {
                $expired++;
            }
        }

        return $expired;
    }

    // ------------------------------------------------------------- internal

    private function assertNoActiveClaim(User $claimant): void
    {
        $hasActive = Claim::query()
            ->where('claimant_id', $claimant->id)
            ->whereIn('status', ClaimStatus::activeValues())
            ->exists();

        if ($hasActive) {
            throw new BusinessRuleException('Anda masih memiliki klaim aktif. Selesaikan atau batalkan klaim tersebut sebelum mengajukan klaim baru.');
        }
    }

    /**
     * BR-11: klaim yang ditolak atau dibatalkan sendiri tidak boleh diulang
     * pada laporan yang sama; kedaluwarsa atau dibatalkan penemu boleh diulang.
     */
    private function assertNotBlockedFromReclaiming(User $claimant, Report $report): void
    {
        $blocked = Claim::query()
            ->where('report_id', $report->id)
            ->where('claimant_id', $claimant->id)
            ->where(function ($q) {
                $q->where('status', ClaimStatus::Rejected->value)
                    ->orWhere(function ($q2) {
                        $q2->where('status', ClaimStatus::Cancelled->value)
                            ->whereColumn('cancelled_by', 'claimant_id');
                    });
            })
            ->exists();

        if ($blocked) {
            throw new BusinessRuleException('Anda sudah pernah mengajukan klaim pada laporan ini dan tidak dapat mengulanginya.');
        }
    }

    private function reopenReport(Claim $claim): void
    {
        $report = Report::whereKey($claim->report_id)->lockForUpdate()->first();

        if ($report && $report->status === ReportStatus::InHandover) {
            $report->update(['status' => ReportStatus::Open]);
        }
    }

    private function recordHistory(
        Claim $claim,
        ?ClaimStatus $from,
        ClaimStatus $to,
        ?User $actor,
        ?string $reason,
    ): void {
        $claim->histories()->create([
            'from_status' => $from?->value,
            'to_status' => $to->value,
            'changed_by' => $actor?->id,
            'reason' => $reason,
            'created_at' => now(),
        ]);
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;

        // 23505 = unique_violation (PostgreSQL), 23000 = constraint (SQLite/MySQL).
        return in_array($sqlState, ['23505', '23000'], true);
    }
}
