@extends('layouts.app')

@section('title', 'Find UMB — Layanan Pelaporan & Pencarian Barang Kampus')

@push('styles')
<style>
    /* Page Container */
    .home-container {
        padding: 0.5rem 1.25rem 1.15rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Hero Banner Card - Uniform Proportional Scaling (Identik dengan Gambar 2) */
    .hero-banner {
        container-type: inline-size;
        container-name: hero;
        background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
        border-radius: clamp(18px, 5cqw, 22px);
        padding: clamp(1.1rem, 3.6cqw, 1.45rem) clamp(1.1rem, 3.5cqw, 1.4rem);
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: clamp(140px, 39cqw, 170px);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 65%;
    }

    .hero-title {
        font-family: var(--font-heading);
        font-size: clamp(0.95rem, 4cqw, 1.18rem);
        font-weight: 600; /* Ketebalan font elegan, tidak terlalu tebal */
        line-height: 1.22;
        letter-spacing: -0.015em;
        margin-bottom: clamp(0.38rem, 1.5cqw, 0.52rem); /* Jarak ke subtext disesuaikan */
        color: #ffffff;
    }

    .hero-title-line {
        display: block;
        white-space: nowrap;
    }

    .hero-subtitle {
        font-family: var(--font-main);
        font-size: clamp(0.67rem, 2.6cqw, 0.775rem);
        font-weight: 400;
        line-height: 1.38;
        color: rgba(255, 255, 255, 0.94);
        margin-bottom: clamp(0.68rem, 2.6cqw, 0.88rem); /* Jarak ke button seimbang persis */
    }

    .hero-sub-line {
        display: block;
        white-space: nowrap;
    }

    /* Button Laporkan Barang - Pill Shape persis seperti Gambar 2 */
    .btn-report-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #0c559c;
        font-family: var(--font-main);
        font-weight: 700;
        font-size: clamp(0.74rem, 2.8cqw, 0.825rem);
        padding: clamp(0.44rem, 1.6cqw, 0.54rem) clamp(0.95rem, 3.4cqw, 1.2rem);
        border-radius: 9999px; /* Smooth Pill Shape persis referensi */
        text-decoration: none;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        transition: var(--transition);
        width: fit-content;
    }

    .btn-report-pill:hover, .btn-report-pill:active {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    /* FindUMB Campus Mascot Graphic - Posisi pas seimbang antara teks dan tepi kanan */
    .hero-mascot-wrap {
        position: absolute;
        right: clamp(-12px, -1.8cqw, -2px);
        bottom: clamp(-58px, -13.5cqw, -44px); /* Diturunkan sedikit agar memiliki ruang lega dari batas atas */
        width: clamp(180px, 51cqw, 240px);
        height: clamp(195px, 55cqw, 255px);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        pointer-events: none;
        z-index: 1;
    }

    .hero-mascot-glow {
        position: absolute;
        width: clamp(135px, 40cqw, 190px);
        height: clamp(135px, 40cqw, 190px);
        right: clamp(4px, 1.5cqw, 16px);
        bottom: clamp(2px, 1.2cqw, 8px);
        background: radial-gradient(circle, rgba(255, 255, 255, 0.24) 0%, rgba(255, 255, 255, 0) 72%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-mascot-img {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: bottom center;
        filter: drop-shadow(0 8px 16px rgba(10, 45, 90, 0.28));
        user-select: none;
        -webkit-user-drag: none;
    }

    /* Search Bar Component - No blue shadow */
    .search-box-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--bg-input);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
        transition: var(--transition);
    }

    .search-box-wrap:focus-within {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.05); /* Clean neutral ring */
    }

    .search-icon-left {
        color: var(--text-muted);
        display: flex;
        align-items: center;
    }

    .search-icon-left svg, .search-icon-left .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }

    .search-input {
        flex: 1;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: var(--text-main);
        outline: none;
    }

    .search-input::placeholder {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .search-calendar-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: #f0f7ff;
        border: 1px solid rgba(21, 114, 184, 0.16);
        border-radius: 10px;
        color: var(--primary);
        flex-shrink: 0;
        transition: var(--transition);
        cursor: pointer;
        padding: 0;
    }

    .search-calendar-btn svg, .search-calendar-btn .lucide {
        width: 17px;
        height: 17px;
        stroke-width: 2.1;
        color: var(--primary);
        stroke: var(--primary);
        transition: var(--transition);
    }

    .search-box-wrap:hover .search-calendar-btn {
        background: #e0f0fe;
        border-color: rgba(21, 114, 184, 0.28);
        color: var(--primary-dark, #0d5a94);
    }

    .search-box-wrap:hover .search-calendar-btn svg,
    .search-box-wrap:hover .search-calendar-btn .lucide {
        color: var(--primary-dark, #0d5a94);
        stroke: var(--primary-dark, #0d5a94);
    }

    /* Section Headings */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.25rem;
    }

    .section-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.01em;
    }

    .section-link-all {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        transition: var(--transition);
    }

    .section-link-all:hover {
        text-decoration: underline;
    }

    /* Featured Reports (Horizontal Carousel) - Stroke tipis, tanpa hover effect, tanpa shadow */
    .featured-carousel-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .featured-track {
        display: flex;
        gap: 0.55rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 0.35rem;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .featured-track::-webkit-scrollbar {
        display: none;
    }

    /* Featured Reports (Horizontal Carousel) - 2.3 card per layar agar lebih lega dan tidak terhimpit */
    .featured-card {
        flex: 0 0 clamp(134px, 39vw, 150px);
        min-width: 130px;
        scroll-snap-align: start;
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.055); /* Stroke tipis lembut */
        border-radius: 14px; /* Sudut membulat proporsional */
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .featured-thumb-box {
        width: 100%;
        height: 90px; /* Proporsi gambar pas dan jelas */
        background: #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .featured-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-body {
        padding: 0.55rem 0.65rem 0.65rem 0.65rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .featured-item-title {
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    /* Subtitle Meta persis referensi: "Lost · Kabul" / "Hilang · Lokasi" */
    .featured-meta {
        display: flex;
        align-items: center;
        gap: 0.2rem;
        font-family: var(--font-main);
        font-size: 0.675rem;
        line-height: 1.2;
        min-width: 0;
        overflow: hidden;
    }

    .meta-status-lost {
        color: #f43f5e; /* Soft red / coral persis seperti teks "Lost" pada referensi */
        font-weight: 600;
        flex-shrink: 0;
    }

    .meta-status-found {
        color: #10b981; /* Emerald green */
        font-weight: 600;
        flex-shrink: 0;
    }

    .meta-dot {
        color: #94a3b8;
        font-size: 0.75rem;
        margin: 0;
        flex-shrink: 0;
    }

    .meta-location {
        color: #64748b;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
    }

    /* Carousel Pagination Dots */
    .carousel-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        margin-top: 0.25rem;
    }

    .dot {
        width: 6px;
        height: 6px;
        border-radius: var(--radius-pill);
        background: #cbd5e1;
        transition: width 0.28s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.28s ease;
        cursor: pointer;
        position: relative;
    }

    .dot::after {
        content: '';
        position: absolute;
        top: -8px;
        bottom: -8px;
        left: -4px;
        right: -4px;
    }

    .dot.active {
        width: 18px;
        background: var(--primary);
    }

    /* Recent Reports (Vertical List - tanpa stroke, gap lebih rapat) */
    .recent-list-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .reports-vertical-container {
        display: flex;
        flex-direction: column;
        gap: 0.65rem; /* Jarak antar card dikurangi sedikit lebih rapat */
    }

    .report-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.055); /* Stroke ultra-tipis lembut, tidak mencolok/tebal */
        border-radius: var(--radius-md);
        padding: 0.65rem 0.75rem; /* Padding lebih ramping dan proporsional */
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    /* Tanpa hover effect pada tiap card bagian Laporan Terbaru */

    .report-thumb {
        width: 70px; /* Sedikit diperkecil dari 78px agar jarak vertikal badge & icon lebih proporsional */
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
        align-self: flex-start;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .report-info {
        flex: 1;
        min-width: 0; /* Enable text ellipsis */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 72px;
        height: auto;
        gap: 0.25rem;
        padding: 0.05rem 0;
    }

    .report-title {
        font-family: var(--font-main);
        font-size: 0.925rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    .report-location {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 400;
        color: #64748b;
        min-width: 0;
        width: 100%;
        line-height: 1.35;
    }

    .report-location span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
        line-height: 1.35;
    }

    .report-location svg, .report-location .lucide {
        width: 13.5px;
        height: 13.5px;
        color: #2563eb;
        flex-shrink: 0;
        stroke-width: 2.2;
    }

    .report-date {
        font-family: var(--font-main);
        font-size: 0.75rem;
        font-weight: 400;
        color: #64748b;
    }

    .report-aside {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        min-height: 72px;
        height: auto;
        align-self: stretch;
        flex-shrink: 0;
        padding: 0.05rem 0;
    }

    /* Badges - Corner radius 8px seimbang dengan card utama (14px) */
    .badge-status {
        padding: 0.22rem 0.65rem;
        border-radius: 8px; /* Dikurangi dari pill 9999px agar seimbang dengan radius card utama */
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

    /* Card Action Icons (Share & Bookmark) - Persis posisi bawah kanan referensi */
    .card-actions-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-action-icon {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: var(--transition);
    }

    .btn-action-icon svg, .btn-action-icon .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 1.8;
        transition: var(--transition);
    }

    .btn-action-icon:hover {
        color: var(--primary);
    }

    .btn-action-icon.bookmarked svg, .btn-action-icon.bookmarked .lucide {
        fill: var(--primary);
        stroke: var(--primary);
    }

    .badge-resolved {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Card Options Dropdown (Titik 3 untuk laporan sendiri) */
    .card-options-wrap {
        position: relative;
    }

    .card-options-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 6px);
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
        min-width: 150px;
        z-index: 60;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: fadeInMenu 0.16s ease;
    }

    @keyframes fadeInMenu {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-options-menu.active {
        display: flex;
    }

    .card-option-item {
        padding: 0.65rem 0.85rem;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        text-align: left;
        transition: var(--transition);
        width: 100%;
    }

    .card-option-item:hover {
        background: #f8fafc;
        color: var(--primary);
    }

    .card-option-item svg, .card-option-item .lucide {
        width: 15px;
        height: 15px;
        stroke-width: 2.1;
    }

    .card-option-item.danger {
        color: #ef4444;
        border-top: 1px solid #f1f5f9;
    }

    .card-option-item.danger:hover {
        background: #fef2f2;
        color: #dc2626;
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
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
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
    }

    .modal-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
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
    }

    .modal-input, .modal-select, .modal-textarea {
        background: #f8fafc;
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
        padding: 0.6rem 0.85rem;
        font-family: var(--font-main);
        font-size: 0.825rem;
        color: #0f172a;
        outline: none;
        transition: var(--transition);
        width: 100%;
    }

    .modal-input:focus, .modal-select:focus, .modal-textarea:focus {
        border-color: var(--primary);
        background: #ffffff;
    }

    .modal-textarea {
        resize: vertical;
        min-height: 75px;
    }

    .modal-actions-row {
        display: flex;
        gap: 0.65rem;
        margin-top: 0.5rem;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 0.7rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-cancel:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .btn-modal-submit {
        flex: 1.4;
        padding: 0.7rem;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.25);
    }

    .btn-modal-submit:hover {
        background: var(--primary-dark);
    }

    /* Bottom 'View all' link */
    .bottom-view-all-wrap {
        display: flex;
        justify-content: center;
        margin-top: 0.85rem;
        margin-bottom: 0.35rem;
        padding-bottom: 0;
    }

    .bottom-view-all-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--primary-dark);
        text-decoration: none;
        padding: 0.48rem 1.4rem;
        border-radius: var(--radius-pill);
        background: var(--primary-light);
        border: none;
        transition: var(--transition);
    }

    .bottom-view-all-btn:hover {
        background: #dbeafe;
        color: var(--primary);
        transform: translateY(-1px);
    }

    .bottom-view-all-btn:active {
        transform: scale(0.97);
    }

    /* Toast Notification */
    .toast-popup {
        position: fixed;
        bottom: 85px;
        left: 50%;
        transform: translateX(-50%) translateY(30px);
        background: #0f172a;
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: var(--radius-pill);
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 100;
    }

    .toast-popup.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<div class="home-container">
    <!-- Hero Blue Gradient Banner -->
    <section class="hero-banner" id="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="hero-title-line">Pusat Laporan Barang</span>
                <span class="hero-title-line">Hilang &amp; Ditemukan</span>
            </h1>
            <p class="hero-subtitle">
                <span class="hero-sub-line">Layanan terpadu pelaporan dan</span>
                <span class="hero-sub-line">pencarian barang civitas UMB.</span>
            </p>
            <!-- Button Laporkan Barang - Radius seimbang dengan radius card utama, tanpa arrow icon -->
            <button type="button" class="btn-report-pill" onclick="openReportModal()" id="btn-hero-report">
                Laporkan Barang
            </button>
        </div>

        <!-- FindUMB Campus Mascot Graphic -->
        <div class="hero-mascot-wrap" aria-hidden="true">
            <div class="hero-mascot-glow"></div>
            <picture>
                <source srcset="{{ asset('images/mascot.webp') }}" type="image/webp">
                <img src="{{ asset('images/mascot.png') }}" alt="Maskot FindUMB" class="hero-mascot-img" width="160" height="160" loading="eager">
            </picture>
        </div>
    </section>

    <!-- Search Input Bar with Lucide Icons -->
    <a href="{{ route('search') }}" class="search-box-wrap" id="search-area" style="text-decoration: none; cursor: pointer;">
        <span class="search-icon-left">
            <i data-lucide="search"></i>
        </span>
        <span class="search-input" style="color: var(--text-muted); display: flex; align-items: center; user-select: none;">Cari barang hilang atau temuan...</span>
        <span class="search-calendar-btn" id="btn-filter" aria-label="Filter Berdasarkan Bulan" title="Filter Bulan" onclick="event.preventDefault(); window.location.href='{{ url('/search?open_month=1') }}';">
            <i data-lucide="calendar"></i>
        </span>
    </a>

    <!-- Featured Reports Carousel (Bahasa Indonesia) -->
    <section class="featured-carousel-wrap">
        <div class="section-header">
            <h2 class="section-title">Laporan Pilihan</h2>
            <a href="#all-featured" class="section-link-all">Lihat semua</a>
        </div>

        <div class="featured-track" id="featured-track">
            <!-- Featured Item 1: Cincin Emas Berlian (Persis referensi Image 2: Lost Diamond...) -->
            <a href="{{ url('/report/5') }}" class="featured-card">
                <div class="featured-thumb-box">
                    <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=360&auto=format&fit=crop&q=80" alt="Cincin Emas" class="featured-thumb-img" loading="lazy">
                </div>
                <div class="featured-body">
                    <h3 class="featured-item-title">Cincin Emas Berlian</h3>
                    <div class="featured-meta">
                        <span class="meta-status-lost">Hilang</span>
                        <span class="meta-dot">·</span>
                        <span class="meta-location">Parkir Barat</span>
                    </div>
                </div>
            </a>

            <!-- Featured Item 2: Tablet Samsung -->
            <a href="{{ url('/report/6') }}" class="featured-card">
                <div class="featured-thumb-box">
                    <img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=360&auto=format&fit=crop&q=80" alt="Tablet" class="featured-thumb-img" loading="lazy">
                </div>
                <div class="featured-body">
                    <h3 class="featured-item-title">Tablet Samsung Tab</h3>
                    <div class="featured-meta">
                        <span class="meta-status-lost">Hilang</span>
                        <span class="meta-dot">·</span>
                        <span class="meta-location">Gedung E</span>
                    </div>
                </div>
            </a>

            <!-- Featured Item 3: Samsung Phone -->
            <a href="{{ url('/report/1') }}" class="featured-card">
                <div class="featured-thumb-box">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=360&auto=format&fit=crop&q=80" alt="Samsung Galaxy" class="featured-thumb-img" loading="lazy">
                </div>
                <div class="featured-body">
                    <h3 class="featured-item-title">Samsung Galaxy A13</h3>
                    <div class="featured-meta">
                        <span class="meta-status-lost">Hilang</span>
                        <span class="meta-dot">·</span>
                        <span class="meta-location">Meruya</span>
                    </div>
                </div>
            </a>

            <!-- Featured Item 4: KTM Mahasiswa -->
            <a href="{{ url('/report/2') }}" class="featured-card">
                <div class="featured-thumb-box">
                    <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=360&auto=format&fit=crop&q=80" alt="KTM Mahasiswa" class="featured-thumb-img" loading="lazy">
                </div>
                <div class="featured-body">
                    <h3 class="featured-item-title">KTM Mahasiswa UMB</h3>
                    <div class="featured-meta">
                        <span class="meta-status-lost">Hilang</span>
                        <span class="meta-dot">·</span>
                        <span class="meta-location">Perpustakaan</span>
                    </div>
                </div>
            </a>

            <!-- Featured Item 5: Smartwatch Apple (Ditemukan) -->
            <a href="{{ url('/report/4') }}" class="featured-card">
                <div class="featured-thumb-box">
                    <img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=360&auto=format&fit=crop&q=80" alt="Smart Watch" class="featured-thumb-img" loading="lazy">
                </div>
                <div class="featured-body">
                    <h3 class="featured-item-title">Smartwatch Apple...</h3>
                    <div class="featured-meta">
                        <span class="meta-status-found">Ditemukan</span>
                        <span class="meta-dot">·</span>
                        <span class="meta-location">Tower UMB</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carousel Pagination Dots -->
        <div class="carousel-dots" id="carousel-dots" aria-label="Indikator slide laporan pilihan">
            <span class="dot active" data-index="0" role="button" aria-label="Slide 1"></span>
            <span class="dot" data-index="1" role="button" aria-label="Slide 2"></span>
            <span class="dot" data-index="2" role="button" aria-label="Slide 3"></span>
            <span class="dot" data-index="3" role="button" aria-label="Slide 4"></span>
            <span class="dot" data-index="4" role="button" aria-label="Slide 5"></span>
        </div>
    </section>

    <!-- Recent Reports Feed (Dengan margin gap, stroke tipis, tanpa shadow, tanpa hover effect, Lucide icons) -->
    <section class="recent-list-wrap">
        <div class="section-header">
            <h2 class="section-title">Laporan Terbaru</h2>
            <a href="#all-recent" class="section-link-all">Lihat semua</a>
        </div>

        <div class="reports-vertical-container" id="reports-container">
            <!-- 1. Samsung SM-A135F (Hilang) -->
            <article class="report-card" data-id="1" data-title="Samsung SM-A135F">
                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=160&auto=format&fit=crop&q=80" alt="Samsung SM-A135F" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Samsung SM-A135F</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Gedung Tower Lt. 7</span>
                    </div>
                    <span class="report-date">20 Jun 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Samsung SM-A135F')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 2. Paspor & Berkas Beasiswa (Hilang) -->
            <article class="report-card" data-id="2" data-title="Paspor & Berkas Beasiswa">
                <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=160&auto=format&fit=crop&q=80" alt="Paspor dan Dokumen" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Paspor &amp; Berkas Beasiswa</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Gedung E R. 204</span>
                    </div>
                    <span class="report-date">12 Mei 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Paspor & Berkas')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 3. Tasbih & Gelang Manik Merah (Ditemukan) -->
            <article class="report-card" data-id="3" data-title="Tasbih & Gelang Manik Merah">
                <img src="https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=160&auto=format&fit=crop&q=80" alt="Tasbih Manik Merah" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Tasbih &amp; Gelang Manik Merah</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Masjid Manarul 'Amal UMB</span>
                    </div>
                    <span class="report-date">15 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-found">Temuan</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tasbih Manik')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 4. Kunci Motor Honda & Remote (Ditemukan) -->
            <article class="report-card" data-id="4" data-title="Kunci Motor Honda & Remote">
                <img src="https://images.unsplash.com/photo-1582139329536-e7284fece509?w=160&auto=format&fit=crop&q=80" alt="Kunci Motor Honda" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Kunci Motor Honda &amp; Remote</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Parkiran Sepeda Motor</span>
                    </div>
                    <span class="report-date">10 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-found">Temuan</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Kunci Motor Honda')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 5. Cincin Emas Berlian (Hilang) -->
            <article class="report-card" data-id="5" data-title="Cincin Emas Berlian">
                <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=160&auto=format&fit=crop&q=80" alt="Cincin Emas" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Cincin Emas Berlian</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Parkir Barat Kampus Meruya</span>
                    </div>
                    <span class="report-date">15 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Cincin Emas')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 6. Tablet Samsung Tab S8 (Hilang) -->
            <article class="report-card" data-id="6" data-title="Tablet Samsung Tab S8">
                <img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=160&auto=format&fit=crop&q=80" alt="Tablet Samsung" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Tablet Samsung Tab S8</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Gedung E Lt. 3</span>
                    </div>
                    <span class="report-date">14 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tablet Samsung')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 7. Tas Laptop Lenovo ThinkPad (Hilang) -->
            <article class="report-card" data-id="7" data-title="Tas Laptop Lenovo ThinkPad">
                <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=160&auto=format&fit=crop&q=80" alt="Tas Laptop" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Tas Laptop Lenovo ThinkPad</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Lab Komputer Gedung Tower</span>
                    </div>
                    <span class="report-date">18 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tas Laptop Lenovo')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 8. Dompet Kulit & KTM Mahasiswa (Hilang) -->
            <article class="report-card" data-id="8" data-title="Dompet Kulit & KTM Mahasiswa">
                <img src="https://images.unsplash.com/photo-1627123424574-724758594e93?w=160&auto=format&fit=crop&q=80" alt="Dompet Kulit" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Dompet Kulit &amp; KTM Mahasiswa</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Kantin Gedung D</span>
                    </div>
                    <span class="report-date">08 Feb 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-lost">Hilang</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Dompet Kulit & KTM')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 9. Jaket Almamater Biru UMB (Ditemukan) -->
            <article class="report-card" data-id="9" data-title="Jaket Almamater Biru UMB">
                <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=160&auto=format&fit=crop&q=80" alt="Jaket Almamater" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Jaket Almamater Biru UMB</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Perpustakaan Pusat Lt. 2</span>
                    </div>
                    <span class="report-date">22 Jan 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-found">Temuan</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Jaket Almamater')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- 10. Earphone TWS Anker Soundcore (Ditemukan) -->
            <article class="report-card" data-id="10" data-title="Earphone TWS Anker Soundcore">
                <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=160&auto=format&fit=crop&q=80" alt="Earphone TWS" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">Earphone TWS Anker Soundcore</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>Gazebo Taman Kampus</span>
                    </div>
                    <span class="report-date">02 Feb 2026</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status badge-found">Temuan</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Earphone TWS Anker')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Tombol Lihat Semua Laporan di bagian bawah -->
        <div class="bottom-view-all-wrap">
            <a href="#all-recent" class="bottom-view-all-btn">Lihat Semua Laporan</a>
        </div>
    </section>
</div>

<!-- EDIT REPORT MODAL (CRUD: Update) -->
<div class="modal-backdrop" id="modal-edit-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-header-row">
            <h2 class="modal-title">Edit Laporan Barang</h2>
            <button type="button" class="btn-modal-icon-close" onclick="closeHomeEditModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="form-edit-report" onsubmit="handleSaveHomeEdit(event)">
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
                    <button type="button" class="btn-modal-cancel" onclick="closeHomeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
                </div>
            </div>
        </form>
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
            <button type="button" class="btn-modal-cancel" onclick="closeHomeEditStatusConfirmModal()">Batal</button>
            <button type="button" class="btn-modal-submit btn-modal-success" onclick="executeHomeSaveEditWithStatusConfirm()">Ya, Selesaikan</button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-backdrop" id="modal-delete-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="trash-2" style="width: 26px; height: 26px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Hapus Laporan?</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <input type="hidden" id="delete-report-id">
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeHomeDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-danger" onclick="executeHomeDelete()">Hapus</button>
        </div>
    </div>
</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite">
    Pesan notifikasi
</div>
@endsection

@push('scripts')
<script>
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

    // Toast notification handler
    function showToast(message) {
        const toast = document.getElementById('toast-popup');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }

    // Toggle Bookmark button
    function toggleBookmark(btn) {
        btn.classList.toggle('bookmarked');
        if (btn.classList.contains('bookmarked')) {
            showToast('Laporan disimpan ke daftar tersimpan.');
        } else {
            showToast('Laporan dihapus dari daftar tersimpan.');
        }
    }

    // Handle Share button
    function handleShare(title) {
        if (navigator.share) {
            navigator.share({
                title: title + ' — Find UMB',
                text: 'Periksa laporan ' + title + ' di Find UMB (Campus Lost & Found Universitas Mercu Buana).',
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showToast('Tautan laporan disalin ke clipboard!');
            }).catch(() => {
                showToast('Tautan siap dibagikan.');
            });
        }
    }

    // Live Search filter
    function handleSearch(query) {
        const filter = query.toLowerCase().trim();
        const cards = document.querySelectorAll('#reports-container .report-card');
        let matchedCount = 0;

        cards.forEach(card => {
            const title = (card.getAttribute('data-title') || '').toLowerCase();
            const location = (card.querySelector('.report-location span')?.textContent || '').toLowerCase();
            if (title.includes(filter) || location.includes(filter)) {
                card.style.display = 'flex';
                matchedCount++;
            } else {
                card.style.display = 'none';
            }
        });
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

    function escapeJs(str) {
        if (!str) return '';
        return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    function toggleCardOptions(event, id) {
        event.stopPropagation();
        const menu = document.getElementById(`options-menu-${id}`);
        const isAlreadyActive = menu && menu.classList.contains('active');
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        if (!isAlreadyActive && menu) {
            menu.classList.add('active');
        }
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
    });

    // Home Edit Modal
    function openHomeEditModal(id) {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        const reports = getUserReports();
        const item = reports.find(r => String(r.id) === String(id));
        if (!item) return;

        document.getElementById('edit-report-id').value = item.id;
        document.getElementById('edit-title').value = item.title || '';
        document.getElementById('edit-status').value = item.status === 'resolved' ? 'resolved' : (item.type || 'lost');
        document.getElementById('edit-category').value = item.category || 'Elektronik & HP';
        document.getElementById('edit-brand').value = item.brand || '';
        document.getElementById('edit-location').value = item.location_full || item.location_campus || '';
        document.getElementById('edit-date').value = item.date || new Date().toISOString().split('T')[0];
        document.getElementById('edit-features').value = item.features || '';

        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeHomeEditModal() {
        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function handleSaveHomeEdit(event) {
        event.preventDefault();
        const id = document.getElementById('edit-report-id').value;
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;

        // Validasi in-app modal jika mengubah status menjadi Terselesaikan
        if (statusVal === 'resolved' && reports[index].status !== 'resolved') {
            openHomeEditStatusConfirmModal();
            return;
        }

        executeHomeSaveEditCommit();
    }

    function openHomeEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeHomeEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeHomeSaveEditWithStatusConfirm() {
        closeHomeEditStatusConfirmModal();
        executeHomeSaveEditCommit();
    }

    function executeHomeSaveEditCommit() {
        const id = document.getElementById('edit-report-id').value;
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
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

        closeHomeEditModal();
        renderCustomReports();
        showToast('✓ Laporan berhasil diperbarui!');
    }

    function confirmDeleteHomeReport(id) {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        const idInput = document.getElementById('delete-report-id');
        if (idInput) idInput.value = id;
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeHomeDeleteModal() {
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeHomeDelete() {
        const idInput = document.getElementById('delete-report-id');
        const id = idInput ? idInput.value : null;
        if (!id) return;

        let reports = getUserReports();
        reports = reports.filter(r => String(r.id) !== String(id));
        try {
            localStorage.setItem('findumb_custom_reports', JSON.stringify(reports));
        } catch(e) {}

        // Remove card from DOM
        const card = document.querySelector(`.report-card[data-id="${id}"]`);
        if (card) card.remove();

        closeHomeDeleteModal();
        showToast('Laporan berhasil dihapus.');
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

    // Render user-created reports from localStorage & sync owner options
    function renderCustomReports() {
        const userReports = getUserReports();
        const container = document.getElementById('reports-container');
        if (!container) return;

        // 1. Sync existing static cards with owner logic
        document.querySelectorAll('#reports-container .report-card').forEach(card => {
            const id = card.getAttribute('data-id');
            const userItem = userReports.find(r => String(r.id) === String(id));

            // Jika item awalnya adalah laporan user (misal id 1 atau 5) tapi sekarang sudah dihapus dari userReports
            if ((id === '1' || id === '5') && !userItem) {
                card.remove();
                return;
            }

            if (userItem) {
                // Update title & status if modified
                const titleEl = card.querySelector('.report-title');
                if (titleEl) titleEl.textContent = userItem.title;
                const locEl = card.querySelector('.report-location span');
                if (locEl) locEl.textContent = userItem.location_full || userItem.location_campus;
                const dateEl = card.querySelector('.report-date');
                if (dateEl) dateEl.textContent = userItem.date_display || userItem.date;

                const badge = card.querySelector('.badge-status');
                if (badge) {
                    if (userItem.status === 'resolved' || userItem.status === 'completed') {
                        badge.className = 'badge-status badge-resolved';
                        badge.textContent = 'Selesai';
                    } else if (userItem.type === 'found') {
                        badge.className = 'badge-status badge-found';
                        badge.textContent = 'Temuan';
                    } else {
                        badge.className = 'badge-status badge-lost';
                        badge.textContent = 'Hilang';
                    }
                }

                // Ganti tombol bookmark dengan tombol option (titik 3)
                const actionsRow = card.querySelector('.card-actions-row');
                if (actionsRow && !actionsRow.querySelector('.card-options-wrap')) {
                    const bookmarkBtn = actionsRow.querySelector('button[aria-label="Simpan"]');
                    if (bookmarkBtn) bookmarkBtn.remove();

                    const optionsWrap = document.createElement('div');
                    optionsWrap.className = 'card-options-wrap';
                    optionsWrap.innerHTML = `
                        <button type="button" class="btn-action-icon btn-card-options" aria-label="Opsi Laporan" onclick="toggleCardOptions(event, '${id}')">
                            <i data-lucide="more-vertical"></i>
                        </button>
                        <div class="card-options-menu" id="options-menu-${id}">
                            <button type="button" class="card-option-item" onclick="openHomeEditModal('${id}')">
                                <i data-lucide="edit-3"></i>
                                <span>Edit Laporan</span>
                            </button>
                            <button type="button" class="card-option-item danger" onclick="confirmDeleteHomeReport('${id}')">
                                <i data-lucide="trash-2"></i>
                                <span>Hapus Laporan</span>
                            </button>
                        </div>
                    `;
                    actionsRow.appendChild(optionsWrap);
                }
            }
        });

        // 2. Render newly added custom reports (e.g. rep_...) that are not in the static DOM
        const newReports = userReports.filter(r => !document.querySelector(`.report-card[data-id="${r.id}"]`));
        const reportsToRender = [...newReports].reverse();

        reportsToRender.forEach(report => {
            let badgeClass = 'badge-lost';
            let badgeLabel = 'Hilang';
            if (report.status === 'resolved' || report.status === 'completed') {
                badgeClass = 'badge-resolved';
                badgeLabel = 'Selesai';
            } else if (report.type === 'found') {
                badgeClass = 'badge-found';
                badgeLabel = 'Temuan';
            }
            
            const card = document.createElement('article');
            card.className = 'report-card';
            card.setAttribute('data-id', report.id);
            card.setAttribute('data-title', report.title);
            card.innerHTML = `
                <img src="${report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=160&auto=format&fit=crop&q=80'}" alt="${escapeHtml(report.title)}" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">${escapeHtml(report.title)}</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>${escapeHtml(report.location_full || report.location_campus || 'Kampus Meruya')}</span>
                    </div>
                    <span class="report-date">${report.date_display || report.date || 'Baru Saja'}</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status ${badgeClass}">${badgeLabel}</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('${escapeJs(report.title)}')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <!-- Titik 3 menggantikan bookmark -->
                        <div class="card-options-wrap">
                            <button type="button" class="btn-action-icon btn-card-options" aria-label="Opsi Laporan" onclick="toggleCardOptions(event, '${report.id}')">
                                <i data-lucide="more-vertical"></i>
                            </button>
                            <div class="card-options-menu" id="options-menu-${report.id}">
                                <button type="button" class="card-option-item" onclick="openHomeEditModal('${report.id}')">
                                    <i data-lucide="edit-3"></i>
                                    <span>Edit Laporan</span>
                                </button>
                                <button type="button" class="card-option-item danger" onclick="confirmDeleteHomeReport('${report.id}')">
                                    <i data-lucide="trash-2"></i>
                                    <span>Hapus Laporan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.prepend(card);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // ----------------------------------------------------
    // Carousel Slide Indicators (Laporan Pilihan)
    // ----------------------------------------------------
    function initFeaturedCarousel() {
        const track = document.getElementById('featured-track');
        const dotsContainer = document.getElementById('carousel-dots');
        if (!track || !dotsContainer) return;

        const cards = track.querySelectorAll('.featured-card');
        const dots = dotsContainer.querySelectorAll('.dot');
        if (cards.length === 0 || dots.length === 0) return;

        function updateActiveDot() {
            const maxScrollLeft = track.scrollWidth - track.clientWidth;
            if (maxScrollLeft > 0 && track.scrollLeft >= maxScrollLeft - 10) {
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === dots.length - 1);
                });
                return;
            }

            const trackLeft = track.getBoundingClientRect().left;
            let closestIndex = 0;
            let minDiff = Infinity;

            cards.forEach((card, idx) => {
                const cardLeft = card.getBoundingClientRect().left;
                const diff = Math.abs(cardLeft - trackLeft);
                if (diff < minDiff) {
                    minDiff = diff;
                    closestIndex = idx;
                }
            });

            closestIndex = Math.max(0, Math.min(closestIndex, dots.length - 1));

            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === closestIndex);
            });
        }

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                if (cards[idx]) {
                    const scrollTarget = cards[idx].offsetLeft - cards[0].offsetLeft;
                    track.scrollTo({
                        left: scrollTarget,
                        behavior: 'smooth'
                    });
                }
            });
        });

        let isTicking = false;
        track.addEventListener('scroll', () => {
            if (!isTicking) {
                window.requestAnimationFrame(() => {
                    updateActiveDot();
                    isTicking = false;
                });
                isTicking = true;
            }
        }, { passive: true });

        window.addEventListener('resize', updateActiveDot, { passive: true });
        updateActiveDot();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCustomReports();
        initFeaturedCarousel();

        // Card Click Navigation to Detail Page
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.report-card');
            if (card && !e.target.closest('.card-actions-row') && !e.target.closest('.btn-action-icon') && !e.target.closest('.card-options-menu')) {
                const id = card.getAttribute('data-id') || '1';
                window.location.href = `{{ url('/report') }}/${encodeURIComponent(id)}`;
            }
        });
    });
</script>
@endpush
