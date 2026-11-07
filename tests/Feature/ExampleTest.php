<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('report-modal');
        $response->assertSee('nav-fab-report');
        $response->assertSee('btn-guide-info');
        $response->assertSee('guide-modal');
        $response->assertSee('Verifikasi Kepemilikan Aman');
        $response->assertSee('search-calendar-btn');
    }

    public function test_the_landing_page_hero_section_returns_a_successful_response(): void
    {
        $response = $this->get('/landing');

        $response->assertStatus(200);
        $response->assertSee('UMBFind');
        $response->assertSee('Masuk');
        $response->assertSee('logo.webp');
        $response->assertSee('Cara tercepat temukan');
        $response->assertSee('barang hilang di area kampus');
        $response->assertSee('Platform resmi civitas Universitas Mercu Buana');
        $response->assertSee('Unduh Aplikasi');
        $response->assertSee('Buka Aplikasi');
        $response->assertSee('phone-mockup-2.webp');
        $response->assertSee('id="features"', false);
        $response->assertSee('Fitur Utama');
        $response->assertSee('Pelaporan Cepat');
        $response->assertSee('Pos Satpam');
        $response->assertSee('Pencarian Cerdas');
        $response->assertDontSee('Verifikasi Kepemilikan Civitas');
        $response->assertDontSee('Status Real-Time');
        $response->assertSee('id="faq"', false);
        $response->assertDontSee('/ FAQS');
        $response->assertSee('Pertanyaan yang');
        $response->assertSee('sering diajukan');
        $response->assertSee('Untuk apa platform UMBFind digunakan?');
        $response->assertSee('Akun & Keamanan');
        $response->assertSee('Pelaporan & Klaim');
        $response->assertDontSee('href="#security"', false);
        $response->assertSee('landing-footer');
        $response->assertSee('admin.lostfound@mercubuana.ac.id');
        $response->assertSee('Universitas Mercu Buana');
    }

    public function test_the_search_page_returns_a_successful_response(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('UMBFind');
        $response->assertSee('report-modal');
        $response->assertSee('nav-fab-report');
        $response->assertSee('btn-guide-info');
        $response->assertSee('guide-modal');
        $response->assertSee('btn-month-filter');
        $response->assertSee('month-active-dot');
        $response->assertSee('Semua Bulan');
        $response->assertDontSee('filter-sheet-overlay');
        $response->assertDontSee('Semua Area');
    }

    public function test_the_messages_page_returns_a_successful_response(): void
    {
        $response = $this->get('/messages');

        $response->assertStatus(200);
        $response->assertSee('Pesan & Klaim');
        $response->assertSee('UMBFind');
        $response->assertSee('report-modal');
        $response->assertSee('nav-fab-report');
    }

    public function test_the_report_lost_page_returns_a_successful_response(): void
    {
        $response = $this->get('/report/lost');

        $response->assertStatus(200);
        $response->assertSee('Lapor Kehilangan');
        $response->assertSee('Informasi Dasar Barang');
    }

    public function test_the_report_found_page_returns_a_successful_response(): void
    {
        $response = $this->get('/report/found');

        $response->assertStatus(200);
        $response->assertSee('Lapor Penemuan');
        $response->assertSee('Informasi Dasar Barang');
    }

    public function test_the_report_detail_page_returns_a_successful_response(): void
    {
        $response = $this->get('/report/1');

        $response->assertStatus(200);
        $response->assertSee('Detail Laporan');
        $response->assertSee('detail-hero-img');
        $response->assertSee('btn-primary-action');
        $response->assertSee('id="btn-detail-back"', false);
        $response->assertSee('handleDetailBack', false);
        $response->assertSee('"1"', false);
        $response->assertDontSee('safe-notice-card');
        $response->assertDontSee('floating-options-wrap');
        $response->assertDontSee('Dipublikasikan');
    }

    public function test_the_report_detail_page_loads_different_ids(): void
    {
        $response4 = $this->get('/report/4');
        $response4->assertStatus(200);
        $response4->assertSee('"4"', false);

        $response8 = $this->get('/report/8');
        $response8->assertStatus(200);
        $response8->assertSee('"8"', false);
    }

    public function test_the_profile_page_returns_a_successful_response(): void
    {
        $response = $this->get('/profile');

        $response->assertStatus(200);
        $response->assertDontSee('Akun Saya');
        $response->assertDontSee('id="brand-logo"', false);
        $response->assertDontSee('id="btn-notification"', false);
        $response->assertDontSee('user-verified-badge');
        $response->assertDontSee('Civitas UMB Terverifikasi');
        $response->assertSee('Pengaturan Aplikasi');
        $response->assertSee('Keluar dari Akun');
        $response->assertDontSee('btn-mobile-settings');
        $response->assertSee('user-mobile-info-faculty');
        $response->assertSee('user-mobile-info-prodi');
        $response->assertSee('Tentang UMBFind');
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('Syarat &amp; Ketentuan', false);
        $response->assertSee('btn-user-pencil');
        $response->assertSee('Aktivitas Saya');
        $response->assertSee('Selamat Datang Kembali, Fauzi!');
        $response->assertSee('Kelola informasi profil civitas');
        $response->assertSee('Informasi Pribadi &amp; Akademik', false);
        $response->assertSee('Laporan Saya');
        $response->assertSee(route('my.reports'));
        $response->assertSee(route('saved.reports'));
        $response->assertSee('Barang Disimpan');
        $response->assertSee('modal-edit-profile');
        $response->assertSee('avatar-camera-btn');
        $response->assertSee('btn-card-edit-profile');
        $response->assertSee('btn-header-edit-profile');
        $response->assertDontSee('btn-pill-edit');
        $response->assertSee('user-info-faculty');
        $response->assertSee('user-info-prodi');
        $response->assertDontSee('Universitas Mercu Buana, Kampus Meruya');
        $response->assertDontSee('btn-header-settings');
        $response->assertDontSee('Mulai Di Sini');
        $response->assertDontSee('Akun &amp; Preferensi');
    }

    public function test_the_settings_page_returns_a_successful_response(): void
    {
        $response = $this->get('/settings');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
        $response->assertSee('Bahasa Aplikasi');
        $response->assertSee('Indonesia');
        $response->assertSee('English');
        $response->assertSee('Notifikasi Push Perangkat');
        $response->assertSee('Pemberitahuan Klaim Barang');
        $response->assertSee('Simpan Pengaturan');
        $response->assertSee('Simpan');
        $response->assertSee('UMBFind');
        $response->assertSee('Bantuan &amp; Informasi', false);
        $response->assertSee('Buku Panduan &amp; Informasi', false);
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('Syarat &amp; Ketentuan', false);
        $response->assertSee('Tentang UMBFind');
        $response->assertSee('Keluar dari Akun');
        $response->assertSee('privacy-modal');
        $response->assertSee('terms-modal');
        $response->assertSee('about-modal');
        $response->assertDontSee('Notifikasi Email');
        $response->assertDontSee('Privasi &amp; Keamanan Data');
        $response->assertDontSee('Lindungi Kontak Pribadi');
        $response->assertDontSee('Data &amp; Akun');
        $response->assertDontSee('Unduh Riwayat Laporan Saya');
        $response->assertDontSee('Bahasa Indonesia');
        $response->assertDontSee('English (US)');
        $response->assertDontSee('Pilih bahasa tampilan antarmuka');
    }

    public function test_the_my_reports_page_returns_a_successful_response(): void
    {
        $response = $this->get('/my-reports');

        $response->assertStatus(200);
        $response->assertSee('Laporan Saya');
        $response->assertDontSee('Total Laporan');
        $response->assertDontSee('btn-header-create');
        $response->assertSee('Semua');
        $response->assertSee('Edit Laporan Barang');
        $response->assertSee('Hapus Laporan?');
        $response->assertSee('modal-resolve-confirm');
        $response->assertSee('modal-edit-status-confirm');
        $response->assertSee('btn-guide-close');
        $response->assertSee('Samsung SM-A135F');
        $response->assertSee('Cincin Emas Berlian');
    }

    public function test_the_notifications_page_returns_a_successful_response(): void
    {
        $response = $this->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee('Notifikasi');
        $response->assertSee('notif-header');
        $response->assertSee('btn-header-filter');
        $response->assertSee('btn-notif-filter');
        $response->assertSee('filter-dropdown-wrap');
        $response->assertSee('header-unread-badge');
        $response->assertSee('btn-mark-all-read');
        $response->assertSee('modal-notif-settings');
        $response->assertSee('notif-empty-state');
        $response->assertSee('Earphone TWS Anker Soundcore');
        $response->assertSee('Pengingat 72 Jam');
        $response->assertSee('Serah Terima');
    }

    public function test_the_saved_reports_page_returns_a_successful_response(): void
    {
        $response = $this->get('/saved-reports');

        $response->assertStatus(200);
        $response->assertSee('Barang Disimpan');
        $response->assertSee('header-saved-count');
        $response->assertSee('Earphone TWS Anker Soundcore');
        $response->assertSee('Tas Laptop Lenovo ThinkPad');
        $response->assertSee('KTM & Dompet Kulit Cokelat', false);
        $response->assertSee('modal-desktop-detail');
        $response->assertSee('saved-reports-list');
    }

    public function test_the_admin_dashboard_returns_a_successful_response(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Pusat komando moderasi pelaporan');
        $response->assertSee('Total Laporan Aktif');
        $response->assertSee('Barang Selesai Serah Terima');
        $response->assertSee('Aduan Menunggu Review');
        $response->assertSee('Akun Ditangguhkan');
        $response->assertSee('Aduan &amp; Moderasi', false);
        $response->assertSee('Kelola Laporan');
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Log Audit Moderasi');
        $response->assertSee('Kategori Barang');
        $response->assertSee('Web Civitas');
        $response->assertSee('Kembali ke Halaman Civitas');
        $response->assertSee('Mode Admin Aktif');
        $response->assertSee('modal-moderation-action');
        $response->assertSee('modal-abuse-detail');
        $response->assertSee('modal-create-admin');
        $response->assertSee('modal-admin-settings');
    }

    public function test_the_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
        $response->assertSee('Masukkan email kampus (@mercubuana.ac.id)');
        $response->assertSee('Masukkan kata sandi');
        $response->assertSee('Ingat saya');
        $response->assertSee('Lupa kata sandi?');
        $response->assertSee('Masuk dengan Google');
    }

    public function test_the_register_page_returns_a_successful_response(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun');
        $response->assertSee('Masukkan nama lengkap');
        $response->assertSee('Buat kata sandi baru');
        $response->assertSee('Tulis ulang kata sandi');
        $response->assertSee('Daftar Sekarang');
        $response->assertSee('Daftar dengan Google');
    }

    public function test_landing_page_buttons_direct_guests_to_login(): void
    {
        $response = $this->get('/landing');

        $response->assertStatus(200);
        $response->assertSee(route('login'));
    }

    public function test_login_submission_authenticates_and_redirects_to_home(): void
    {
        $response = $this->post('/login', [
            'email' => 'fauzi.mahasiswa@mercubuana.ac.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertTrue(session('authenticated') === true);
    }

    public function test_login_fails_with_non_mercubuana_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'hacker@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_register_fails_with_non_mercubuana_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@yahoo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_register_fails_with_mismatched_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@student.mercubuana.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'mismatchedpass',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_register_succeeds_and_redirects_to_verify_otp(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@student.mercubuana.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.otp'));
        $this->assertEquals('siti.nurhaliza@student.mercubuana.ac.id', session('pending_email'));
    }

    public function test_the_verify_otp_page_returns_a_successful_response(): void
    {
        $response = $this->withSession(['pending_email' => 'siti.nurhaliza@student.mercubuana.ac.id'])
                         ->get('/verify-otp');

        $response->assertStatus(200);
        $response->assertSee('Verifikasi Kode OTP');
        $response->assertSee('siti.nurhaliza@student.mercubuana.ac.id');
        $response->assertSee('Verifikasi Akun');
        $response->assertSee('Kirim Ulang');
    }

    public function test_verify_otp_fails_with_invalid_length(): void
    {
        $response = $this->post('/verify-otp', [
            'otp' => '123',
        ]);

        $response->assertSessionHasErrors('otp');
    }

    public function test_verify_otp_submission_authenticates_and_redirects_to_home(): void
    {
        $response = $this->withSession([
            'pending_email' => 'siti.nurhaliza@student.mercubuana.ac.id',
            'pending_name' => 'Siti Nurhaliza',
        ])->post('/verify-otp', [
            'otp' => '654321',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertTrue(session('authenticated') === true);
        $this->assertEquals('siti.nurhaliza@student.mercubuana.ac.id', session('user.email'));
    }

    public function test_resend_otp_returns_back_with_success_message(): void
    {
        $response = $this->from('/verify-otp')->post('/verify-otp/resend');

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHas('success');
    }
}
