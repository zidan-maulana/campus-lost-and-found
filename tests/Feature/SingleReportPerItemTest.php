<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use App\Services\ClaimService;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * DEC-027 — satu barang = satu laporan aktif.
 *
 *  - laporan temuan yang dibuat dari laporan hilang menutup laporan hilangnya;
 *  - laporan yang sudah selesai tidak tampil lagi di katalog;
 *  - laporan temuan ganda ditolak;
 *  - klaim memakai pertanyaan verifikasi + foto bukti (PR-04).
 */
class SingleReportPerItemTest extends TestCase
{
    use RefreshDatabase;

    private ReportService $reports;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reports = app(ReportService::class);
    }

    public function test_laporan_temuan_dari_laporan_hilang_menutup_laporan_hilangnya(): void
    {
        $owner = User::factory()->create();
        $finder = User::factory()->create();
        $lost = Report::factory()->lost()->ownedBy($owner)->create(['title' => 'Dompet Hitam']);

        $found = $this->reports->create($finder, [
            'type' => 'found',
            'title' => 'Dompet Hitam',
            'reference_report_id' => $lost->id,
        ]);

        $this->assertSame($lost->id, $found->reference_report_id);
        $this->assertSame(ReportStatus::Resolved, $lost->fresh()->status);
        $this->assertSame('Sudah Ketemu', $lost->fresh()->statusLabel());
    }

    public function test_laporan_hilang_yang_sudah_ketemu_tidak_tampil_di_katalog(): void
    {
        $lost = Report::factory()->lost()->create(['title' => 'Kunci Motor Biru']);

        $this->getJson('/api/reports?type=lost')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        $lost->update(['status' => ReportStatus::Resolved]);

        $this->getJson('/api/reports?type=lost')
            ->assertOk()
            ->assertJsonPath('meta.total', 0);
    }

    public function test_laporan_temuan_yang_selesai_tidak_tampil_di_katalog(): void
    {
        Report::factory()->found()->resolved()->create(['title' => 'Tas Kulit']);

        $this->getJson('/api/reports?type=found')
            ->assertOk()
            ->assertJsonPath('meta.total', 0);
    }

    public function test_laporan_selesai_masih_bisa_dibuka_lewat_tautan_langsung(): void
    {
        $report = Report::factory()->found()->resolved()->create();

        $this->getJson('/api/reports/'.$report->id)->assertOk();
    }

    public function test_membatalkan_laporan_temuan_membuka_kembali_laporan_hilang(): void
    {
        $owner = User::factory()->create();
        $finder = User::factory()->create();
        $lost = Report::factory()->lost()->ownedBy($owner)->create(['title' => 'Payung Lipat']);

        $found = $this->reports->create($finder, [
            'type' => 'found',
            'title' => 'Payung Lipat',
            'reference_report_id' => $lost->id,
        ]);

        $this->assertSame(ReportStatus::Resolved, $lost->fresh()->status);

        $this->reports->cancel($found->fresh(), $finder);

        $this->assertSame(ReportStatus::Open, $lost->fresh()->status);
    }

    public function test_laporan_temuan_ganda_untuk_barang_sama_ditolak(): void
    {
        $finder = User::factory()->create();

        $this->reports->create($finder, ['type' => 'found', 'title' => 'Botol Tumbler Merah']);

        $this->expectException(\App\Exceptions\BusinessRuleException::class);
        $this->reports->create($finder, ['type' => 'found', 'title' => 'botol tumbler merah']);
    }

    public function test_klaim_wajib_menjawab_pertanyaan_verifikasi(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create([
            'proof_question' => 'Apa isi dompetnya selain uang?',
        ]);

        $this->actingAs($claimant)
            ->postJson('/api/reports/'.$report->id.'/claims', ['claim_reason' => 'Ini dompet saya, ada KTM di dalamnya.'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('proof_answer');
    }

    public function test_klaim_menyimpan_jawaban_dan_foto_bukti(): void
    {
        Storage::fake('public');

        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $report = Report::factory()->found()->ownedBy($finder)->create([
            'proof_question' => 'Apa isi dompetnya selain uang?',
        ]);

        $response = $this->actingAs($claimant)
            ->post('/api/reports/'.$report->id.'/claims', [
                'claim_reason' => 'Ini dompet saya, ada KTM atas nama saya di dalamnya.',
                'proof_answer' => 'Ada KTM dan kartu ATM BCA.',
                'proof_attachment' => UploadedFile::fake()->image('bukti.jpg'),
            ], ['Accept' => 'application/json']);

        $response->assertCreated();

        $stored = \App\Models\Claim::query()->where('report_id', $report->id)->firstOrFail();
        $this->assertSame('Ada KTM dan kartu ATM BCA.', $stored->proof_answer);
        $this->assertNotNull($stored->proof_attachment);
    }

    public function test_bukti_klaim_tidak_bocor_ke_pengguna_lain(): void
    {
        $finder = User::factory()->create();
        $claimant = User::factory()->create();
        $outsider = User::factory()->create();

        $report = Report::factory()->found()->ownedBy($finder)->create();

        app(ClaimService::class)->submit($claimant, $report, [
            'proof_answer' => 'Rahasia hanya untuk penemu.',
        ]);

        $claim = \App\Models\Claim::query()->where('report_id', $report->id)->firstOrFail();

        $this->assertSame('Rahasia hanya untuk penemu.', $claim->toUiArray($finder)['proof_answer']);
        $this->assertNull($claim->toUiArray($outsider)['proof_answer']);
        $this->assertNull($claim->toUiArray(null)['proof_answer']);
    }

    /**
     * Alur penuh lewat HTTP: temuan terhubung → laporan hilang tertutup →
     * klaim dengan bukti → penemu setujui → serah terima dua arah → selesai.
     */
    public function test_alur_lengkap_dari_laporan_hilang_sampai_serah_terima(): void
    {
        $owner = User::factory()->create();
        $finder = User::factory()->create();

        $lost = Report::factory()->lost()->ownedBy($owner)->create(['title' => 'Dompet Kulit Coklat']);

        // 1. Penemu membuat laporan temuan yang terhubung ke laporan hilang.
        $this->actingAs($finder)
            ->postJson('/api/reports', [
                'type' => 'found',
                'title' => 'Dompet Kulit Coklat',
                'proof_question' => 'Apa isi dompetnya?',
                'reference_report_id' => $lost->id,
            ])
            ->assertCreated();

        $this->assertSame(ReportStatus::Resolved, $lost->fresh()->status);
        $this->assertSame('Sudah Ketemu', $lost->fresh()->statusLabel());

        $found = Report::query()->where('type', 'found')->where('user_id', $finder->id)->firstOrFail();
        $this->assertSame($lost->id, $found->reference_report_id);

        // 2. Laporan hilang sudah tidak tampil di katalog.
        $this->getJson('/api/reports?type=lost')->assertJsonPath('meta.total', 0);

        // 3. Pengklaim mengajukan klaim dengan jawaban verifikasi.
        $this->actingAs($owner)
            ->postJson('/api/reports/'.$found->id.'/claims', [
                'claim_reason' => 'Dompet saya, ada KTM dan kartu ATM di dalamnya.',
                'proof_answer' => 'Ada KTM dan kartu ATM BCA.',
            ])
            ->assertCreated();

        $claimId = \App\Models\Claim::query()->where('report_id', $found->id)->value('id');

        // 4. Penemu menyetujui → masuk tahap serah terima.
        $this->actingAs($finder)
            ->postJson('/api/claims/'.$claimId.'/select')
            ->assertOk()
            ->assertJsonPath('data.status', 'in_handover');

        // 5. Kedua pihak konfirmasi → klaim selesai, laporan temuan resolved.
        $this->actingAs($owner)->postJson('/api/claims/'.$claimId.'/confirm-handover')
            ->assertOk()->assertJsonPath('completed', false);

        $this->actingAs($finder)->postJson('/api/claims/'.$claimId.'/confirm-handover')
            ->assertOk()->assertJsonPath('completed', true);

        $this->assertSame('completed', \App\Models\Claim::query()->find($claimId)->status->value);
        $this->assertSame(ReportStatus::Resolved, $found->fresh()->status);

        // 6. Laporan temuan yang selesai juga hilang dari katalog.
        $this->getJson('/api/reports?type=found')->assertJsonPath('meta.total', 0);
    }

    public function test_form_laporan_temuan_menampilkan_panel_hubungkan_dan_pertanyaan_verifikasi(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/report/found');

        $response->assertOk();
        $response->assertSee('Barang ini dari laporan kehilangan?', false);
        $response->assertSee('input-proof-question', false);
        $response->assertSee('searchLostReports', false);
    }

    public function test_pemilik_dapat_menutup_laporan_hilang_yang_barangnya_sudah_ketemu(): void
    {
        $owner = User::factory()->create();
        $lost = Report::factory()->lost()->ownedBy($owner)->create(['title' => 'Tumbler Stanley']);

        $this->actingAs($owner)
            ->postJson('/api/reports/'.$lost->id.'/resolve')
            ->assertOk()
            ->assertJsonPath('data.status', 'resolved')
            ->assertJsonPath('data.status_label', 'Sudah Ketemu');

        // Hilang dari katalog, tetap ada di "Laporan Saya".
        $this->getJson('/api/reports?type=lost')->assertJsonPath('meta.total', 0);
        $this->getJson('/api/my-reports')->assertJsonPath('data.0.status', 'resolved');
    }

    public function test_laporan_temuan_tidak_bisa_ditandai_selesai_langsung(): void
    {
        $owner = User::factory()->create();
        $found = Report::factory()->found()->ownedBy($owner)->create();

        $this->actingAs($owner)
            ->postJson('/api/reports/'.$found->id.'/resolve')
            ->assertStatus(422);
    }

    public function test_orang_lain_tidak_bisa_menutup_laporan(): void
    {
        $lost = Report::factory()->lost()->create();

        $this->actingAs(User::factory()->create())
            ->postJson('/api/reports/'.$lost->id.'/resolve')
            ->assertStatus(403);
    }

    public function test_menutup_laporan_dua_kali_ditolak(): void
    {
        $owner = User::factory()->create();
        $lost = Report::factory()->lost()->ownedBy($owner)->create();

        $this->actingAs($owner)->postJson('/api/reports/'.$lost->id.'/resolve')->assertOk();
        $this->actingAs($owner)->postJson('/api/reports/'.$lost->id.'/resolve')->assertStatus(422);
    }

    public function test_halaman_detail_menampilkan_modal_klaim_dengan_pertanyaan_verifikasi(): void
    {
        $report = Report::factory()->found()->create([
            'proof_question' => 'Apa warna gantungannya?',
        ]);

        $response = $this->actingAs(User::factory()->create())->get('/report/'.$report->id);

        $response->assertOk();
        $response->assertSee('modal-claim-report', false);
        $response->assertSee('Apa warna gantungannya?', false);
        $response->assertSee('submitClaimModal', false);
    }
}
