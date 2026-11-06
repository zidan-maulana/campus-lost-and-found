@extends('layouts.app')

@section('title', 'Detail Laporan Barang — UMBFind')
@section('app_shell_class', 'bg-white')

@section('header')
<!-- Custom edge-to-edge layout, header digantikan floating bar di atas foto -->
@endsection

@push('styles')
<style>
    /* Detail Page Container */
    .detail-page-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        background-color: var(--bg-app);
        position: relative;
    }

    /* 1. Hero Image Container */
    .detail-hero-wrapper {
        position: relative;
        width: 100%;
        height: 310px;
        background: #0f172a;
        overflow: hidden;
    }

    .detail-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    /* Top Floating Action Header over Image */
    .detail-floating-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 1.15rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 25;
        background: linear-gradient(to bottom, rgba(15, 23, 42, 0.55) 0%, rgba(15, 23, 42, 0.2) 65%, transparent 100%);
    }

    .floating-circle-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        transition: var(--transition);
        text-decoration: none;
        pointer-events: auto;
        -webkit-tap-highlight-color: transparent;
        z-index: 30;
    }

    .floating-circle-btn:active {
        transform: scale(0.92);
        background: #f8fafc;
    }

    .floating-circle-btn svg, .floating-circle-btn .lucide {
        width: 20px;
        height: 20px;
        stroke-width: 2.3;
        pointer-events: none;
    }

    .floating-actions-right {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    /* 2. Overlapping Detail Card Sheet */
    .detail-content-card {
        margin-top: -26px;
        border-radius: 26px 26px 0 0;
        background: #ffffff;
        padding: 1.4rem 1.25rem 105px 1.25rem;
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.04);
        min-height: calc(100vh - 284px);
        margin-bottom: -95px; /* Memanjang ke bawah memenuhi layar hingga dasar paling bawah */
    }

    /* Main Title */
    .detail-heading-title {
        font-family: var(--font-heading);
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.3;
        letter-spacing: -0.015em;
        margin: 0;
    }

    /* Author / Reporter Information */
    .detail-author-row {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-top: -0.35rem;
    }

    .detail-author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e0f2fe;
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .detail-author-avatar svg, .detail-author-avatar .lucide {
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
    }

    .detail-author-meta {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 0.35rem;
        font-size: 0.8rem;
        color: #64748b;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
    }

    .detail-author-name {
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 175px;
    }

    .reporter-badge-verified {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        background: transparent;
        padding: 0;
        flex-shrink: 0;
    }

    .reporter-badge-verified svg, .reporter-badge-verified .lucide {
        width: 15px;
        height: 15px;
        stroke-width: 2.5;
        color: #0284c7;
    }

    .detail-meta-dot {
        color: #cbd5e1;
        font-weight: bold;
        flex-shrink: 0;
    }

    .detail-post-date {
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* 3. Item Properties Table */
    .detail-properties-table {
        display: flex;
        flex-direction: column;
        padding: 0 4px 0 8px;
    }

    .detail-prop-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.85rem;
        line-height: 1.45;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-prop-row:first-child {
        padding-top: 0.25rem;
    }

    .detail-prop-label {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        width: 140px;
        flex-shrink: 0;
        color: #64748b;
        font-weight: 500;
    }

    .detail-prop-label svg, .detail-prop-label .lucide {
        width: 16px;
        height: 16px;
        color: #64748b;
        flex-shrink: 0;
        stroke-width: 2.2;
    }

    .detail-prop-value {
        flex: 1;
        min-width: 0;
        color: #0f172a;
        font-weight: 600;
        word-break: break-word;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-pill);
        font-size: 0.725rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .badge-lost {
        background: var(--lost-bg);
        color: var(--lost-text);
    }

    .badge-found {
        background: var(--found-bg);
        color: var(--found-text);
    }

    /* Divider */
    .detail-divider {
        border: none;
        height: 1px;
        background-color: #f1f5f9;
        margin: 0.25rem 0;
    }

    /* 4. Description Section */
    .detail-description-section {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.35rem;
        padding: 0 4px 0 8px;
    }

    .detail-section-heading {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        padding: 0;
        line-height: 1.3;
    }

    .detail-description-content {
        font-size: 0.85rem;
        line-height: 1.55;
        color: #334155;
        margin: 0;
        padding: 0;
        white-space: normal;
    }

    /* 7. Bottom Sticky Action Bar (Flat, No Shadows) */
    .detail-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-top: 1px solid var(--border-subtle);
        padding: 0.85rem 1.25rem 1.15rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 50;
        box-shadow: none !important;
    }

    .btn-action-primary {
        flex: 1;
        background: #0f5791; /* UMBFind Blue */
        color: #ffffff;
        border: none;
        height: 48px;
        border-radius: 12px;
        font-family: var(--font-main);
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        text-decoration: none;
        box-shadow: none !important;
        transition: var(--transition);
    }

    .btn-action-primary:active {
        background: #0b4372;
        transform: scale(0.99);
    }

    .btn-action-primary svg, .btn-action-primary .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.3;
    }

    .btn-action-bookmark {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: none !important;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .btn-action-bookmark:active {
        background: #f8fafc;
        transform: scale(0.96);
    }

    .btn-action-bookmark.bookmarked {
        color: #eab308;
        background: #fefce8;
        border-color: #fef08a;
    }

    .btn-action-bookmark svg, .btn-action-bookmark .lucide {
        width: 20px;
        height: 20px;
        stroke-width: 2.2;
    }

    /* Owner Action Controls */
    .btn-action-delete-mine {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .btn-action-delete-mine:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action-delete-mine svg, .btn-action-delete-mine .lucide {
        width: 19px;
        height: 19px;
        stroke-width: 2.2;
    }

    /* Modal Backdrop & Sheets */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(3px);
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
    }

    .modal-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-card {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 440px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transform: scale(0.95);
        transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-backdrop.active .modal-card {
        transform: scale(1);
    }

    .modal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.25rem;
    }

    .modal-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .btn-modal-icon-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-icon-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .modal-form-body {
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
    }

    .modal-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .modal-label {
        font-size: 0.775rem;
        font-weight: 600;
        color: #334155;
        line-height: 1.2;
    }

    .modal-input, .modal-select, .modal-textarea {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        font-family: var(--font-main);
        font-size: 0.85rem;
        color: #0f172a;
        outline: none;
        transition: var(--transition);
        width: 100%;
        box-sizing: border-box;
    }

    .modal-input:focus, .modal-select:focus, .modal-textarea:focus {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .modal-textarea {
        resize: vertical;
        min-height: 85px;
        line-height: 1.45;
    }

    .modal-actions-row {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.65rem;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .btn-modal-cancel:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .btn-modal-submit {
        flex: 1.4;
        padding: 0.75rem;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(21, 114, 184, 0.22);
        text-align: center;
    }

    .btn-modal-submit:hover {
        background: var(--primary-dark);
    }

    .btn-modal-danger {
        flex: 1.4;
        padding: 0.75rem;
        border-radius: 10px;
        border: none;
        background: #ef4444;
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .btn-modal-danger:hover {
        background: #dc2626;
    }

    /* Toast Notification */
    .toast-popup {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #0f172a;
        color: #ffffff;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-pill);
        font-size: 0.825rem;
        font-weight: 500;
        z-index: 100;
        opacity: 0;
        pointer-events: none;
        transition: var(--transition);
        box-shadow: 0 4px 14px rgba(0,0,0,0.18);
        white-space: nowrap;
    }

    .toast-popup.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<div class="detail-page-container">

    <!-- 1. Hero Image Container with Edge-to-Edge Visual & Lightbox Trigger -->
    <div class="detail-hero-wrapper" onclick="openImageLightbox(document.getElementById('detail-hero-img').src, document.getElementById('detail-title').textContent)" style="cursor: zoom-in;" title="Klik untuk lihat detail foto">
        <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80" alt="Foto Barang" class="detail-hero-img" id="detail-hero-img">
        
        <div class="desktop-detail-zoom-pill">
            <i data-lucide="zoom-in"></i>
            <span>Lihat Detail Foto</span>
        </div>

        <!-- Floating Header Buttons -->
        <div class="detail-floating-header">
            <a href="{{ route('home') }}" class="floating-circle-btn" id="btn-detail-back" onclick="event.stopPropagation(); handleDetailBack(event)" aria-label="Kembali">
                <i data-lucide="chevron-left"></i>
            </a>
            <div class="floating-actions-right">
                <button type="button" class="floating-circle-btn" onclick="event.stopPropagation(); handleDetailShare()" aria-label="Bagikan Laporan">
                    <i data-lucide="share-2"></i>
                </button>
                <button type="button" class="floating-circle-btn" id="btn-top-bookmark" onclick="event.stopPropagation(); toggleDetailBookmark()" aria-label="Simpan Laporan">
                    <i data-lucide="bookmark"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Overlapping Detail Card Sheet -->
    <article class="detail-content-card">
        
        <!-- Heading Title -->
        <h1 class="detail-heading-title" id="detail-title">Cincin Emas Berlian</h1>

        <!-- Author / Reporter Row -->
        <div class="detail-author-row">
            <div class="detail-author-avatar">
                <i data-lucide="user"></i>
            </div>
            <div class="detail-author-meta">
                <span class="detail-author-name" id="reporter-display-name">Civitas UMB</span>
                <span class="reporter-badge-verified" title="Terverifikasi">
                    <i data-lucide="check-circle-2"></i>
                </span>
                <span class="detail-meta-dot">•</span>
                <span class="detail-post-date" id="detail-post-date">28 Okt 2026</span>
            </div>
        </div>

        <!-- 3. Structured Properties Table -->
        <div class="detail-properties-table">
            <!-- Status Laporan -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="tag"></i>
                    <span>Status:</span>
                </div>
                <div class="detail-prop-value">
                    <span class="badge-status badge-lost" id="detail-status-badge">Hilang</span>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="calendar"></i>
                    <span id="label-prop-date">Tanggal Hilang:</span>
                </div>
                <div class="detail-prop-value" id="detail-date">15 Jan 2026</div>
            </div>

            <!-- Perkiraan Waktu -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="clock"></i>
                    <span>Waktu:</span>
                </div>
                <div class="detail-prop-value" id="detail-time">Sekitar Pagi</div>
            </div>

            <!-- Lokasi Kampus -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="map-pin"></i>
                    <span>Lokasi Kampus:</span>
                </div>
                <div class="detail-prop-value" id="detail-location">Kampus Meruya — Parkir Barat Kampus (Dekat Pos Satpam)</div>
            </div>

            <!-- Kategori Barang -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="layers"></i>
                    <span>Kategori:</span>
                </div>
                <div class="detail-prop-value" id="detail-category">Aksesoris &amp; Perhiasan</div>
            </div>

            <!-- Merek / Tipe -->
            <div class="detail-prop-row">
                <div class="detail-prop-label">
                    <i data-lucide="bookmark"></i>
                    <span>Merek / Tipe:</span>
                </div>
                <div class="detail-prop-value" id="detail-brand">Emas Kuning</div>
            </div>

            <!-- Status Simpan / Pengamanan -->
            <div class="detail-prop-row" id="row-prop-storage">
                <div class="detail-prop-label">
                    <i data-lucide="shield-check"></i>
                    <span>Status Simpan:</span>
                </div>
                <div class="detail-prop-value" id="detail-storage">Saya Simpan Sendiri</div>
            </div>
        </div>

        <!-- 4. Deskripsi & Ciri Khusus -->
        <div class="detail-description-section">
            <h2 class="detail-section-heading">Deskripsi &amp; Ciri Khusus</h2>
            <p class="detail-description-content" id="detail-features">Cincin emas polos dengan ukiran tipis di bagian dalam dan mata berlian kecil. Kemungkinan terjatuh saat melepas sarung tangan di area parkiran barat motor.</p>
        </div>

    </article>
</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite">
    Pesan notifikasi
</div>

<!-- EDIT REPORT MODAL (CRUD: Update) -->
<div class="modal-backdrop" id="modal-edit-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-header-row">
            <h2 class="modal-title">Edit Laporan Barang</h2>
            <button type="button" class="btn-modal-icon-close" onclick="closeDetailEditModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="form-edit-report" onsubmit="handleSaveDetailEdit(event)">
            <input type="hidden" id="edit-report-id">

            <div class="modal-form-body">
                <div class="modal-form-group">
                    <label for="edit-title" class="modal-label">Nama Barang / Judul</label>
                    <input type="text" id="edit-title" class="modal-input" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-status" class="modal-label">Status Laporan</label>
                    <select id="edit-status" class="modal-select">
                        <option value="lost">Hilang (Sedang Dicari)</option>
                        <option value="found">Temuan (Telah Ditemukan)</option>
                        <option value="resolved">Terselesaikan (Selesai Serah Terima)</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label for="edit-category" class="modal-label">Kategori Barang</label>
                    <select id="edit-category" class="modal-select">
                        <option value="Elektronik & HP">Elektronik &amp; HP</option>
                        <option value="Tas & Ransel">Tas &amp; Ransel</option>
                        <option value="Dompet & Kartu">Dompet &amp; Kartu</option>
                        <option value="Kunci Kendaraan / Ruangan">Kunci Kendaraan / Ruangan</option>
                        <option value="Jam & Aksesori">Jam &amp; Aksesori</option>
                        <option value="Buku, Dokumen & ATK">Buku, Dokumen &amp; ATK</option>
                        <option value="Pakaian & Sepatu">Pakaian &amp; Sepatu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label for="edit-brand" class="modal-label">Merek / Brand (Opsional)</label>
                    <input type="text" id="edit-brand" class="modal-input" placeholder="Contoh: Samsung, Eiger, Casio">
                </div>

                <div class="modal-form-group">
                    <label for="edit-location" class="modal-label">Lokasi Kampus &amp; Ruangan</label>
                    <input type="text" id="edit-location" class="modal-input" placeholder="Contoh: Gedung Tower Lt. 7 R. 702" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-date" class="modal-label">Tanggal Kejadian</label>
                    <input type="date" id="edit-date" class="modal-input" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-features" class="modal-label">Deskripsi &amp; Ciri Khusus</label>
                    <textarea id="edit-features" class="modal-textarea" placeholder="Warna, goresan, stiker, atau ciri pembeda..."></textarea>
                </div>

                <div class="modal-actions-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeDetailEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL (CRUD: Delete) -->
<div class="modal-backdrop" id="modal-delete-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="trash-2" style="width: 26px; height: 26px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Hapus Laporan?</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeDetailDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-danger" onclick="executeDetailDelete()">Hapus</button>
        </div>
    </div>
</div>

<!-- VALIDATION MODAL FOR STATUS CHANGE IN EDIT MODAL -->
<div class="modal-backdrop" id="modal-edit-status-confirm" role="dialog" aria-modal="true" aria-hidden="true" style="z-index: 110;">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="check-circle-2" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Validasi Status Laporan</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menyelesaikan laporan ini? Status barang akan diubah menjadi <strong>Terselesaikan</strong>.</p>
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeDetailEditStatusConfirmModal()">Batal</button>
            <button type="button" class="btn-modal-submit btn-modal-success" onclick="executeDetailSaveEditWithStatusConfirm()">Ya, Selesaikan</button>
        </div>
    </div>
</div>
@endsection

@section('custom_bottom_bar')
<!-- Fixed Bottom Action Bar (Flat, No Shadows) -->
<div class="detail-bottom-nav" id="detail-bottom-nav">
    <a href="{{ route('messages') }}" class="btn-action-primary" id="btn-primary-action">
        <i data-lucide="hand"></i>
        <span id="btn-action-label">Saya Menemukan Barang Ini</span>
    </a>
    <button type="button" class="btn-action-bookmark" id="btn-bookmark-action" onclick="toggleDetailBookmark()" aria-label="Simpan Laporan">
        <i data-lucide="bookmark"></i>
    </button>
    <button type="button" class="btn-action-delete-mine" id="btn-action-delete-mine" onclick="openDetailDeleteModal()" style="display: none;" aria-label="Hapus Laporan" title="Hapus Laporan">
        <i data-lucide="trash-2"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
    // Current Report ID passed from Laravel route
    const currentReportId = @json($id ?? '1');

    // Default Seed Reports for User
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

    function isMyReport(id) {
        const userReports = getUserReports();
        return userReports.some(r => String(r.id) === String(id));
    }

    // Built-in Mock Reports Catalog
    const mockReportsCatalog = {
        '1': {
            id: '1',
            type: 'lost',
            title: 'Samsung SM-A135F',
            brand: 'Samsung Galaxy A13',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung Tower Lt. 7 R. 702',
            location_full: 'Kampus Meruya — Gedung Tower Lt. 7 R. 702',
            date: '2026-06-20',
            date_display: '20 Jun 2026',
            time: '14.30 WIB (Siang)',
            storage: 'Disimpan Sendiri',
            features: 'Smartphone Samsung warna hitam, terpasang casing transparan dengan stiker logo Informatika di bagian belakang. Layar dalam keadaan terkunci.',
            image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Fasilkom',
            reporter_meta: 'Civitas Terverifikasi • NIM 4152***'
        },
        '2': {
            id: '2',
            type: 'lost',
            title: 'Paspor & Berkas Beasiswa',
            brand: 'Dokumen Resmi Imigrasi',
            category: 'Buku, Dokumen & ATK',
            category_slug: 'dokumen',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung E R. 204',
            location_full: 'Kampus Meruya — Gedung E R. 204',
            date: '2026-05-12',
            date_display: '12 Mei 2026',
            time: '10.00 - 11.30 WIB',
            storage: 'Disimpan Sendiri',
            features: 'Map plastik biru berisi Paspor Republik Indonesia atas nama mahasiswa dan berkas pendaftaran beasiswa luar negeri.',
            image: 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswi FEB',
            reporter_meta: 'Civitas Terverifikasi • NIM 4312***'
        },
        '3': {
            id: '3',
            type: 'found',
            title: 'Tasbih & Gelang Manik Merah',
            brand: 'Aksesoris Ibadah',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            location_campus: 'Kampus Meruya',
            location_specific: 'Masjid Manarul \'Amal UMB, Area Saf Laki-laki',
            location_full: 'Kampus Meruya — Masjid Manarul \'Amal UMB',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Ba\'da Salat Dzuhur',
            storage: 'Dititipkan di Pengurus DKM Masjid',
            features: 'Tasbih kayu berbutir merah marun berjumlah 33 butir dan gelang manik elastis. Saat ini diamankan di meja pengurus DKM.',
            image: 'https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Pengurus DKM Manarul \'Amal',
            reporter_meta: 'Pengurus Masjid Kampus UMB'
        },
        '4': {
            id: '4',
            type: 'found',
            title: 'Kunci Motor Honda & Remote',
            brand: 'Honda Smart Key',
            category: 'Kunci Kendaraan / Ruangan',
            category_slug: 'kunci',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya (Dekat Pohon Rindang)',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-10',
            date_display: '10 Jan 2026',
            time: 'Pagi (± 08.15 WIB)',
            storage: 'Dititipkan di Pos Satpam Parkir',
            features: 'Kunci motor Honda dengan gantungan tali lanyard hitam bertuliskan One Heart dan remote alarm hitam.',
            image: 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Petugas Keamanan Parkir',
            reporter_meta: 'Biro Keamanan & Ketertiban Kampus'
        },
        '5': {
            id: '5',
            type: 'lost',
            title: 'Cincin Emas Berlian',
            brand: 'Perhiasan Emas Kuning',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Siang saat menuju kendaraan',
            storage: 'Disimpan Sendiri',
            features: 'Cincin emas sepasang model pertunangan/pernikahan dengan ukiran inisial halus di lingkar dalam. Terjatuh di sekitar parkiran.',
            image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Civitas UMB',
            reporter_meta: 'Civitas Terverifikasi • UMB Meruya'
        },
        '6': {
            id: '6',
            type: 'lost',
            title: 'Tablet Samsung Tab S8',
            brand: 'Samsung Galaxy Tab S8',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung E Lt. 3 Ruang Diskusi',
            location_full: 'Kampus Meruya — Gedung E Lt. 3 Ruang Diskusi',
            date: '2026-01-14',
            date_display: '14 Jan 2026',
            time: 'Sore (± 16.00 WIB)',
            storage: 'Disimpan Sendiri',
            features: 'Tablet warna graphite abu-abu tua dengan book cover magnetic hitam dan S-Pen menempel di punggung tablet.',
            image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Desain (FDSK)',
            reporter_meta: 'Civitas Terverifikasi • NIM 4232***'
        },
        '7': {
            id: '7',
            type: 'lost',
            title: 'Tas Laptop Lenovo ThinkPad',
            brand: 'ThinkPad Essential Backpack',
            category: 'Tas & Ransel',
            category_slug: 'tas',
            location_campus: 'Kampus Meruya',
            location_specific: 'Lab Komputer Gedung Tower Lt. 4',
            location_full: 'Kampus Meruya — Lab Komputer Gedung Tower',
            date: '2026-01-18',
            date_display: '18 Jan 2026',
            time: '13.00 WIB usai praktikum',
            storage: 'Dititipkan di Lab Komputer Tower',
            features: 'Ransel laptop hitam bergaris merah khas ThinkPad, di dalamnya ada charger laptop 65W dan buku catatan kuliah.',
            image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Teknik Elektro',
            reporter_meta: 'Civitas Terverifikasi • NIM 4142***'
        },
        '8': {
            id: '8',
            type: 'lost',
            title: 'Dompet Kulit & KTM Mahasiswa',
            brand: 'Dompet Kulit Cokelat',
            category: 'Dompet & Kartu',
            category_slug: 'tas',
            location_campus: 'Kampus Meruya',
            location_specific: 'Kantin Gedung D Lantai 1',
            location_full: 'Kampus Meruya — Kantin Gedung D',
            date: '2026-02-08',
            date_display: '08 Feb 2026',
            time: 'Istirahat makan siang',
            storage: 'Dititipkan di Pengelola Kantin',
            features: 'Dompet kulit warna cokelat tua berisi KTM UMB, e-KTP, kartu debit bank, dan uang tunai.',
            image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Fikom',
            reporter_meta: 'Civitas Terverifikasi • NIM 4422***'
        },
        '9': {
            id: '9',
            type: 'found',
            title: 'Jaket Almamater UMB',
            brand: 'Jas Almamater Resmi UMB',
            category: 'Pakaian & Sepatu',
            category_slug: 'pakaian',
            location_campus: 'Kampus Meruya',
            location_specific: 'Perpustakaan Pusat Lantai 2, Meja Baca Mandiri',
            location_full: 'Kampus Meruya — Perpustakaan Pusat Lt. 2',
            date: '2026-01-22',
            date_display: '22 Jan 2026',
            time: '17.00 WIB (Sore)',
            storage: 'Dititipkan di Staf Perpustakaan',
            features: 'Jaket almamater biru navy ukuran L dengan badge bordir logo Universitas Mercu Buana di dada kiri. Tertinggal di sandaran kursi.',
            image: 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Staf Perpustakaan UMB',
            reporter_meta: 'Pengelola Fasilitas Perpustakaan'
        },
        '10': {
            id: '10',
            type: 'found',
            title: 'Earphone TWS Anker Soundcore',
            brand: 'Anker Soundcore R50i',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gazebo Taman Depan Gedung Tower',
            location_full: 'Kampus Meruya — Gazebo Taman Kampus',
            date: '2026-02-02',
            date_display: '02 Feb 2026',
            time: '15.45 WIB (Sore)',
            storage: 'Dititipkan di Pos Keamanan Kampus',
            features: 'Charging case TWS warna hitam matte beserta sepasang earphone di dalamnya. Kondisi baterai masih menyala.',
            image: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Civitas UMB',
            reporter_meta: 'Civitas Terverifikasi • Kampus Meruya'
        }
    };

    let activeReportData = null;

    // Load active report data
    function loadReportData() {
        const userReports = getUserReports();
        const foundCustom = userReports.find(r => String(r.id) === String(currentReportId));
        if (foundCustom) {
            activeReportData = {
                ...foundCustom,
                reporter_name: 'Ahmad Fauzi (Saya)',
                reporter_meta: 'Civitas Terverifikasi • Akun Saya',
                is_mine: true
            };
        } else if (mockReportsCatalog[currentReportId]) {
            activeReportData = mockReportsCatalog[currentReportId];
        } else {
            // Fallback to item 5 or 1 if arbitrary string passed
            activeReportData = mockReportsCatalog['5'] || mockReportsCatalog['1'];
        }

        renderReportDetails(activeReportData);
    }

    function renderReportDetails(report) {
        if (!report) return;

        // Title & Document Title
        document.title = `${report.title} — Detail Laporan UMBFind`;
        const titleEl = document.getElementById('detail-title');
        if (titleEl) titleEl.textContent = report.title;

        // Hero Image
        const imgEl = document.getElementById('detail-hero-img');
        if (imgEl) {
            imgEl.src = report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=800&auto=format&fit=crop&q=80';
            imgEl.alt = report.title;
        }

        // Status Badge
        const badgeEl = document.getElementById('detail-status-badge');
        if (badgeEl) {
            if (report.status === 'resolved' || report.status === 'completed') {
                badgeEl.className = 'badge-status badge-resolved';
                badgeEl.textContent = 'Selesai';
            } else if (report.type === 'found') {
                badgeEl.className = 'badge-status badge-found';
                badgeEl.textContent = 'Temuan';
            } else {
                badgeEl.className = 'badge-status badge-lost';
                badgeEl.textContent = 'Hilang';
            }
        }

        // Reporter & Post Date
        const repNameEl = document.getElementById('reporter-display-name');
        if (repNameEl) repNameEl.textContent = report.reporter_name || 'Civitas UMB';
        const postDateEl = document.getElementById('detail-post-date');
        if (postDateEl) {
            postDateEl.textContent = report.date_display || report.date || 'Baru Saja';
        }

        // Location with clean formatting
        const locEl = document.getElementById('detail-location');
        if (locEl) {
            locEl.textContent = report.location_full || report.location_campus || 'Kampus Meruya';
        }

        // Metric: Date
        const metricDateEl = document.getElementById('detail-date');
        const metricDateLabel = document.getElementById('label-prop-date');
        if (metricDateEl) metricDateEl.textContent = report.date_display || report.date || 'Hari Ini';
        if (metricDateLabel) {
            metricDateLabel.textContent = report.type === 'found' ? 'Tanggal Temuan:' : 'Tanggal Hilang:';
        }

        // Metric: Time
        const metricTimeEl = document.getElementById('detail-time');
        if (metricTimeEl) {
            if (report.time) {
                const cleanTime = report.time.replace(/^waktu\s*:\s*/i, '').trim();
                metricTimeEl.textContent = cleanTime;
            } else {
                metricTimeEl.textContent = 'Tidak ditentukan';
            }
        }

        // Metric: Category & Brand
        const catEl = document.getElementById('detail-category');
        if (catEl) catEl.textContent = report.category_label || report.category || 'Lainnya';
        const brandEl = document.getElementById('detail-brand');
        if (brandEl) {
            brandEl.textContent = report.brand ? report.brand : 'Sesuai Ciri Fisik';
        }

        // Metric: Storage Status
        const storageEl = document.getElementById('detail-storage');
        const storageRow = document.getElementById('row-prop-storage');
        if (storageEl && storageRow) {
            if (report.storage) {
                storageEl.textContent = report.storage;
                storageRow.style.display = 'flex';
            } else if (report.type === 'found') {
                storageEl.textContent = 'Saya Simpan Sendiri';
                storageRow.style.display = 'flex';
            } else {
                storageRow.style.display = 'none';
            }
        }

        // Features & Description
        const featEl = document.getElementById('detail-features');
        if (featEl) {
            featEl.textContent = (report.features || 'Tidak ada catatan deskripsi tambahan yang disertakan.').trim();
        }

        // Owner Checking Logic: jika laporan milik user sendiri
        const isOwner = isMyReport(report.id) || report.is_mine;
        const topBookmarkBtn = document.getElementById('btn-top-bookmark');
        const bottomBookmarkBtn = document.getElementById('btn-bookmark-action');
        const bottomDeleteBtn = document.getElementById('btn-action-delete-mine');
        const primaryBtn = document.getElementById('btn-primary-action');
        const actionLabel = document.getElementById('btn-action-label');

        if (isOwner) {
            // Hide bookmark button for report author
            if (topBookmarkBtn) topBookmarkBtn.style.display = 'none';

            // Sembunyikan tombol simpan bawah, tampilkan tombol hapus
            if (bottomBookmarkBtn) bottomBookmarkBtn.style.display = 'none';
            if (bottomDeleteBtn) bottomDeleteBtn.style.display = 'flex';

            // Switch action button to Edit Report for author
            if (primaryBtn) {
                primaryBtn.href = "javascript:void(0)";
                primaryBtn.onclick = function(e) {
                    e.preventDefault();
                    openDetailEditModal();
                };
                primaryBtn.innerHTML = `<i data-lucide="edit-3"></i><span>Edit Laporan</span>`;
            }
        } else {
            if (topBookmarkBtn) topBookmarkBtn.style.display = 'flex';
            if (bottomBookmarkBtn) bottomBookmarkBtn.style.display = 'flex';
            if (bottomDeleteBtn) bottomDeleteBtn.style.display = 'none';

            if (primaryBtn && actionLabel) {
                const chatUrl = `{{ route('messages') }}?item=${encodeURIComponent(report.title)}&report_id=${encodeURIComponent(report.id)}`;
                primaryBtn.href = chatUrl;
                primaryBtn.onclick = null;

                if (report.type === 'found') {
                    actionLabel.textContent = 'Saya Pemilik Barang Ini (Klaim)';
                    primaryBtn.innerHTML = `<i data-lucide="shield-check"></i><span>Saya Pemilik Barang Ini (Klaim)</span>`;
                } else {
                    actionLabel.textContent = 'Saya Menemukan Barang Ini';
                    primaryBtn.innerHTML = `<i data-lucide="hand"></i><span>Saya Menemukan Barang Ini</span>`;
                }
            }
            syncBookmarkButtonState();
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Options dropdown in detail floating header
    function toggleDetailOptions(event) {
        event.stopPropagation();
        const menu = document.getElementById('detail-options-menu');
        if (menu) {
            menu.classList.toggle('active');
        }
    }

    document.addEventListener('click', () => {
        const menu = document.getElementById('detail-options-menu');
        if (menu) menu.classList.remove('active');
    });

    // Detail Edit Modal Functions
    function openDetailEditModal() {
        const menu = document.getElementById('detail-options-menu');
        if (menu) menu.classList.remove('active');

        if (!activeReportData) return;
        document.getElementById('edit-report-id').value = activeReportData.id;
        document.getElementById('edit-title').value = activeReportData.title || '';
        document.getElementById('edit-status').value = activeReportData.status === 'resolved' ? 'resolved' : (activeReportData.type || 'lost');
        document.getElementById('edit-category').value = activeReportData.category || 'Elektronik & HP';
        document.getElementById('edit-brand').value = activeReportData.brand || '';
        document.getElementById('edit-location').value = activeReportData.location_full || activeReportData.location_campus || '';
        document.getElementById('edit-date').value = activeReportData.date || new Date().toISOString().split('T')[0];
        document.getElementById('edit-features').value = activeReportData.features || '';

        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeDetailEditModal() {
        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function handleSaveDetailEdit(event) {
        event.preventDefault();
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(currentReportId));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;

        // Validate completion modal requirements
        if (statusVal === 'resolved' && reports[index].status !== 'resolved') {
            openDetailEditStatusConfirmModal();
            return;
        }

        executeDetailSaveEditCommit();
    }

    function openDetailEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeDetailEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeDetailSaveEditWithStatusConfirm() {
        closeDetailEditStatusConfirmModal();
        executeDetailSaveEditCommit();
    }

    function executeDetailSaveEditCommit() {
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(currentReportId));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;
        const newTitle = document.getElementById('edit-title').value.trim();
        const newCategory = document.getElementById('edit-category').value;
        const newBrand = document.getElementById('edit-brand').value.trim();
        const newLocation = document.getElementById('edit-location').value.trim();
        const newDate = document.getElementById('edit-date').value;
        const newFeatures = document.getElementById('edit-features').value.trim();

        reports[index].title = newTitle;
        reports[index].category = newCategory;
        reports[index].category_label = newCategory;
        reports[index].brand = newBrand;
        reports[index].location_full = newLocation;
        reports[index].location_campus = newLocation;
        reports[index].date = newDate;
        reports[index].date_display = formatDateIndo(newDate);
        reports[index].features = newFeatures;

        if (statusVal === 'resolved') {
            reports[index].status = 'resolved';
        } else {
            reports[index].type = statusVal;
            reports[index].status = statusVal;
        }

        try {
            localStorage.setItem('findumb_custom_reports', JSON.stringify(reports));
        } catch(e) {}

        activeReportData = reports[index];
        closeDetailEditModal();
        renderReportDetails(activeReportData);
        showToast('✓ Laporan berhasil diperbarui!');
    }

    function openDetailDeleteModal() {
        const menu = document.getElementById('detail-options-menu');
        if (menu) menu.classList.remove('active');
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeDetailDeleteModal() {
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeDetailDelete() {
        let reports = getUserReports();
        reports = reports.filter(r => String(r.id) !== String(currentReportId));
        try {
            localStorage.setItem('findumb_custom_reports', JSON.stringify(reports));
        } catch(e) {}

        closeDetailDeleteModal();
        showToast('Laporan berhasil dihapus.');
        setTimeout(() => {
            window.location.href = "{{ route('my.reports') }}";
        }, 800);
    }

    function formatDateIndo(dateStr) {
        if (!dateStr) return '';
        try {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const day = parseInt(parts[2], 10);
                const month = months[parseInt(parts[1], 10) - 1];
                const year = parts[0];
                return `${day} ${month} ${year}`;
            }
        } catch(e) {}
        return dateStr;
    }

    // Navigation Back Handler
    function handleDetailBack(event) {
        if (event) {
            event.preventDefault();
        }

        const referrer = document.referrer;
        const currentOrigin = window.location.origin || (window.location.protocol + '//' + window.location.host);

        // 1. If user navigated from within the app (e.g. Search, Home, Messages) and not this exact URL
        if (referrer && referrer.startsWith(currentOrigin) && referrer !== window.location.href) {
            window.location.href = referrer;
            return;
        }

        // 2. If browser has history from the same origin
        if (window.history.length > 1 && referrer && referrer.startsWith(currentOrigin)) {
            window.history.back();
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 250);
            return;
        }

        // 3. Fallback direct navigation to Home
        window.location.href = "{{ route('home') }}";
    }

    // Share Handler
    function handleDetailShare() {
        const title = activeReportData ? activeReportData.title : 'Laporan Barang Temuan/Hilang';
        if (navigator.share) {
            navigator.share({
                title: `${title} — UMBFind`,
                text: `Lihat detail laporan ${title} di UMBFind (Campus Lost & Found).`,
                url: window.location.href
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showToast('Tautan laporan berhasil disalin!');
            }).catch(() => {
                showToast('Tautan siap dibagikan.');
            });
        }
    }

    // Stored Bookmarks Management
    function getStoredBookmarks() {
        try {
            const raw = localStorage.getItem('findumb_bookmarks');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) return parsed.map(String);
            }
        } catch(e) {}
        const defaultBookmarks = ['2', '3', '7'];
        try {
            if (!localStorage.getItem('findumb_bookmarks_init')) {
                localStorage.setItem('findumb_bookmarks', JSON.stringify(defaultBookmarks));
                localStorage.setItem('findumb_bookmarks_init', 'true');
            }
        } catch(e) {}
        return defaultBookmarks;
    }

    function setStoredBookmarks(bookmarks) {
        try {
            localStorage.setItem('findumb_bookmarks', JSON.stringify(bookmarks));
            localStorage.setItem('findumb_bookmarks_init', 'true');
        } catch(e) {}
    }

    // Bookmark Toggle Handler
    function toggleDetailBookmark() {
        const topBtn = document.getElementById('btn-top-bookmark');
        const bottomBtn = document.getElementById('btn-bookmark-action');

        let bookmarks = getStoredBookmarks();
        const reportKey = String(currentReportId);
        const index = bookmarks.indexOf(reportKey);

        if (index > -1) {
            bookmarks.splice(index, 1);
            if (topBtn) topBtn.classList.remove('bookmarked');
            if (bottomBtn) bottomBtn.classList.remove('bookmarked');
            showToast('Laporan dihapus dari daftar tersimpan.');
        } else {
            bookmarks.push(reportKey);
            if (topBtn) topBtn.classList.add('bookmarked');
            if (bottomBtn) bottomBtn.classList.add('bookmarked');
            showToast('Laporan disimpan ke daftar tersimpan.');
        }

        setStoredBookmarks(bookmarks);
    }

    function syncBookmarkButtonState() {
        const bookmarks = getStoredBookmarks();
        const isBookmarked = bookmarks.includes(String(currentReportId));
        const topBtn = document.getElementById('btn-top-bookmark');
        const bottomBtn = document.getElementById('btn-bookmark-action');

        if (topBtn) {
            topBtn.classList.toggle('bookmarked', isBookmarked);
        }
        if (bottomBtn) {
            bottomBtn.classList.toggle('bookmarked', isBookmarked);
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast-popup');
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function initDetailPage() {
        loadReportData();
        const backBtn = document.getElementById('btn-detail-back');
        if (backBtn) {
            backBtn.addEventListener('click', handleDetailBack);
        }
    }

    // Initialize immediately to prevent flash of static fallback
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDetailPage);
    } else {
        initDetailPage();
    }
</script>
@endpush

