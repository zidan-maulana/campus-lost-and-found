<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Enums\ModerationActionType;
use App\Enums\ReportStatus;
use App\Enums\UserStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\ModerationAction;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Moderasi administrator (PR-08, FR-12, DEC-013, DEC-026).
 *
 * Administrator TIDAK menentukan kepemilikan barang (BR-06) — hanya
 * menegakkan aturan konten dan status akun.
 */
class ModerationService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function warn(User $admin, User $target, string $reason): ModerationAction
    {
        $this->assertAdmin($admin);

        $action = $this->log($admin, ModerationActionType::Warning, $reason, targetUser: $target);

        $this->notifications->send(
            $target,
            'warning',
            'Peringatan dari Administrator',
            $reason,
            route('profile'),
        );

        return $action;
    }

    public function hideReport(User $admin, Report $report, string $reason): ModerationAction
    {
        $this->assertAdmin($admin);

        return DB::transaction(function () use ($admin, $report, $reason) {
            $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

            $locked->update(['status' => ReportStatus::Hidden]);

            // Klaim aktif pada laporan ini diakhiri tanpa sanksi bagi pengklaim.
            $this->cancelActiveClaims($locked, $admin, 'Laporan disembunyikan oleh administrator.');

            return $this->log(
                $admin,
                ModerationActionType::HideReport,
                $reason,
                targetUser: $locked->user,
                targetType: 'report',
                targetId: $locked->id,
            );
        });
    }

    public function unhideReport(User $admin, Report $report, string $reason): ModerationAction
    {
        $this->assertAdmin($admin);

        return DB::transaction(function () use ($admin, $report, $reason) {
            $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== ReportStatus::Hidden) {
                throw new BusinessRuleException('Hanya laporan berstatus disembunyikan yang dapat ditampilkan kembali.');
            }

            $locked->update(['status' => ReportStatus::Open]);

            return $this->log(
                $admin,
                ModerationActionType::UnhideReport,
                $reason,
                targetUser: $locked->user,
                targetType: 'report',
                targetId: $locked->id,
            );
        });
    }

    /**
     * Penonaktifan akun dengan dampak berbeda per peran (DEC-026).
     */
    public function suspendUser(User $admin, User $target, string $reason): ModerationAction
    {
        $this->assertAdmin($admin);

        if ($admin->is($target)) {
            throw new BusinessRuleException('Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        if ($target->isAdmin()) {
            throw new BusinessRuleException('Akun administrator lain tidak dapat dinonaktifkan dari sini.');
        }

        return DB::transaction(function () use ($admin, $target, $reason) {
            $locked = User::whereKey($target->id)->lockForUpdate()->firstOrFail();

            $locked->update(['status' => UserStatus::Suspended]);

            // --- Dampak sebagai PENGKLAIM ---
            $claimsAsClaimant = Claim::query()
                ->where('claimant_id', $locked->id)
                ->whereIn('status', ClaimStatus::activeValues())
                ->get();

            foreach ($claimsAsClaimant as $claim) {
                $previous = $claim->status;

                $claim->update([
                    'status' => ClaimStatus::Cancelled,
                    'cancelled_by' => $admin->id,
                    'cancellation_reason' => 'Akun pengklaim dinonaktifkan oleh administrator.',
                    'resolved_at' => now(),
                ]);

                // Bila sedang in_handover, laporan kembali open agar penemu tidak dirugikan.
                if ($previous === ClaimStatus::InHandover) {
                    Report::whereKey($claim->report_id)
                        ->where('status', ReportStatus::InHandover->value)
                        ->update(['status' => ReportStatus::Open]);
                }

                $claim->histories()->create([
                    'from_status' => $previous->value,
                    'to_status' => ClaimStatus::Cancelled->value,
                    'changed_by' => $admin->id,
                    'reason' => 'Akun pengklaim dinonaktifkan.',
                    'created_at' => now(),
                ]);
            }

            // --- Dampak sebagai PENEMU ---
            $ownedReports = Report::query()
                ->where('user_id', $locked->id)
                ->whereIn('status', [ReportStatus::Open->value, ReportStatus::InHandover->value])
                ->get();

            foreach ($ownedReports as $report) {
                $report->update(['status' => ReportStatus::Hidden]);
                $this->cancelActiveClaims($report, $admin, 'Akun penemu dinonaktifkan oleh administrator.');
            }

            return $this->log($admin, ModerationActionType::SuspendUser, $reason, targetUser: $locked);
        });
    }

    /**
     * Reaktivasi akun. Status terminal TIDAK dihidupkan kembali (DEC-026).
     */
    public function reactivateUser(User $admin, User $target, string $reason): ModerationAction
    {
        $this->assertAdmin($admin);

        return DB::transaction(function () use ($admin, $target, $reason) {
            $locked = User::whereKey($target->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== UserStatus::Suspended) {
                throw new BusinessRuleException('Akun ini tidak dalam status ditangguhkan.');
            }

            $locked->update(['status' => UserStatus::Active]);

            return $this->log($admin, ModerationActionType::ReactivateUser, $reason, targetUser: $locked);
        });
    }

    /**
     * Menutup laporan penyalahgunaan setelah ditindak.
     */
    public function resolveAbuseReport(User $admin, AbuseReport $abuseReport, string $decision, string $reason): AbuseReport
    {
        $this->assertAdmin($admin);

        return DB::transaction(function () use ($admin, $abuseReport, $decision, $reason) {
            $locked = AbuseReport::whereKey($abuseReport->id)->lockForUpdate()->firstOrFail();

            $locked->update([
                'status' => $decision,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            $this->log(
                $admin,
                ModerationActionType::Warning,
                $reason,
                targetUser: null,
                targetType: $locked->target_type,
                targetId: $locked->target_id,
                abuseReportId: $locked->id,
            );

            return $locked->fresh();
        });
    }

    // ------------------------------------------------------------- internal

    private function cancelActiveClaims(Report $report, User $admin, string $reason): void
    {
        $claims = $report->claims()->whereIn('status', ClaimStatus::activeValues())->get();

        foreach ($claims as $claim) {
            $previous = $claim->status;

            $claim->update([
                'status' => ClaimStatus::Cancelled,
                'cancelled_by' => $admin->id,
                'cancellation_reason' => $reason,
                'resolved_at' => now(),
            ]);

            $claim->histories()->create([
                'from_status' => $previous->value,
                'to_status' => ClaimStatus::Cancelled->value,
                'changed_by' => $admin->id,
                'reason' => $reason,
                'created_at' => now(),
            ]);

            $this->notifications->claimCancelled($claim, $admin);
        }
    }

    private function log(
        User $admin,
        ModerationActionType $type,
        string $reason,
        ?User $targetUser = null,
        ?string $targetType = null,
        ?int $targetId = null,
        ?int $abuseReportId = null,
    ): ModerationAction {
        return ModerationAction::create([
            'admin_id' => $admin->id,
            'target_user_id' => $targetUser?->id,
            'abuse_report_id' => $abuseReportId,
            'action_type' => $type,
            'reason' => $reason,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'created_at' => now(),
        ]);
    }

    private function assertAdmin(User $user): void
    {
        if (! $user->isAdmin()) {
            throw new BusinessRuleException('Tindakan ini hanya untuk administrator.', 403);
        }
    }
}
