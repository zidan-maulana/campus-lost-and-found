<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Mail\OtpCodeMail;
use App\Models\Claim;
use App\Models\ItemCategory;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Verifikasi API v1 terhadap docs/API_CONTRACT.md (kontrak front-end).
 *
 * Fokus: path, metode, envelope { success, message, data, meta }, serta
 * field wajib per modul — bukan mengulang aturan bisnis yang sudah diuji
 * di ClaimRulesTest / ModerationTest / HandoverFlowTest.
 */
class ApiV1ContractTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    private function foundReportPayload(ItemCategory $category, array $overrides = []): array
    {
        return array_merge([
            'type' => 'found',
            'title' => 'Tas Laptop Hitam Lenovo',
            'category_id' => $category->id,
            'campus' => 'meruya',
            'location' => 'Gedung Tower Lt. 1',
            'incident_date' => now()->subDay()->toDateString(),
            'description' => 'Tas ransel hitam berisi laptop dan buku catatan kuliah semester lima.',
            'proof_question' => 'Apa isi kantong depan tas?',
            'storage_location' => 'security_post',
            'security_post_id' => 1,
        ], $overrides);
    }

    // ---------------------------------------------------------------- auth

    public function test_login_mengembalikan_envelope_sukses(): void
    {
        $user = $this->verifiedUser(['email' => 'civitas@student.mercubuana.ac.id']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'civitas@student.mercubuana.ac.id',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Login berhasil.')
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.user.role', 'user')
            ->assertJsonStructure(['success', 'message', 'data' => ['user' => ['id', 'name', 'email', 'role', 'status']], 'meta' => ['timestamp', 'version']]);
    }

    public function test_login_password_salah_mengembalikan_envelope_error_422(): void
    {
        $this->verifiedUser(['email' => 'civitas@student.mercubuana.ac.id']);

        $this->postJson('/api/v1/auth/login', [
            'email' => 'civitas@student.mercubuana.ac.id',
            'password' => 'salah-total',
        ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 422)
            ->assertJsonStructure(['success', 'message', 'errors', 'code']);
    }

    public function test_endpoint_terproteksi_tanpa_login_mengembalikan_401_envelope(): void
    {
        $this->getJson('/api/v1/auth/me')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 401);
    }

    public function test_auth_me_mengembalikan_profil_dan_statistik(): void
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonStructure(['data' => ['user', 'stats' => ['reports_total', 'claims_active', 'unread_notifications']]]);
    }

    public function test_registrasi_email_non_kampus_ditolak(): void
    {
        $this->postJson('/api/v1/auth/register', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => 'PasswordRahasia123!',
            'password_confirmation' => 'PasswordRahasia123!',
        ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['email']]);
    }

    public function test_registrasi_email_kampus_berhasil(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@student.mercubuana.ac.id',
            'password' => 'PasswordRahasia123!',
            'password_confirmation' => 'PasswordRahasia123!',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'user');

        $this->assertDatabaseHas('users', ['email' => 'siti.nurhaliza@student.mercubuana.ac.id']);
    }

    // ------------------------------------------------------------- laporan

    public function test_katalog_laporan_memakai_envelope_paginasi(): void
    {
        Report::factory()->found()->count(3)->create();

        $this->getJson('/api/v1/reports')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [['id', 'title', 'status', 'campus']],
                'pagination' => ['current_page', 'per_page', 'total', 'last_page', 'has_next_page', 'has_prev_page'],
                'meta',
            ]);
    }

    public function test_katalog_default_hanya_menampilkan_status_open(): void
    {
        Report::factory()->found()->create();
        Report::factory()->found()->resolved()->create();

        $response = $this->getJson('/api/v1/reports')->assertOk();

        $this->assertSame(1, $response->json('pagination.total'));
    }

    public function test_buat_laporan_temuan_sesuai_kontrak(): void
    {
        $user = $this->verifiedUser();
        $category = ItemCategory::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/v1/reports', $this->foundReportPayload($category))
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'open')
            ->assertJsonPath('data.campus', 'meruya')
            ->assertJsonPath('data.storage_location', 'security_post')
            ->assertJsonPath('data.proof_question', 'Apa isi kantong depan tas?');

        $this->assertDatabaseHas('reports', [
            'id' => $response->json('data.id'),
            'campus_location' => 'meruya',
            'proof_question' => 'Apa isi kantong depan tas?',
        ]);
    }

    public function test_laporan_temuan_wajib_menyertakan_pertanyaan_rahasia(): void
    {
        $user = $this->verifiedUser();
        $category = ItemCategory::factory()->create();

        $payload = $this->foundReportPayload($category);
        unset($payload['proof_question']);

        $this->actingAs($user)
            ->postJson('/api/v1/reports', $payload)
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['proof_question']]);
    }

    public function test_pertanyaan_rahasia_tidak_bocor_ke_publik(): void
    {
        $report = Report::factory()->found()->create([
            'proof_question' => 'Apa isi kantong depan tas?',
        ]);

        $this->getJson('/api/v1/reports/'.$report->id)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonMissingPath('data.proof_question');
    }

    public function test_batal_laporan_milik_sendiri(): void
    {
        $user = $this->verifiedUser();
        $report = Report::factory()->lost()->ownedBy($user)->create();

        $this->actingAs($user)
            ->postJson('/api/v1/reports/'.$report->id.'/cancel', ['reason' => 'Barang sudah ditemukan sendiri.'])
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');
    }

    // --------------------------------------------------------------- klaim

    public function test_klaim_wajib_menyertakan_jawaban_bukti_dan_alasan(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $this->actingAs($claimant)
            ->postJson('/api/v1/reports/'.$report->id.'/claims', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => ['proof_answer', 'claim_reason']]);
    }

    public function test_alur_klaim_sampai_serah_terima(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create();

        $claimId = $this->actingAs($claimant)
            ->postJson('/api/v1/reports/'.$report->id.'/claims', [
                'proof_answer' => 'Ada kartu flazz BCA dan foto polaroid kecil.',
                'claim_reason' => 'Dompet saya jatuh di kantin saat makan siang.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'submitted')
            ->assertJsonPath('data.proof_answer', 'Ada kartu flazz BCA dan foto polaroid kecil.')
            ->json('data.id');

        $this->assertDatabaseHas('claims', [
            'id' => $claimId,
            'proof_answer' => 'Ada kartu flazz BCA dan foto polaroid kecil.',
        ]);

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claimId.'/accept')
            ->assertOk()
            ->assertJsonPath('data.status', 'in_handover');

        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'in_handover']);
    }

    public function test_tolak_klaim_wajib_menyertakan_alasan(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create();
        $claim = Claim::factory()->forReport($report)->by($claimant)->create();

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claim->id.'/reject', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => ['rejection_reason']]);

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claim->id.'/reject', [
                'rejection_reason' => 'Ciri barang yang disebutkan tidak sesuai fisik asli.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'rejected');

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => ClaimStatus::Rejected->value,
            'rejection_reason' => 'Ciri barang yang disebutkan tidak sesuai fisik asli.',
        ]);
    }

    public function test_batal_serah_terima_wajib_alasan_minimal_10_karakter(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create([
            'status' => ReportStatus::InHandover,
        ]);
        $claim = Claim::factory()->forReport($report)->by($claimant)->inHandover()->create();

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claim->id.'/handover/cancel', ['cancellation_reason' => 'pendek'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors' => ['cancellation_reason']]);

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claim->id.'/handover/cancel', [
                'cancellation_reason' => 'Pengklaim tidak hadir di pos satpam selama tiga hari.',
            ])
            ->assertOk();

        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'open']);
    }

    public function test_konfirmasi_serah_terima_dua_pihak_menutup_laporan(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create([
            'status' => ReportStatus::InHandover,
        ]);
        $claim = Claim::factory()->forReport($report)->by($claimant)->inHandover()->create();

        $this->actingAs($claimant)
            ->postJson('/api/v1/claims/'.$claim->id.'/handover/confirm')
            ->assertOk()
            ->assertJsonPath('data.completed', false);

        $this->actingAs($finder)
            ->postJson('/api/v1/claims/'.$claim->id.'/handover/confirm')
            ->assertOk()
            ->assertJsonPath('data.completed', true);

        $this->assertDatabaseHas('claims', ['id' => $claim->id, 'status' => 'completed']);
        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'resolved']);
    }

    // ------------------------------------------------------- chat & notifikasi

    public function test_riwayat_pesan_memakai_bentuk_kontrak(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create();
        $claim = Claim::factory()->forReport($report)->by($claimant)->create();

        $this->actingAs($claimant)
            ->postJson('/api/v1/claims/'.$claim->id.'/messages', ['message' => 'Barang sudah saya ambil di pos satpam.'])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->actingAs($finder)
            ->getJson('/api/v1/claims/'.$claim->id.'/messages')
            ->assertOk()
            ->assertJsonPath('data.claim_id', $claim->id)
            ->assertJsonPath('data.is_readonly', false)
            ->assertJsonStructure(['data' => ['claim_id', 'item_title', 'status', 'is_readonly', 'messages' => [['id', 'sender_id', 'sender_name', 'message', 'created_at', 'is_me']]]]);
    }

    public function test_daftar_percakapan_tersedia(): void
    {
        $finder = $this->verifiedUser();
        $claimant = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($finder)->create();
        Claim::factory()->forReport($report)->by($claimant)->create();

        $this->actingAs($claimant)
            ->getJson('/api/v1/chats')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => [['claim_id', 'item_title', 'status', 'unread_count']]]);
    }

    public function test_tandai_notifikasi_terbaca_memakai_patch(): void
    {
        $user = $this->verifiedUser();

        $notification = \App\Models\AppNotification::create([
            'user_id' => $user->id,
            'type' => 'report',
            'title' => 'Laporan Berhasil Dipublikasikan',
            'body' => 'Laporan Anda telah tayang.',
        ]);

        $this->actingAs($user)
            ->patchJson('/api/v1/notifications/'.$notification->id.'/read')
            ->assertOk()
            ->assertJsonPath('data.read', true);

        $this->assertNotNull($notification->refresh()->read_at);
    }

    // -------------------------------------------------------------- admin

    public function test_admin_overview_dan_penolakan_non_admin(): void
    {
        $admin = $this->verifiedUser(['role' => 'admin']);
        $user = $this->verifiedUser();

        $this->actingAs($admin)
            ->getJson('/api/v1/admin/overview')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total_reports', 'open_reports', 'resolved_reports', 'total_users', 'pending_moderations']]);

        $this->actingAs($user)
            ->getJson('/api/v1/admin/overview')
            ->assertStatus(403)
            ->assertJsonPath('success', false);
    }

    public function test_admin_moderasi_status_laporan_dan_akun(): void
    {
        $admin = $this->verifiedUser(['role' => 'admin']);
        $user = $this->verifiedUser();
        $report = Report::factory()->found()->create();

        $this->actingAs($admin)
            ->patchJson('/api/v1/admin/reports/'.$report->id.'/status', [
                'status' => 'hidden',
                'reason' => 'Mengandung data pribadi yang dilarang.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'hidden');

        $this->actingAs($admin)
            ->patchJson('/api/v1/admin/users/'.$user->id.'/status', [
                'status' => 'suspended',
                'reason' => 'Indikasi penipuan klaim berulang.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'suspended');
    }

    public function test_admin_kelola_kategori_dan_hapus_terblokir_saat_dipakai(): void
    {
        $admin = $this->verifiedUser(['role' => 'admin']);

        $categoryId = $this->actingAs($admin)
            ->postJson('/api/v1/admin/categories', [
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik mahasiswa',
            ])
            ->assertCreated()
            ->json('data.id');

        $this->actingAs($admin)
            ->putJson('/api/v1/admin/categories/'.$categoryId, ['name' => 'Elektronik & Gadget'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Elektronik & Gadget');

        Report::factory()->found()->create(['category_id' => $categoryId]);

        $this->actingAs($admin)
            ->deleteJson('/api/v1/admin/categories/'.$categoryId)
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseHas('item_categories', ['id' => $categoryId]);
    }

    // -------------------------------------------------- aktivitas & profil

    public function test_aktivitas_pengguna_dan_pembaruan_profil(): void
    {
        $user = $this->verifiedUser();
        Report::factory()->lost()->ownedBy($user)->create();

        $this->actingAs($user)
            ->getJson('/api/v1/user/activities')
            ->assertOk()
            ->assertJsonPath('data.summary.reports_total', 1)
            ->assertJsonStructure(['data' => ['summary', 'reports', 'claims']]);

        $this->actingAs($user)
            ->putJson('/api/v1/user/profile', ['name' => 'Nama Baru Civitas'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nama Baru Civitas');

        $this->actingAs($user)
            ->putJson('/api/v1/user/settings', ['preferences' => ['email_notification' => false]])
            ->assertOk()
            ->assertJsonPath('data.preferences.email_notification', false);
    }

    // ------------------------------------------------------------ OTP email

    public function test_kode_otp_verifikasi_email_kampus(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/verify-otp')->assertOk();
        Mail::assertSent(OtpCodeMail::class);

        $code = $user->refresh()->issueEmailOtp();

        $this->actingAs($user)
            ->post('/verify-otp', ['otp' => '000000'])
            ->assertSessionHasErrors('otp');

        $this->actingAs($user)
            ->post('/verify-otp', ['otp' => $code])
            ->assertRedirect(route('home'));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_kirim_ulang_kode_otp(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post('/verify-otp/resend')
            ->assertRedirect();

        Mail::assertSent(OtpCodeMail::class);
    }
}
