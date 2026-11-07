<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Enums\UserStatus;
use App\Models\Report;
use App\Models\User;
use App\Services\ClaimService;
use App\Services\ModerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Moderasi & penonaktifan akun per peran (PR-08, FR-12, DEC-026).
 */
class ModerationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_suspend_pengklaim_membatalkan_klaim_dan_membuka_laporan(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claims = app(ClaimService::class);
        $claim = $claims->submit($claimant, $report);
        $claims->selectForHandover($claim, $finder);

        app(ModerationService::class)->suspendUser($this->admin(), $claimant, 'Pelanggaran');

        $this->assertSame(ClaimStatus::Cancelled, $claim->fresh()->status);
        $this->assertSame(ReportStatus::Open, $report->fresh()->status);
        $this->assertSame(UserStatus::Suspended, $claimant->fresh()->status);
    }

    public function test_suspend_penemu_menyembunyikan_laporan_dan_membatalkan_klaim(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claim = app(ClaimService::class)->submit($claimant, $report);

        app(ModerationService::class)->suspendUser($this->admin(), $finder, 'Penipuan berulang');

        $this->assertSame(ReportStatus::Hidden, $report->fresh()->status);
        $this->assertSame(ClaimStatus::Cancelled, $claim->fresh()->status);
        // Pengklaim tidak dikenai sanksi.
        $this->assertSame(UserStatus::Active, $claimant->fresh()->status);
        $this->assertFalse($claimant->fresh()->hasActiveClaim());
    }

    public function test_reaktivasi_tidak_menghidupkan_laporan_hidden(): void
    {
        $finder = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create();
        $admin = $this->admin();
        $moderation = app(ModerationService::class);

        $moderation->suspendUser($admin, $finder, 'Pelanggaran');
        $moderation->reactivateUser($admin, $finder, 'Banding diterima');

        $this->assertSame(UserStatus::Active, $finder->fresh()->status);
        $this->assertSame(ReportStatus::Hidden, $report->fresh()->status);
    }

    public function test_tindakan_moderasi_tercatat_dengan_alasan(): void
    {
        $user = User::factory()->create();
        $admin = $this->admin();

        app(ModerationService::class)->warn($admin, $user, 'Unggahan tidak pantas');

        $this->assertDatabaseHas('moderation_actions', [
            'admin_id' => $admin->id,
            'target_user_id' => $user->id,
            'action_type' => 'warning',
            'reason' => 'Unggahan tidak pantas',
        ]);
    }

    public function test_admin_tidak_bisa_menonaktifkan_akun_sendiri(): void
    {
        $admin = $this->admin();

        $this->expectException(\App\Exceptions\BusinessRuleException::class);
        app(ModerationService::class)->suspendUser($admin, $admin, 'Tes');
    }

    public function test_pengguna_biasa_tidak_bisa_membuka_dashboard_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin')->assertNotFound();
    }

    public function test_admin_dapat_membuka_dashboard(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
    }

    public function test_laporan_hidden_tidak_tampil_di_katalog_publik(): void
    {
        $hidden = Report::factory()->found()->hidden()->create();
        $visible = Report::factory()->found()->create();

        $response = $this->get('/api/reports');

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');

        $this->assertFalse($ids->contains((string) $hidden->id));
        $this->assertTrue($ids->contains((string) $visible->id));
    }
}
