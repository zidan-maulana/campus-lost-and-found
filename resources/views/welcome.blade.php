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

    /* Mobile vs Desktop Hero Typography */
    .hero-title-desktop,
    .hero-sub-desktop {
        display: none;
    }

    .hero-title-mobile,
    .hero-sub-mobile {
        display: block;
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

    /* Responsive Desktop Breakpoints for Home (min-width: 768px & min-width: 1024px) */
    @media (min-width: 768px) {
        .home-container {
            width: 100%;
            max-width: 100%;
            padding: 1.75rem clamp(1.5rem, 3.5vw, 3.5rem) 3.5rem clamp(1.5rem, 3.5vw, 3.5rem);
            gap: 2.25rem;
            box-sizing: border-box;
        }

        /* Hero Banner Expansion */
        .hero-banner {
            min-height: 220px;
            border-radius: 24px;
            padding: 2.25rem 2.75rem;
            width: 100%;
        }

        .hero-content {
            max-width: 68%;
            z-index: 2;
        }

        .hero-title-mobile,
        .hero-sub-mobile {
            display: none !important;
        }

        .hero-title-desktop {
            display: block !important;
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.015em;
            margin-bottom: 0.65rem;
            color: #ffffff;
        }

        .hero-sub-desktop {
            display: block !important;
            font-size: 0.95rem;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.94);
            max-width: 90%;
            margin-bottom: 1.35rem;
        }

        .btn-report-pill {
            font-size: 0.9rem;
            padding: 0.65rem 1.55rem;
        }

        .hero-mascot-wrap {
            right: clamp(1.5rem, 3.5vw, 3.5rem);
            bottom: -30px;
            width: clamp(240px, 20vw, 290px);
            height: clamp(240px, 20vw, 290px);
        }

        .hero-mascot-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Search Bar: Kurangi corner radius agar balance dengan radius icon calendar (10px) */
        .search-box-wrap {
            border-radius: 14px;
            padding: 0.65rem 1rem 0.65rem 1.25rem;
            width: 100%;
        }

        .search-input {
            font-size: 0.95rem;
        }

        .section-title {
            font-size: 1.25rem;
        }

        .section-link-all {
            font-size: 0.875rem;
        }

        /* Featured Reports Grid */
        .featured-track {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.15rem;
            width: 100%;
            min-width: 0;
            overflow-x: visible;
            padding-bottom: 0;
            scroll-snap-type: none;
        }

        .featured-card {
            flex: none;
            width: 100%;
            min-width: 0;
            border-radius: 16px;
        }

        .featured-card:hover {
            transform: none !important;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03) !important;
            border-color: rgba(15, 23, 42, 0.055) !important;
        }

        .featured-thumb-box {
            height: 120px;
        }

        .featured-item-title {
            font-size: 0.875rem;
        }

        .carousel-dots {
            display: none !important;
        }

        /* Recent Reports Grid (2 columns on tablet, robust minmax to prevent overflow bug) */
        .reports-vertical-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.15rem;
            width: 100%;
            min-width: 0;
        }

        .report-card {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
            height: 100%;
            padding: 0.85rem 1rem;
            border-radius: 16px;
        }

        .report-card:hover {
            transform: none !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
            border-color: rgba(15, 23, 42, 0.055) !important;
        }

        .report-thumb {
            width: 80px;
            height: 80px;
            border-radius: 10px;
        }

        .report-info {
            min-width: 0;
        }

        .report-title {
            font-size: 0.975rem;
            min-width: 0;
        }

        .report-location {
            min-width: 0;
        }

        .report-location span {
            min-width: 0;
        }

        .toast-popup {
            bottom: 30px;
        }
    }

    /* Desktop Hero Showcase (min-width: 1024px) */
    .hero-desktop-showcase {
        display: none;
    }

    @media (min-width: 1024px) {
        .home-container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 0 4rem 0 !important;
            gap: 2.25rem !important;
        }

        /* Sembunyikan hero blue card dan mobile search bar pada desktop */
        .hero-banner,
        #search-area {
            display: none !important;
        }

        /* Empty State: Ketika belum ada barang yang diupload sama sekali pada aplikasi */
        .home-container.is-empty-state {
            min-height: 100vh !important;
            padding: 0 !important;
            gap: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            box-sizing: border-box !important;
        }

        .home-container.is-empty-state .hero-desktop-showcase {
            width: 100% !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 2rem clamp(2rem, 5vw, 5rem) !important;
            background: linear-gradient(180deg, #d8eefe 0%, #e8f4fd 35%, #f4f9fd 70%, #ffffff 100%) !important;
            box-sizing: border-box !important;
        }

        .home-container.is-empty-state .hero-desktop-showcase .hero-stage-unit {
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 940px !important;
            transform: translateY(-20px);
        }

        /* Desktop New Layout Hero Showcase */
        .hero-desktop-showcase {
            display: block !important;
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 3.85rem clamp(2rem, 5vw, 5rem) 2.65rem clamp(2rem, 5vw, 5rem);
            background: linear-gradient(180deg, #d8eefe 0%, #e8f4fd 35%, #f4f9fd 70%, #ffffff 100%);
            box-sizing: border-box;
        }

        /* Decorative Circle Shapes (FindUMB Color Palette - Komposisi Harmonis & Mengisi Ruang) */
        .hero-bg-circle-canvas {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
            -webkit-mask-image: linear-gradient(to bottom, black 70%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 70%, transparent 100%);
        }

        .hero-bg-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        /* 1. Circle Mascot: Aksen halo lembut di belakang maskot, lebih kompak dan tidak mencolok */
        .circle-mascot {
            width: clamp(300px, 25vw, 370px);
            height: clamp(300px, 25vw, 370px);
            left: calc(50% + 235px);
            top: 44%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.65) 0%, rgba(186, 230, 253, 0.42) 45%, rgba(219, 234, 254, 0.18) 72%, rgba(255, 255, 255, 0) 100%);
        }

        /* 2. Circle Outer Right: Sisi kanan luar menambah kedalaman lembut */
        .circle-top-right {
            width: clamp(340px, 26vw, 410px);
            height: clamp(340px, 26vw, 410px);
            right: -70px;
            top: 40%;
            transform: translateY(-50%);
            background: radial-gradient(circle at 50% 50%, rgba(186, 230, 253, 0.65) 0%, rgba(219, 234, 254, 0.32) 55%, rgba(255, 255, 255, 0) 85%);
        }

        /* 3. Circle Top Left: Mengisi ruang kosong kiri atas di belakang judul secara halus */
        .circle-top-left {
            width: clamp(240px, 18vw, 300px);
            height: clamp(240px, 18vw, 300px);
            left: 3%;
            top: 15px;
            background: radial-gradient(circle at 45% 45%, rgba(186, 230, 253, 0.6) 0%, rgba(219, 234, 254, 0.28) 55%, rgba(255, 255, 255, 0) 80%);
        }

        /* 4. Circle Mid Left: Aksen lembut penyeimbang di sisi kiri */
        .circle-mid-left {
            width: clamp(160px, 12vw, 200px);
            height: clamp(160px, 12vw, 200px);
            left: 12%;
            top: 46%;
            background: radial-gradient(circle at 50% 50%, rgba(219, 234, 254, 0.5) 0%, rgba(240, 247, 253, 0.2) 60%, rgba(255, 255, 255, 0) 80%);
        }

        /* 5. Circle Bottom Left: Sudut kiri bawah utuh dan lembut */
        .circle-bottom-left {
            width: clamp(280px, 22vw, 340px);
            height: clamp(280px, 22vw, 340px);
            left: 1%;
            bottom: 16px;
            background: radial-gradient(circle at 50% 50%, rgba(186, 230, 253, 0.65) 0%, rgba(219, 234, 254, 0.3) 55%, rgba(255, 255, 255, 0) 85%);
        }

        /* 6. Circle Center: Aksen lembut di bagian tengah menghubungkan sisi kiri & kanan */
        .circle-center {
            width: clamp(230px, 19vw, 290px);
            height: clamp(230px, 19vw, 290px);
            left: 48%;
            top: 36%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle at 48% 48%, rgba(186, 230, 253, 0.6) 0%, rgba(219, 234, 254, 0.28) 55%, rgba(255, 255, 255, 0) 80%);
        }

        /* Unified Hero Stage: Search bar, Head/subtext, & Mascot */
        .hero-stage-unit {
            max-width: 940px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 4;
            display: flex;
            flex-direction: column;
        }

        .hero-stage-header {
            width: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            position: relative;
            padding: 0 0.25rem;
            box-sizing: border-box;
        }

        .hero-text-col {
            flex: 1;
            max-width: 580px;
            padding-bottom: 2.25rem;
            text-align: left;
        }

        .hero-desktop-heading {
            margin: 0;
            font-family: 'Nunito', 'Quicksand', sans-serif;
            font-size: clamp(2.6rem, 3.15vw, 3.45rem);
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.015em;
            background: linear-gradient(135deg, #093c72 0%, #1572b8 50%, #29a0dc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .hero-heading-line {
            display: block;
            white-space: nowrap;
        }

        .hero-desktop-subheading {
            margin: 1.15rem 0 0 0;
            font-family: 'Nunito', 'Plus Jakarta Sans', var(--font-main);
            font-size: clamp(1.08rem, 1.22vw, 1.2rem);
            line-height: 1.6;
            color: #4b5e76;
            font-weight: 600;
            max-width: 520px;
        }

        .hero-subheading-line {
            display: block;
        }

        /* Mascot Box - Posisi diatur agar cakar menggenggam search bar secara pas & presisi */
        .hero-mascot-peeking-box {
            width: clamp(330px, 27vw, 385px);
            margin-bottom: -39.1px; /* <-- UBAH NILAI INI UNTUK MENGATUR POSISI VERTIKAL MASKOT (semakin negatif semakin turun) */
            position: relative;
            z-index: 6;
            pointer-events: none;
            user-select: none;
            flex-shrink: 0;
            margin-right: 0.75rem;
        }

        .hero-mascot-v2-img {
            width: 100%;
            height: auto;
            display: block;
            filter: drop-shadow(0 10px 24px rgba(10, 45, 90, 0.12));
        }

        /* Enlarged Search Bar */
        .hero-search-bar-enlarged {
            width: 100%;
            height: 66px;
            background: #ffffff;
            border-radius: 9999px;
            border: 2px solid #29a0dc;
            box-shadow: 0 14px 38px rgba(21, 114, 184, 0.08), 0 0 0 3.5px rgba(41, 160, 220, 0.2);
            display: flex;
            align-items: center;
            padding: 0.45rem 0.65rem 0.45rem 1.85rem;
            gap: 0.75rem;
            position: relative;
            z-index: 5;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .hero-search-bar-enlarged:focus-within {
            border-color: #1572b8;
            box-shadow: 0 16px 42px rgba(21, 114, 184, 0.16), 0 0 0 4.5px rgba(21, 114, 184, 0.25);
        }

        .search-bar-input-enlarged {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font-main);
            font-size: 1.05rem;
            color: #1e293b;
            padding: 0;
            min-width: 0;
        }

        .search-bar-input-enlarged::placeholder {
            color: #94a3b8;
            font-size: 1.02rem;
        }

        .search-bar-submit-enlarged {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #1572b8;
            border: none;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 2px 10px rgba(21, 114, 184, 0.3);
        }

        .search-bar-submit-enlarged:hover {
            background: #0f5791;
            transform: scale(1.04);
        }

        .search-bar-submit-enlarged svg,
        .search-bar-submit-enlarged .lucide {
            width: 22px;
            height: 22px;
            stroke-width: 2.5;
            color: #ffffff;
            stroke: #ffffff;
        }

        /* Container margins for sections below hero - Rapi & Konsisten */
        .featured-carousel-wrap,
        .recent-list-wrap,
        .bottom-view-all-wrap {
            padding: 0 clamp(1.75rem, 4vw, 4rem);
            max-width: 1380px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            box-sizing: border-box;
        }

        .featured-carousel-wrap {
            margin-top: 0;
        }

        /* Featured Reports: 5 items in a pristine 5-column row */
        .featured-track {
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .featured-thumb-box {
            height: 135px;
        }

        /* Recent Reports Grid on Desktop - Rapi 3 Kolom Sempurna */
        .reports-vertical-container {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
            width: 100%;
        }

        /* Sembunyikan item yatim ke-10 pada desktop agar membentuk grid 3x3 simetris */
        .reports-vertical-container .report-card:nth-child(n+10) {
            display: none !important;
        }

        /* Card Hover Effect: Outline tipis & warna lembut */
        .featured-card {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .featured-card:hover {
            border-color: rgba(41, 160, 220, 0.35) !important;
            box-shadow: 0 2px 8px rgba(21, 114, 184, 0.05) !important;
            transform: none !important;
        }

        .report-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.055);
            border-radius: 16px;
            padding: 0.85rem 1rem;
            display: flex;
            gap: 0.95rem;
            align-items: center;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
            min-height: 96px;
            box-sizing: border-box;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .report-card:hover {
            border-color: rgba(41, 160, 220, 0.35) !important;
            box-shadow: 0 2px 8px rgba(21, 114, 184, 0.05) !important;
            transform: none !important;
        }

        .report-thumb {
            width: 76px;
            height: 76px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            align-self: center;
        }

        .report-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.3rem;
            min-height: unset;
            padding: 0;
        }

        .report-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .report-location {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.35;
        }

        .report-date {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .report-aside {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            min-height: 76px;
            align-self: center;
            padding: 0;
            gap: 0.6rem;
        }

        .bottom-view-all-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.75rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .bottom-view-all-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f0f7fe;
            color: #1572b8;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.7rem 2.25rem;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(21, 114, 184, 0.16);
            box-shadow: 0 2px 6px rgba(21, 114, 184, 0.06);
        }

        .bottom-view-all-btn:hover {
            background: #1572b8;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(21, 114, 184, 0.25);
            transform: translateY(-1px);
        }

        /* ----------------------------------------------------
           Desktop Floating Detail Modal (Jendela Mengambang)
           ---------------------------------------------------- */
        .desktop-detail-modal-backdrop {
            z-index: 150;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
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
@php
    $hasReports = isset($recentReports) && $recentReports->isNotEmpty();
@endphp
<div class="home-container {{ !$hasReports ? 'is-empty-state' : '' }}" id="home-container">
    <!-- Desktop New Layout Hero Showcase (Persis Referensi Mockup) -->
    <section class="hero-desktop-showcase" id="hero-desktop-showcase">
        <!-- Decorative Circle Shapes (FindUMB Color Palette - Komposisi Harmonis & Mengisi Ruang) -->
        <div class="hero-bg-circle-canvas" aria-hidden="true">
            <div class="hero-bg-circle circle-bottom-left"></div>
            <div class="hero-bg-circle circle-top-left"></div>
            <div class="hero-bg-circle circle-mid-left"></div>
            <div class="hero-bg-circle circle-center"></div>
            <div class="hero-bg-circle circle-top-right"></div>
            <div class="hero-bg-circle circle-mascot"></div>
        </div>

        <!-- Unified Hero Stage: Left (Head & Subtext), Right (Mascot), Bottom (Search Bar) -->
        <div class="hero-stage-unit">
            <!-- Top Section: Text on Left, Mascot on Right -->
            <div class="hero-stage-header">
                <!-- Text on Left (flush with left edge of search bar) -->
                <div class="hero-text-col">
                    <h1 class="hero-desktop-heading">
                        <span class="hero-heading-line">Pusat Pelaporan</span>
                        <span class="hero-heading-line">Barang Hilang UMB</span>
                    </h1>
                    <p class="hero-desktop-subheading">
                        <span class="hero-subheading-line">Temukan barang hilangmu di sekitar kampus</span>
                        <span class="hero-subheading-line">atau laporkan barang temuan dengan mudah.</span>
                    </p>
                </div>

                <!-- Mascot on Right (paws resting on search bar) -->
                <div class="hero-mascot-peeking-box" aria-hidden="true">
                    <picture>
                        <source srcset="{{ asset('images/mascot-peeking-3.webp') }}" type="image/webp">
                        <img src="{{ asset('images/mascot-peeking-3.png') }}" alt="Maskot FindUMB" class="hero-mascot-v2-img">
                    </picture>
                </div>
            </div>

            <!-- Bottom Section: Enlarged Floating Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="hero-search-bar-enlarged" id="hero-desktop-search-form">
                <input type="text" name="q" id="hero-desktop-search-input" class="search-bar-input-enlarged" placeholder="Cari barang hilang atau temuan di kampus..." autocomplete="off">
                <button type="submit" class="search-bar-submit-enlarged" aria-label="Cari barang">
                    <i data-lucide="search"></i>
                </button>
            </form>
        </div>
    </section>

    <!-- Hero Blue Gradient Banner (Mobile Only) -->
    <section class="hero-banner" id="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="hero-title-desktop">
                    <span class="hero-title-desktop-line">Pusat Pelaporan Barang Hilang</span>
                    <span class="hero-title-desktop-line">dan Temuan Civitas Mercu Buana</span>
                </span>
                <span class="hero-title-mobile">
                    <span class="hero-title-line">Pusat Laporan Barang</span>
                    <span class="hero-title-line">Hilang &amp; Ditemukan</span>
                </span>
            </h1>
            <p class="hero-subtitle">
                <span class="hero-sub-desktop">Platform terpadu civitas Universitas Mercu Buana untuk melaporkan, melacak, serta mengamankan barang hilang dan temuan secara transparan, cepat, dan terpercaya.</span>
                <span class="hero-sub-mobile">
                    <span class="hero-sub-line">Layanan terpadu pelaporan dan</span>
                    <span class="hero-sub-line">pencarian barang civitas UMB.</span>
                </span>
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

    @if($hasReports)
    <!-- Featured Reports Carousel (Bahasa Indonesia) -->
    <section class="featured-carousel-wrap">
        <div class="section-header">
            <h2 class="section-title">Laporan Prioritas</h2>
            <a href="{{ route('search') }}" class="section-link-all">Lihat semua</a>
        </div>

        <div class="featured-track" id="featured-track">
            @foreach ($featuredReports as $f)
                @php $fLost = $f->type->value === 'lost'; @endphp
                <a href="{{ route('report.detail', $f) }}" class="featured-card" data-id="{{ $f->id }}">
                    <div class="featured-thumb-box">
                        <img src="{{ $f->imageUrl() }}" alt="{{ $f->title }}" class="featured-thumb-img" loading="lazy">
                    </div>
                    <div class="featured-body">
                        <h3 class="featured-item-title">{{ $f->title }}</h3>
                        <div class="featured-meta">
                            <span class="{{ $fLost ? 'meta-status-lost' : 'meta-status-found' }}">{{ $fLost ? 'Hilang' : 'Ditemukan' }}</span>
                            <span class="meta-dot">·</span>
                            <span class="meta-location">{{ $f->location ?: $f->campus_location }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
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
        </div>

        <div class="reports-vertical-container" id="reports-container">
            @forelse ($recentReports as $r)
                @php $rLost = $r->type->value === 'lost'; @endphp
                <article class="report-card" data-id="{{ $r->id }}" data-title="{{ $r->title }}">
                    <img src="{{ $r->imageUrl() }}" alt="{{ $r->title }}" class="report-thumb" loading="lazy">
                    <div class="report-info">
                        <h3 class="report-title">{{ $r->title }}</h3>
                        <div class="report-location">
                            <i data-lucide="map-pin"></i>
                            <span>{{ $r->location ?: $r->campus_location }}</span>
                        </div>
                        <span class="report-date">{{ \App\Support\Format::dateShort($r->incident_date) }}</span>
                    </div>
                    <div class="report-aside">
                        <span class="badge-status {{ $rLost ? 'badge-lost' : 'badge-found' }}">{{ $rLost ? 'Hilang' : 'Temuan' }}</span>
                        <div class="card-actions-row">
                            <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare(@js($r->title))">
                                <i data-lucide="share-2"></i>
                            </button>
                            <button type="button" class="btn-action-icon {{ in_array((string) $r->id, $savedIds ?? [], true) ? 'bookmarked' : '' }}" aria-label="Simpan" onclick="toggleBookmark(this)">
                                <i data-lucide="bookmark"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <p class="empty-desc" style="padding:24px 0;text-align:center;">Belum ada laporan yang dipublikasikan.</p>
            @endforelse
        </div>

        <!-- Tombol Lihat Semua Laporan di bagian bawah -->
        <div class="bottom-view-all-wrap">
            <a href="{{ route('search') }}" class="bottom-view-all-btn">Lihat Semua Laporan</a>
        </div>
    </section>
    @endif
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

            <!-- Structured Properties List (Persis Image 1) -->
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

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite">
    Pesan notifikasi
</div>
@endsection

@push('scripts')
<script>
    // Default Seed Reports for User
    // Data laporan langsung dari Supabase (di-render server, bukan mock localStorage).
    const SERVER_REPORTS = @json($recentReports->map(fn ($r) => $r->toUiArray($viewer))->values());
    let SERVER_BOOKMARKS = @json($savedIds ?? []);
    const IS_AUTHENTICATED = @json(auth()->check());

    function getUserReports() {
        return Array.isArray(SERVER_REPORTS) ? SERVER_REPORTS : [];
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

    // Stored Bookmarks Management
    function getStoredBookmarks() {
        return (SERVER_BOOKMARKS || []).map(String);
    }

    function setStoredBookmarks(bookmarks) {
        SERVER_BOOKMARKS = (bookmarks || []).map(String);
    }
    // Simpan/hapus bookmark ke server (Supabase), bukan cuma di browser.
    function syncBookmarkToServer(id) {
        if (!IS_AUTHENTICATED) return;
        fetch(`{{ url('/api/bookmarks') }}/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.FindUMB_CSRF || document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
        }).catch(() => {});
    }


    // Toggle Bookmark button (Feed cards & detail modal)
    function toggleBookmark(btnOrId) {
        let id = null;
        let btn = null;

        if (typeof btnOrId === 'string' || typeof btnOrId === 'number') {
            id = String(btnOrId);
        } else if (btnOrId && btnOrId.nodeType) {
            btn = btnOrId;
            const card = btn.closest('.report-card, .featured-card');
            if (card) id = card.getAttribute('data-id');
        }

        if (!id) return;

        let bookmarks = getStoredBookmarks();
        const index = bookmarks.indexOf(id);
        const isAdding = (index === -1);

        if (isAdding) {
            bookmarks.push(id);
            showToast('Laporan disimpan ke daftar tersimpan.');
        } else {
            bookmarks.splice(index, 1);
            showToast('Laporan dihapus dari daftar tersimpan.');
        }

        setStoredBookmarks(bookmarks);
        syncBookmarkToServer(id);
        syncCardBookmarks();
        syncDetailModalBookmarkState(id);
    }

    function syncCardBookmarks() {
        const bookmarks = getStoredBookmarks();
        document.querySelectorAll('#reports-container .report-card').forEach(card => {
            const id = String(card.getAttribute('data-id') || '');
            const btn = card.querySelector('.card-actions-row button[aria-label="Simpan"]');
            if (btn && id) {
                btn.classList.toggle('bookmarked', bookmarks.includes(id));
            }
        });
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

        // Validate completion modal requirements
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
        const id = document.getElementById('edit-report-id')?.value;
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
                closeHomeEditModal();
                showToast('✓ Laporan berhasil diperbarui!');
                setTimeout(() => window.location.reload(), 600);
            })
            .catch(() => showToast('Gagal memperbarui laporan.'));
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
        const id = document.getElementById('delete-report-id')?.value;
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
                closeHomeDeleteModal();
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

    // Render user-created reports from localStorage & sync owner options
    function renderCustomReports() {
        // Server sudah me-render semua kartu dari Supabase. Dulu fungsi ini
        // menempel ulang kartu dari localStorage → itulah "kedip" mock → data asli.
        return;
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

    // ----------------------------------------------------
    // Desktop Floating Detail Modal Catalog & Handlers
    // ----------------------------------------------------
    // (katalog mock dihapus — semua data dari Supabase)

    function getReportDataById(id) {
        const idStr = String(id);
        return (SERVER_REPORTS || []).find(r => String(r.id) === idStr) || null;
    }

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
        const report = getReportDataById(id);
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
        if (primaryBtn && btnLabel) {
            if (report.status === 'resolved' || report.status === 'completed') {
                btnLabel.textContent = 'Laporan Selesai';
                primaryBtn.style.background = '#64748b';
                primaryBtn.style.pointerEvents = 'none';
            } else if (report.type === 'found') {
                btnLabel.textContent = 'Ajukan Klaim Barang Ini';
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            } else {
                btnLabel.textContent = 'Saya Menemukan Barang Ini';
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            }
        }

        // External Link to Full Report Page
        const fullPageBtn = document.getElementById('modal-detail-open-full');
        if (fullPageBtn) {
            const fallbackId = String(report.id).replace('featured-', '');
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
        toggleBookmark(id);
    }

    function syncDetailModalBookmarkState(id) {
        const bookmarks = getStoredBookmarks();
        const isBookmarked = bookmarks.includes(String(id));

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

    document.addEventListener('DOMContentLoaded', () => {
        renderCustomReports();
        syncCardBookmarks();
        initFeaturedCarousel();

        // Desktop Hero Search Form Navigation
        const desktopSearchForm = document.getElementById('hero-desktop-search-form');
        const desktopSearchInput = document.getElementById('hero-desktop-search-input');
        if (desktopSearchForm && desktopSearchInput) {
            desktopSearchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const q = desktopSearchInput.value.trim();
                if (q) {
                    window.location.href = `{{ route('search') }}?q=${encodeURIComponent(q)}`;
                } else {
                    window.location.href = `{{ route('search') }}`;
                }
            });
        }

        // Card Click Navigation to Detail Page / Floating Desktop Modal
        document.addEventListener('click', (e) => {
            // Check for .featured-card click
            const featuredCard = e.target.closest('.featured-card');
            if (featuredCard) {
                if (window.innerWidth >= 1024) {
                    e.preventDefault();
                    const id = featuredCard.getAttribute('data-id') || 
                               (featuredCard.getAttribute('href') ? featuredCard.getAttribute('href').split('/').pop() : '5');
                    openDesktopDetailModal(id);
                    return;
                }
                // Mobile: let default <a> navigation proceed to /report/{id}
                return;
            }

            // Check for .report-card click
            const card = e.target.closest('.report-card');
            if (card && !e.target.closest('.card-actions-row') && !e.target.closest('.btn-action-icon') && !e.target.closest('.card-options-menu')) {
                const id = card.getAttribute('data-id') || '1';
                if (window.innerWidth >= 1024) {
                    e.preventDefault();
                    openDesktopDetailModal(id);
                } else {
                    window.location.href = `{{ url('/report') }}/${encodeURIComponent(id)}`;
                }
            }
        });
    });
</script>
@endpush
