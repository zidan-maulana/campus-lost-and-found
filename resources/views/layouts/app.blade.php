<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="description" content="Find UMB — Layanan pelaporan dan pencarian barang hilang atau temuan civitas kampus Universitas Mercu Buana.">
    <meta name="theme-color" content="#1e40af">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>@yield('title', 'Find UMB — Campus Lost & Found Universitas Mercu Buana')</title>

    <!-- Google Fonts: Inter (Exact match to reference) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Color & Typography System - Exact match to reference */
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-heading: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            --bg-page: #f1f5f9;
            --bg-app: #f8fafc; /* Sedikit lebih terang (Slate-50), bersih dan lapang namun kartu putih tetap kontras */
            --bg-card: #ffffff;
            --bg-subtle: #ffffff;
            --bg-input: #ffffff;

            --primary: #1572b8;
            --primary-dark: #0f5791;
            --primary-gradient: linear-gradient(135deg, #0e5090 0%, #1572b8 60%, #29a0dc 100%);
            --primary-light: #f0f7fd;
            --primary-hover: #0f5791;

            --text-main: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --text-light: #cbd5e1;

            --lost-bg: #fee2e2;
            --lost-text: #ef4444;
            --lost-border: #fca5a5;

            --found-bg: #dcfce7;
            --found-text: #16a34a;
            --found-border: #86efac;

            --border-light: #f1f5f9;
            --border-subtle: #e2e8f0;

            /* Neutral clean shadows - NO BLUE SHADOWS */
            --shadow-card: 0 2px 8px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
            --shadow-elevated: 0 4px 14px rgba(15, 23, 42, 0.12);
            --shadow-nav: 0 10px 24px rgba(15, 23, 42, 0.08);

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 18px;
            --radius-xl: 24px;
            --radius-pill: 9999px;

            --transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.5;
            display: flex;
            justify-content: center;
            overflow-x: hidden;
        }

        /* Mobile Container Wrapper (Mobile-First Shell) */
        .app-shell {
            width: 100%;
            max-width: 480px;
            background-color: var(--bg-app);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.06);
            padding-bottom: 95px; /* Space for docked notched bottom nav */
        }

        /* Seamless white background support for full-bleed white pages (eliminates cutoff above navbar) */
        .app-shell.bg-white,
        .app-shell:has(.notif-page-container),
        .app-shell:has([data-full-white="true"]) {
            background-color: #ffffff;
        }

        #main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .app-header {
            padding: 1.15rem 1.25rem 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--bg-app);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .brand-logo-area {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo-img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            display: block;
        }

        .brand-icon-squircle {
            width: 38px;
            height: 38px;
            background: var(--primary-gradient);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); /* Clean neutral shadow */
        }

        .brand-name {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
            color: #1572b8;
            display: flex;
            align-items: center;
            gap: 0;
        }

        .brand-name span {
            color: #0c487b;
            font-weight: 800;
            font-size: 1.3rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .icon-circle-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-pill);
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .icon-circle-btn:hover, .icon-circle-btn:active {
            background: var(--bg-subtle);
            border-color: var(--text-muted);
            transform: scale(0.96);
        }

        .badge-notification-dot {
            position: absolute;
            top: 9px;
            right: 9px;
            width: 7px;
            height: 7px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
        }

        /* Docked Full-Width Smooth Arched Dome Bottom Navigation Bar */
        .bottom-nav-wrapper {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px; /* Memenuhi layar kesamping sesuai container shell */
            z-index: 50;
            filter: drop-shadow(0 -2px 10px rgba(15, 23, 42, 0.04));
            pointer-events: none;
        }

        .notched-nav-inner {
            position: relative;
            width: 100%;
            pointer-events: auto;
        }

        .nav-svg-bg {
            width: 100%;
            height: 92px;
            display: block;
        }

        .nav-items-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 24px 8px 8px 8px; /* Ruang lapang di atas icon agar tidak dempet dengan tepi shape */
            z-index: 10;
        }

        .nav-btn-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: #64748b;
            font-size: 0.725rem;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
        }

        /* Saat button aktif/diseleksi: icon dan teks keduanya berubah warna menjadi biru */
        .nav-btn-item.active {
            color: var(--primary) !important;
        }

        .nav-btn-item.active span {
            color: var(--primary) !important;
            font-weight: 700;
        }

        .nav-btn-item svg {
            width: 22px;
            height: 22px;
            stroke-width: 1.8;
            stroke: currentColor;
            fill: none;
            transition: var(--transition);
        }

        .nav-btn-item:hover {
            color: var(--primary);
        }

        .nav-btn-item.active svg {
            stroke: var(--primary) !important;
            transform: scale(1.06);
        }

        /* Center Dome Raised Button Slot */
        .center-btn-slot {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 100%;
        }

        .center-floating-circle {
            position: absolute;
            top: -14px; /* Bersarang simetris di puncak kubah dengan margin seragam */
            left: 50%;
            transform: translateX(-50%);
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: #1572b8; /* Warna biru senada dengan logo UMB */
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important; /* Tanpa shadow */
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
            transition: var(--transition);
        }

        .center-floating-circle:active {
            transform: translateX(-50%) scale(0.94);
        }

        .center-floating-circle svg {
            width: 24px;
            height: 24px;
            stroke: #ffffff;
            stroke-width: 2.5;
            fill: none;
            display: block;
        }

        .ios-home-indicator {
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 130px;
            height: 4.5px;
            background: #334155;
            border-radius: 10px;
            opacity: 0.75;
        }

        .brand-icon-squircle svg, .brand-icon-squircle .lucide {
            width: 20px;
            height: 20px;
            stroke-width: 2.3;
        }

        /* Global Quick Report Bottom Sheet Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition);
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 24px 24px 0 0;
            padding: 1.5rem;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-overlay.active .modal-sheet {
            transform: translateY(0);
        }

        .modal-pill-drag {
            width: 40px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 99px;
            margin: 0 auto 1.25rem auto;
        }

        .modal-title {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.25rem;
        }

        .modal-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }

        .modal-options-grid {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .modal-option-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            background: #ffffff;
        }

        .modal-option-card:hover {
            border-color: var(--primary);
            background: #f8fafc;
        }

        .option-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .option-icon-box.lost {
            background: var(--lost-bg);
            color: var(--lost-text);
        }

        .option-icon-box.found {
            background: var(--found-bg);
            color: var(--found-text);
        }

        .option-icon-box svg, .option-icon-box .lucide {
            width: 24px;
            height: 24px;
            stroke-width: 2.2;
        }

        .option-text h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.15rem;
        }

        .option-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.35;
        }

        .btn-modal-close {
            width: 100%;
            margin-top: 1.25rem;
            padding: 0.75rem;
            background: transparent;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-modal-close:hover {
            background: var(--bg-subtle);
        }

        .btn-guide-close {
            width: 100%;
            margin-top: 1rem;
            padding: 0.8rem 1rem;
            background: #f1f5f9;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            color: #334155;
            font-family: var(--font-main);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .btn-guide-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Global Modal Action Buttons */
        .modal-actions-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-modal-cancel {
            flex: 1;
            padding: 0.75rem 1rem;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .btn-modal-cancel:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #94a3b8;
        }

        .btn-modal-submit, .btn-modal-success {
            flex: 1.4;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: none;
            color: #ffffff;
            font-family: var(--font-main);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .btn-modal-submit {
            background: var(--primary);
            box-shadow: 0 2px 8px rgba(21, 114, 184, 0.22);
        }

        .btn-modal-submit:hover {
            background: var(--primary-dark);
        }

        .btn-modal-success {
            background: #16a34a;
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.22);
        }

        .btn-modal-success:hover {
            background: #15803d;
        }

        .btn-modal-danger {
            flex: 1.4;
            padding: 0.75rem 1rem;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .btn-modal-danger:hover {
            background: #dc2626;
        }

        /* Buku Panduan & Informasi Modal Styling */
        .guide-modal-sheet {
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1.25rem 1.5rem 1.25rem;
        }

        .guide-modal-body {
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            padding: 0.25rem 0.15rem 0.5rem 0.15rem;
            scrollbar-width: thin;
        }

        .guide-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 0.95rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .guide-card-header {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        /* Unified Blue Theme for Guide Icons (matching Panduan Barang Hilang icon) */
        .guide-card-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #e0f2fe; /* Light sky blue */
            color: #0284c7;      /* Primary ocean blue */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .guide-card-icon svg, .guide-card-icon .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .guide-card-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .guide-list {
            margin: 0;
            padding-left: 1.15rem;
            font-size: 0.775rem;
            line-height: 1.55;
            color: var(--text-secondary);
        }

        .guide-list li {
            margin-bottom: 0.3rem;
        }

        .guide-list li:last-child {
            margin-bottom: 0;
        }

        .guide-list strong {
            color: var(--text-main);
            font-weight: 600;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-shell @yield('app_shell_class')" id="app">
        @hasSection('header')
            @yield('header')
        @else
            <!-- Top App Header -->
            <header class="app-header">
                <a href="{{ url('/') }}" class="brand-logo-area" id="brand-logo">
                    <picture>
                        <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Find UMB" class="brand-logo-img" width="38" height="38" loading="eager">
                    </picture>
                    <div class="brand-name">Find<span>UMB</span></div>
                </a>
                <div class="header-actions">
                    @if(request()->routeIs('home', 'search') || request()->is('/') || request()->is('search*'))
                    <button type="button" class="icon-circle-btn" id="btn-guide-info" onclick="openGuideModal()" aria-label="Buku Panduan &amp; Informasi" title="Buku Panduan &amp; Informasi">
                        <i data-lucide="book-open" style="width: 19px; height: 19px;"></i>
                    </button>
                    @endif
                    <a href="{{ route('notifications') }}" class="icon-circle-btn" id="btn-notification" aria-label="Notifikasi" style="text-decoration: none;">
                        <i data-lucide="bell" style="width: 19px; height: 19px;"></i>
                        <span class="badge-notification-dot" id="header-notif-dot"></span>
                    </a>
                </div>
            </header>
        @endif

        <!-- Main Content Slot -->
        <main id="main-content">
            @yield('content')
        </main>

        @hasSection('custom_bottom_bar')
            @yield('custom_bottom_bar')
        @elseif(!View::hasSection('hide_bottom_nav'))
        <!-- Mobile Bottom Arched Dome Navigation Bar (Full Width Kesamping) -->
        <nav class="bottom-nav-wrapper" id="bottom-navbar">
            <div class="notched-nav-inner">
                <!-- Smooth Bezier Arched Raised Dome Background - S2 Affine-Scaled Curve 3 (Baseline Y=20, Dome H=20, Extra Headroom) -->
                <svg class="nav-svg-bg" viewBox="0 0 380 92" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="
                        M 0 20 
                        L 132 20 
                        C 146 20 154 16 161 10.7 
                        C 168 5.4 178 0 190 0 
                        C 202 0 212 5.4 219 10.7 
                        C 226 16 234 20 248 20 
                        L 380 20 
                        L 380 92 
                        L 0 92 
                        Z" 
                        fill="#ffffff" 
                    />
                </svg>

                <!-- Overlay Items dengan Icon yang Relevan untuk Setiap Aksi -->
                <div class="nav-items-overlay">
                    <!-- 1. Beranda (Home) -->
                    <a href="{{ url('/') }}" class="nav-btn-item {{ request()->is('/') ? 'active' : '' }}" id="nav-home">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 10.5L10.5 4.5C11.33 3.74 12.67 3.74 13.5 4.5L20 10.5C20.63 11.08 21 11.89 21 12.75V18C21 19.66 19.66 21 18 21H6C4.34 21 3 19.66 3 18V12.75C3 11.89 3.37 11.08 4 10.5Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 21V14.5C10 13.67 10.67 13 11.5 13H12.5C13.33 13 14 13.67 14 14.5V21" stroke-linecap="round"/>
                        </svg>
                        <span>Beranda</span>
                    </a>

                    <!-- 2. Cari (Pencarian Barang Hilang/Temuan) -->
                    <a href="{{ route('search') }}" class="nav-btn-item {{ request()->is('search*') ? 'active' : '' }}" id="nav-search">
                        <svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" stroke-linecap="round"/>
                            <path d="M16.5 16.5L21 21" stroke-linecap="round"/>
                        </svg>
                        <span>Cari</span>
                    </a>

                    <!-- Center: Lapor Barang (Raised Dome Button Biru Flat tanpa Shadow dengan icon +) -->
                    <div class="center-btn-slot">
                        <a href="#quick-report-modal" class="center-floating-circle" id="nav-fab-report" aria-label="Laporkan Barang">
                            <!-- Icon: Plus (+) -->
                            <svg viewBox="0 0 24 24">
                                <path d="M12 5V19M5 12H19" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>

                    <!-- 4. Pesan (Chat / Pesan Masuk Civitas - Relevan) -->
                    <a href="{{ route('messages') }}" class="nav-btn-item {{ request()->is('messages*') ? 'active' : '' }}" id="nav-inbox">
                        <svg viewBox="0 0 24 24">
                            <path d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.11 19.15 8.81 19 8.5 19Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="7.5" cy="10.5" r="0.8" fill="currentColor"/>
                            <circle cx="12" cy="10.5" r="0.8" fill="currentColor"/>
                            <circle cx="16.5" cy="10.5" r="0.8" fill="currentColor"/>
                        </svg>
                        <span>Pesan</span>
                    </a>

                    <!-- 5. Profil (User Profile - Relevan) -->
                    <a href="{{ route('profile') }}" class="nav-btn-item {{ request()->is('profile*') || request()->is('settings*') ? 'active' : '' }}" id="nav-profile">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round"/>
                            <circle cx="12" cy="9.5" r="3" stroke-linecap="round"/>
                            <path d="M6.5 18C7.6 15.8 9.6 14.5 12 14.5C14.4 14.5 16.4 15.8 17.5 18" stroke-linecap="round"/>
                        </svg>
                        <span>Profil</span>
                    </a>
                </div>

                <!-- iOS Home Indicator -->
                <div class="ios-home-indicator"></div>
            </div>
        </nav>
        @endif
        <!-- Global Quick Report Bottom Sheet Modal -->
        <div class="modal-overlay" id="report-modal" onclick="closeReportModal(event)">
            <div class="modal-sheet" onclick="event.stopPropagation()">
                <div class="modal-pill-drag"></div>
                <h3 class="modal-title">Buat Laporan Baru</h3>
                <p class="modal-desc">Pilih jenis pelaporan barang di lingkungan Universitas Mercu Buana.</p>
                
                <div class="modal-options-grid">
                    <a href="{{ route('report.lost') }}" class="modal-option-card">
                        <div class="option-icon-box lost">
                            <i data-lucide="help-circle"></i>
                        </div>
                        <div class="option-text">
                            <h4>Saya Kehilangan Barang (Hilang)</h4>
                            <p>Laporkan barang Anda yang tertinggal atau hilang agar lekas ditemukan.</p>
                        </div>
                    </a>

                    <a href="{{ route('report.found') }}" class="modal-option-card">
                        <div class="option-icon-box found">
                            <i data-lucide="check-circle-2"></i>
                        </div>
                        <div class="option-text">
                            <h4>Saya Menemukan Barang (Temuan)</h4>
                            <p>Laporkan barang temuan tanpa harus menyerahkan nomor HP pribadi Anda.</p>
                        </div>
                    </a>
                </div>

                <button type="button" class="btn-modal-close" onclick="closeReportModal()">Batal</button>
            </div>
        </div>

        <!-- Global Buku Panduan & Informasi Modal -->
        <div class="modal-overlay" id="guide-modal" onclick="closeGuideModal(event)">
            <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
                <div class="modal-pill-drag"></div>
                <h3 class="modal-title">Buku Panduan &amp; Informasi</h3>
                <p class="modal-desc">Panduan pelaporan, keamanan privasi, dan alur serah terima barang di lingkungan Universitas Mercu Buana.</p>
                
                <div class="guide-modal-body">
                    <!-- 1. Verifikasi Kepemilikan Aman (Poin-poin) -->
                    <div class="guide-card">
                        <div class="guide-card-header">
                            <div class="guide-card-icon">
                                <i data-lucide="shield-check"></i>
                            </div>
                            <h4 class="guide-card-title">Verifikasi Kepemilikan Aman</h4>
                        </div>
                        <ul class="guide-list">
                            <li>Kontak pribadi civitas UMB <strong>tidak dipublikasikan terbuka</strong> demi menjaga privasi dan mencegah spam/penipuan.</li>
                            <li>Komunikasi awal antar civitas dilakukan secara aman melalui fitur <strong>Pesan / Chat</strong> resmi FindUMB.</li>
                            <li>Pemilik yang sah <strong>wajib membuktikan ciri khusus</strong> rahasia barang sebelum proses serah terima.</li>
                            <li>Lakukan serah terima di area kampus yang ramai atau melalui <strong>Pos Penitipan Resmi</strong> UMB.</li>
                        </ul>
                    </div>

                    <!-- 2. Panduan Kehilangan Barang -->
                    <div class="guide-card">
                        <div class="guide-card-header">
                            <div class="guide-card-icon">
                                <i data-lucide="help-circle"></i>
                            </div>
                            <h4 class="guide-card-title">Panduan Barang Hilang</h4>
                        </div>
                        <ul class="guide-list">
                            <li>Lengkapi nama barang spesifik dan pilih kategori yang sesuai.</li>
                            <li>Sebutkan perkiraan lokasi kampus dan rentang waktu saat barang terakhir terlihat.</li>
                            <li>Tuliskan ciri umum barang, simpan ciri rahasia sebagai bukti verifikasi kepemilikan.</li>
                            <li>Pantau tab <strong>Pesan</strong> berkala untuk respon dari penemu barang.</li>
                        </ul>
                    </div>

                    <!-- 3. Panduan Menemukan Barang -->
                    <div class="guide-card">
                        <div class="guide-card-header">
                            <div class="guide-card-icon">
                                <i data-lucide="check-circle-2"></i>
                            </div>
                            <h4 class="guide-card-title">Panduan Penemuan Barang</h4>
                        </div>
                        <ul class="guide-list">
                            <li>Unggah foto jelas barang temuan dan cantumkan lokasi spesifik penemuan.</li>
                            <li>Pilih status penyimpanan: <strong>Disimpan Sendiri</strong> atau <strong>Dititipkan</strong> ke petugas kampus.</li>
                            <li>Bila dititipkan, pastikan mencatat nama staf atau pos keamanan penerima titipan.</li>
                            <li>Sebelum menyerahkan barang, mintalah pengklaim membuktikan ciri khusus yang valid.</li>
                        </ul>
                    </div>

                    <!-- 4. Pos Penitipan Resmi Kampus -->
                    <div class="guide-card">
                        <div class="guide-card-header">
                            <div class="guide-card-icon">
                                <i data-lucide="building-2"></i>
                            </div>
                            <h4 class="guide-card-title">Pos Penitipan Resmi UMB</h4>
                        </div>
                        <ul class="guide-list">
                            <li><strong>Pos Satpam Gerbang Barat &amp; Tower</strong>: Siaga 24 jam untuk pengamanan barang.</li>
                            <li><strong>Layanan Perpustakaan</strong>: Gedung Tower Lantai 2 (Jam Kerja Kuliah).</li>
                            <li><strong>Pengurus DKM Masjid</strong>: Masjid Manarul 'Amal Kampus Meruya.</li>
                        </ul>
                    </div>
                </div>

                <button type="button" class="btn-guide-close" onclick="closeGuideModal()">Tutup Panduan</button>
            </div>
        </div>
    </div>

    <!-- Lucide Icons Library (Local with CDN fallback) -->
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <script>
        if (typeof lucide === 'undefined') {
            document.write('<script src="https://unpkg.com/lucide@latest"><\/script>');
        }
    </script>
    <script>
        // Global Quick Report Modal Handlers
        function openReportModal() {
            const modal = document.getElementById('report-modal');
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }

        function closeReportModal(e) {
            if (!e || e.target.id === 'report-modal' || e.target.classList.contains('btn-modal-close')) {
                const modal = document.getElementById('report-modal');
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        }

        // Global Guide Modal Handlers
        function openGuideModal() {
            const modal = document.getElementById('guide-modal');
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }

        function closeGuideModal(e) {
            if (!e || e.target.id === 'guide-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close') || e.target.classList.contains('btn-modal-close') || e.target.closest('.btn-modal-close')) {
                const modal = document.getElementById('guide-modal');
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Interactive bottom navigation item selection
            const navItems = document.querySelectorAll('.nav-btn-item');
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    navItems.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Connect floating bottom action button (+) to Report Modal on EVERY page
            const fab = document.getElementById('nav-fab-report');
            if (fab) {
                fab.addEventListener('click', (e) => {
                    e.preventDefault();
                    openReportModal();
                });
            }

            // Sync notification dot with stored notifications
            const headerDot = document.getElementById('header-notif-dot');
            if (headerDot) {
                try {
                    const rawNotifs = localStorage.getItem('findumb_notifications');
                    if (rawNotifs) {
                        const notifs = JSON.parse(rawNotifs);
                        const hasUnread = Array.isArray(notifs) && notifs.some(n => !n.read);
                        headerDot.style.display = hasUnread ? 'block' : 'none';
                    }
                } catch(e) {}
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
