<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Claim;
use App\Models\Report;
use App\Models\User;
use App\Services\ClaimService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Aturan bisnis klaim (PR-04, FR-06/FR-07, BR-03/BR-05/BR-11, DEC-024).
 */
class ClaimRulesTest extends TestCase
{
    use RefreshDatabase;

    private ClaimService $claims;

    protected function setUp(): void
    {
        parent::setUp();
        $this->claims = app(ClaimService::class);
    }

    public function test_pengguna_tidak_bisa_mengklaim_barang_temuannya_sendiri(): void
    {
        $finder = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $this->expectException(BusinessRuleException::class);
        $this->claims->submit($finder, $report);
    }

    public function test_klaim_hanya_untuk_laporan_barang_temuan(): void
    {
        $claimant = User::factory()->create();
        $lost = Report::factory()->lost()->create();

        $this->expectException(BusinessRuleException::class);
        $this->claims->submit($claimant, $lost);
    }

    public function test_satu_pengguna_hanya_boleh_satu_klaim_aktif(): void
    {
        $claimant = User::factory()->create();
        $first = Report::factory()->found()->create();
        $second = Report::factory()->found()->create();

        $this->claims->submit($claimant, $first);

        $this->expectException(BusinessRuleException::class);
        $this->claims->submit($claimant, $second);
    }

    public function test_klaim_terminal_membebaskan_pengguna_untuk_klaim_baru(): void
    {
        $claimant = User::factory()->create();
        $first = Report::factory()->found()->create();
        $second = Report::factory()->found()->create();

        $claim = $this->claims->submit($claimant, $first);
        $this->claims->reject($claim, $first->user, 'Tidak cocok');

        $newClaim = $this->claims->submit($claimant, $second);

        $this->assertSame(ClaimStatus::Submitted, $newClaim->status);
    }

    public function test_klaim_ditolak_tidak_boleh_diulang_pada_laporan_yang_sama(): void
    {
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->create();

        $claim = $this->claims->submit($claimant, $report);
        $this->claims->reject($claim, $report->user, 'Ciri tidak sesuai');

        $this->expectException(BusinessRuleException::class);
        $this->claims->submit($claimant, $report);
    }

    public function test_klaim_kedaluwarsa_boleh_diulang_pada_laporan_yang_sama(): void
    {
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->create();

        $claim = Claim::factory()->forReport($report)->by($claimant)->submittedHoursAgo(121)->create();
        $this->claims->expireStale();

        $this->assertSame(ClaimStatus::Expired, $claim->fresh()->status);

        $reclaim = $this->claims->submit($claimant, $report->fresh());
        $this->assertSame(ClaimStatus::Submitted, $reclaim->status);
    }

    public function test_pengingat_dikirim_pada_72_jam(): void
    {
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->create();

        $claim = Claim::factory()->forReport($report)->by($claimant)->submittedHoursAgo(73)->create();

        $sent = $this->claims->sendReminders();

        $this->assertSame(1, $sent);
        $this->assertNotNull($claim->fresh()->response_notified_at);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $claimant->id,
            'type' => 'claim_reminder',
        ]);
    }

    public function test_klaim_kedaluwarsa_setelah_120_jam(): void
    {
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->create();

        $claim = Claim::factory()->forReport($report)->by($claimant)->submittedHoursAgo(121)->create();

        $count = $this->claims->expireStale();

        $this->assertSame(1, $count);
        $this->assertSame(ClaimStatus::Expired, $claim->fresh()->status);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $claimant->id,
            'type' => 'claim_expired',
        ]);
    }

    public function test_klaim_yang_sudah_direspons_tidak_kedaluwarsa(): void
    {
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->create();

        $claim = Claim::factory()->forReport($report)->by($claimant)->inHandover()->submittedHoursAgo(200)->create();

        $this->claims->expireStale();

        $this->assertSame(ClaimStatus::InHandover, $claim->fresh()->status);
    }

    public function test_penemu_dapat_memilih_satu_klaim_untuk_serah_terima(): void
    {
        $finder = User::factory()->create();
        $claimantA = User::factory()->create();
        $claimantB = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $first = $this->claims->submit($claimantA, $report);
        $second = $this->claims->submit($claimantB, $report);

        $selected = $this->claims->selectForHandover($first, $finder);

        $this->assertSame(ClaimStatus::InHandover, $selected->status);
        $this->assertSame(ReportStatus::InHandover, $report->fresh()->status);
        // Klaim lain tetap mengantre (DEC-025).
        $this->assertSame(ClaimStatus::Submitted, $second->fresh()->status);
    }

    public function test_klaim_lain_tidak_bisa_dipilih_saat_serah_terima_berjalan(): void
    {
        $finder = User::factory()->create();
        $claimantA = User::factory()->create();
        $claimantB = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $first = $this->claims->submit($claimantA, $report);
        $second = $this->claims->submit($claimantB, $report);

        $this->claims->selectForHandover($first, $finder);

        $this->expectException(BusinessRuleException::class);
        $this->claims->selectForHandover($second, $finder);
    }

    public function test_pembatalan_serah_terima_oleh_penemu_membuka_laporan(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claim = $this->claims->submit($claimant, $report);
        $this->claims->selectForHandover($claim, $finder);

        $cancelled = $this->claims->cancelByFinder($claim->fresh(), $finder, 'Barang tidak jadi diserahkan');

        $this->assertSame(ClaimStatus::Cancelled, $cancelled->status);
        $this->assertSame($finder->id, $cancelled->cancelled_by);
        $this->assertSame(ReportStatus::Open, $report->fresh()->status);
    }

    public function test_pembatalan_serah_terima_wajib_menyertakan_alasan(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claim = $this->claims->submit($claimant, $report);
        $this->claims->selectForHandover($claim, $finder);

        $this->expectException(BusinessRuleException::class);
        $this->claims->cancelByFinder($claim->fresh(), $finder, '');
    }
}
