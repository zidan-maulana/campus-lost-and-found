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
}
