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

    public function test_the_search_page_returns_a_successful_response(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('FindUMB');
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
        $response->assertSee('FindUMB');
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
        $response->assertSee('btn-user-pencil');
        $response->assertSee('Pengaturan Aplikasi');
        $response->assertSee('Laporan Saya');
        $response->assertSee(route('my.reports'));
        $response->assertSee('Keluar dari Akun');
        $response->assertSee('privacy-modal');
        $response->assertSee('terms-modal');
        $response->assertSee('about-modal');
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('Syarat &amp; Ketentuan', false);
        $response->assertSee('Tentang FindUMB');
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
        $response->assertSee('FindUMB');
        $response->assertDontSee('Notifikasi Email');
        $response->assertDontSee('Privasi &amp; Keamanan Data');
        $response->assertDontSee('Lindungi Kontak Pribadi');
        $response->assertDontSee('Data &amp; Akun');
        $response->assertDontSee('Unduh Riwayat Laporan Saya');
        $response->assertDontSee('Bantuan &amp; Legalitas');
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
}
