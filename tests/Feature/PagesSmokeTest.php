<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AbuseReport;
use App\Models\Claim;
use App\Models\ItemCategory;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Smoke test seluruh halaman: memastikan setiap view tetap dapat dirender
 * dengan data dari database (UI tidak diubah).
 */
class PagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(): User
    {
        return User::factory()->create([
            'role' => UserRole::User,
            'email_verified_at' => now(),
        ]);
    }

    public function test_halaman_publik_dapat_dirender(): void
    {
        $category = ItemCategory::factory()->create(['slug' => 'elektronik']);
        Report::factory()->found()->create(['category_id' => $category->id]);

        $this->get('/')->assertOk()->assertSee('FindUMB', false);
        $this->get('/search')->assertOk();
        $this->get('/search?q=dompet&type=found')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_halaman_detail_laporan_dapat_dirender(): void
    {
        $report = Report::factory()->lost()->create();

        // Judul diisi oleh jembatan API di sisi klien, jadi yang diperiksa adalah
        // ketersediaan halaman beserta titik-titik isian yang dipakai jembatan.
        $this->get('/report/'.$report->id)
            ->assertOk()
            ->assertSee('id="detail-title"', false)
            ->assertSee('id="detail-hero-img"', false)
            ->assertSee('id="detail-features"', false);
    }

    public function test_halaman_terproteksi_dapat_dirender_setelah_login(): void
    {
        $user = $this->verifiedUser();
        $report = Report::factory()->found()->ownedBy($user)->create();
        Claim::factory()->forReport($report)->create();

        $this->actingAs($user);

        $this->get('/my-reports')->assertOk();
        $this->get('/saved-reports')->assertOk();
        $this->get('/notifications')->assertOk();
        $this->get('/messages')->assertOk();
        $this->get('/profile')->assertOk();
        $this->get('/settings')->assertOk();
        $this->get('/report/lost')->assertOk();
        $this->get('/report/found')->assertOk();
    }

    public function test_jembatan_api_dimuat_di_layout(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('js/findumb-api.js', false)
            ->assertSee('FindUMB_CSRF', false);
    }

    public function test_api_mengembalikan_bentuk_data_yang_dipakai_ui(): void
    {
        $category = ItemCategory::factory()->create(['slug' => 'tas', 'name' => 'Tas & Ransel']);
        $report = Report::factory()->found()->create([
            'category_id' => $category->id,
            'title' => 'Ransel Hitam',
        ]);

        $response = $this->getJson('/api/reports')->assertOk();

        $row = collect($response->json('data'))->firstWhere('id', (string) $report->id);

        $this->assertNotNull($row);

        // Kunci yang dipakai view lama (kontrak localStorage) harus tetap ada.
        foreach ([
            'id', 'type', 'title', 'brand', 'category', 'category_slug', 'category_label',
            'location_campus', 'location_specific', 'location_full', 'date', 'date_display',
            'time', 'storage', 'features', 'image', 'status', 'is_mine',
        ] as $key) {
            $this->assertArrayHasKey($key, $row, "Kunci '{$key}' hilang dari respons API.");
        }
    }

    /**
     * Dashboard admin harus membaca database, bukan data contoh di view.
     */
    public function test_dashboard_admin_memakai_data_database(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $report = Report::factory()->found()->create(['title' => 'Jaket Hoodie Uji Coba']);
        AbuseReport::create([
            'reporter_id' => $report->user_id,
            'target_type' => 'report',
            'target_id' => $report->id,
            'reason' => 'fake_report',
            'details' => 'Laporan uji coba dashboard.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin')->assertOk();

        $response->assertSee('Jaket Hoodie Uji Coba', false);
        $response->assertSee('Laporan uji coba dashboard.', false);

        // Tanda-tanda data contoh lama tidak boleh muncul lagi.
        $response->assertDontSee('findumb_admin_reports', false);
        $response->assertDontSee('DEFAULT_REPORTS_CATALOG', false);
        $response->assertDontSee('iPhone 15 Pro Max Deep Blue', false);
    }

    /**
     * Halaman detail laporan admin harus bisa dibuka (dulu view-nya tidak ada).
     */
    public function test_halaman_detail_laporan_admin_dapat_dirender(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $report = Report::factory()->found()->create(['title' => 'Dompet Kulit Cokelat']);

        $this->actingAs($admin)
            ->get('/admin/reports/'.$report->id)
            ->assertOk()
            ->assertSee('Dompet Kulit Cokelat', false)
            ->assertSee('Detail Barang', false)
            ->assertSee('Tindakan Moderasi', false);
    }

    /**
     * Aksi moderasi dari dashboard harus tersimpan di server, bukan hanya di
     * state browser: status laporan, status akun, dan log audit.
     */
    public function test_aksi_moderasi_dashboard_tersimpan_ke_server(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);
        $pelapor = User::factory()->create(['status' => 'active']);
        $report = Report::factory()->found()->ownedBy($pelapor)->create();

        // Sembunyikan laporan lewat endpoint yang dipanggil dashboard.
        $this->actingAs($admin)
            ->patchJson('/api/v1/admin/reports/'.$report->id.'/status', [
                'status' => 'hidden',
                'reason' => 'Laporan memuat foto dari internet.',
            ])
            ->assertOk();

        $this->assertSame('hidden', $report->refresh()->status->value);
        $this->assertDatabaseHas('moderation_actions', [
            'action_type' => 'hide_report',
            'admin_id' => $admin->id,
        ]);

        // Tangguhkan akun pelapor.
        $this->actingAs($admin)
            ->patchJson('/api/v1/admin/users/'.$pelapor->id.'/status', [
                'status' => 'suspended',
                'reason' => 'Klaim fiktif berulang.',
            ])
            ->assertOk();

        $this->assertSame('suspended', $pelapor->refresh()->status->value);
        $this->assertDatabaseHas('moderation_actions', [
            'action_type' => 'suspend_user',
            'target_user_id' => $pelapor->id,
        ]);
    }

    /**
     * Administrator baru yang dibuat dari dashboard langsung bisa dipakai login.
     */
    public function test_admin_baru_dari_dashboard_langsung_aktif_dan_terverifikasi(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'Petugas Biro',
                'email' => 'petugas.biro@mercubuana.ac.id',
                'unit' => 'Biro Kemahasiswaan',
                'password' => 'rahasia123',
            ])
            ->assertRedirect();

        $baru = User::where('email', 'petugas.biro@mercubuana.ac.id')->first();

        $this->assertNotNull($baru);
        $this->assertSame('admin', $baru->role->value);
        $this->assertSame('active', $baru->status->value);
        $this->assertNotNull($baru->email_verified_at);
    }

    /**
     * Angka di tab Overview harus berasal dari database, bukan angka contoh
     * yang ditulis langsung di view (mis. "+94", "78.3%", "30 Total").
     */
    public function test_angka_overview_admin_dihitung_dari_database(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        Report::factory()->found()->count(3)->create(['status' => 'open']);
        Report::factory()->lost()->count(2)->create(['status' => 'open']);
        Report::factory()->found()->count(1)->create(['status' => 'resolved']);

        $response = $this->actingAs($admin)->get('/admin')->assertOk();

        $html = $response->getContent();

        // 6 laporan total, 5 open, 1 resolved.
        $this->assertMatchesRegularExpression('/id="stat-active-reports">5</', $html);
        $this->assertMatchesRegularExpression('/id="stat-resolved-reports">1</', $html);
        $this->assertMatchesRegularExpression('/>6 Total</', $html);
        $this->assertMatchesRegularExpression('/id="pill-count-all">6</', $html);
        $this->assertMatchesRegularExpression('/id="reports-kpi-total">6</', $html);

        // Sisa angka karangan tidak boleh muncul lagi.
        foreach (['+ 94', '78.3%', '30 Total', '2 Urgent', '2.4 Hari', '3.1%', '123 Laporan', '348 Laporan'] as $fake) {
            $this->assertStringNotContainsString($fake, $html, "Angka contoh '{$fake}' masih muncul di dashboard.");
        }
    }

    /**
     * Grafik tren memakai satu titik per tanggal, bukan blok beberapa hari.
     *
     * Dulu 30d memakai blok 3 hari sehingga label pertamanya 05 Nov dan
     * 90d memakai blok 8 hari sehingga label pertamanya 01 Nov — laporan
     * tanggal 7 Nov muncul di titik yang berbeda-beda tergantung rentang.
     */
    public function test_grafik_tren_memakai_titik_harian_yang_unik(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        // Satu laporan di hari yang sama, 3 hari lalu.
        Report::factory()->found()->create(['created_at' => now()->subDays(3)]);

        $html = $this->actingAs($admin)->get('/admin')->assertOk()->getContent();

        preg_match('/const CHART_CONFIG = (\{.*?\});/s', $html, $m);
        $this->assertNotEmpty($m, 'CHART_CONFIG tidak ditemukan di halaman.');

        $config = json_decode($m[1], true);

        foreach (['7d' => 7, '30d' => 30, '90d' => 90] as $range => $expectedDays) {
            $series = $config[$range];

            $this->assertCount($expectedDays, $series['labels'], "Rentang {$range} harus punya {$expectedDays} titik harian.");
            $this->assertCount($expectedDays, $series['temuan'], "Deret temuan {$range} tidak sepanjang labelnya.");
            $this->assertCount($expectedDays, $series['hilang'], "Deret hilang {$range} tidak sepanjang labelnya.");

            // Tidak boleh ada label tanggal yang dobel.
            $this->assertSame(
                count($series['labels']),
                count(array_unique($series['labels'])),
                "Rentang {$range} punya label tanggal duplikat."
            );

            // Titik terakhir selalu hari ini.
            $this->assertSame(now()->translatedFormat('d M'), end($series['labels']), "Titik terakhir {$range} bukan hari ini.");

            // keyPoints harus menunjuk indeks yang ada.
            foreach ($series['keyPoints'] as $point) {
                $this->assertLessThan($expectedDays, $point, "keyPoints {$range} menunjuk di luar jangkauan.");
            }
        }

        // Laporan 3 hari lalu harus muncul di titik yang sama pada semua rentang.
        foreach (['7d', '30d', '90d'] as $range) {
            $label = now()->subDays(3)->translatedFormat('d M');
            $index = array_search($label, $config[$range]['labels'], true);

            $this->assertNotFalse($index, "Label {$label} tidak ada di rentang {$range}.");
            $this->assertSame(1, $config[$range]['temuan'][$index] + $config[$range]['hilang'][$index],
                "Laporan 3 hari lalu tidak muncul di titik {$label} pada rentang {$range}.");
        }
    }
}
