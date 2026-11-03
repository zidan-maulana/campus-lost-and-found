@extends('layouts.app')

@section('title', 'Pengaturan — Find UMB')

@section('header')
<header class="settings-header">
    <button type="button" class="btn-header-back" onclick="handleSettingsBack()" aria-label="Kembali">
        <i data-lucide="chevron-left"></i>
    </button>
    <h1 class="settings-header-title">Pengaturan</h1>
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

    /* Primary Save Button */
    .btn-save-settings {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius-md);
        border: none;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.2);
        margin-top: 0.25rem;
    }

    .btn-save-settings:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-save-settings:active {
        transform: scale(0.98);
    }

    .btn-save-settings svg, .btn-save-settings .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
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

    <!-- Tombol Simpan Perubahan -->
    <button type="button" class="btn-save-settings" id="btn-save-settings" onclick="saveSettings()">
        <i data-lucide="save"></i>
        <span>Simpan Pengaturan</span>
    </button>

    <!-- 3. DIAGNOSTIK SISTEM -->
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

</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
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
        try {
            const raw = localStorage.getItem('findumb_app_settings');
            if (raw) {
                const settings = JSON.parse(raw);
                if (settings.language && document.getElementById('app-language-select')) {
                    document.getElementById('app-language-select').value = settings.language;
                }
                if (settings.push !== undefined && document.getElementById('toggle-notif-push')) {
                    document.getElementById('toggle-notif-push').checked = settings.push;
                }
                if (settings.claim !== undefined && document.getElementById('toggle-notif-claim')) {
                    document.getElementById('toggle-notif-claim').checked = settings.claim;
                }
                if (settings.status !== undefined && document.getElementById('toggle-notif-status')) {
                    document.getElementById('toggle-notif-status').checked = settings.status;
                }
                if (settings.chat !== undefined && document.getElementById('toggle-notif-chat')) {
                    document.getElementById('toggle-notif-chat').checked = settings.chat;
                }
            }
        } catch(e) {}
    }

    function saveSettings() {
        const settings = {
            language: document.getElementById('app-language-select')?.value || 'id',
            push: document.getElementById('toggle-notif-push')?.checked ?? true,
            claim: document.getElementById('toggle-notif-claim')?.checked ?? true,
            status: document.getElementById('toggle-notif-status')?.checked ?? true,
            chat: document.getElementById('toggle-notif-chat')?.checked ?? true,
            savedAt: new Date().toISOString()
        };

        try {
            localStorage.setItem('findumb_app_settings', JSON.stringify(settings));
        } catch(e) {}

        showToast('✓ Pengaturan berhasil disimpan!');
    }

    function handleLanguageChange(val) {
        const name = val === 'en' ? 'English' : 'Indonesia';
        showToast(`Bahasa diatur ke ${name}`);
    }
</script>
@endpush

