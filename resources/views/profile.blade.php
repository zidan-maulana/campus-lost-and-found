@extends('layouts.app')

@section('title', 'Profil — Find UMB')

@section('header')
<div style="display: none;" aria-hidden="true"></div>
@endsection

@push('styles')
<style>
    /* Profile Page Root Container */
    .profile-page-wrapper {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 100%;
        background: #f8fafc;
        margin-bottom: -95px; /* Memanjang ke bawah memenuhi layar hingga belakang bottom nav */
    }

    /* Top Main Card (White Shape yang Memuat Blue Header & Stats Area Persis Referensi) */
    .profile-top-card {
        background: #ffffff;
        border-radius: 0 0 46px 46px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        border-bottom: 1px solid rgba(15, 23, 42, 0.04);
        position: relative;
        margin-bottom: 1.25rem;
    }

    /* Top Full-Width Curved Blue Hero Card (Memenuhi Layar ke Atas & ke Samping) */
    .profile-hero-card {
        margin: 0;
        width: 100%;
        border-radius: 0 0 46px 46px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        text-align: center;
        box-shadow: 0 8px 24px rgba(15, 87, 145, 0.16);
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* White Shape Area (Memuat Avatar & Stats Row Persis Referensi) */
    .profile-white-section {
        background: #ffffff;
        border-radius: 0 0 46px 46px;
        position: relative;
        width: 100%;
    }

    /* SVG Fluid Wave Background (Shape Persis Referensi dengan Warna FindUMB) */
    .hero-svg-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
        z-index: 1;
    }

    /* Content inside Hero Card (Lebih Ramping / Sedikit Whitespace ke Atas) */
    .hero-content-wrap {
        position: relative;
        z-index: 2;
        padding: 1.35rem 1.25rem 3.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    /* Inline Edit Pencil Button Langsung Menggantikan Verif Icon di Sebelah Nama (Ukuran Diperkecil) */
    .btn-user-pencil {
        background: transparent;
        border: none;
        padding: 0.15rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.75);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        border-radius: 50%;
        margin-left: 0.2rem;
        flex-shrink: 0;
    }

    .btn-user-pencil:hover, .btn-user-pencil:active {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: scale(1.1);
    }

    .btn-user-pencil svg, .btn-user-pencil .lucide {
        width: 13px;
        height: 13px;
        stroke-width: 2.1;
        color: inherit;
        stroke: currentColor;
    }

    /* Name & Subtitle Center */
    .hero-user-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        padding: 0 0.5rem;
    }

    .hero-name-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
    }

    .hero-user-name {
        font-family: var(--font-heading);
        font-size: 1.45rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.015em;
        line-height: 1.2;
    }


    .hero-user-sub {
        font-family: var(--font-main);
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    /* Overlapping Avatar dengan Stroke Diperkecil (2px) */
    .profile-avatar-wrap {
        display: flex;
        justify-content: center;
        margin-top: -42px;
        position: relative;
        z-index: 10;
        margin-bottom: 1.25rem;
    }

    .profile-avatar-box {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        border: 2px solid #ffffff; /* Perkecil stroke menjadi 2px persis referensi */
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar-fallback {
        font-family: var(--font-heading);
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--primary);
    }

    /* Stats Row (Komposisi 3 Kolom Angka & Keterangan Persis Referensi) */
    .profile-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        text-align: center;
        padding: 0.5rem 1rem 1.85rem 1rem;
        max-width: 440px;
        margin: 0 auto;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .stat-val {
        font-family: var(--font-heading);
        font-size: 1.35rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.15;
        letter-spacing: -0.015em;
    }

    .stat-lbl {
        font-family: var(--font-main);
        font-size: 0.785rem;
        font-weight: 500;
        color: #94a3b8;
        margin-top: 0.25rem;
        letter-spacing: 0.01em;
    }

    /* Main Menu Card (Memenuhi Layar Kesamping & Memanjang ke Bawah) */
    .profile-body-content {
        padding: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .profile-menu-card {
        background: #ffffff;
        border-radius: 46px 46px 0 0;
        border: 1px solid rgba(15, 23, 42, 0.05);
        border-bottom: none;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.035);
        display: flex;
        flex-direction: column;
        padding: 1.15rem 0.65rem 105px 0.65rem;
        width: 100%;
        flex: 1;
    }

    .menu-group-header {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        padding: 0.75rem 1.25rem 0.35rem 1.25rem;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.25rem;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        transition: var(--transition);
    }

    .profile-menu-item:hover {
        background: #f8fafc;
    }

    .profile-item-left {
        display: flex;
        align-items: center;
        gap: 0.95rem;
    }

    /* Clean Bare Icon (Tanpa Shape / Background Box) */
    .profile-item-icon {
        width: 21px;
        height: 21px;
        stroke-width: 2.1;
        color: #1572b8;
        stroke: #1572b8;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .profile-menu-item:hover .profile-item-icon {
        transform: scale(1.08);
        color: #0f5791;
    }

    .profile-item-title {
        font-family: var(--font-main);
        font-size: 0.885rem;
        font-weight: 600;
        color: #1e293b;
    }

    .item-chevron {
        color: #cbd5e1;
        stroke: #cbd5e1;
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
        flex-shrink: 0;
    }

    .menu-card-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0.5rem 1.25rem;
    }

    .profile-notif-pill {
        font-size: 0.68rem;
        font-weight: 700;
        background: #fee2e2;
        color: #ef4444;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        line-height: 1;
    }

    /* Sign Out Button */
    .btn-signout-wrap {
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        padding: 0 0.85rem;
        width: 100%;
    }

    .btn-signout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.85rem 1.5rem;
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
        border-radius: var(--radius-md);
        font-family: var(--font-main);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-signout:hover {
        background: #ffe4e6;
        border-color: #fecdd3;
    }

    .btn-signout svg, .btn-signout .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
        color: #e11d48;
        stroke: #e11d48;
    }

    /* Floating Toast Popup */
    .toast-popup {
        position: fixed;
        bottom: 95px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #0f172a;
        color: #ffffff;
        padding: 0.65rem 1.35rem;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 500;
        z-index: 120;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.25);
        white-space: nowrap;
        text-align: center;
    }

    .toast-popup.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<div class="profile-page-wrapper">

    <!-- TOP COMBINED CARD (Blue Curved Header + White Shape Persis Referensi) -->
    <div class="profile-top-card">
        <!-- 1. TOP CURVED HERO CARD (Shape Persis Gambar Referensi) -->
        <section class="profile-hero-card">
            <!-- SVG Fluid Curves Persis Referensi -->
            <svg class="hero-svg-bg" viewBox="0 0 480 155" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#0f5da0"/>
                        <stop offset="100%" stop-color="#1879c2"/>
                    </linearGradient>
                    <linearGradient id="darkWave" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#0a3d68"/>
                        <stop offset="100%" stop-color="#0e4f84"/>
                    </linearGradient>
                    <linearGradient id="lightWave" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.46"/>
                        <stop offset="100%" stop-color="#2ba6e1" stop-opacity="0.22"/>
                    </linearGradient>
                </defs>
                <rect width="480" height="155" fill="url(#bgGrad)"/>
                <path d="M 0 0 L 115 0 C 122 48, 142 105, 178 155 L 0 155 Z" fill="url(#darkWave)"/>
                <path d="M 330 0 C 348 48, 382 105, 428 155 L 480 155 L 480 0 Z" fill="url(#lightWave)"/>
            </svg>

            <div class="hero-content-wrap">
                <!-- Name & Subtitle Center -->
                <div class="hero-user-details">
                    <div class="hero-name-row">
                        <h1 class="hero-user-name" id="user-display-name">Ahmad Fauzi</h1>
                        <button type="button" class="btn-user-pencil" onclick="handleEditProfile()" title="Ubah Nama Profil" aria-label="Ubah Nama Profil">
                            <i data-lucide="pencil"></i>
                        </button>
                    </div>
                    <span class="hero-user-sub">41521010045@student.mercubuana.ac.id</span>
                </div>
            </div>
        </section>

        <!-- 2. SHAPE BERWARNA PUTIH (Memuat Overlapping Avatar & Stats Persis Referensi) -->
        <div class="profile-white-section">
            <!-- Overlapping Avatar (Stroke 2px Lebih Tipis Persis Referensi) -->
            <div class="profile-avatar-wrap">
                <div class="profile-avatar-box">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=240&auto=format&fit=crop&q=80" alt="Foto Profil" class="profile-avatar-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="profile-avatar-fallback" style="display: none;">A</span>
                </div>
            </div>

            <!-- Stats Row (Komposisi Angka & Keterangan Persis Referensi) -->
            <div class="profile-stats-row">
                <div class="stat-item">
                    <span class="stat-val" id="stat-reports-count">2</span>
                    <span class="stat-lbl">Laporan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" id="stat-recovered-count">1</span>
                    <span class="stat-lbl">Terselesaikan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" id="stat-saved-count">3</span>
                    <span class="stat-lbl">Disimpan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. MENU CARD (Mempertahankan Seluruh Opsi yang Ada) -->
    <div class="profile-body-content">
        <div class="profile-menu-card">
            <div class="menu-group-header">Aktivitas Saya</div>

            <!-- 1. Laporan Saya -->
            <a href="{{ route('my.reports') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="file-text" class="profile-item-icon"></i>
                    <span class="profile-item-title">Laporan Saya</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 2. Barang Disimpan -->
            <div class="profile-menu-item" onclick="handleMenuNotice('Barang Tersimpan', 'Membuka 3 barang yang Anda simpan')">
                <div class="profile-item-left">
                    <i data-lucide="bookmark" class="profile-item-icon"></i>
                    <span class="profile-item-title">Barang Disimpan</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 3. Pesan & Klaim Masuk -->
            <a href="{{ route('messages') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="message-square" class="profile-item-icon"></i>
                    <span class="profile-item-title">Pesan &amp; Klaim Masuk</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 4. Notifikasi Aktivitas -->
            <a href="{{ route('notifications') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="bell" class="profile-item-icon"></i>
                    <span class="profile-item-title">Notifikasi Aktivitas</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="profile-notif-pill" id="profile-notif-pill" style="display: none;">3 Baru</span>
                    <i data-lucide="chevron-right" class="item-chevron"></i>
                </div>
            </a>

            <div class="menu-card-divider"></div>

            <div class="menu-group-header">Pengaturan &amp; Bantuan</div>

            <!-- 4. Pengaturan Aplikasi -->
            <a href="{{ route('settings') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="settings" class="profile-item-icon"></i>
                    <span class="profile-item-title">Pengaturan Aplikasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 5. Buku Panduan & Informasi -->
            <div class="profile-menu-item" onclick="openGuideModal()">
                <div class="profile-item-left">
                    <i data-lucide="book-open" class="profile-item-icon"></i>
                    <span class="profile-item-title">Buku Panduan &amp; Informasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 6. Kebijakan Privasi -->
            <div class="profile-menu-item" onclick="openPrivacyModal()">
                <div class="profile-item-left">
                    <i data-lucide="shield-check" class="profile-item-icon"></i>
                    <span class="profile-item-title">Kebijakan Privasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 7. Syarat & Ketentuan -->
            <div class="profile-menu-item" onclick="openTermsModal()">
                <div class="profile-item-left">
                    <i data-lucide="file-check" class="profile-item-icon"></i>
                    <span class="profile-item-title">Syarat &amp; Ketentuan</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 8. Tentang FindUMB -->
            <div class="profile-menu-item" onclick="openAboutModal()">
                <div class="profile-item-left">
                    <i data-lucide="info" class="profile-item-icon"></i>
                    <span class="profile-item-title">Tentang FindUMB</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 5. TOMBOL KELUAR (SIGN OUT) -->
            <div class="btn-signout-wrap">
                <button type="button" class="btn-signout" onclick="handleSignOut()">
                    <i data-lucide="log-out"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>

<!-- Modal 1: Kebijakan Privasi -->
<div class="modal-overlay" id="privacy-modal" onclick="closePrivacyModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Kebijakan Privasi</h3>
        <p class="modal-desc">Kebijakan perlindungan data dan privasi civitas akademika Universitas Mercu Buana pada platform FindUMB.</p>
        
        <div class="guide-modal-body">
            <!-- 1. Perlindungan Identitas Civitas -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <h4 class="guide-card-title">Perlindungan Identitas Civitas</h4>
                </div>
                <ul class="guide-list">
                    <li>Kontak pribadi civitas (nomor HP, WhatsApp, &amp; email) <strong>tidak pernah dipublikasikan terbuka</strong> untuk mencegah spam dan penipuan.</li>
                    <li>Data akun civitas (NIM dan email student) hanya digunakan untuk keperluan verifikasi keabsahan civitas internal UMB.</li>
                    <li>Tampilan publik pada laporan barang hanya mencantumkan nama panggilan/singkat dan fakultas pelapor.</li>
                </ul>
            </div>

            <!-- 2. Keamanan Komunikasi & Pesan -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="lock"></i>
                    </div>
                    <h4 class="guide-card-title">Keamanan Komunikasi Internal</h4>
                </div>
                <ul class="guide-list">
                    <li>Seluruh koordinasi awal serah terima dilakukan melalui fitur <strong>Pesan / Chat</strong> resmi yang terisolasi di dalam FindUMB.</li>
                    <li>Sistem tidak meminta dan melarang civitas membagikan informasi sensitif perbankan (PIN, kode OTP, atau transfer uang).</li>
                </ul>
            </div>

            <!-- 3. Kerahasiaan Ciri Khusus Barang -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="eye-off"></i>
                    </div>
                    <h4 class="guide-card-title">Kerahasiaan Ciri Khusus Barang</h4>
                </div>
                <ul class="guide-list">
                    <li>Rincian ciri khusus rahasia (seperti nomor seri, goresan mikro, atau stiker pembeda) tidak dipublikasikan ke publik.</li>
                    <li>Ciri khusus hanya digunakan sebagai instrumen pencocokan bukti kepemilikan sah saat klaim serah terima berlangsung.</li>
                </ul>
            </div>

            <!-- 4. Retensi & Penghapusan Data -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="database"></i>
                    </div>
                    <h4 class="guide-card-title">Retensi &amp; Kendali Data</h4>
                </div>
                <ul class="guide-list">
                    <li>Civitas memiliki kendali penuh untuk memperbarui atau menghapus laporan barang melalui menu <strong>Laporan Saya</strong>.</li>
                    <li>Laporan yang telah tuntas diubah statusnya menjadi <strong>Terselesaikan</strong> dan diarsipkan secara aman.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closePrivacyModal()">Tutup Kebijakan</button>
    </div>
</div>

<!-- Modal 2: Syarat & Ketentuan -->
<div class="modal-overlay" id="terms-modal" onclick="closeTermsModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Syarat &amp; Ketentuan</h3>
        <p class="modal-desc">Pedoman etika dan aturan penggunaan layanan Lost &amp; Found di lingkungan Universitas Mercu Buana.</p>
        
        <div class="guide-modal-body">
            <!-- 1. Integritas & Kejujuran Pelaporan -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="scale"></i>
                    </div>
                    <h4 class="guide-card-title">Integritas &amp; Kejujuran Pelaporan</h4>
                </div>
                <ul class="guide-list">
                    <li>Setiap civitas wajib memberikan informasi pelaporan yang <strong>benar, akurat, dan dapat dipertanggungjawabkan</strong>.</li>
                    <li>Dilarang keras membuat laporan fiktif, melakukan klaim sepihak tanpa bukti, atau mengunggah konten provokatif.</li>
                </ul>
            </div>

            <!-- 2. Prosedur Klaim & Verifikasi -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="user-check"></i>
                    </div>
                    <h4 class="guide-card-title">Prosedur Klaim Kepemilikan</h4>
                </div>
                <ul class="guide-list">
                    <li>Pengklaim barang temuan <strong>wajib membuktikan ciri khusus rahasia</strong> kepada penemu atau petugas sebelum barang diserahkan.</li>
                    <li>Penemu atau pos penitipan kampus berhak menolak klaim jika bukti kepemilikan tidak dapat dipertanggungjawabkan.</li>
                    <li>Serah terima fisik diimbau dilakukan di area kampus yang ramai atau difasilitasi oleh Pos Satpam Resmi UMB.</li>
                </ul>
            </div>

            <!-- 3. Ketentuan Barang Berharga -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="building-2"></i>
                    </div>
                    <h4 class="guide-card-title">Penanganan Barang Berharga</h4>
                </div>
                <ul class="guide-list">
                    <li>Barang bernilai tinggi (laptop, dompet, perhiasan, KTP, KTM, &amp; dokumen negara) sangat disarankan segera dititipkan ke <strong>Pos Satpam Gerbang Barat / Gedung Tower</strong>.</li>
                    <li>FindUMB berfungsi sebagai sarana publikasi informasi civitas dan tidak bertanggung jawab atas kelalaian fisik di luar sistem.</li>
                </ul>
            </div>

            <!-- 4. Sanksi Pelanggaran Kode Etik -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="alert-triangle"></i>
                    </div>
                    <h4 class="guide-card-title">Sanksi Kode Etik Kampus</h4>
                </div>
                <ul class="guide-list">
                    <li>Upaya penggelapan barang temuan atau pemalsuan identitas akan diteruskan ke <strong>Biro Kemahasiswaan &amp; Komisi Disiplin UMB</strong>.</li>
                    <li>Pelanggaran berat akan dikenakan sanksi akademik sesuai peraturan kode etik civitas akademika Universitas Mercu Buana.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closeTermsModal()">Tutup Ketentuan</button>
    </div>
</div>

<!-- Modal 3: Tentang FindUMB -->
<div class="modal-overlay" id="about-modal" onclick="closeAboutModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Tentang FindUMB</h3>
        <p class="modal-desc">Platform digital Lost &amp; Found terpadu civitas akademika Universitas Mercu Buana.</p>
        
        <div class="guide-modal-body">
            <!-- 1. Visi & Misi Inovasi -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="sparkles"></i>
                    </div>
                    <h4 class="guide-card-title">Platform Lost &amp; Found Kampus</h4>
                </div>
                <ul class="guide-list">
                    <li><strong>FindUMB</strong> dirancang untuk mewujudkan ekosistem kampus yang saling peduli, jujur, dan aman dalam mengembalikan barang hilang civitas.</li>
                    <li>Dilengkapi fitur pelaporan cepat, pencarian kategori cerdas, sistem verifikasi chat internal, dan integrasi pos penitipan resmi.</li>
                    <li>Versi Rilis: <strong>v1.0.0 (Responsive Web &amp; Mobile-Optimized)</strong>.</li>
                </ul>
            </div>

            <!-- 2. Cakupan Wilayah Kampus -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <h4 class="guide-card-title">Cakupan Wilayah Kampus UMB</h4>
                </div>
                <ul class="guide-list">
                    <li><strong>Kampus Meruya (Pusat)</strong>: Gedung Tower, Blok A s/d E, Parkir Barat/Timur, Kantin, &amp; Masjid Manarul 'Amal.</li>
                    <li><strong>Kampus Menteng, Kampus Pejaten, &amp; Kampus Warung Buncit</strong>.</li>
                </ul>
            </div>

            <!-- 3. Tim Pengembang Mahasiswa -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="code-2"></i>
                    </div>
                    <h4 class="guide-card-title">Pengembang &amp; Civitas</h4>
                </div>
                <ul class="guide-list">
                    <li>Pengembang: <strong>Ahmad Fauzi</strong> (NIM: <strong>41521010045</strong>).</li>
                    <li>Program Studi: <strong>Teknik Informatika — Fakultas Ilmu Komputer</strong>.</li>
                    <li>Institusi: <strong>Universitas Mercu Buana</strong> Jakarta.</li>
                    <li>Mata Kuliah: <strong>Pemrograman Web Enterprise</strong>.</li>
                </ul>
            </div>

            <!-- 4. Layanan & Bantuan Kampus -->
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <h4 class="guide-card-title">Layanan Bantuan &amp; Helpdesk</h4>
                </div>
                <ul class="guide-list">
                    <li>Email Dukungan: <strong>findumb@mercubuana.ac.id</strong></li>
                    <li>Lokasi Fisik: <strong>Pos Pelayanan Keamanan Kampus Meruya (24 Jam)</strong>.</li>
                    <li>Saran &amp; Masukan: Hubungi biro administrasi kampus atau melalui pesan pengelola aplikasi.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closeAboutModal()">Tutup Informasi</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        updateProfileStats();
        updateProfileNotifBadge();
    });

    function updateProfileNotifBadge() {
        const pill = document.getElementById('profile-notif-pill');
        if (!pill) return;
        try {
            const raw = localStorage.getItem('findumb_notifications');
            let unreadCount = 3;
            if (raw) {
                const list = JSON.parse(raw);
                if (Array.isArray(list)) {
                    unreadCount = list.filter(n => !n.read).length;
                }
            }
            if (unreadCount > 0) {
                pill.textContent = `${unreadCount} Baru`;
                pill.style.display = 'inline-block';
            } else {
                pill.style.display = 'none';
            }
        } catch(e) {}
    }

    function showToast(message) {
        const toast = document.getElementById('toast-popup');
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
        }
    }

    const DEFAULT_USER_REPORTS = [
        {
            id: '1',
            type: 'lost',
            title: 'Samsung SM-A135F',
            brand: 'Samsung Galaxy A13',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            category_label: 'Elektronik & HP',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung Tower Lt. 7 R. 702',
            location_full: 'Kampus Meruya — Gedung Tower Lt. 7 R. 702',
            date: '2026-06-20',
            date_display: '20 Jun 2026',
            time: '14.30 WIB (Siang)',
            storage: 'Disimpan Sendiri',
            features: 'Smartphone Samsung warna hitam, terpasang casing transparan dengan stiker logo Informatika di bagian belakang. Layar dalam keadaan terkunci.',
            image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
            status: 'lost',
            is_mine: true,
            created_at: '2026-06-20T14:30:00.000Z'
        },
        {
            id: '5',
            type: 'lost',
            title: 'Cincin Emas Berlian',
            brand: 'Perhiasan Emas Kuning',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            category_label: 'Jam & Aksesori',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Siang saat menuju kendaraan',
            storage: 'Disimpan Sendiri',
            features: 'Cincin emas sepasang model pertunangan/pernikahan dengan ukiran inisial halus di lingkar dalam. Terjatuh di sekitar parkiran.',
            image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80',
            status: 'lost',
            is_mine: true,
            created_at: '2026-01-15T12:00:00.000Z'
        }
    ];

    function getUserReports() {
        try {
            let list = [];
            const raw = localStorage.getItem('findumb_custom_reports');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    list = parsed;
                }
            }
            if (!list || list.length === 0) {
                list = [...DEFAULT_USER_REPORTS];
                localStorage.setItem('findumb_reports_initialized', 'true');
                localStorage.setItem('findumb_custom_reports', JSON.stringify(list));
                return list;
            }
            return list.filter(r => {
                const idStr = String(r.id);
                if (['2', '3', '4', '6', '7', '8'].includes(idStr)) return false;
                if (r.is_mine === false) return false;
                if (idStr === '1' || idStr === '5') return true;
                if (idStr.startsWith('rep_') || r.is_mine === true) return true;
                if (r.reporter_name && r.reporter_name.includes('Ahmad Fauzi')) return true;
                return false;
            });
        } catch(e) {
            return [...DEFAULT_USER_REPORTS];
        }
    }

    function updateProfileStats() {
        try {
            const userReports = getUserReports();
            const statReports = document.getElementById('stat-reports-count');
            const statRecovered = document.getElementById('stat-recovered-count');
            if (statReports) {
                statReports.textContent = userReports.length;
            }
            if (statRecovered) {
                const completedCount = userReports.filter(r => r.status === 'resolved' || r.status === 'completed' || r.type === 'found').length;
                statRecovered.textContent = completedCount > 0 ? completedCount : '1';
            }
        } catch(e) {}
    }

    function handleMenuNotice(title, desc) {
        showToast(desc);
    }

    function handleEditProfile() {
        const currentName = document.getElementById('user-display-name').textContent || 'Ahmad Fauzi';
        const newName = prompt('Ubah Nama Lengkap Civitas:', currentName);
        if (newName && newName.trim() !== '') {
            document.getElementById('user-display-name').textContent = newName.trim();
            showToast('✓ Profil civitas berhasil diperbarui!');
        }
    }

    function handleSignOut() {
        if (confirm('Apakah Anda ingin keluar dari sesi akun FindUMB?')) {
            showToast('Anda telah keluar.');
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 1000);
        }
    }

    // Modal Kebijakan Privasi
    function openPrivacyModal() {
        const modal = document.getElementById('privacy-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closePrivacyModal(e) {
        if (!e || e.target.id === 'privacy-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('privacy-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }

    // Modal Syarat & Ketentuan
    function openTermsModal() {
        const modal = document.getElementById('terms-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeTermsModal(e) {
        if (!e || e.target.id === 'terms-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('terms-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }

    // Modal Tentang FindUMB
    function openAboutModal() {
        const modal = document.getElementById('about-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeAboutModal(e) {
        if (!e || e.target.id === 'about-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('about-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }
</script>
@endpush
