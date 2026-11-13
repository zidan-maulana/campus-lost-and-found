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

    /* --- Modal Ajukan Klaim (DEC-027) --- */
    #modal-claim-report .input-field-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.75rem;
    }

    #modal-claim-report .input-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
    }

    #modal-claim-report .input-label-required {
        color: #ef4444;
    }

    #modal-claim-report .form-text-input {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.65rem 0.9rem;
        font-family: inherit;
        font-size: 0.85rem;
        color: #0f172a;
        background: #ffffff;
        line-height: 1.45;
    }

    #modal-claim-report .form-text-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    #modal-claim-report .field-error-msg {
        display: none;
        font-size: 0.72rem;
        font-weight: 600;
        color: #dc2626;
    }

    #modal-claim-report .field-error-msg.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="detail-page-container">

    <!-- 1. Hero Image Container with Edge-to-Edge Visual & Lightbox Trigger -->
    <div class="detail-hero-wrapper" onclick="openImageLightbox(document.getElementById('detail-hero-img').src, document.getElementById('detail-title').textContent)" style="cursor: zoom-in;" title="Klik untuk lihat detail foto">
        <img src="{{ $reportData['image'] ?? asset('images/samples/bag.png') }}" alt="Foto Barang" class="detail-hero-img" id="detail-hero-img">
        
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
        <h1 class="detail-heading-title" id="detail-title">{{ $reportData['title'] ?? 'Detail Laporan' }}</h1>

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
<!-- MODAL AJUKAN KLAIM (PR-04 + DEC-027: verifikasi & bukti foto) -->
<div class="modal-backdrop" id="modal-claim-report" role="dialog" aria-modal="true" aria-hidden="true" style="z-index: 120;">
    <div class="modal-card" style="max-width: 420px;">
        <div class="modal-header-row">
            <h3 class="modal-title">Ajukan Klaim</h3>
            <button type="button" class="btn-modal-close" onclick="closeClaimModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">
            Jelaskan mengapa barang ini milik Anda. Penemu akan memeriksa bukti ini sebelum menyetujui.
        </p>

        <div id="claim-proof-question-box" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.7rem 0.85rem; margin-top: 0.85rem;">
            <span style="display:block; font-size: 0.68rem; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: .04em;">Pertanyaan dari penemu</span>
            <span id="claim-proof-question-text" style="display:block; font-size: 0.82rem; font-weight: 600; color: #0f172a; margin-top: .2rem;"></span>
        </div>

        <div class="input-field-group" style="margin-top: 0.85rem;">
            <label class="input-label" for="claim-proof-answer" id="claim-proof-answer-label">Jawaban Verifikasi</label>
            <div class="input-box-wrapper">
                <textarea class="form-text-input" id="claim-proof-answer" rows="2" placeholder="Contoh: isi dompet ada KTM atas nama saya" style="padding-left: 0.9rem; resize: vertical;"></textarea>
            </div>
            <span class="field-error-msg" id="error-claim-proof-answer">Jawaban verifikasi minimal 10 karakter.</span>
        </div>

        <div class="input-field-group">
            <label class="input-label" for="claim-reason">Alasan Klaim <span class="input-label-required">*</span></label>
            <div class="input-box-wrapper">
                <textarea class="form-text-input" id="claim-reason" rows="3" placeholder="Ceritakan ciri khusus barang, kapan dan di mana terakhir Anda pegang." style="padding-left: 0.9rem; resize: vertical;"></textarea>
            </div>
            <span class="field-error-msg" id="error-claim-reason">Alasan klaim minimal 10 karakter.</span>
        </div>

        <div class="input-field-group">
            <label class="input-label" for="claim-photo">Foto Bukti <span style="color:#94a3b8; font-weight:500;">(opsional)</span></label>
            <input type="file" id="claim-photo" accept="image/*" class="form-text-input" style="padding: 0.55rem 0.75rem;">
            <span style="display:block; font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem;">Contoh: foto nota pembelian, kotak, atau foto barang dari sisi lain. Maks 2 MB.</span>
            <span class="field-error-msg" id="error-claim-photo">Format foto harus JPG, PNG, atau WebP (maks 2 MB).</span>
        </div>

        <div class="modal-actions-row" style="margin-top: 1rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeClaimModal()">Batal</button>
            <button type="button" class="btn-modal-submit" id="btn-submit-claim" onclick="submitClaimModal()">Kirim Klaim</button>
        </div>
    </div>
</div>
@endsection

@section('custom_bottom_bar')
<!-- Fixed Bottom Action Bar (Flat, No Shadows) -->
<div class="detail-bottom-nav" id="detail-bottom-nav">
    <button type="button" class="btn-action-primary" id="btn-primary-action" onclick="handlePrimaryAction()">
        <i data-lucide="hand"></i>
        <span id="btn-action-label">Saya Menemukan Barang Ini</span>
    </button>
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

    // Status klaim dari server (PR-04) — dipakai tombol aksi utama.
    const FINDUMB_IS_GUEST = @json($viewer === null);
    const FINDUMB_IS_OWNER = @json($isOwner ?? false);
    const FINDUMB_CAN_CLAIM = @json($canClaim ?? false);
    const FINDUMB_MY_CLAIM_ID = @json($myClaim?->id);
    const FINDUMB_REPORT_TYPE = @json($reportData['type'] ?? null);
    const FINDUMB_VERIFIED = @json($viewer?->hasVerifiedEmail() ?? false);

    /**
     * Tombol aksi utama (PR-04):
     *  - tamu           → ke halaman login
     *  - pemilik        → edit laporan
     *  - belum verifikasi / sudah punya klaim aktif → jelaskan alasannya
     *  - barang temuan  → AJUKAN KLAIM lalu masuk ke chat klaim tersebut
     *  - barang hilang  → arahkan buat laporan temuan
     */
    async function handlePrimaryAction() {
        if (FINDUMB_IS_OWNER) {
            openDetailEditModal();
            return;
        }

        if (FINDUMB_IS_GUEST) {
            window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.pathname);
            return;
        }

        if (!FINDUMB_VERIFIED) {
            showToast('Verifikasi email kampus Anda terlebih dahulu untuk dapat mengajukan klaim.');
            return;
        }

        if (FINDUMB_MY_CLAIM_ID) {
            window.location.href = "{{ route('messages') }}?claim=" + FINDUMB_MY_CLAIM_ID;
            return;
        }

        if (FINDUMB_REPORT_TYPE !== 'found') {
            // Barang hilang + kita yang menemukan → buat laporan TEMUAN yang
            // terhubung ke laporan hilang ini (pemiliknya otomatis dinotifikasi).
            window.location.href = "{{ route('report.found') }}?ref=" + currentReportId;
            return;
        }

        if (!FINDUMB_CAN_CLAIM) {
            showToast('Anda belum bisa mengajukan klaim: mungkin masih ada klaim aktif lain atau laporan ini sudah tidak menerima klaim.');
            return;
        }

        openClaimModal();
    }

    // ------------------------------------------------ modal ajukan klaim
    // DEC-027: klaim wajib menyertakan alasan; pertanyaan verifikasi dari
    // penemu (bila ada) wajib dijawab, dan foto bukti boleh dilampirkan.
    function openClaimModal() {
        const modal = document.getElementById('modal-claim-report');
        if (!modal) return;

        const question = (serverReport && serverReport.proof_question) ? String(serverReport.proof_question).trim() : '';
        const box = document.getElementById('claim-proof-question-box');
        const text = document.getElementById('claim-proof-question-text');
        const answerField = document.getElementById('claim-proof-answer');
        const answerLabel = document.getElementById('claim-proof-answer-label');

        if (box && text) {
            box.style.display = question ? 'block' : 'none';
            text.textContent = question;
        }

        if (answerField) {
            answerField.value = '';
            answerField.setAttribute('data-required', question ? '1' : '0');
        }

        if (answerLabel) {
            answerLabel.innerHTML = question
                ? 'Jawaban Verifikasi <span class="input-label-required">*</span>'
                : 'Jawaban Verifikasi <span style="color:#94a3b8;font-weight:500;">(opsional)</span>';
        }

        const reason = document.getElementById('claim-reason');
        if (reason) reason.value = '';

        const photo = document.getElementById('claim-photo');
        if (photo) photo.value = '';

        document.querySelectorAll('#modal-claim-report .field-error-msg').forEach(function (el) {
            el.classList.remove('show');
        });

        const submit = document.getElementById('btn-submit-claim');
        if (submit) {
            submit.disabled = false;
            submit.textContent = 'Kirim Klaim';
        }

        modal.classList.add('active');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeClaimModal() {
        const modal = document.getElementById('modal-claim-report');
        if (modal) modal.classList.remove('active');
    }

    async function submitClaimModal() {
        const reasonEl = document.getElementById('claim-reason');
        const answerEl = document.getElementById('claim-proof-answer');
        const photoEl = document.getElementById('claim-photo');
        const submit = document.getElementById('btn-submit-claim');

        const reason = reasonEl ? reasonEl.value.trim() : '';
        const answer = answerEl ? answerEl.value.trim() : '';
        const answerRequired = answerEl && answerEl.getAttribute('data-required') === '1';

        const errReason = document.getElementById('error-claim-reason');
        const errAnswer = document.getElementById('error-claim-proof-answer');
        const errPhoto = document.getElementById('error-claim-photo');

        [errReason, errAnswer, errPhoto].forEach(function (el) {
            if (el) el.classList.remove('show');
        });

        let invalid = false;

        if (reason.length < 10) {
            if (errReason) errReason.classList.add('show');
            invalid = true;
        }

        if ((answerRequired && answer.length < 10) || (answer !== '' && answer.length < 10)) {
            if (errAnswer) errAnswer.classList.add('show');
            invalid = true;
        }

        if (invalid) return;

        if (photoEl && photoEl.files && photoEl.files[0] && photoEl.files[0].size > 2 * 1024 * 1024) {
            if (errPhoto) errPhoto.classList.add('show');
            return;
        }

        if (submit) {
            submit.disabled = true;
            submit.textContent = 'Mengirim...';
        }

        const form = new FormData();
        form.append('claim_reason', reason);
        if (answer) form.append('proof_answer', answer);
        if (photoEl && photoEl.files && photoEl.files[0]) form.append('proof_attachment', photoEl.files[0]);

        try {
            const res = await fetch(`{{ url('/api/reports') }}/${currentReportId}/claims`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                    'Accept': 'application/json',
                },
                body: form,
            });

            const payload = await res.json().catch(() => ({}));

            if (!res.ok) {
                const firstError = payload && payload.errors ? Object.values(payload.errors)[0][0] : null;
                throw new Error(firstError || payload.message || 'Klaim gagal diajukan.');
            }

            const claimId = payload?.data?.id;
            closeClaimModal();
            showToast('Klaim diajukan. Membuka percakapan...');
            window.location.href = claimId
                ? "{{ route('messages') }}?claim=" + claimId
                : "{{ route('messages') }}";
        } catch (err) {
            if (submit) {
                submit.disabled = false;
                submit.textContent = 'Kirim Klaim';
            }
            showToast(err.message || 'Klaim gagal diajukan.');
        }
    }

    // Data asli dari server (sumber utama). Mock di bawah hanya fallback
    // untuk halaman statis lama yang tidak lewat controller.
    const serverReport = @json($reportData ?? null);

    // Default Seed Reports for User
    // Data laporan langsung dari Supabase (di-render server, bukan mock localStorage).
    const SERVER_REPORTS = @json(array_filter([$reportData ?? null]) ? array_values(array_filter([$reportData ?? null])) : []);
    let SERVER_BOOKMARKS = @json($savedIds ?? []);
    // Nama kategori -> id di Supabase (buat update laporan).
    const CATEGORY_ID_MAP = @json($categories->mapWithKeys(fn ($c) => [$c->name => $c->id]));

    function getUserReports() {
        return Array.isArray(SERVER_REPORTS) ? SERVER_REPORTS : [];
    }

    function isMyReport(id) {
        const userReports = getUserReports();
        return userReports.some(r => String(r.id) === String(id));
    }

    // Built-in Mock Reports Catalog
    // (katalog mock dihapus — semua data dari Supabase)

    let activeReportData = null;

    // Load active report data
    function loadReportData() {
        // 1) Data asli dari server selalu menang (tidak ada kedip ganti barang).
        if (serverReport && String(serverReport.id) === String(currentReportId)) {
            activeReportData = serverReport;
            renderReportDetails(activeReportData);
            return;
        }

        // Tidak ada fallback mock: kalau server nggak punya datanya, jangan tampilkan apa pun.
        activeReportData = (SERVER_REPORTS || []).find(r => String(r.id) === String(currentReportId)) || null;
        if (!activeReportData) return;

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
        const isOwner = serverReport ? !!report.is_mine : (isMyReport(report.id) || !!report.is_mine);
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

            // Pemilik: tombol utama = edit laporan (handlePrimaryAction menanganinya).
            if (primaryBtn) {
                primaryBtn.innerHTML = `<i data-lucide="edit-3"></i><span>Edit Laporan</span>`;
            }
        } else {
            if (topBookmarkBtn) topBookmarkBtn.style.display = 'flex';
            if (bottomBookmarkBtn) bottomBookmarkBtn.style.display = 'flex';
            if (bottomDeleteBtn) bottomDeleteBtn.style.display = 'none';

            if (primaryBtn && actionLabel) {
                // Jangan pasang href/onclick palsu: handlePrimaryAction() yang
                // benar-benar mengajukan klaim ke /api/reports/{id}/claims.
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
        const id = currentReportId;
        if (!id) return;

        const payload = {
            title: document.getElementById('edit-title').value.trim(),
            brand: document.getElementById('edit-brand').value.trim() || null,
            location: document.getElementById('edit-location').value.trim() || null,
            campus_location: document.getElementById('edit-location').value.trim() || null,
            incident_date: document.getElementById('edit-date').value || null,
            description: document.getElementById('edit-features').value.trim() || null,
        };

        const catName = document.getElementById('edit-category').value;
        if (CATEGORY_ID_MAP[catName]) payload.category_id = CATEGORY_ID_MAP[catName];

        fetch(`{{ url('/api/reports') }}/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then(r => r.ok ? r.json() : Promise.reject(r))
            .then(() => {
                closeDetailEditModal();
                showToast('✓ Laporan berhasil diperbarui!');
                setTimeout(() => window.location.reload(), 600);
            })
            .catch(() => showToast('Gagal memperbarui laporan.'));
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
        const id = currentReportId;
        if (!id) return;

        fetch(`{{ url('/api/reports') }}/${id}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reason: 'Dibatalkan oleh pemilik laporan.' }),
        })
            .then(r => r.ok ? r.json() : Promise.reject(r))
            .then(() => {
                closeDetailDeleteModal();
                showToast('Laporan dibatalkan.');
                setTimeout(() => window.location.reload(), 600);
            })
            .catch(() => showToast('Gagal membatalkan laporan.'));
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
        return (SERVER_BOOKMARKS || []).map(String);
    }

    function setStoredBookmarks(bookmarks) {
        SERVER_BOOKMARKS = (bookmarks || []).map(String);
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

