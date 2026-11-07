<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Enums\UserStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Autentikasi, akses publik, dan otorisasi laporan (PR-01, PR-02, DEC-020).
 */
class AuthAndReportAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengunjung_dapat_melihat_katalog_dan_pencarian(): void
    {
        Report::factory()->found()->create();

        $this->get('/')->assertOk();
        $this->get('/search')->assertOk();
        $this->get('/api/reports')->assertOk();
    }

    public function test_pengunjung_tidak_dapat_membuka_halaman_terproteksi(): void
    {
        $this->get('/my-reports')->assertRedirect('/login');
        $this->get('/messages')->assertRedirect('/login');
        $this->get('/report/lost')->assertRedirect('/login');
    }

    public function test_registrasi_menolak_email_di_luar_domain_kampus(): void
    {
        config(['findumb.campus_email_domains' => ['mercubuana.ac.id']]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'someone@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'someone@gmail.com']);
    }

    public function test_registrasi_menerima_email_kampus(): void
    {
        config(['findumb.campus_email_domains' => ['mercubuana.ac.id']]);

        $response = $this->post('/register', [
            'name' => 'Civitas Baru',
            'email' => 'civitas.baru@mercubuana.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'civitas.baru@mercubuana.ac.id', 'role' => 'user']);
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_akun_ditangguhkan_tidak_dapat_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'status' => UserStatus::Suspended,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_pengguna_terverifikasi_dapat_membuat_laporan(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->postJson('/api/reports', [
            'type' => 'found',
            'title' => 'Dompet Hitam',
            'description' => 'Dompet kulit hitam berisi KTM.',
            'location' => 'Kantin Gedung D',
            'incident_date' => now()->toDateString(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('reports', [
            'title' => 'Dompet Hitam',
            'user_id' => $user->id,
            'status' => 'open',
        ]);
    }

    public function test_pengguna_tidak_dapat_mengubah_laporan_orang_lain(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $report = Report::factory()->lost()->ownedBy($owner)->create();

        $this->actingAs($other)
            ->patchJson('/api/reports/'.$report->id, ['title' => 'Diubah orang lain'])
            ->assertForbidden();

        $this->assertNotSame('Diubah orang lain', $report->fresh()->title);
    }

    public function test_laporan_in_handover_tidak_dapat_diubah_pemiliknya(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $report = Report::factory()->lost()->ownedBy($owner)->create([
            'status' => ReportStatus::InHandover,
        ]);

        $this->actingAs($owner)
            ->patchJson('/api/reports/'.$report->id, ['title' => 'Coba ubah'])
            ->assertStatus(422);
    }

    public function test_email_belum_verifikasi_tidak_bisa_mengakses_fitur_inti(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)->get('/my-reports')->assertRedirect(route('verification.notice'));
    }
}
