@extends('layouts.app')

@section('title', 'Notifikasi — Find UMB')
@section('app_shell_class', 'bg-white')

@section('header')
<header class="notif-header">
    <div class="notif-header-left">
        <button type="button" class="btn-header-back" onclick="handleNotifBack()" aria-label="Kembali">
            <i data-lucide="chevron-left"></i>
        </button>
        <div class="notif-title-group">
            <h1 class="notif-header-title">Notifikasi</h1>
            <span class="header-unread-badge" id="header-unread-badge">3</span>
        </div>
    </div>
    <div class="notif-header-actions">
        <!-- Filter Dropdown Button: Hari, Minggu, Bulan, Yang Lain -->
        <div class="filter-dropdown-wrap">
            <button type="button" class="btn-header-filter" id="btn-notif-filter" onclick="toggleNotifFilterMenu(event)" aria-haspopup="listbox" aria-expanded="false" title="Filter Notifikasi">
                <i data-lucide="calendar" class="filter-icon-left"></i>
                <span id="filter-btn-text">Semua</span>
                <i data-lucide="chevron-down" class="filter-icon-arrow"></i>
            </button>
            <div class="notif-filter-dropdown" id="notif-filter-dropdown" style="display: none;">
                <div class="filter-dropdown-header">Filter Notifikasi</div>
                <button type="button" class="filter-menu-item selected" data-filter="all" onclick="selectNotifFilter('all', 'Semua')">
                    <div class="filter-item-left">
                        <i data-lucide="layers"></i>
                        <span>Semua Notifikasi</span>
                    </div>
                    <i data-lucide="check" class="item-check"></i>
                </button>
                <button type="button" class="filter-menu-item" data-filter="today" onclick="selectNotifFilter('today', 'Hari Ini')">
                    <div class="filter-item-left">
                        <i data-lucide="calendar-check"></i>
                        <span>Hari Ini</span>
                    </div>
                    <i data-lucide="check" class="item-check"></i>
                </button>
                <button type="button" class="filter-menu-item" data-filter="week" onclick="selectNotifFilter('week', 'Minggu Ini')">
                    <div class="filter-item-left">
                        <i data-lucide="calendar-days"></i>
                        <span>Minggu Ini</span>
                    </div>
                    <i data-lucide="check" class="item-check"></i>
                </button>
                <button type="button" class="filter-menu-item" data-filter="month" onclick="selectNotifFilter('month', 'Bulan Ini')">
                    <div class="filter-item-left">
                        <i data-lucide="calendar"></i>
                        <span>Bulan Ini</span>
                    </div>
                    <i data-lucide="check" class="item-check"></i>
                </button>
                <div class="filter-dropdown-divider"></div>
                <button type="button" class="filter-menu-item" data-filter="unread" onclick="selectNotifFilter('unread', 'Belum Dibaca')">
                    <div class="filter-item-left">
                        <i data-lucide="bell" style="color: #ef4444;"></i>
                        <span>Belum Dibaca</span>
                    </div>
                    <i data-lucide="check" class="item-check"></i>
                </button>
                <div class="filter-dropdown-divider"></div>
                <button type="button" class="filter-menu-item action-mark-read" id="btn-mark-all-read" onclick="markAllAsReadFromMenu()">
                    <div class="filter-item-left">
                        <i data-lucide="check-check"></i>
                        <span>Tandai Semua Dibaca</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</header>
@endsection

@push('styles')
<style>
    /* Full Page Edge-to-Edge Container (Tanpa Pembungkus Card) */
    .notif-page-container {
        padding: 0.5rem 1.15rem 105px 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        background: #ffffff;
        min-height: 100%;
        margin-bottom: -95px; /* Memanjang ke bawah memenuhi layar hingga belakang bottom nav */
    }

    /* Top Sticky Header */
    .notif-header {
        padding: 0.75rem 1.15rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border-bottom: 1px solid var(--border-subtle);
        position: sticky;
        top: 0;
        z-index: 40;
    }

    .notif-header-left {
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

    .notif-title-group {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .notif-header-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.01em;
        line-height: 1.2;
        margin: 0;
    }

    /* Indikator Angka Notif Merah */
    .header-unread-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 0.35rem;
        border-radius: 9999px;
        background: #fee2e2;
        color: #ef4444; /* Merah */
        border: 1px solid #fecaca;
        font-size: 0.68rem;
        font-weight: 700;
        line-height: 1;
    }

    .notif-header-actions {
        display: flex;
        align-items: center;
    }

    /* Filter Dropdown Container */
    .filter-dropdown-wrap {
        position: relative;
    }

    .btn-header-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.38rem 0.65rem;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-family: var(--font-main);
        font-size: 0.74rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        user-select: none;
    }

    .btn-header-filter:hover, .btn-header-filter.dropdown-open {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1572b8;
    }

    .btn-header-filter.active-filter {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1572b8;
    }

    .filter-icon-left {
        width: 14px;
        height: 14px;
        stroke-width: 2.1;
        color: #1572b8;
    }

    .filter-icon-arrow {
        width: 12px;
        height: 12px;
        stroke-width: 2.2;
        color: #94a3b8;
        transition: transform 0.2s ease;
    }

    .btn-header-filter.dropdown-open .filter-icon-arrow {
        transform: rotate(180deg);
    }

    /* Floating Dropdown Menu */
    .notif-filter-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 200px;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
        z-index: 100;
        padding: 0.4rem;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        animation: fadeInMenu 0.15s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInMenu {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .filter-dropdown-header {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        padding: 0.35rem 0.6rem 0.25rem 0.6rem;
    }

    .filter-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.52rem 0.6rem;
        border-radius: 8px;
        background: transparent;
        border: none;
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        transition: background-color 0.15s ease, color 0.15s ease;
        text-align: left;
    }

    .filter-menu-item:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .filter-menu-item.selected {
        background: #eff6ff;
        color: #1572b8;
        font-weight: 600;
    }

    .filter-item-left {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .filter-item-left svg, .filter-item-left .lucide {
        width: 14px;
        height: 14px;
        stroke-width: 2.1;
        color: #64748b;
    }

    .filter-menu-item.selected .filter-item-left svg,
    .filter-menu-item.selected .filter-item-left .lucide {
        color: #1572b8;
    }

    .item-check {
        width: 13px;
        height: 13px;
        stroke-width: 2.4;
        color: #1572b8;
        display: none;
    }

    .filter-menu-item.selected .item-check {
        display: block;
    }

    .filter-dropdown-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0.25rem 0.4rem;
    }

    .action-mark-read {
        color: #475569;
        font-weight: 600;
    }

    .action-mark-read:hover {
        background: #eff6ff;
        color: #1572b8;
    }

    /* Notification Items List */
    .notif-items-list {
        display: flex;
        flex-direction: column;
    }

    /* Single Notification Row Item */
    .notif-row-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.95rem 0.2rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background-color 0.15s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .notif-row-item:last-child {
        border-bottom: none;
    }

    .notif-row-item:hover {
        background-color: #fbfdff;
    }

    /* Squircle / Rounded Square Shape (Bukan Lingkaran) */
    .notif-blue-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px; /* Modern squircle / rounded-rect */
        background: #eff6ff;
        border: 1px solid #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }

    .notif-row-item:hover .notif-blue-icon {
        transform: scale(1.04);
        background: #e0f2fe;
        border-color: #bfdbfe;
    }

    .notif-blue-icon svg, .notif-blue-icon .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.1;
        color: #1572b8; /* Ikon berwarna biru */
    }

    /* Text Column (Maksimal 1 Baris Per Teks) */
    .notif-body-col {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    /* Row 1: Title + Unread Dot Merah + Time */
    .notif-line-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .notif-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.42rem;
        min-width: 0;
        flex: 1;
    }

    /* Indikator Dot Notif Merah */
    .unread-indicator-dot {
        width: 6.5px;
        height: 6.5px;
        border-radius: 50%;
        background: #ef4444; /* Merah */
        flex-shrink: 0;
    }

    /* Title: Maksimal 1 Baris */
    .notif-item-title {
        font-family: var(--font-heading);
        font-size: 0.86rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        letter-spacing: -0.01em;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notif-row-item.read .notif-item-title {
        font-weight: 500;
        color: #334155;
    }

    /* Time: Maksimal 1 Baris */
    .notif-item-time {
        font-family: var(--font-main);
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* Row 2: Snippet Description + Subtle Chevron */
    .notif-line-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    /* Snippet: Maksimal 1 Baris */
    .notif-item-desc {
        font-family: var(--font-main);
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.35;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        min-width: 0;
    }

    .notif-row-item.unread .notif-item-desc {
        color: #475569;
    }

    .notif-item-chevron {
        width: 14px;
        height: 14px;
        color: #cbd5e1;
        flex-shrink: 0;
        transition: transform 0.15s ease, color 0.15s ease;
    }

    .notif-row-item:hover .notif-item-chevron {
        transform: translateX(2px);
        color: #1572b8;
    }

    /* Empty State - Centered Perfectly Vertically & Horizontally */
    .notif-empty-state {
        display: none;
        flex: 1;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2.5rem 1.5rem;
        margin: auto 0;
        min-height: 52vh;
    }

    .empty-mascot-box {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.25rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .empty-mascot-box picture {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .empty-mascot-img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 6px 16px rgba(15, 23, 42, 0.08));
    }

    .empty-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .empty-desc {
        font-family: var(--font-main);
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.45;
        max-width: 270px;
        margin-bottom: 1.25rem;
    }

    .empty-reset-btn {
        padding: 0.5rem 1.25rem;
        background: #1572b8;
        color: #ffffff;
        border: none;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .empty-reset-btn:hover {
        background: #0f5994;
    }

    /* Modal Preferences Backing (Hidden by Default) */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 100;
        display: none;
        align-items: flex-end;
        justify-content: center;
    }

    .modal-backdrop.active {
        display: flex;
    }

    .modal-bottom-sheet {
        background: #ffffff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px 20px 0 0;
        padding: 1.25rem 1.25rem 2rem 1.25rem;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Floating Toast Popup */
    .toast-popup {
        position: fixed;
        bottom: 5.5rem;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #0f172a;
        color: #ffffff;
        padding: 0.65rem 1.25rem;
        border-radius: var(--radius-pill);
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        z-index: 120;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, transform 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .toast-popup.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }

    .toast-popup svg, .toast-popup .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
        color: #38bdf8;
    }
</style>
@endpush

@section('content')
<div class="notif-page-container" id="notif-page-root" data-full-white="true">

    <!-- Clean Notification List (Maksimal 1 baris per teks) -->
    <div class="notif-items-list" id="notif-list-container">
        <!-- Rendered dynamically by JavaScript -->
    </div>

    <!-- Clean Empty State -->
    <div class="notif-empty-state" id="notif-empty-state">
        <div class="empty-mascot-box">
            <picture>
                <source srcset="{{ asset('images/mascot.webp') }}" type="image/webp">
                <img src="{{ asset('images/mascot.png') }}" alt="Maskot FindUMB" class="empty-mascot-img" width="100" height="100" loading="lazy">
            </picture>
        </div>
        <h3 class="empty-title" id="empty-title-text">Belum Ada Notifikasi</h3>
        <p class="empty-desc" id="empty-desc-text">Semua pemberitahuan klaim, pesan, dan status barang akan tampil di sini.</p>
        <button type="button" class="empty-reset-btn" onclick="selectNotifFilter('all', 'Semua')">Tampilkan Semua</button>
    </div>

</div>

<!-- NOTIFICATION SETTINGS MODAL (Maintained for Feature Compatibility) -->
<div class="modal-backdrop" id="modal-notif-settings" role="dialog" aria-modal="true" aria-hidden="true" onclick="closeNotifSettingsModal(event)">
    <div class="modal-bottom-sheet" onclick="event.stopPropagation()">
        <div class="sheet-header">
            <h2 class="sheet-title" style="font-size: 1.05rem; font-weight: 700;">Pengaturan Notifikasi</h2>
            <button type="button" style="background: transparent; border: none; cursor: pointer;" onclick="closeNotifSettingsModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>
        <p style="font-size: 0.78rem; color: #64748b; margin-top: -0.3rem;">Pemberitahuan aktif untuk klaim, pesan, dan serah terima.</p>
    </div>
</div>

<!-- TOAST POPUP FEEDBACK -->
<div class="toast-popup" id="notif-toast" role="status" aria-live="polite">
    <i data-lucide="check-circle-2"></i>
    <span id="toast-message">Notifikasi diperbarui</span>
</div>
@endsection

@push('scripts')
<script>
    // Canonical Seed Notifications strictly reflecting FindUMB Specifications
    const DEFAULT_NOTIFICATIONS = [
        {
            id: 'notif_1',
            type: 'claim',
            time_category: 'today',
            icon: 'shield-alert',
            tag: 'Klaim Masuk',
            title: 'Pengajuan Klaim: Earphone TWS Anker Soundcore',
            body: 'Dinda Kirana (NIM 4152***) mengajukan klaim kepemilikan atas barang temuan Anda.',
            time_display: '10 mnt yang lalu',
            target_url: '{{ route('messages') }}',
            read: false,
            timestamp: new Date(Date.now() - 10 * 60 * 1000).toISOString()
        },
        {
            id: 'notif_2',
            type: 'warning',
            time_category: 'today',
            icon: 'clock-3',
            tag: 'Pengingat 72 Jam',
            title: 'Peringatan Masa Tunggu: Samsung SM-A135F',
            body: 'Klaim Anda telah 72 jam tanpa respons dari penemu. Sisa 48 jam sebelum kedaluwarsa (DEC-012).',
            time_display: '45 mnt yang lalu',
            target_url: '{{ url('/report/1') }}',
            read: false,
            timestamp: new Date(Date.now() - 45 * 60 * 1000).toISOString()
        },
        {
            id: 'notif_3',
            type: 'handover',
            time_category: 'today',
            icon: 'key-round',
            tag: 'Serah Terima',
            title: 'Jadwal Serah Terima Telah Disepakati',
            body: 'Pertemuan serah terima Tas Laptop ThinkPad dijadwalkan di Pos Satpam Tower, 20 Jan 14:00 WIB.',
            time_display: '3 jam yang lalu',
            target_url: '{{ url('/report/7') }}',
            read: false,
            timestamp: new Date(Date.now() - 3 * 3600 * 1000).toISOString()
        },
        {
            id: 'notif_4',
            type: 'message',
            time_category: 'week',
            icon: 'message-square',
            tag: 'Pesan Baru',
            title: 'Pesan Baru dari Ahmad Fauzi',
            body: '"Halo Kak, apakah dompet kulit cokelat tersebut masih tersimpan di pos kantin?"',
            time_display: 'Kemarin, 16:45',
            target_url: '{{ route('messages') }}',
            read: true,
            timestamp: new Date(Date.now() - 24 * 3600 * 1000).toISOString()
        },
        {
            id: 'notif_5',
            type: 'system',
            time_category: 'month',
            icon: 'check-circle-2',
            tag: 'Laporan Terbit',
            title: 'Laporan Berhasil Dipublikasikan',
            body: 'Laporan kehilangan "Cincin Emas Berlian" telah divalidasi sistem dan tampil di katalog publik.',
            time_display: '15 Jan 2026',
            target_url: '{{ url('/report/5') }}',
            read: true,
            timestamp: new Date(Date.now() - 3 * 24 * 3600 * 1000).toISOString()
        },
        {
            id: 'notif_6',
            type: 'handover',
            time_category: 'earlier',
            icon: 'shield-check',
            tag: 'Serah Terima',
            title: 'Serah Terima Selesai divalidasi',
            body: 'Proses serah terima Kunci Motor Honda Vario telah sukses divalidasi dengan PIN.',
            time_display: '10 Jan 2026',
            target_url: '{{ url('/report/4') }}',
            read: true,
            timestamp: new Date(Date.now() - 7 * 24 * 3600 * 1000).toISOString()
        }
    ];

    let currentFilter = 'all'; // 'all' | 'today' | 'week' | 'month' | 'unread'

    function getStoredNotifications() {
        try {
            const raw = localStorage.getItem('findumb_notifications');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    return parsed;
                }
            }
            localStorage.setItem('findumb_notifications', JSON.stringify(DEFAULT_NOTIFICATIONS));
            return [...DEFAULT_NOTIFICATIONS];
        } catch (e) {
            return [...DEFAULT_NOTIFICATIONS];
        }
    }

    function saveNotifications(list) {
        try {
            localStorage.setItem('findumb_notifications', JSON.stringify(list));
            updateGlobalHeaderDot(list);
        } catch (e) {
            console.error('Failed to save notifications', e);
        }
    }

    function updateGlobalHeaderDot(list) {
        const dot = document.getElementById('header-notif-dot');
        if (dot) {
            const hasUnread = list.some(n => !n.read);
            dot.style.display = hasUnread ? 'block' : 'none';
        }
    }

    function toggleNotifFilterMenu(e) {
        e.stopPropagation();
        const menu = document.getElementById('notif-filter-dropdown');
        const btn = document.getElementById('btn-notif-filter');
        if (!menu || !btn) return;
        const isOpen = menu.style.display !== 'none';
        if (isOpen) {
            closeNotifFilterMenu();
        } else {
            menu.style.display = 'flex';
            btn.classList.add('dropdown-open');
            btn.setAttribute('aria-expanded', 'true');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeNotifFilterMenu() {
        const menu = document.getElementById('notif-filter-dropdown');
        const btn = document.getElementById('btn-notif-filter');
        if (menu) menu.style.display = 'none';
        if (btn) {
            btn.classList.remove('dropdown-open');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    function selectNotifFilter(filter, label) {
        currentFilter = filter;
        const btn = document.getElementById('btn-notif-filter');
        const text = document.getElementById('filter-btn-text');

        if (text) text.textContent = label;
        if (btn) {
            if (filter !== 'all') {
                btn.classList.add('active-filter');
            } else {
                btn.classList.remove('active-filter');
            }
        }

        document.querySelectorAll('.filter-menu-item').forEach(item => {
            if (item.getAttribute('data-filter')) {
                item.classList.toggle('selected', item.getAttribute('data-filter') === filter);
            }
        });

        closeNotifFilterMenu();
        window.scrollTo({ top: 0, behavior: 'instant' });
        renderNotifications();
        showToast(`Filter: ${label}`);
    }

    function markAllAsReadFromMenu() {
        closeNotifFilterMenu();
        markAllAsRead();
    }

    function renderNotifications() {
        const list = getStoredNotifications();
        const container = document.getElementById('notif-list-container');
        const emptyState = document.getElementById('notif-empty-state');
        const unreadBadge = document.getElementById('header-unread-badge');

        const unreadCount = list.filter(n => !n.read).length;

        // Update header badge merah
        if (unreadCount > 0) {
            unreadBadge.textContent = unreadCount;
            unreadBadge.style.display = 'inline-flex';
        } else {
            unreadBadge.style.display = 'none';
        }

        // Apply filter logic: all, today, week, month, unread
        let filtered = [];
        if (currentFilter === 'all') {
            filtered = list;
        } else if (currentFilter === 'today') {
            filtered = list.filter(n => n.time_category === 'today');
        } else if (currentFilter === 'week') {
            filtered = list.filter(n => n.time_category === 'today' || n.time_category === 'week');
        } else if (currentFilter === 'month') {
            filtered = list.filter(n => n.time_category === 'today' || n.time_category === 'week' || n.time_category === 'month');
        } else if (currentFilter === 'unread') {
            filtered = list.filter(n => !n.read);
        }

        if (filtered.length === 0) {
            container.innerHTML = '';
            container.style.display = 'none';
            emptyState.style.display = 'flex';
            window.scrollTo({ top: 0, behavior: 'instant' });
            const emptyTitle = document.getElementById('empty-title-text');
            const emptyDesc = document.getElementById('empty-desc-text');
            if (currentFilter === 'today') {
                emptyTitle.textContent = 'Tidak Ada Notifikasi Hari Ini';
                emptyDesc.textContent = 'Belum ada notifikasi baru yang masuk hari ini.';
            } else if (currentFilter === 'month') {
                emptyTitle.textContent = 'Tidak Ada Notifikasi Bulan Ini';
                emptyDesc.textContent = 'Belum ada notifikasi pada bulan ini.';
            } else if (currentFilter === 'unread') {
                emptyTitle.textContent = 'Semua Notifikasi Telah Dibaca';
                emptyDesc.textContent = 'Bagus sekali! Tidak ada notifikasi yang belum dibaca.';
            } else {
                emptyTitle.textContent = 'Belum Ada Notifikasi';
                emptyDesc.textContent = 'Semua pemberitahuan klaim, pesan, dan status barang akan tampil di sini.';
            }
            return;
        }

        container.style.display = 'flex';
        emptyState.style.display = 'none';

        // Render clean 1-line-capped notification rows
        let html = '';
        filtered.forEach(item => {
            const isUnread = !item.read;
            const unreadClass = isUnread ? 'unread' : 'read';
            const dotHtml = isUnread ? '<span class="unread-indicator-dot"></span>' : '';

            html += `
                <article class="notif-row-item ${unreadClass}" id="item-${item.id}" onclick="handleCardClick(event, '${item.id}', '${item.target_url}')">
                    <div class="notif-blue-icon">
                        <i data-lucide="${item.icon}"></i>
                    </div>
                    <div class="notif-body-col">
                        <div class="notif-line-top">
                            <div class="notif-title-wrap">
                                ${dotHtml}
                                <h3 class="notif-item-title">${escapeHtml(item.title)}</h3>
                            </div>
                            <span class="notif-item-time">${escapeHtml(item.time_display)}</span>
                        </div>
                        <div class="notif-line-bottom">
                            <p class="notif-item-desc">${escapeHtml(item.body)}</p>
                            <i data-lucide="chevron-right" class="notif-item-chevron"></i>
                        </div>
                    </div>
                </article>
            `;
        });

        container.innerHTML = html;

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function handleCardClick(event, id, url) {
        const list = getStoredNotifications();
        const item = list.find(n => String(n.id) === String(id));
        if (item && !item.read) {
            item.read = true;
            saveNotifications(list);
        }

        if (url && url !== '#') {
            window.location.href = url;
        } else {
            renderNotifications();
        }
    }

    function markAllAsRead() {
        const list = getStoredNotifications();
        let updatedCount = 0;
        list.forEach(item => {
            if (!item.read) {
                item.read = true;
                updatedCount++;
            }
        });
        saveNotifications(list);
        renderNotifications();
        showToast(updatedCount > 0 ? 'Semua notifikasi ditandai telah dibaca.' : 'Semua notifikasi sudah dibaca.');
    }

    function closeNotifSettingsModal(e) {
        const modal = document.getElementById('modal-notif-settings');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function handleNotifBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ url('/') }}";
        }
    }

    function showToast(msg) {
        const toast = document.getElementById('notif-toast');
        const text = document.getElementById('toast-message');
        if (toast && text) {
            text.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
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

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('notif-filter-dropdown');
        const btn = document.getElementById('btn-notif-filter');
        if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
            closeNotifFilterMenu();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        renderNotifications();
    });
</script>
@endpush
