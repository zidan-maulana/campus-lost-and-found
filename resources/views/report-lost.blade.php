@extends('layouts.app')

@section('title', 'Lapor Barang Hilang — Find UMB')

@section('header')
<!-- Custom Header: Back Arrow & Judul Halaman sejajar di sebelah kiri -->
<header class="report-header">
    <button type="button" class="btn-header-back" onclick="handleHeaderBack()" aria-label="Kembali">
        <i data-lucide="chevron-left"></i>
    </button>
    <h1 class="report-header-title">Lapor Kehilangan</h1>
</header>
@endsection

@push('styles')
<style>
    /* Report Container - Mobile Shell Spacing */
    .report-wizard-container {
        padding: 1rem 1.25rem 7.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    /* Top Custom Header - Title aligned right next to back arrow */
    .report-header {
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        border-bottom: 1px solid var(--border-subtle);
        position: sticky;
        top: 0;
        z-index: 40;
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
        flex-shrink: 0;
    }

    .btn-header-back:hover, .btn-header-back:active {
        background: #f1f5f9;
        transform: scale(0.96);
    }

    .btn-header-back svg, .btn-header-back .lucide {
        width: 22px;
        height: 22px;
        stroke-width: 2.4;
    }

    .report-header-title {
        font-family: var(--font-heading);
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.015em;
        margin: 0;
    }

    /* Stepper Progress Indicator (5 Langkah) */
    .stepper-wrapper {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .stepper-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stepper-step-count {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
    }

    .stepper-step-name {
        font-size: 0.825rem;
        font-weight: 700;
        color: #0f172a;
    }

    .stepper-bars-track {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        width: 100%;
    }

    .stepper-bar-segment {
        flex: 1;
        height: 5px;
        background: #e2e8f0;
        border-radius: 10px;
        transition: background-color 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .stepper-bar-segment.active {
        background: #0f5791; /* FindUMB Blue */
    }

    /* Form Card Container */
    .report-form-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        padding: 1.35rem 1.25rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-section-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin-bottom: 0.15rem;
    }

    .form-section-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.25rem;
        line-height: 1.4;
    }

    /* Form Input Fields */
    .input-field-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        position: relative;
    }

    .input-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .input-label-required {
        color: #ef4444;
        font-weight: 700;
    }

    .input-box-wrapper {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.75rem 0.95rem;
        transition: var(--transition);
        position: relative;
    }

    .input-box-wrapper:focus-within {
        border-color: #0f5791;
        box-shadow: 0 0 0 3px rgba(15, 87, 145, 0.12);
    }

    .input-box-wrapper.has-error {
        border-color: #ef4444;
        background: #fffafa;
    }

    .input-icon-left {
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .input-icon-left svg, .input-icon-left .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }

    .form-text-input, .form-select-input {
        flex: 1;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: #0f172a;
        outline: none;
        width: 100%;
    }

    .form-text-input::placeholder {
        color: #94a3b8;
    }

    /* Hide browser default duplicate date picker indicator & overlay it transparently across wrapper */
    input[type="date"]::-webkit-calendar-picker-indicator {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Select Dropdown Box: Entire box behaves as a clickable button */
    .select-box-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        min-height: 48px;
        padding: 0 !important;
        transition: var(--transition);
        cursor: pointer;
    }

    .select-box-wrapper:focus-within {
        border-color: #0f5791;
        box-shadow: 0 0 0 3px rgba(15, 87, 145, 0.12);
    }

    .select-box-wrapper.has-error {
        border-color: #ef4444;
        background: #fffafa;
    }

    .select-box-wrapper select.form-select-input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        padding: 0 2.4rem 0 2.65rem;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: #0f172a;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        z-index: 2;
    }

    .select-box-wrapper select.form-select-input.is-placeholder {
        color: #94a3b8;
    }

    .select-box-wrapper .input-icon-left {
        position: absolute;
        left: 0.95rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 1;
    }

    .select-box-wrapper .select-chevron-icon {
        position: absolute;
        right: 0.95rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 1;
        transition: color 0.2s ease;
    }

    .select-box-wrapper:focus-within .select-chevron-icon {
        color: #0f5791;
    }

    .form-textarea {
        flex: 1;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: #0f172a;
        outline: none;
        width: 100%;
        min-height: 76px;
        resize: vertical;
        line-height: 1.45;
    }

    .form-textarea::placeholder {
        color: #94a3b8;
    }

    .field-error-msg {
        font-size: 0.725rem;
        color: #ef4444;
        font-weight: 500;
        display: none;
    }

    .field-error-msg.visible {
        display: block;
    }

    /* Photo Upload Dropzone (Langkah 4) */
    .photo-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 1.75rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.65rem;
        background: #f8fafc;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .photo-upload-zone:hover, .photo-upload-zone.dragover {
        border-color: #0f5791;
        background: #f0f7fd;
    }

    .photo-upload-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e0f0fa;
        color: #0f5791;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-upload-icon-box svg, .photo-upload-icon-box .lucide {
        width: 24px;
        height: 24px;
        stroke-width: 2.2;
    }

    .photo-upload-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0f172a;
    }

    .photo-upload-hint {
        font-size: 0.75rem;
        color: #64748b;
        max-width: 280px;
    }

    .photo-preview-box {
        width: 100%;
        height: 180px;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        display: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .photo-preview-box.active {
        display: block;
    }

    .photo-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-remove-photo {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(15, 23, 42, 0.7);
        color: #ffffff;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-remove-photo:hover {
        background: #ef4444;
    }

    /* Privacy In-App Notice Card (Langkah 5) */
    .privacy-notice-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 14px;
        padding: 0.95rem 1.1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .privacy-notice-icon {
        color: #16a34a;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .privacy-notice-icon svg, .privacy-notice-icon .lucide {
        width: 20px;
        height: 20px;
        stroke-width: 2.3;
    }

    .privacy-notice-text h4 {
        font-size: 0.825rem;
        font-weight: 700;
        color: #14532d;
        margin-bottom: 0.2rem;
    }

    .privacy-notice-text p {
        font-size: 0.75rem;
        color: #166534;
        line-height: 1.45;
        margin: 0;
    }

    /* Review Card (Langkah 5) - Simple Table Style with Divider Lines */
    .review-summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.25rem 1rem;
        display: flex;
        flex-direction: column;
    }

    .review-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.8rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eef2f6;
    }

    .review-row:last-child {
        border-bottom: none;
    }

    .review-label {
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }

    .review-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
        max-width: 65%;
        word-break: break-word;
    }

    .consent-checkbox-wrap {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-top: 0.5rem;
        cursor: pointer;
    }

    .consent-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #0f5791;
        margin-top: 2px;
        cursor: pointer;
    }

    .consent-text {
        font-size: 0.75rem;
        color: #475569;
        line-height: 1.4;
    }

    /* Bottom Fixed Sticky Navigation (Batal/Kembali & Lanjut) */
    .report-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-top: 1px solid #eef2f6;
        padding: 0.85rem 1.25rem calc(0.85rem + env(safe-area-inset-bottom));
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 0.85rem;
        z-index: 50;
        box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.05);
    }

    .btn-nav-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.8rem 1rem;
        font-family: var(--font-main);
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-nav-back:hover, .btn-nav-back:active {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-nav-next {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0f5791; /* FindUMB Blue */
        border: none;
        border-radius: 14px;
        padding: 0.8rem 1rem;
        font-family: var(--font-main);
        font-size: 0.875rem;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        box-shadow: none;
        transition: var(--transition);
    }

    .btn-nav-next:hover, .btn-nav-next:active {
        background: #0b4370;
        transform: translateY(-1px);
        box-shadow: none;
    }

    .btn-nav-next.btn-submit {
        background: #0f5791;
        box-shadow: none;
    }

    .btn-nav-next.btn-submit:hover {
        background: #0b4370;
        box-shadow: none;
    }

    /* Success Modal */
    .success-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        z-index: 200;
        opacity: 0;
        pointer-events: none;
        transition: var(--transition);
    }

    .success-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .success-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 2rem 1.5rem;
        width: 100%;
        max-width: 360px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.85rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transform: scale(0.92);
        transition: var(--transition);
    }

    .success-modal-overlay.active .success-card {
        transform: scale(1);
    }

    .success-icon-badge {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(22, 163, 74, 0.2);
    }

    .success-icon-badge svg, .success-icon-badge .lucide {
        width: 36px;
        height: 36px;
        stroke-width: 2.6;
    }

    .success-title {
        font-family: var(--font-heading);
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }

    .success-desc {
        font-size: 0.825rem;
        color: #64748b;
        line-height: 1.5;
    }

    .success-actions {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-success-primary {
        background: #0f5791;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.75rem;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-success-secondary {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="report-wizard-container">

    <!-- 1. Stepper Progress Indicator (5 Langkah) -->
    <div class="stepper-wrapper" id="stepper-wrapper">
        <div class="stepper-header-row">
            <span class="stepper-step-count" id="step-count-text">Langkah 1 dari 5</span>
            <span class="stepper-step-name" id="step-name-text">Info Barang</span>
        </div>
        <div class="stepper-bars-track">
            <div class="stepper-bar-segment" id="step-bar-1"></div>
            <div class="stepper-bar-segment" id="step-bar-2"></div>
            <div class="stepper-bar-segment" id="step-bar-3"></div>
            <div class="stepper-bar-segment" id="step-bar-4"></div>
            <div class="stepper-bar-segment" id="step-bar-5"></div>
        </div>
    </div>

    <!-- 2. Form Wizard Step Panels -->
    <form id="form-report-lost" onsubmit="event.preventDefault();">

        <!-- ============================================================== -->
        <!-- LANGKAH 1: Informasi Dasar Barang                             -->
        <!-- ============================================================== -->
        <div class="report-form-card wizard-step-panel" id="step-panel-1">
            <div>
                <h3 class="form-section-title">Informasi Dasar Barang</h3>
                <p class="form-section-desc">Pilih kategori dan sebutkan nama barang yang hilang.</p>
            </div>

            <!-- Category Selector Dropdown with "Lainnya" -->
            <div class="input-field-group">
                <label class="input-label" for="select-category">Kategori Barang <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper select-box-wrapper" id="wrap-category">
                    <span class="input-icon-left"><i data-lucide="layers"></i></span>
                    <select class="form-select-input is-placeholder" id="select-category" required>
                        <option value="" disabled selected>Pilih Kategori Barang</option>
                        <option value="Elektronik & HP">Elektronik &amp; HP</option>
                        <option value="Dompet & Kartu">Dompet &amp; Kartu Identitas</option>
                        <option value="Kunci Kendaraan / Ruangan">Kunci Kendaraan / Ruangan</option>
                        <option value="Tas & Ransel">Tas &amp; Ransel</option>
                        <option value="Jam & Aksesori">Jam &amp; Aksesori</option>
                        <option value="Buku & Dokumen">Buku, Dokumen &amp; ATK</option>
                        <option value="Pakaian & Sepatu">Pakaian &amp; Sepatu</option>
                        <option value="Botol Minum & Tumbler">Botol Minum &amp; Tumbler</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <span class="select-chevron-icon"><i data-lucide="chevron-down"></i></span>
                </div>
                <span class="field-error-msg" id="error-category">Silakan pilih kategori barang.</span>
            </div>

            <!-- Additional input when 'Lainnya' is selected -->
            <div class="input-field-group" id="group-category-other" style="display: none;">
                <label class="input-label" for="input-category-other">Kategori Lainnya <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper" id="wrap-category-other">
                    <span class="input-icon-left"><i data-lucide="help-circle"></i></span>
                    <input type="text" class="form-text-input" id="input-category-other" placeholder="Tuliskan jenis / kategori barang">
                </div>
                <span class="field-error-msg" id="error-category-other">Silakan sebutkan kategori lainnya.</span>
            </div>

            <!-- Item Name -->
            <div class="input-field-group">
                <label class="input-label" for="input-item-name">Nama / Judul Barang <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper" id="wrap-item-name">
                    <span class="input-icon-left"><i data-lucide="tag"></i></span>
                    <input type="text" class="form-text-input" id="input-item-name" placeholder="Contoh: iPhone 13 Pro Biru Sierra" required>
                </div>
                <span class="field-error-msg" id="error-item-name">Nama barang wajib diisi.</span>
            </div>

            <!-- Brand / Model (Optional) -->
            <div class="input-field-group">
                <label class="input-label" for="input-brand">Merek / Seri <span style="font-weight: 400; color: #94a3b8; font-size: 0.75rem;">(Opsional)</span></label>
                <div class="input-box-wrapper">
                    <span class="input-icon-left"><i data-lucide="box"></i></span>
                    <input type="text" class="form-text-input" id="input-brand" placeholder="Contoh: Apple, Samsung, Eiger, Casio">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- LANGKAH 2: Lokasi & Waktu Kejadian                            -->
        <!-- ============================================================== -->
        <div class="report-form-card wizard-step-panel" id="step-panel-2" style="display: none;">
            <div>
                <h3 class="form-section-title">Lokasi &amp; Waktu Kejadian</h3>
                <p class="form-section-desc">Tentukan area kampus dan perkiraan waktu saat barang terakhir terlihat.</p>
            </div>

            <!-- 1. Area Kampus Dropdown -->
            <div class="input-field-group">
                <label class="input-label" for="select-location">Area Kampus <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper select-box-wrapper" id="wrap-location">
                    <span class="input-icon-left"><i data-lucide="building-2"></i></span>
                    <select class="form-select-input is-placeholder" id="select-location">
                        <option value="" disabled selected>Pilih Kampus</option>
                        <option value="Kampus Meruya">Kampus Meruya (Kampus Utama)</option>
                        <option value="Kampus Menteng">Kampus Menteng</option>
                        <option value="Kampus Pejaten">Kampus Pejaten</option>
                        <option value="Kampus Kranggan">Kampus Kranggan</option>
                    </select>
                    <span class="select-chevron-icon"><i data-lucide="chevron-down"></i></span>
                </div>
                <span class="field-error-msg" id="error-location">Silakan pilih area kampus.</span>
            </div>

            <!-- 2. Lokasi Spesifik di Lingkungan Kampus -->
            <div class="input-field-group">
                <label class="input-label" for="input-specific-location">Lokasi Spesifik di Kampus <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper" id="wrap-specific-location">
                    <span class="input-icon-left"><i data-lucide="map-pin"></i></span>
                    <input type="text" class="form-text-input" id="input-specific-location" placeholder="Contoh: Parkir Barat Gedung E, Lantai 2, Kantin D" required>
                </div>
                <span class="field-error-msg" id="error-specific-location">Lokasi spesifik wajib diisi untuk memudahkan pencarian.</span>
            </div>

            <!-- 3. Tanggal Hilang -->
            <div class="input-field-group">
                <label class="input-label" for="input-date-lost">Tanggal Kehilangan <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper" id="wrap-date-lost">
                    <input type="date" class="form-text-input" id="input-date-lost" value="2026-10-28">
                    <span class="select-chevron-icon"><i data-lucide="calendar"></i></span>
                </div>
                <span class="field-error-msg" id="error-date-lost">Tanggal kehilangan wajib diisi.</span>
            </div>

            <!-- 4. Perkiraan Jam / Rentang Waktu (Opsional) -->
            <div class="input-field-group">
                <label class="input-label" for="input-time-lost">Perkiraan Waktu <span style="font-weight: 400; color: #94a3b8; font-size: 0.75rem;">(Opsional)</span></label>
                <div class="input-box-wrapper">
                    <span class="input-icon-left"><i data-lucide="clock"></i></span>
                    <input type="text" class="form-text-input" id="input-time-lost" placeholder="Contoh: Sekitar jam 11.30 - 13.00 WIB">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- LANGKAH 3: Ciri Khusus & Kronologi                           -->
        <!-- ============================================================== -->
        <div class="report-form-card wizard-step-panel" id="step-panel-3" style="display: none;">
            <div>
                <h3 class="form-section-title">Ciri Khusus &amp; Kronologi</h3>
                <p class="form-section-desc">Jelaskan ciri pengenal barang serta kronologi singkat kejadian.</p>
            </div>

            <!-- Distinct Features & Color Description -->
            <div class="input-field-group">
                <label class="input-label" for="input-features">Ciri Khusus &amp; Warna Barang <span class="input-label-required">*</span></label>
                <div class="input-box-wrapper" id="wrap-features">
                    <textarea class="form-textarea" id="input-features" placeholder="Warna, stiker, goresan khas, atau ciri pengenal lainnya..." required></textarea>
                </div>
                <span class="field-error-msg" id="error-features">Ciri khusus dan warna barang wajib diisi.</span>
            </div>

            <!-- Chronology & Details -->
            <div class="input-field-group">
                <label class="input-label" for="input-desc">Kronologi Kejadian <span style="font-weight: 400; color: #94a3b8; font-size: 0.75rem;">(Opsional)</span></label>
                <div class="input-box-wrapper">
                    <textarea class="form-textarea" id="input-desc" placeholder="Perkiraan tempat atau waktu saat menyadari barang hilang..."></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- LANGKAH 4: Foto Bukti Barang (Opsional)                        -->
        <!-- ============================================================== -->
        <div class="report-form-card wizard-step-panel" id="step-panel-4" style="display: none;">
            <div>
                <h3 class="form-section-title">Foto Barang <span style="font-weight: 400; color: #94a3b8; font-size: 0.8rem;">(Opsional)</span></h3>
                <p class="form-section-desc">Unggah foto barang saat masih ada atau foto referensi model barang yang serupa.</p>
            </div>

            <div class="photo-upload-zone" id="dropzone-box" onclick="triggerPhotoUpload()">
                <div class="photo-upload-icon-box">
                    <i data-lucide="camera"></i>
                </div>
                <span class="photo-upload-title">Pilih Foto dari Galeri atau Kamera</span>
                <span class="photo-upload-hint">Format JPG atau PNG maksimal 5 MB.</span>
                <input type="file" id="file-photo-input" accept="image/*" style="display: none;" onchange="handleFileSelected(event)">
            </div>

            <!-- Photo Preview Display -->
            <div class="photo-preview-box" id="preview-box">
                <img src="" alt="Pratinjau Foto" class="photo-preview-img" id="preview-image">
                <button type="button" class="btn-remove-photo" onclick="removePhoto()" aria-label="Hapus Foto">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- LANGKAH 5: Pratinjau & Konfirmasi (Privasi Chat Terjaga)      -->
        <!-- ============================================================== -->
        <div class="report-form-card wizard-step-panel" id="step-panel-5" style="display: none;">
            <div>
                <h3 class="form-section-title">Pratinjau &amp; Konfirmasi</h3>
                <p class="form-section-desc">Periksa kembali data laporan Anda sebelum dipublikasikan ke civitas kampus.</p>
            </div>

            <!-- Privacy Guarantee Notice (Tanpa Nomor HP) -->
            <div class="privacy-notice-box">
                <div class="privacy-notice-icon">
                    <i data-lucide="shield-check"></i>
                </div>
                <div class="privacy-notice-text">
                    <h4>Privasi &amp; Keamanan Terjaga</h4>
                    <p>Kontak pribadi dirahasiakan. Penemu barang akan menghubungi Anda secara aman via <strong>Chat FindUMB</strong>.</p>
                </div>
            </div>

            <!-- Summary Review Card -->
            <div class="review-summary-card">
                <div class="review-row">
                    <span class="review-label">Barang:</span>
                    <span class="review-value" id="summary-name">-</span>
                </div>
                <div class="review-row">
                    <span class="review-label">Kategori:</span>
                    <span class="review-value" id="summary-category">-</span>
                </div>
                <div class="review-row">
                    <span class="review-label">Lokasi Kampus:</span>
                    <span class="review-value" id="summary-location">-</span>
                </div>
                <div class="review-row">
                    <span class="review-label">Tanggal Hilang:</span>
                    <span class="review-value" id="summary-date">-</span>
                </div>
                <div class="review-row">
                    <span class="review-label">Ciri Khusus:</span>
                    <span class="review-value" id="summary-features">-</span>
                </div>
                <div class="review-row">
                    <span class="review-label">Kanal Verifikasi:</span>
                    <span class="review-value" style="color: #0f5791; font-weight: 700;">Chat Internal FindUMB</span>
                </div>
            </div>

            <label class="consent-checkbox-wrap" id="wrap-consent">
                <input type="checkbox" class="consent-checkbox" id="check-consent" checked>
                <span class="consent-text">Saya menyatakan bahwa barang yang saya laporkan ini benar-benar milik saya dan informasi yang diberikan dapat dipertanggungjawabkan.</span>
            </label>
            <span class="field-error-msg" id="error-consent" style="margin-top: 0.35rem; display: none;">Silakan centang persetujuan keabsahan laporan untuk melanjutkan.</span>
        </div>

    </form>
</div>

<!-- Success Modal Dialog -->
<div class="success-modal-overlay" id="success-modal">
    <div class="success-card">
        <div class="success-icon-badge">
            <i data-lucide="check"></i>
        </div>
        <h3 class="success-title">Laporan Diterbitkan!</h3>
        <p class="success-desc">Laporan barang hilang Anda telah berhasil dipublikasikan di FindUMB. Civitas yang menemukan barang akan menghubungi Anda melalui menu Pesan.</p>
        <div class="success-actions">
            <a href="{{ route('home') }}" class="btn-success-primary">Kembali ke Beranda</a>
            <a href="{{ route('search') }}" class="btn-success-secondary">Lihat di Daftar Pencarian</a>
        </div>
    </div>
</div>
@endsection

@section('custom_bottom_bar')
<!-- Bottom Fixed Sticky Navigation Bar (Batal/Kembali & Lanjut tanpa arrow icon) -->
<div class="report-bottom-nav" id="report-bottom-bar">
    <button type="button" class="btn-nav-back" id="btn-wizard-back" onclick="navigateStep(-1)">
        Batal
    </button>
    <button type="button" class="btn-nav-next" id="btn-wizard-next" onclick="navigateStep(1)">
        <span>Lanjut</span>
    </button>
</div>
@endsection

@push('scripts')
<script>
    // State management for 5-step wizard
    let currentStep = 1;
    const totalSteps = 5;

    const stepMetadata = [
        { countText: 'Langkah 1 dari 5', nameText: 'Info Barang' },
        { countText: 'Langkah 2 dari 5', nameText: 'Lokasi & Waktu' },
        { countText: 'Langkah 3 dari 5', nameText: 'Ciri-Ciri' },
        { countText: 'Langkah 4 dari 5', nameText: 'Foto Barang' },
        { countText: 'Langkah 5 dari 5', nameText: 'Konfirmasi' }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        setStep(1);

        // Setup select dropdowns so clicking anywhere on the wrapper/button triggers the select
        document.querySelectorAll('.select-box-wrapper').forEach(wrapper => {
            const select = wrapper.querySelector('select');
            if (!select) return;

            const updateColor = () => {
                if (!select.value) {
                    select.classList.add('is-placeholder');
                } else {
                    select.classList.remove('is-placeholder');
                }
            };
            updateColor();
            select.addEventListener('change', updateColor);

            wrapper.addEventListener('click', (e) => {
                if (e.target !== select) {
                    if (typeof select.showPicker === 'function') {
                        try {
                            select.showPicker();
                        } catch (err) {
                            select.focus();
                        }
                    } else {
                        select.focus();
                    }
                }
            });
        });

        // Listener for category select dropdown
        const catSelect = document.getElementById('select-category');
        if (catSelect) {
            catSelect.addEventListener('change', function() {
                const otherGroup = document.getElementById('group-category-other');
                if (this.value === 'Lainnya') {
                    otherGroup.style.display = 'flex';
                    const otherInput = document.getElementById('input-category-other');
                    if (otherInput) otherInput.focus();
                } else {
                    otherGroup.style.display = 'none';
                }
                document.getElementById('wrap-category').classList.remove('has-error');
                document.getElementById('error-category').classList.remove('visible');
            });
        }

        // Listener for real-time validation clearing
        ['input-item-name', 'input-category-other', 'select-location', 'input-specific-location', 'input-date-lost', 'input-features'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const clearErr = () => {
                    const wrapId = id.replace('input-', 'wrap-').replace('select-', 'wrap-');
                    const errId = id.replace('input-', 'error-').replace('select-', 'error-');
                    const wrap = document.getElementById(wrapId);
                    const err = document.getElementById(errId);
                    if (wrap) wrap.classList.remove('has-error');
                    if (err) err.classList.remove('visible');
                };
                el.addEventListener('input', clearErr);
                el.addEventListener('change', clearErr);
            }
        });

        const consentEl = document.getElementById('check-consent');
        if (consentEl) {
            consentEl.addEventListener('change', () => {
                const errConsent = document.getElementById('error-consent');
                if (errConsent && consentEl.checked) {
                    errConsent.style.display = 'none';
                    errConsent.classList.remove('visible');
                }
            });
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    let currentPhotoDataUrl = null;
    let currentPhotoFile = null;

    // Kategori dari Supabase (nama -> id) supaya laporan masuk ke DB, bukan localStorage.
    const CATEGORY_MAP = @json($categories->mapWithKeys(fn ($c) => [$c->name => $c->id]));

    function setStep(stepIndex) {
        currentStep = stepIndex;

        // 1. Update Stepper Header & Progress Bars
        const meta = stepMetadata[stepIndex - 1];
        document.getElementById('step-count-text').textContent = meta.countText;
        document.getElementById('step-name-text').textContent = meta.nameText;

        for (let i = 1; i <= totalSteps; i++) {
            const bar = document.getElementById(`step-bar-${i}`);
            if (bar) {
                if (i <= stepIndex) {
                    bar.classList.add('active');
                } else {
                    bar.classList.remove('active');
                }
            }
        }

        // 2. Toggle Form Panels
        for (let i = 1; i <= totalSteps; i++) {
            const panel = document.getElementById(`step-panel-${i}`);
            if (panel) {
                panel.style.display = (i === stepIndex) ? 'flex' : 'none';
            }
        }

        // Update navigation buttons
        const btnBack = document.getElementById('btn-wizard-back');
        const btnNext = document.getElementById('btn-wizard-next');

        if (stepIndex === 1) {
            btnBack.textContent = 'Batal';
        } else {
            btnBack.textContent = 'Kembali';
        }

        if (stepIndex === totalSteps) {
            btnNext.innerHTML = '<span>Terbitkan Laporan</span>';
            btnNext.classList.add('btn-submit');
            updateSummaryData();
        } else {
            btnNext.innerHTML = '<span>Lanjut</span>';
            btnNext.classList.remove('btn-submit');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Scroll smoothly to top of form
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function navigateStep(delta) {
        if (delta === 1) {
            // Validate current step before proceeding
            if (!validateStep(currentStep)) {
                return;
            }

            if (currentStep < totalSteps) {
                setStep(currentStep + 1);
            } else {
                submitReport();
            }
        } else if (delta === -1) {
            if (currentStep > 1) {
                setStep(currentStep - 1);
            } else {
                window.location.href = "{{ route('home') }}";
            }
        }
    }

    function handleHeaderBack() {
        if (currentStep > 1) {
            setStep(currentStep - 1);
        } else {
            window.location.href = "{{ route('home') }}";
        }
    }

    // Step validation helper
    function validateStep(step) {
        let valid = true;

        if (step === 1) {
            // Validate Category Dropdown
            const catSelect = document.getElementById('select-category');
            const wrapCat = document.getElementById('wrap-category');
            const errCat = document.getElementById('error-category');

            if (!catSelect.value) {
                wrapCat.classList.add('has-error');
                errCat.classList.add('visible');
                valid = false;
            } else {
                wrapCat.classList.remove('has-error');
                errCat.classList.remove('visible');
            }

            // If "Lainnya" chosen, validate custom other category
            if (catSelect.value === 'Lainnya') {
                const otherInput = document.getElementById('input-category-other');
                const wrapOther = document.getElementById('wrap-category-other');
                const errOther = document.getElementById('error-category-other');
                if (!otherInput.value.trim()) {
                    wrapOther.classList.add('has-error');
                    errOther.classList.add('visible');
                    valid = false;
                } else {
                    wrapOther.classList.remove('has-error');
                    errOther.classList.remove('visible');
                }
            }

            // Validate Item Name
            const nameInput = document.getElementById('input-item-name');
            const wrapName = document.getElementById('wrap-item-name');
            const errName = document.getElementById('error-item-name');
            if (!nameInput.value.trim()) {
                wrapName.classList.add('has-error');
                errName.classList.add('visible');
                valid = false;
            } else {
                wrapName.classList.remove('has-error');
                errName.classList.remove('visible');
            }
        }

        if (step === 2) {
            const locSelect = document.getElementById('select-location');
            const wrapLoc = document.getElementById('wrap-location');
            const errLoc = document.getElementById('error-location');

            if (!locSelect.value) {
                wrapLoc.classList.add('has-error');
                errLoc.classList.add('visible');
                valid = false;
            } else {
                wrapLoc.classList.remove('has-error');
                errLoc.classList.remove('visible');
            }

            const specLoc = document.getElementById('input-specific-location');
            const wrapSpec = document.getElementById('wrap-specific-location');
            const errSpec = document.getElementById('error-specific-location');

            if (!specLoc.value.trim()) {
                wrapSpec.classList.add('has-error');
                errSpec.classList.add('visible');
                valid = false;
            } else {
                wrapSpec.classList.remove('has-error');
                errSpec.classList.remove('visible');
            }

            const dateInput = document.getElementById('input-date-lost');
            const wrapDate = document.getElementById('wrap-date-lost');
            const errDate = document.getElementById('error-date-lost');

            if (!dateInput.value) {
                wrapDate.classList.add('has-error');
                errDate.classList.add('visible');
                valid = false;
            } else {
                wrapDate.classList.remove('has-error');
                errDate.classList.remove('visible');
            }
        }

        if (step === 3) {
            const featuresInput = document.getElementById('input-features');
            const wrapFeatures = document.getElementById('wrap-features');
            const errFeatures = document.getElementById('error-features');

            if (!featuresInput.value.trim()) {
                wrapFeatures.classList.add('has-error');
                errFeatures.classList.add('visible');
                valid = false;
            } else {
                wrapFeatures.classList.remove('has-error');
                errFeatures.classList.remove('visible');
            }
        }

        if (step === 5) {
            const consentCheck = document.getElementById('check-consent');
            const errConsent = document.getElementById('error-consent');
            if (!consentCheck.checked) {
                if (errConsent) {
                    errConsent.style.display = 'block';
                    errConsent.classList.add('visible');
                }
                valid = false;
            } else {
                if (errConsent) {
                    errConsent.style.display = 'none';
                    errConsent.classList.remove('visible');
                }
            }
        }

        // Smoothly auto-scroll to the first invalid field
        if (!valid) {
            setTimeout(() => {
                const currentPanel = document.getElementById(`step-panel-${step}`);
                if (currentPanel) {
                    const firstErr = currentPanel.querySelector('.has-error, .field-error-msg.visible');
                    if (firstErr) {
                        firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        const inputEl = firstErr.matches('input, select, textarea') ? firstErr : firstErr.querySelector('input, select, textarea');
                        if (inputEl && typeof inputEl.focus === 'function') {
                            inputEl.focus();
                        }
                    }
                }
            }, 50);
        }

        return valid;
    }

    // Photo Upload Simulator
    function triggerPhotoUpload() {
        document.getElementById('file-photo-input').click();
    }

    function handleFileSelected(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentPhotoDataUrl = e.target.result;
            currentPhotoFile = file;
                const previewBox = document.getElementById('preview-box');
                const previewImg = document.getElementById('preview-image');
                const dropzone = document.getElementById('dropzone-box');
                previewImg.src = currentPhotoDataUrl;
                previewBox.classList.add('active');
                dropzone.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function removePhoto() {
        currentPhotoDataUrl = null;
        const previewBox = document.getElementById('preview-box');
        const previewImg = document.getElementById('preview-image');
        const dropzone = document.getElementById('dropzone-box');
        const input = document.getElementById('file-photo-input');
        input.value = '';
        previewImg.src = '';
        previewBox.classList.remove('active');
        dropzone.style.display = 'flex';
    }

    // Helpers
    function formatDateIndo(dateStr) {
        if (!dateStr) return 'Hari Ini';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const monthIdx = parseInt(parts[1], 10) - 1;
            const day = parseInt(parts[2], 10);
            if (months[monthIdx]) {
                return `${day} ${months[monthIdx]} ${year}`;
            }
        }
        return dateStr;
    }

    function getCategorySlug(categoryText) {
        const lower = (categoryText || '').toLowerCase();
        if (lower.includes('elektronik') || lower.includes('hp')) return 'elektronik';
        if (lower.includes('dompet') || lower.includes('tas') || lower.includes('ransel')) return 'tas';
        if (lower.includes('kunci')) return 'kunci';
        if (lower.includes('jam') || lower.includes('aksesor')) return 'aksesoris';
        if (lower.includes('buku') || lower.includes('dokumen') || lower.includes('ktm')) return 'dokumen';
        if (lower.includes('pakaian') || lower.includes('sepatu') || lower.includes('baju')) return 'pakaian';
        return 'lainnya';
    }

    function getLocationSlug(locText, specLocText) {
        const combined = ((locText || '') + ' ' + (specLocText || '')).toLowerCase();
        if (combined.includes('tower')) return 'tower';
        if (combined.includes('gedung e') || combined.includes(' r. ') || combined.includes('ruang')) return 'gedung_e';
        if (combined.includes('parkir')) return 'parkir';
        if (combined.includes('kantin')) return 'kantin';
        if (combined.includes('perpus')) return 'perpustakaan';
        if (combined.includes('masjid') || combined.includes('musholla')) return 'masjid';
        if (combined.includes('lab')) return 'lab';
        if (combined.includes('gazebo') || combined.includes('taman')) return 'gazebo';
        return 'all';
    }

    function getDefaultImageForCategory(catSlug) {
        switch (catSlug) {
            case 'elektronik':
                return 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=240&auto=format&fit=crop&q=80';
            case 'tas':
                return 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=240&auto=format&fit=crop&q=80';
            case 'kunci':
                return 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=240&auto=format&fit=crop&q=80';
            case 'aksesoris':
                return 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=240&auto=format&fit=crop&q=80';
            case 'dokumen':
                return 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=240&auto=format&fit=crop&q=80';
            case 'pakaian':
                return 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=240&auto=format&fit=crop&q=80';
            default:
                return 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=240&auto=format&fit=crop&q=80';
        }
    }

    // Summary Update for Step 5
    function updateSummaryData() {
        const name = document.getElementById('input-item-name').value.trim() || 'Barang Hilang';
        const catSelect = document.getElementById('select-category').value;
        const catOther = document.getElementById('input-category-other') ? document.getElementById('input-category-other').value.trim() : '';
        const cat = (catSelect === 'Lainnya' && catOther) ? `Lainnya (${catOther})` : (catSelect || '-');
        const loc = document.getElementById('select-location').value || 'Kampus Meruya';
        const specLoc = document.getElementById('input-specific-location').value.trim();
        const date = document.getElementById('input-date-lost').value || '2026-10-28';
        const features = document.getElementById('input-features').value.trim() || 'Tidak ada catatan khusus';

        document.getElementById('summary-name').textContent = name;
        document.getElementById('summary-category').textContent = cat;
        document.getElementById('summary-location').textContent = specLoc ? `${loc} (${specLoc})` : loc;
        document.getElementById('summary-date').textContent = date;
        document.getElementById('summary-features').textContent = features.length > 50 ? features.substring(0, 48) + '...' : features;
    }

    // Submit Report Handler & Persistence
    // Notifikasi ringan untuk halaman formulir.
    function showToast(message) {
        let el = document.getElementById('form-toast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'form-toast';
            el.style.cssText = 'position:fixed;left:50%;bottom:28px;transform:translateX(-50%);background:#0f172a;color:#fff;'
                + 'padding:12px 18px;border-radius:12px;font-size:13.5px;font-weight:600;z-index:9999;'
                + 'box-shadow:0 10px 30px rgba(15,23,42,.25);opacity:0;transition:opacity .2s ease;max-width:88vw;text-align:center;';
            document.body.appendChild(el);
        }
        el.textContent = message;
        el.style.opacity = '1';
        clearTimeout(el._t);
        el._t = setTimeout(() => { el.style.opacity = '0'; }, 3200);
    }

    function submitReport() {
        const name = document.getElementById('input-item-name').value.trim();
        const catSelect = document.getElementById('select-category').value;
        const catOther = document.getElementById('input-category-other') ? document.getElementById('input-category-other').value.trim() : '';
        const catName = (catSelect === 'Lainnya' && catOther) ? catOther : catSelect;
        const locCampus = document.getElementById('select-location').value || 'Kampus Meruya';
        const specLoc = document.getElementById('input-specific-location').value.trim();
        const dateVal = document.getElementById('input-date-lost').value;
        const timeVal = document.getElementById('input-time-lost') ? document.getElementById('input-time-lost').value.trim() : '';
        const featuresVal = document.getElementById('input-features').value.trim();
        const brandVal = document.getElementById('input-brand') ? document.getElementById('input-brand').value.trim() : '';

        if (!name) { showToast('Nama barang wajib diisi.'); return; }

        // Kirim ke Supabase lewat API — bukan localStorage lagi.
        const form = new FormData();
        form.append('type', 'lost');
        form.append('title', name);
        form.append('description', featuresVal);
        form.append('campus_location', locCampus);
        form.append('location', specLoc);
        form.append('brand', brandVal);
        form.append('incident_date', dateVal);
        form.append('incident_time', timeVal);

        const catId = CATEGORY_MAP[catName];
        if (catId) form.append('category_id', catId);
        if (currentPhotoFile) form.append('photo', currentPhotoFile);

        const btn = document.querySelector('[onclick="submitReport()"]');
        if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Menyimpan...'; }

        fetch('{{ route('api.reports.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                'Accept': 'application/json',
            },
            body: form,
        })
            .then(async (res) => {
                const payload = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const msg = payload.message
                        || (payload.errors ? Object.values(payload.errors)[0][0] : null)
                        || 'Laporan gagal disimpan.';
                    throw new Error(msg);
                }
                return payload;
            })
            .then((payload) => {
                // Arahkan ke laporan asli yang barusan dibuat di Supabase.
                const id = payload?.data?.id;
                window.location.href = id ? `/report/${id}` : "{{ route('my.reports') }}";
            })
            .catch((err) => {
                if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Kirim Laporan'; }
                showToast(err.message || 'Laporan gagal disimpan.');
            });
    }
</script>
@endpush
