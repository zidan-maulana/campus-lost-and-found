@extends('layouts.app')

@section('title', 'Pengaturan — Find UMB')

@section('header')
<header class="settings-header">
    <div class="settings-header-left">
        <button type="button" class="btn-header-back" onclick="handleSettingsBack()" aria-label="Kembali">
            <i data-lucide="chevron-left"></i>
        </button>
        <h1 class="settings-header-title">Pengaturan</h1>
    </div>
    <button type="button" class="btn-save-settings" id="btn-save-settings" onclick="saveSettings()">
        <i data-lucide="save"></i>
        <span class="btn-save-text-desktop">Simpan Pengaturan</span>
        <span class="btn-save-text-mobile">Simpan</span>
    </button>
</header>
@endsection

@push('styles')
<style>
    /* Settings Page Container */
    .settings-container {
        padding: 1rem 1.25rem 5.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.35rem;
    }

    /* Top Custom Sticky Header */
    .settings-header {
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border-bottom: 1px solid var(--border-subtle);
        position: sticky;
        top: 0;
        z-index: 40;
    }

    .settings-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-header-back {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        cursor: pointer;
        transition: var(--transition);
        margin-left: -4px;
    }

    .btn-header-back:hover {
        background: #f1f5f9;
    }

    .btn-header-back svg, .btn-header-back .lucide {
        width: 22px;
        height: 22px;
        stroke-width: 2.2;
    }

    .settings-header-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.01em;
    }

    /* Section Groups */
    .settings-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .settings-group-label {
        font-family: var(--font-main);
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        padding-left: 0.25rem;
    }

    /* Group Card */
    .settings-card {
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Row Item inside Card */
    .settings-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.95rem 1.1rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
        text-decoration: none;
        color: inherit;
    }

    .settings-row:last-child {
        border-bottom: none;
    }

    .settings-row-interactive {
        cursor: pointer;
    }

    .settings-row-interactive:hover {
        background: #f8fafc;
    }

    .settings-row-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 0;
        flex: 1;
    }

    .settings-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f0f7fd;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .settings-icon-box svg, .settings-icon-box .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.1;
    }

    .settings-icon-box.danger {
        background: #fee2e2;
        color: #ef4444;
    }

    .settings-row-text {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        min-width: 0;
    }

    .settings-row-title {
        font-family: var(--font-main);
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.3;
    }

    .settings-row-title.danger {
        color: #ef4444;
    }

    .settings-row-desc {
        font-family: var(--font-main);
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Dropdown Select for Language */
    .settings-select-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .settings-select {
        appearance: none;
        -webkit-appearance: none;
        background: #f8fafc;
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        padding: 0.45rem 2rem 0.45rem 0.75rem;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        outline: none;
        transition: var(--transition);
    }

    .settings-select:focus {
        border-color: var(--primary);
        background: #ffffff;
    }

    .settings-select-arrow {
        position: absolute;
        right: 0.65rem;
        pointer-events: none;
        color: #64748b;
        width: 14px;
        height: 14px;
    }

    /* iOS/Android Style Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 26px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(15, 23, 42, 0.15);
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: #1572b8;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    /* Chevron Right Indicator */
    .row-chevron {
        color: #94a3b8;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    /* Primary Save Button in Header */
    .btn-save-settings {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.52rem 1.15rem;
        height: 38px;
        box-sizing: border-box;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.2);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-save-settings:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(21, 114, 184, 0.28);
    }

    .btn-save-settings:active {
        transform: scale(0.98);
    }

    .btn-save-settings svg, .btn-save-settings .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
    }

    .btn-save-text-desktop {
        display: none;
    }

    .btn-save-text-mobile {
        display: inline;
    }

    @media (min-width: 1024px) {
        .btn-save-text-desktop {
            display: inline;
        }

        .btn-save-text-mobile {
            display: none;
        }
    }

    /* App Diagnostics Box */
    .diagnostics-header {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        font-family: var(--font-heading);
        font-size: 0.875rem;
        font-weight: 700;
        color: #0f172a;
    }

    .diagnostics-header i, .diagnostics-header svg {
        color: var(--primary);
        width: 18px;
        height: 18px;
    }

    .diagnostics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        padding: 0.85rem 1rem;
        gap: 0.5rem;
        background: #f8fafc;
    }

    .diag-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 0.55rem 0.35rem;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
    }

    .diag-label {
        font-size: 0.675rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.2rem;
    }

    .diag-val {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0f172a;
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

    /* Desktop Responsive Adjustments (min-width: 1024px) */
    @media (min-width: 1024px) {
        .settings-header {
            padding: 0.95rem clamp(1.75rem, 4vw, 4rem);
        }

        .settings-container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 2.25rem clamp(1.75rem, 4vw, 4rem) 4rem clamp(1.75rem, 4vw, 4rem);
            width: 100%;
            box-sizing: border-box;
            gap: 1.5rem;
        }

        .btn-save-settings {
            height: 40px;
            padding: 0.55rem 1.35rem;
            font-size: 0.88rem;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">

    <!-- 1. BAHASA APLIKASI -->
    <div class="settings-group">
        <span class="settings-group-label">Bahasa (Language)</span>
        <div class="settings-card">
            <div class="settings-row">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="globe"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Bahasa Aplikasi</span>
                    </div>
                </div>
                <div class="settings-select-wrap">
                    <select class="settings-select" id="app-language-select" onchange="handleLanguageChange(this.value)">
                        <option value="id" selected>Indonesia</option>
                        <option value="en">English</option>
                    </select>
                    <i data-lucide="chevron-down" class="settings-select-arrow"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. NOTIFIKASI CIVITAS -->
    <div class="settings-group">
        <span class="settings-group-label">Notifikasi &amp; Pengingat</span>
        <div class="settings-card">
            <!-- Push / Perangkat -->
            <div class="settings-row">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="bell"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Notifikasi Push Perangkat</span>
                        <span class="settings-row-desc">Pemberitahuan instan perangkat</span>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="toggle-notif-push" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Match Alerts / Klaim Masuk -->
            <div class="settings-row">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="sparkles"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Pemberitahuan Klaim Barang</span>
                        <span class="settings-row-desc">Notifikasi pengajuan klaim barang</span>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="toggle-notif-claim" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Status Updates -->
            <div class="settings-row">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="refresh-cw"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Pembaruan Status Serah Terima</span>
                        <span class="settings-row-desc">Update tahap proses serah terima</span>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="toggle-notif-status" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Chat Masuk -->
            <div class="settings-row">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="message-square"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Pesan &amp; Chat Masuk</span>
                        <span class="settings-row-desc">Pemberitahuan pesan chat masuk</span>
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" id="toggle-notif-chat" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- 3. BANTUAN & INFORMASI (Dipindahkan dari Halaman Profile) -->
    <div class="settings-group">
        <span class="settings-group-label">Bantuan &amp; Informasi</span>
        <div class="settings-card">
            <!-- Buku Panduan & Informasi -->
            <div class="settings-row settings-row-interactive" onclick="openGuideModal()">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="book-open"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Buku Panduan &amp; Informasi</span>
                        <span class="settings-row-desc">Panduan alur klaim, verifikasi kepemilikan, dan SOP serah terima</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="row-chevron"></i>
            </div>

            <!-- Kebijakan Privasi -->
            <div class="settings-row settings-row-interactive" onclick="openPrivacyModal()">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Kebijakan Privasi</span>
                        <span class="settings-row-desc">Perlindungan data identitas dan privasi civitas UMB</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="row-chevron"></i>
            </div>

            <!-- Syarat & Ketentuan -->
            <div class="settings-row settings-row-interactive" onclick="openTermsModal()">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="file-check"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Syarat &amp; Ketentuan</span>
                        <span class="settings-row-desc">Etika dan aturan penggunaan platform Lost &amp; Found kampus</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="row-chevron"></i>
            </div>

            <!-- Tentang FindUMB -->
            <div class="settings-row settings-row-interactive" onclick="openAboutModal()">
                <div class="settings-row-left">
                    <div class="settings-icon-box">
                        <i data-lucide="info"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title">Tentang FindUMB</span>
                        <span class="settings-row-desc">Pengembang mahasiswa dan ekosistem kampus Universitas Mercu Buana</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="row-chevron"></i>
            </div>
        </div>
    </div>

    <!-- 4. DIAGNOSTIK SISTEM -->
    <div class="settings-group">
        <span class="settings-group-label">Diagnostik Sistem</span>
        <div class="settings-card">
            <div class="diagnostics-header">
                <i data-lucide="info"></i>
                <span>FindUMB — Campus Lost &amp; Found</span>
            </div>
            <div class="diagnostics-grid">
                <div class="diag-item">
                    <span class="diag-label">Versi</span>
                    <span class="diag-val">1.0.0</span>
                </div>
                <div class="diag-item">
                    <span class="diag-label">Build</span>
                    <span class="diag-val">2026.1</span>
                </div>
                <div class="diag-item">
                    <span class="diag-label">Platform</span>
                    <span class="diag-val">Web / PWA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. KELUAR DARI AKUN (SIGN OUT) -->
    <div class="settings-group">
        <div class="settings-card">
            <div class="settings-row settings-row-interactive" onclick="handleSignOut()">
                <div class="settings-row-left">
                    <div class="settings-icon-box danger">
                        <i data-lucide="log-out"></i>
                    </div>
                    <div class="settings-row-text">
                        <span class="settings-row-title danger">Keluar dari Akun</span>
                        <span class="settings-row-desc">Akhiri sesi akun civitas aktif di perangkat ini</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="row-chevron"></i>
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

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="code-2"></i>
                    </div>
                    <h4 class="guide-card-title">Pengembang &amp; Civitas</h4>
                </div>
                <ul class="guide-list">
                    <li>Pengembang:
                        <ul style="margin-top: 0.3rem; padding-left: 1.15rem; list-style-type: disc; display: flex; flex-direction: column; gap: 0.2rem;">
                            <li><strong>Muhammad Isya Zidan Maulana</strong> (NIM: 41524010001)</li>
                            <li><strong>Faiz Muhammad Ridho</strong> (NIM: 41524010046)</li>
                            <li><strong>Rifky Nabil Firdaus</strong> (41524010055)</li>
                        </ul>
                    </li>
                    <li>Program Studi: <strong>Teknik Informatika — Fakultas Ilmu Komputer</strong>.</li>
                    <li>Institusi: <strong>Universitas Mercu Buana</strong> Jakarta.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <h4 class="guide-card-title">Layanan Bantuan &amp; Helpdesk</h4>
                </div>
                <ul class="guide-list">
                    <li>Email Dukungan: <strong>-</strong></li>
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
    // Preferensi dari akun (kolom preferences di Supabase), bukan localStorage.
    const SERVER_PREFERENCES = @json($preferences ?? []);
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        loadSavedSettings();
    });

    function handleSettingsBack() {
        if (document.referrer && document.referrer.includes(window.location.host) && !document.referrer.includes('/settings')) {
            window.location.href = document.referrer;
        } else {
            window.location.href = "{{ route('profile') }}";
        }
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

    function loadSavedSettings() {
        const settings = SERVER_PREFERENCES || {};
        if (settings.language && document.getElementById('app-language-select')) {
            document.getElementById('app-language-select').value = settings.language;
        }
        [['push','toggle-notif-push'],['claim','toggle-notif-claim'],['status','toggle-notif-status'],['chat','toggle-notif-chat']]
            .forEach(([key, id]) => {
                const el = document.getElementById(id);
                if (el && settings[key] !== undefined) el.checked = !!settings[key];
            });
    }

    function saveSettings() {
        const payload = {
            preferences: {
                language: document.getElementById('app-language-select')?.value || 'id',
                push: document.getElementById('toggle-notif-push')?.checked ?? true,
                claim: document.getElementById('toggle-notif-claim')?.checked ?? true,
                status: document.getElementById('toggle-notif-status')?.checked ?? true,
                chat: document.getElementById('toggle-notif-chat')?.checked ?? true,
            },
        };

        fetch('{{ route('api.settings.update') }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then(r => r.ok ? r.json() : Promise.reject(r))
            .then(() => showToast('✓ Pengaturan berhasil disimpan!'))
            .catch(() => showToast('Gagal menyimpan pengaturan.'));
    }

    function handleLanguageChange(val) {
        const name = val === 'en' ? 'English' : 'Indonesia';
        showToast(`Bahasa diatur ke ${name}`);
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

