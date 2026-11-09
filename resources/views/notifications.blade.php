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

    /* Status Badges for Desktop Detail Modal */
    .badge-status {
        padding: 0.22rem 0.65rem;
        border-radius: 8px;
        font-family: var(--font-main);
        font-size: 0.725rem;
        font-weight: 500;
        letter-spacing: 0;
        line-height: 1.1;
    }

    .badge-lost {
        background: #fee2e2;
        color: #e11d48;
    }

    .badge-found {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-resolved {
        background: #f1f5f9;
        color: #475569;
    }

    /* ----------------------------------------------------
       Desktop Floating Detail Modal (Jendela Mengambang)
       ---------------------------------------------------- */
    @media (min-width: 1024px) {
        .desktop-detail-modal-backdrop {
            z-index: 150;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            align-items: center !important;
            justify-content: center !important;
            padding: 1.5rem;
        }

        .desktop-detail-modal-backdrop.active {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .desktop-detail-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 560px;
            max-height: 86vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(15, 23, 42, 0.06);
            transform: scale(0.96);
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.24s ease;
        }

        .desktop-detail-modal-backdrop.active .desktop-detail-card {
            transform: scale(1);
        }

        .desktop-detail-hero-box {
            position: relative;
            width: 100%;
            height: 220px;
            background: #f1f5f9;
            flex-shrink: 0;
            overflow: hidden;
            cursor: pointer;
        }

        .desktop-detail-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .desktop-detail-floating-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.5) 0%, rgba(15, 23, 42, 0) 100%);
            z-index: 2;
        }

        .desktop-detail-floating-actions-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .desktop-detail-circle-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .desktop-detail-circle-btn:hover {
            background: #ffffff;
            color: #0f172a;
            transform: scale(1.06);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
        }

        .desktop-detail-circle-btn.bookmarked {
            background: #eff6ff;
            color: #1572b8;
            border-color: #bfdbfe;
        }

        .desktop-detail-circle-btn svg, .desktop-detail-circle-btn .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .desktop-detail-content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.65rem 1.25rem 1.65rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .desktop-detail-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
            line-height: 1.3;
        }

        .desktop-detail-author-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            color: #64748b;
            margin-bottom: 1.15rem;
        }

        .desktop-detail-author-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0284c7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .desktop-detail-author-avatar svg, .desktop-detail-author-avatar .lucide {
            width: 14px;
            height: 14px;
        }

        .desktop-detail-author-name {
            font-weight: 600;
            color: #1e293b;
        }

        .reporter-badge-verified {
            display: inline-flex;
            align-items: center;
            color: #0284c7;
        }

        .reporter-badge-verified svg, .reporter-badge-verified .lucide {
            width: 14px;
            height: 14px;
        }

        .desktop-detail-meta-dot {
            color: #cbd5e1;
        }

        .desktop-detail-props-table {
            border-top: 1px solid #f1f5f9;
            margin-bottom: 1.1rem;
        }

        .desktop-detail-prop-row {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.85rem;
            line-height: 1.45;
            padding: 0.55rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .desktop-detail-prop-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            width: 140px;
            flex-shrink: 0;
            color: #64748b;
            font-weight: 500;
        }

        .desktop-detail-prop-label svg, .desktop-detail-prop-label .lucide {
            width: 15px;
            height: 15px;
            color: #64748b;
            flex-shrink: 0;
            stroke-width: 2.2;
        }

        .desktop-detail-prop-value {
            flex: 1;
            min-width: 0;
            color: #0f172a;
            font-weight: 600;
            word-break: break-word;
        }

        .desktop-detail-desc-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-top: 0.25rem;
        }

        .desktop-detail-desc-heading {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .desktop-detail-desc-content {
            font-size: 0.825rem;
            line-height: 1.55;
            color: #475569;
            margin: 0;
        }

        .desktop-detail-footer-bar {
            padding: 0.95rem 1.65rem 1.15rem 1.65rem;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .desktop-detail-btn-primary {
            flex: 1;
            background: #0f5791;
            color: #ffffff;
            border: none;
            height: 46px;
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
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .desktop-detail-btn-primary:hover {
            background: #0b4372;
        }

        .desktop-detail-btn-primary svg, .desktop-detail-btn-primary .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .desktop-detail-btn-bookmark, .desktop-detail-btn-external {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .desktop-detail-btn-bookmark:hover, .desktop-detail-btn-external:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .desktop-detail-btn-bookmark.bookmarked {
            background: #eff6ff;
            color: #1572b8;
            border-color: #bfdbfe;
        }

        .desktop-detail-btn-bookmark svg, .desktop-detail-btn-external svg,
        .desktop-detail-btn-bookmark .lucide, .desktop-detail-btn-external .lucide {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
        }
    }

    @media (max-width: 1023px) {
        .desktop-detail-modal-backdrop {
            display: none !important;
        }
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

<!-- DESKTOP DETAIL MODAL (Floating Modal / Jendela Mengambang) -->
<div class="modal-backdrop desktop-detail-modal-backdrop" id="modal-desktop-detail" onclick="closeDesktopDetailModalOnBackdrop(event)" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="desktop-detail-card" onclick="event.stopPropagation()">
        <!-- Top Image with Floating Action Buttons & Zoom Trigger -->
        <div class="desktop-detail-hero-box" onclick="openImageLightbox(document.getElementById('modal-detail-hero-img').src, document.getElementById('modal-detail-title').textContent)" title="Klik untuk lihat detail foto">
            <img src="" alt="Foto Barang" class="desktop-detail-hero-img" id="modal-detail-hero-img">
            <div class="desktop-detail-zoom-pill">
                <i data-lucide="zoom-in"></i>
                <span>Lihat Detail Foto</span>
            </div>
            <div class="desktop-detail-floating-header">
                <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); closeDesktopDetailModal()" aria-label="Tutup Detail" title="Tutup">
                    <i data-lucide="x"></i>
                </button>
                <div class="desktop-detail-floating-actions-right">
                    <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); handleDetailModalShare()" aria-label="Bagikan Laporan" title="Bagikan Laporan">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="desktop-detail-circle-btn" id="modal-detail-top-bookmark" onclick="event.stopPropagation(); handleDetailModalBookmark()" aria-label="Simpan Laporan" title="Simpan Laporan">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Scrollable Modal Content -->
        <div class="desktop-detail-content-scroll">
            <!-- Heading Title -->
            <h2 class="desktop-detail-title" id="modal-detail-title">Nama Barang</h2>

            <!-- Author / Reporter Row -->
            <div class="desktop-detail-author-row">
                <div class="desktop-detail-author-avatar">
                    <i data-lucide="user"></i>
                </div>
                <span class="desktop-detail-author-name" id="modal-detail-reporter-name">Civitas UMB</span>
                <span class="reporter-badge-verified" title="Terverifikasi">
                    <i data-lucide="check-circle-2"></i>
                </span>
                <span class="desktop-detail-meta-dot">•</span>
                <span class="desktop-detail-post-date" id="modal-detail-post-date">12 Mei 2026</span>
            </div>

            <!-- Structured Properties List -->
            <div class="desktop-detail-props-table">
                <!-- Status Laporan -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="tag"></i>
                        <span>Status:</span>
                    </div>
                    <div class="desktop-detail-prop-value">
                        <span class="badge-status badge-lost" id="modal-detail-status-badge">Hilang</span>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="calendar"></i>
                        <span id="modal-label-prop-date">Tanggal Hilang:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-date">12 Mei 2026</div>
                </div>

                <!-- Waktu -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="clock"></i>
                        <span>Waktu:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-time">10.00 - 11.30 WIB</div>
                </div>

                <!-- Lokasi Kampus -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="map-pin"></i>
                        <span>Lokasi Kampus:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-location">Kampus Meruya — Gedung E R. 204</div>
                </div>

                <!-- Kategori Barang -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="layers"></i>
                        <span>Kategori:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-category">Buku, Dokumen &amp; ATK</div>
                </div>

                <!-- Merek / Tipe -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="bookmark"></i>
                        <span>Merek / Tipe:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-brand">Dokumen Resmi Imigrasi</div>
                </div>

                <!-- Status Simpan / Pengamanan -->
                <div class="desktop-detail-prop-row" id="modal-row-prop-storage">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="shield-check"></i>
                        <span>Status Simpan:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-storage">Disimpan Sendiri</div>
                </div>
            </div>

            <!-- Deskripsi & Ciri Khusus -->
            <div class="desktop-detail-desc-section">
                <h3 class="desktop-detail-desc-heading">Deskripsi &amp; Ciri Khusus</h3>
                <p class="desktop-detail-desc-content" id="modal-detail-features">Map plastik biru berisi Paspor Republik Indonesia...</p>
            </div>
        </div>

        <!-- Bottom Action Bar inside Modal -->
        <div class="desktop-detail-footer-bar">
            <button type="button" class="desktop-detail-btn-primary" id="modal-detail-primary-btn" onclick="handleModalPrimaryAction()">
                <i data-lucide="hand" id="modal-detail-btn-icon"></i>
                <span id="modal-detail-btn-label">Saya Menemukan Barang Ini</span>
            </button>
            <button type="button" class="desktop-detail-btn-bookmark" id="modal-detail-footer-bookmark" onclick="handleDetailModalBookmark()" aria-label="Simpan Laporan" title="Simpan Laporan">
                <i data-lucide="bookmark"></i>
            </button>
            <a href="#" class="desktop-detail-btn-external" id="modal-detail-open-full" target="_self" title="Buka di Halaman Penuh" aria-label="Buka di Halaman Penuh">
                <i data-lucide="external-link"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Notifikasi asli milik akun yang login (dari tabel notifications).
    const SERVER_NOTIFICATIONS = @json($notifications->map(fn ($n) => $n->toUiArray())->values());
    let SERVER_BOOKMARKS = @json($savedIds ?? []);
    const SERVER_REPORTS = []; // detail laporan diambil dari API saat modal dibuka
    const IS_AUTHENTICATED = @json(auth()->check());
    let NOTIFICATION_STATE = SERVER_NOTIFICATIONS.map(n => ({ ...n }));

    let currentFilter = 'all'; // 'all' | 'today' | 'week' | 'month' | 'unread'

    function getStoredNotifications() {
        return NOTIFICATION_STATE;
    }

    function saveNotifications(list) {
        NOTIFICATION_STATE = list;
        updateGlobalHeaderDot(list);
    }

    function syncNotificationRead(notificationId) {
        // Simpan status baca ke server (id dari server: "notif_12").
        const numericId = String(notificationId).replace('notif_', '');
        fetch(`{{ url('/api/notifications') }}/${numericId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (window.FindUMB_CSRF || document.querySelector('meta[name="csrf-token"]')?.content || ''),
                'Accept': 'application/json',
            },
        }).catch(() => {});
    }

    function syncAllNotificationsRead() {
        fetch('{{ url('/api/notifications/read-all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (window.FindUMB_CSRF || document.querySelector('meta[name="csrf-token"]')?.content || ''),
                'Accept': 'application/json',
            },
        }).catch(() => {});
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

    // ----------------------------------------------------
    // Desktop Floating Detail Modal Catalog & Handlers
    // ----------------------------------------------------
    // (katalog mock dihapus — semua data dari Supabase)

    function getReportDataById(id) {
        const idStr = String(id);
        try {
            const raw = JSON.stringify(SERVER_REPORTS || []);
            if (raw) {
                const list = JSON.parse(raw);
                if (Array.isArray(list)) {
                    const found = list.find(r => String(r.id) === idStr);
                    if (found) {
                        return {
                            ...found,
                            reporter_name: found.reporter_name || (found.is_mine ? @js((auth()->user()?->name ?? 'Civitas UMB').' (Saya)') : 'Civitas UMB'),
                            reporter_meta: found.reporter_meta || 'Civitas Terverifikasi • NIM 4152***'
                        };
                    }
                }
            }
        } catch(e) {}

        // Tidak ada fallback mock: data harus ada di Supabase.
        return null;
    }

    // Isi modal detail dari data laporan asli (Supabase).
// Ambil laporan dari API lalu isi modal; kalau gagal, buka halaman detailnya.
// Tombol utama di modal detail desktop → klaim sungguhan (PR-04).
    function handleModalPrimaryAction() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') : null;
        if (!id) return;

        if (window.FindUMB && typeof window.FindUMB.claimReport === 'function') {
            window.FindUMB.claimReport(id);
            return;
        }

        // Fallback: buka halaman detail penuh (di sana tombolnya sudah benar).
        window.location.href = '/report/' + String(id).replace('featured-', '');
    }

    function openDesktopDetailModal(id) {
        fetch(`{{ url('/api/reports') }}/${id}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(r))
            .then(res => fillDesktopDetailModal(res.data))
            .catch(() => { window.location.href = `{{ url('/report') }}/${id}`; });
    }

    function fillDesktopDetailModal(report) {
        if (!report) return;

        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;

        // Title
        const titleEl = document.getElementById('modal-detail-title');
        if (titleEl) titleEl.textContent = report.title || 'Detail Laporan';

        // Hero Image
        const imgEl = document.getElementById('modal-detail-hero-img');
        if (imgEl) {
            imgEl.src = report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=800&auto=format&fit=crop&q=80';
            imgEl.alt = report.title || 'Foto Barang';
        }

        // Status Badge
        const badgeEl = document.getElementById('modal-detail-status-badge');
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

        // Reporter & Date
        const repNameEl = document.getElementById('modal-detail-reporter-name');
        if (repNameEl) repNameEl.textContent = report.reporter_name || 'Civitas UMB';

        const postDateEl = document.getElementById('modal-detail-post-date');
        if (postDateEl) postDateEl.textContent = report.date_display || report.date || 'Baru Saja';

        // Location
        const locEl = document.getElementById('modal-detail-location');
        if (locEl) locEl.textContent = report.location_full || report.location_campus || 'Kampus Meruya';

        // Date Metric
        const dateEl = document.getElementById('modal-detail-date');
        const labelDateEl = document.getElementById('modal-label-prop-date');
        if (dateEl) dateEl.textContent = report.date_display || report.date || 'Hari Ini';
        if (labelDateEl) {
            labelDateEl.textContent = report.type === 'found' ? 'Tanggal Temuan:' : 'Tanggal Hilang:';
        }

        // Time Metric
        const timeEl = document.getElementById('modal-detail-time');
        if (timeEl) {
            if (report.time) {
                timeEl.textContent = report.time.replace(/^waktu\s*:\s*/i, '').trim();
            } else {
                timeEl.textContent = 'Tidak ditentukan';
            }
        }

        // Category & Brand
        const catEl = document.getElementById('modal-detail-category');
        if (catEl) catEl.textContent = report.category_label || report.category || 'Lainnya';

        const brandEl = document.getElementById('modal-detail-brand');
        if (brandEl) brandEl.textContent = report.brand || 'Sesuai Ciri Fisik';

        // Storage status
        const storageEl = document.getElementById('modal-detail-storage');
        const storageRow = document.getElementById('modal-row-prop-storage');
        if (storageEl && storageRow) {
            if (report.storage) {
                storageEl.textContent = report.storage;
                storageRow.style.display = 'flex';
            } else if (report.type === 'found') {
                storageEl.textContent = 'Disimpan Sendiri';
                storageRow.style.display = 'flex';
            } else {
                storageRow.style.display = 'none';
            }
        }

        // Description & features
        const descEl = document.getElementById('modal-detail-features');
        if (descEl) {
            descEl.textContent = report.features || 'Tidak ada catatan tambahan mengenai ciri khusus atau deskripsi fisik barang.';
        }

        // Primary Action Button
        const primaryBtn = document.getElementById('modal-detail-primary-btn');
        const btnLabel = document.getElementById('modal-detail-btn-label');
        const btnIcon = document.getElementById('modal-detail-btn-icon');
        const fallbackId = String(report.id).replace('featured-', '');

        if (primaryBtn && btnLabel) {
            if (report.status === 'resolved' || report.status === 'completed') {
                btnLabel.textContent = 'Laporan Selesai';
                if (btnIcon) btnIcon.setAttribute('data-lucide', 'check-circle-2');
                primaryBtn.href = '#';
                primaryBtn.style.background = '#64748b';
                primaryBtn.style.pointerEvents = 'none';
            } else if (report.is_mine || report.id === '1' || report.id === '5') {
                btnLabel.textContent = 'Edit Laporan';
                if (btnIcon) btnIcon.setAttribute('data-lucide', 'edit-3');
                primaryBtn.href = `{{ url('/report') }}/${encodeURIComponent(fallbackId)}`;
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            } else if (report.type === 'found') {
                btnLabel.textContent = 'Ajukan Klaim Barang Ini';
                if (btnIcon) btnIcon.setAttribute('data-lucide', 'shield-alert');
                primaryBtn.href = `{{ route('messages') }}`;
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            } else {
                btnLabel.textContent = 'Saya Menemukan Barang Ini';
                if (btnIcon) btnIcon.setAttribute('data-lucide', 'hand');
                primaryBtn.href = `{{ route('messages') }}`;
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            }
        }

        // External Link to Full Report Page
        const fullPageBtn = document.getElementById('modal-detail-open-full');
        if (fullPageBtn) {
            fullPageBtn.href = `{{ url('/report') }}/${encodeURIComponent(fallbackId)}`;
        }

        // Set active id for bookmarks & shares
        modal.setAttribute('data-active-id', report.id || id);
        syncDetailModalBookmarkState(report.id || id);

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeDesktopDetailModal() {
        const modal = document.getElementById('modal-desktop-detail');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    function closeDesktopDetailModalOnBackdrop(e) {
        if (e.target.id === 'modal-desktop-detail') {
            closeDesktopDetailModal();
        }
    }

    function handleDetailModalBookmark() {
        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;
        const id = modal.getAttribute('data-active-id') || '1';

        let bookmarks = SERVER_BOOKMARKS.map(String);

        const index = bookmarks.indexOf(String(id));
        if (index === -1) {
            bookmarks.push(String(id));
            showToast('Laporan disimpan ke daftar tersimpan.');
        } else {
            bookmarks.splice(index, 1);
            showToast('Laporan dihapus dari daftar tersimpan.');
        }

        SERVER_BOOKMARKS = bookmarks;
        if (IS_AUTHENTICATED) {
            fetch(`{{ url('/api/bookmarks') }}/${id}/toggle`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.FindUMB_CSRF || '', 'Accept': 'application/json' },
            }).catch(() => {});
        }

        syncDetailModalBookmarkState(id);
    }

    function syncDetailModalBookmarkState(id) {
        const isBookmarked = SERVER_BOOKMARKS.map(String).includes(String(id));

        const topBtn = document.getElementById('modal-detail-top-bookmark');
        const footerBtn = document.getElementById('modal-detail-footer-bookmark');

        if (topBtn) topBtn.classList.toggle('bookmarked', isBookmarked);
        if (footerBtn) footerBtn.classList.toggle('bookmarked', isBookmarked);
    }

    function handleDetailModalShare() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') || '1' : '1';
        const titleEl = document.getElementById('modal-detail-title');
        const title = titleEl ? titleEl.textContent : 'Laporan';
        const fallbackId = String(id).replace('featured-', '');
        const reportUrl = `${window.location.origin}/report/${encodeURIComponent(fallbackId)}`;

        if (navigator.share) {
            navigator.share({
                title: title + ' — Find UMB',
                text: 'Periksa laporan ' + title + ' di Find UMB (Campus Lost & Found).',
                url: reportUrl,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(reportUrl).then(() => {
                showToast('Tautan laporan disalin ke clipboard!');
            }).catch(() => {
                showToast('Tautan siap dibagikan.');
            });
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (lightbox && lightbox.classList.contains('active')) {
                return; // Lightbox will be closed by global handler
            }
            closeDesktopDetailModal();
        }
    });

    function handleCardClick(event, id, url) {
        const list = getStoredNotifications();
        const item = list.find(n => String(n.id) === String(id));
        if (item && !item.read) {
            item.read = true;
            saveNotifications(list);
            syncNotificationRead(item.id);
        }

        // Check if URL points to a specific report (e.g. /report/1)
        const reportMatch = url ? url.match(/\/report\/([a-zA-Z0-9_\-]+)/) : null;
        if (reportMatch && window.innerWidth >= 1024) {
            const reportId = reportMatch[1];
            openDesktopDetailModal(reportId);
            renderNotifications();
            return;
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
        if (updatedCount > 0) syncAllNotificationsRead();
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
