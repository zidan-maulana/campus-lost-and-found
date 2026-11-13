<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use App\Services\ChatService;
use App\Services\ClaimService;
use App\Services\HandoverService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Serah terima dua arah & chat privat (PR-06, FR-08/FR-09, BR-07/BR-08).
 */
class HandoverFlowTest extends TestCase
{
    use RefreshDatabase;

    private function scenario(): array
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claimService = app(ClaimService::class);
        $claim = $claimService->submit($claimant, $report);
        $claim = $claimService->selectForHandover($claim, $finder);

        return [$finder, $claimant, $report, $claim];
    }

    public function test_serah_terima_butuh_konfirmasi_kedua_pihak(): void
    {
        [$finder, $claimant, $report, $claim] = $this->scenario();
        $handover = app(HandoverService::class);

        $first = $handover->confirm($claim, $claimant);
        $this->assertFalse($first['completed']);
        $this->assertSame(ClaimStatus::InHandover, $claim->fresh()->status);

        $second = $handover->confirm($claim->fresh(), $finder);
        $this->assertTrue($second['completed']);
        $this->assertSame(ClaimStatus::Completed, $claim->fresh()->status);
        $this->assertSame(ReportStatus::Resolved, $report->fresh()->status);
    }

    public function test_konfirmasi_ganda_oleh_pihak_yang_sama_diabaikan(): void
    {
        [$finder, $claimant, , $claim] = $this->scenario();
        $handover = app(HandoverService::class);

        $handover->confirm($claim, $claimant);
        $again = $handover->confirm($claim->fresh(), $claimant);

        $this->assertFalse($again['completed']);
        $this->assertSame(1, $claim->confirmations()->count());
    }

    public function test_orang_luar_tidak_bisa_mengonfirmasi(): void
    {
        [, , , $claim] = $this->scenario();
        $outsider = User::factory()->create();

        $this->expectException(BusinessRuleException::class);
        app(HandoverService::class)->confirm($claim, $outsider);
    }

    public function test_klaim_mengantre_dibatalkan_saat_serah_terima_selesai(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $queuedClaimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claims = app(ClaimService::class);

        // Dua klaim masuk saat laporan masih open, lalu satu dipilih.
        $selected = $claims->submit($claimant, $report);
        $queued = $claims->submit($queuedClaimant, $report->fresh());
        $claims->selectForHandover($selected, $finder);

        $handover = app(HandoverService::class);
        $handover->confirm($selected, $claimant);
        $handover->confirm($selected->fresh(), $finder);

        $this->assertSame(ClaimStatus::Cancelled, $queued->fresh()->status);
    }

    public function test_chat_hanya_untuk_peserta(): void
    {
        [$finder, $claimant, , $claim] = $this->scenario();
        $chat = app(ChatService::class);
        $outsider = User::factory()->create();

        $chat->send($claim, $claimant, 'Halo, saya pengklaim.');

        $this->expectException(BusinessRuleException::class);
        $chat->send($claim, $outsider, 'Menyusup');
    }

    public function test_chat_terkunci_setelah_klaim_terminal(): void
    {
        [$finder, $claimant, , $claim] = $this->scenario();
        $handover = app(HandoverService::class);

        $handover->confirm($claim, $claimant);
        $handover->confirm($claim->fresh(), $finder);

        $this->expectException(BusinessRuleException::class);
        app(ChatService::class)->send($claim->fresh(), $claimant, 'Pesan setelah selesai');
    }

    public function test_pesan_dihapus_setelah_masa_retensi(): void
    {
        [, $claimant, , $claim] = $this->scenario();
        $chat = app(ChatService::class);

        $message = $chat->send($claim, $claimant, 'Pesan lama');

        // Tandai klaim selesai 31 hari lalu.
        $claim->forceFill([
            'status' => ClaimStatus::Completed,
            'resolved_at' => now()->subDays(31),
        ])->save();

        $deleted = $chat->purgeExpired();

        $this->assertSame(1, $deleted);
        $this->assertDatabaseMissing('chat_messages', ['id' => $message->id]);
    }

    public function test_pesan_yang_ditahan_tidak_dihapus(): void
    {
        [, $claimant, , $claim] = $this->scenario();
        $chat = app(ChatService::class);

        $message = $chat->send($claim, $claimant, 'Bukti untuk moderasi');
        $message->update(['is_retained' => true]);

        $claim->forceFill([
            'status' => ClaimStatus::Completed,
            'resolved_at' => now()->subDays(31),
        ])->save();

        $chat->purgeExpired();

        $this->assertDatabaseHas('chat_messages', ['id' => $message->id]);
    }
}
