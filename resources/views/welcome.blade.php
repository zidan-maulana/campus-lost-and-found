@extends('layouts.app')

@section('title', 'Beranda — Campus Lost & Found Universitas Mercu Buana')

@push('styles')
<style>
    .hero-section {
        text-align: center;
        max-width: 820px;
        margin: 1.5rem auto 3.5rem auto;
    }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.825rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--color-accent);
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        padding: 0.4rem 1rem;
        border-radius: var(--radius-pill);
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.1rem, 4.5vw, 3.25rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: #ffffff;
        margin-bottom: 1.25rem;
    }

    .hero-title .gradient-text {
        background: linear-gradient(135deg, #60a5fa 20%, #f59e0b 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-description {
        font-size: 1.1rem;
        color: var(--color-text-muted);
        line-height: 1.7;
        margin: 0 auto;
    }

    /* Grid Section */
    .section-title-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--color-border);
        padding-bottom: 0.75rem;
    }

    .section-title {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--color-text-main);
    }

    .section-subtitle {
        font-size: 0.85rem;
        color: var(--color-text-dim);
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3.5rem;
    }

    .glass-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-subtle);
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.4), transparent);
        opacity: 0;
        transition: var(--transition-smooth);
    }

    .glass-card:hover {
        background: var(--color-bg-card-hover);
        border-color: var(--color-border-glow);
        transform: translateY(-4px);
        box-shadow: var(--shadow-subtle), var(--shadow-glow);
    }

    .glass-card:hover::before {
        opacity: 1;
    }

    .card-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        background: rgba(59, 130, 246, 0.12);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .card-title {
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.65rem;
    }

    .card-text {
        font-size: 0.925rem;
        color: var(--color-text-muted);
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex-grow: 1;
    }

    .card-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--color-text-muted);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1rem;
    }

    .card-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-list li::before {
        content: '✓';
        color: var(--color-emerald);
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* Status Table */
    .status-panel {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-subtle);
    }

    .status-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.925rem;
        margin-top: 1rem;
    }

    .status-table th {
        text-align: left;
        padding: 0.85rem 1rem;
        font-family: var(--font-display);
        font-weight: 600;
        color: var(--color-text-dim);
        border-bottom: 1px solid var(--color-border);
        text-transform: uppercase;
        font-size: 0.775rem;
        letter-spacing: 0.05em;
    }

    .status-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        color: var(--color-text-main);
    }

    .status-badge-ok {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #34d399;
        background: rgba(16, 185, 129, 0.12);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-pill);
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .status-badge-ready {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #93c5fd;
        background: rgba(59, 130, 246, 0.12);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-pill);
        border: 1px solid rgba(59, 130, 246, 0.25);
    }
</style>
@endpush

@section('content')
<section class="hero-section" id="hero-area">
    <div class="hero-tag">
        <span>Universitas Mercu Buana</span>
    </div>
    <h1 class="hero-title">
        Campus Lost &amp; Found <br>
        <span class="gradient-text">Fondasi Sistem Terverifikasi</span>
    </h1>
    <p class="hero-description">
        Infrastruktur awal aplikasi web pelaporan, pencarian, dan pengembalian barang hilang atau temuan di lingkungan Universitas Mercu Buana telah siap untuk pengembangan modular.
    </p>
</section>

<div class="section-title-group">
    <h2 class="section-title">Kesiapan Arsitektur &amp; Fondasi</h2>
    <span class="section-subtitle">Sesuai Dokumen Perancangan Sistem</span>
</div>

<div class="cards-grid" id="foundation-cards">
    <div class="glass-card" id="card-framework">
        <div class="card-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
        </div>
        <h3 class="card-title">Kerangka Laravel &amp; Blade</h3>
        <p class="card-text">
            Menggunakan Laravel 12 modern dengan engine Blade server-side rendering, struktur MVC bersih, dan styling Vanilla CSS performa tinggi tanpa dependensi build kompleks.
        </p>
        <ul class="card-list">
            <li>Laravel Framework 12.70.1 &amp; PHP 8.2</li>
            <li>Routing web bersih &amp; penanganan session file</li>
            <li>Layout template modular &amp; semantic HTML5</li>
        </ul>
    </div>

    <div class="glass-card" id="card-database">
        <div class="card-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
            </svg>
        </div>
        <h3 class="card-title">Konfigurasi MySQL Database</h3>
        <p class="card-text">
            Parameter koneksi database MySQL terpusat via environment variable aman, disiapkan untuk integrasi skema tabel bisnis pada Fase 2 dan Fase 3.
        </p>
        <ul class="card-list">
            <li>Database: campus_lost_and_found</li>
            <li>MySQL Client: 8.4.3 Community Server</li>
            <li>Environment credentials terlindungi (.gitignore)</li>
        </ul>
    </div>

    <div class="glass-card" id="card-roadmap">
        <div class="card-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
        </div>
        <h3 class="card-title">Rencana Pengembangan Bertahap</h3>
        <p class="card-text">
            Pengembangan mengikuti roadmap formal 8 fase untuk memastikan setiap modul terverifikasi, aman, dan konsisten sebelum melangkah ke tahap berikutnya.
        </p>
        <ul class="card-list">
            <li>Fase 1: Inisialisasi &amp; Fondasi (Selesai)</li>
            <li>Fase 2: Autentikasi Pengguna &amp; Domain UMB</li>
            <li>Fase 3: Modul Laporan Barang Hilang &amp; Temuan</li>
        </ul>
    </div>
</div>

<div class="status-panel" id="environment-verification-panel">
    <div class="section-title-group" style="margin-bottom: 0.5rem; border-bottom: none;">
        <h3 class="section-title" style="font-size: 1.15rem;">Hasil Verifikasi Lingkungan Lokal (Fase 1)</h3>
        <span class="status-badge-ok">Verified</span>
    </div>
    <table class="status-table">
        <thead>
            <tr>
                <th>Komponen Lingkungan</th>
                <th>Versi / Konfigurasi Aktual</th>
                <th>Status Kesiapan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>PHP Runtime</strong></td>
                <td>PHP 8.2.34 (Visual C++ 2019 x64)</td>
                <td><span class="status-badge-ok">Aktif &amp; Kompatibel</span></td>
            </tr>
            <tr>
                <td><strong>Dependency Manager</strong></td>
                <td>Composer 2.10.3</td>
                <td><span class="status-badge-ok">Terpasang</span></td>
            </tr>
            <tr>
                <td><strong>Laravel Framework</strong></td>
                <td>v12.70.1 (Application Key Configured)</td>
                <td><span class="status-badge-ok">Siap Berjalan</span></td>
            </tr>
            <tr>
                <td><strong>Node.js / NPM</strong></td>
                <td>Node v22.21.0 / NPM 10.9.4</td>
                <td><span class="status-badge-ready">Tersedia</span></td>
            </tr>
            <tr>
                <td><strong>Database Client</strong></td>
                <td>MySQL 8.4.3 (Laragon Bundle)</td>
                <td><span class="status-badge-ready">Terkonfigurasi di .env</span></td>
            </tr>
            <tr>
                <td><strong>Routing Engine</strong></td>
                <td>GET / (Home Blade View)</td>
                <td><span class="status-badge-ok">OK (HTTP 200)</span></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
