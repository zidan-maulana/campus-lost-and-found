<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#f8fafc]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="description" content="Dashboard Administrator FindUMB — Sistem Moderasi dan Tata Kelola Lost & Found Universitas Mercu Buana.">
    <meta name="theme-color" content="#1572b8">

    <title>@yield('title', 'Dashboard Administrator — FindUMB')</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite / CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ["class"],
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: "#1572b8",
                            dark: "#0f5791",
                            light: "#f0f7fd",
                            50: "#f0f7fd",
                            100: "#e0f2fe",
                            200: "#bae6fd",
                            500: "#1572b8",
                            600: "#0f5791",
                            700: "#0c487b",
                        },
                        border: "#eaecf0",
                        input: "#eaecf0",
                        background: "#f8fafc",
                        foreground: "#111827",
                        muted: {
                            DEFAULT: "#f3f4f6",
                            foreground: "#6b7280",
                        },
                        card: {
                            DEFAULT: "#ffffff",
                            foreground: "#111827",
                        },
                    },
                    borderRadius: {
                        xl: "0.75rem",
                        '2xl': "1rem",
                    },
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-heading: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --primary: #1572b8;
            --primary-dark: #0f5791;
            --primary-gradient: linear-gradient(135deg, #0e5090 0%, #1572b8 60%, #29a0dc 100%);
            --bg-page: #f8fafc;
            --border-color: #eaecf0;
            --border-subtle: #e2e8f0;
            --text-title: #111827;
            --text-body: #4b5563;
            --text-muted: #9ca3af;
            --transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: var(--font-main);
            color: var(--text-title);
            background-color: var(--bg-page);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Desktop Left Sidebar (Exact Civitas Pointsale Style) */
        .desktop-sidebar {
            display: flex !important;
            flex-direction: column;
            justify-content: space-between;
            width: 260px;
            min-width: 260px;
            max-width: 260px;
            height: 100vh;
            height: 100dvh;
            position: sticky;
            top: 0;
            background: #ffffff;
            border-right: 1px solid #eaecf0;
            padding: 0 0.95rem 1.25rem 0.95rem;
            z-index: 60;
            box-sizing: border-box;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                        min-width 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                        max-width 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                        padding 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow-y: auto;
            overflow-x: hidden;
            white-space: nowrap;
            scrollbar-width: none;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .desktop-sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-top-container {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            width: 100%;
        }

        /* 1. Header Row (Fixed in when sidebar is scrolled) */
        .sidebar-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 0.25rem 0.65rem 0.25rem;
            min-height: 40px;
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 30;
            flex-shrink: 0;
            width: 100%;
        }

        .sidebar-brand-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .sidebar-logo-btn {
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-container {
            width: 36px;
            height: 36px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 9px;
            transition: background-color 0.2s ease;
        }

        .sidebar-logo-btn:hover .sidebar-logo-container {
            background-color: #f1f5f9;
        }

        .sidebar-logo-flipper {
            width: 32px;
            height: 32px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-face {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.35s ease, transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar-logo-face-logo {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        .sidebar-logo-face-icon {
            opacity: 0;
            transform: scale(0.75) rotate(-10deg);
            color: #1572b8;
        }

        .sidebar-open-slide-icon {
            width: 22px;
            height: 22px;
            stroke-width: 2.3;
            color: #1572b8;
            stroke: #1572b8;
        }

        @keyframes logoSlideOut {
            0%, 65% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
            72%, 92% {
                opacity: 0;
                transform: scale(0.7) rotate(12deg);
            }
            98%, 100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes iconSlideIn {
            0%, 65% {
                opacity: 0;
                transform: scale(0.7) rotate(-12deg);
                pointer-events: none;
            }
            72%, 92% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
                pointer-events: auto;
            }
            98%, 100% {
                opacity: 0;
                transform: scale(0.7) rotate(-12deg);
                pointer-events: none;
            }
        }

        .sidebar-brand-title-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
        }

        .sidebar-logo-img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
            color: #1572b8;
        }

        .sidebar-brand-name span {
            color: #0c487b;
        }

        .sidebar-collapse-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            color: #64748b;
            display: flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .sidebar-collapse-btn:hover {
            background: #f8fafc;
            color: var(--primary);
            border-color: #cbd5e1;
        }

        .sidebar-collapse-btn svg, .sidebar-collapse-btn .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2;
        }

        /* 2. Navigation Groups & Links */
        .sidebar-links-group,
        .sidebar-nav-list,
        .sidebar-secondary-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            width: 100%;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.6rem 0.9rem;
            border-radius: 10px;
            color: #475569;
            font-family: var(--font-main);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            box-sizing: border-box;
            text-align: left;
        }

        .sidebar-nav-item:hover {
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Active Item: Shape biru gradient persis di referensi civitas, tanpa shadow */
        .sidebar-nav-item.active {
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
            border: none !important;
            box-shadow: none !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        .sidebar-nav-item.active .sidebar-item-icon {
            color: #ffffff !important;
        }

        .sidebar-nav-item.active .sidebar-item-label {
            color: #ffffff !important;
        }

        /* Indikator notif warna putih ketika button dipilih */
        .sidebar-nav-item.active .sidebar-badge-counter,
        .sidebar-nav-item.active .sidebar-badge-counter.sidebar-badge-notif {
            background-color: #ffffff !important;
            color: #0c559c !important;
            font-weight: 800 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
        }

        .sidebar-item-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-item-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: inherit;
        }

        .sidebar-item-icon svg, .sidebar-item-icon .lucide {
            width: 19px;
            height: 19px;
            stroke-width: 2;
            color: inherit;
        }

        .sidebar-item-label {
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-badge-counter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.725rem;
            font-weight: 700;
            color: #475569;
            min-width: 22px;
            height: 20px;
            padding: 0 6px;
            border-radius: 9999px;
            background: #f1f5f9;
            line-height: 1;
            box-sizing: border-box;
            text-align: center;
            flex-shrink: 0;
            margin-left: auto;
        }

        /* Indikator Merah untuk Pesan Masuk & Notifikasi saat tidak dipilih */
        .sidebar-badge-counter.sidebar-badge-notif {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            font-weight: 700;
        }

        .sidebar-divider {
            height: 1px;
            background-color: #f1f5f9;
            width: 100%;
            margin: 0.25rem 0;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            padding: 0 0.5rem;
            margin-bottom: 0.4rem;
            letter-spacing: 0.02em;
        }

        /* Container bawah sidebar untuk account */
        .sidebar-bottom-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
            margin-top: auto;
            flex-shrink: 0;
        }

        /* 5. Footer User Profile Card */
        .sidebar-footer {
            padding-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
            margin-top: 0.75rem;
            width: 100%;
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            padding: 0.55rem 0.65rem;
            border-radius: 10px;
            background: transparent;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
        }

        .sidebar-user-card:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .sidebar-user-left {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 0;
        }

        .sidebar-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e0f2fe;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-user-avatar svg, .sidebar-user-avatar .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .sidebar-user-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 0.825rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .sidebar-user-chevrons {
            width: 16px;
            height: 16px;
            color: #94a3b8;
            flex-shrink: 0;
            stroke-width: 1.8;
        }

        /* Collapsed State (Mini-rail 64px) */
        .admin-layout-wrapper.sidebar-collapsed .desktop-sidebar {
            width: 64px !important;
            min-width: 64px !important;
            max-width: 64px !important;
            padding: 0 0.4rem 1.15rem 0.4rem !important;
            align-items: center;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-brand-name,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-brand-title-link,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-collapse-btn,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-item-label,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-badge-counter,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-section-title,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-user-info,
        .admin-layout-wrapper.sidebar-collapsed .sidebar-user-chevrons {
            display: none !important;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-header-row {
            justify-content: center;
            padding: 1.15rem 0 0.5rem 0 !important;
            width: 100%;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-nav-item {
            width: 44px;
            height: 44px;
            padding: 0;
            justify-content: center;
            margin: 0 auto;
            border-radius: 12px;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-nav-item.active {
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
            border: none !important;
            box-shadow: none !important;
            color: #ffffff !important;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-nav-item.active .sidebar-item-icon {
            color: #ffffff !important;
        }

        .sidebar-rail-notif-dot {
            display: none;
            position: absolute;
            top: -2px;
            right: -2px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ef4444;
            border: 1.5px solid #ffffff;
        }

        .admin-layout-wrapper.sidebar-collapsed .sidebar-rail-notif-dot {
            display: block;
        }

        /* Persistent full-height sticky on desktop, fixed overlay on mobile */
        @media (max-width: 1023px) {
            .desktop-sidebar {
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                height: 100dvh !important;
                z-index: 70 !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
        }

        @media (min-width: 1024px) {
            .desktop-sidebar {
                position: sticky !important;
                top: 0 !important;
                height: 100vh !important;
                height: 100dvh !important;
                flex-shrink: 0 !important;
                align-self: flex-start !important;
                transform: none !important;
            }
        }

        /* Clean Table Styles */
        .clean-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.8125rem;
        }

        .clean-table th {
            background-color: #ffffff;
            color: #6b7280;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        .clean-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }

        .clean-table tr:hover td {
            background-color: #fafbfc;
        }

        .clean-table tr:last-child td {
            border-bottom: none;
        }

        /* Toast Popup */
        .admin-toast-popup {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: #ffffff;
            color: #111827;
            border: 1px solid var(--border-color);
            border-left: 4px solid #1572b8;
            border-radius: 10px;
            font-size: 0.825rem;
            font-weight: 500;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            opacity: 0;
            transform: translateY(12px);
            visibility: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            max-width: 420px;
        }

        .admin-toast-popup.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        /* Subtle Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen antialiased bg-[#f8fafc] text-[#111827]">

    <div class="min-h-screen flex flex-col lg:flex-row bg-[#f8fafc] admin-layout-wrapper" id="admin-layout-wrapper">

        <!-- Mobile Sidebar Backdrop -->
        <div id="mobile-sidebar-backdrop" onclick="toggleAdminSidebar()" class="fixed inset-0 z-40 bg-black/30 backdrop-blur-xs lg:hidden hidden"></div>

        <!-- =======================================================================
             DESKTOP LEFT SIDEBAR (Identical to Civitas Pointsale Style)
             ======================================================================= -->
        <aside id="admin-sidebar" class="desktop-sidebar fixed top-0 bottom-0 left-0 z-50 lg:sticky lg:top-0 lg:h-screen lg:shrink-0 transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0" aria-label="Navigasi Utama Admin">
            
            <div class="sidebar-top-container">
                <!-- 1. Header Row (Logo dengan Animasi Open Slide + Brand Name + Toggle Button) -->
                <div class="sidebar-header-row">
                    <div class="sidebar-brand-wrap">
                        <button type="button" class="sidebar-logo-btn" onclick="toggleDesktopSidebar()" title="Buka / Ciutkan Sidebar" aria-label="Buka / Ciutkan Sidebar">
                            <div class="sidebar-logo-container">
                                <div class="sidebar-logo-flipper">
                                    <div class="sidebar-logo-face sidebar-logo-face-logo">
                                        <picture>
                                            <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                                            <img src="{{ asset('images/logo.png') }}" alt="Logo Find UMB" class="sidebar-logo-img" width="32" height="32" loading="eager">
                                        </picture>
                                    </div>
                                    <div class="sidebar-logo-face sidebar-logo-face-icon">
                                        <i data-lucide="panel-left-open" class="sidebar-open-slide-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-title-link" title="Dashboard Administrator Find UMB">
                            <span class="sidebar-brand-name">Find<span>UMB</span></span>
                        </a>
                    </div>
                    <button type="button" class="sidebar-collapse-btn" onclick="toggleDesktopSidebar()" title="Ciutkan Sidebar" aria-label="Ciutkan Sidebar">
                        <i data-lucide="panel-left"></i>
                    </button>
                </div>

                <!-- 2. Navigasi Menu Utama -->
                <div class="sidebar-menu-section">
                    <div class="sidebar-section-title">Menu</div>
                    <nav class="sidebar-nav-list">
                        <!-- Beranda / Overview -->
                        <a href="#overview" onclick="switchAdminTab('overview')" id="nav-tab-overview" class="sidebar-nav-item active" title="Beranda Administrator">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="layout-grid"></i>
                                </div>
                                <span class="sidebar-item-label">Beranda</span>
                            </div>
                        </a>

                        <!-- Kelola Laporan -->
                        <a href="#reports" onclick="switchAdminTab('reports')" id="nav-tab-reports" class="sidebar-nav-item" title="Kelola Laporan">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon" style="position: relative;">
                                    <i data-lucide="compass"></i>
                                    <span class="sidebar-rail-notif-dot"></span>
                                </div>
                                <span class="sidebar-item-label">Kelola Laporan</span>
                            </div>
                            <span id="sidebar-reports-count" class="sidebar-badge-counter sidebar-badge-notif">28</span>
                        </a>

                        <!-- Aduan & Moderasi -->
                        <a href="#moderation" onclick="switchAdminTab('moderation')" id="nav-tab-moderation" class="sidebar-nav-item" title="Aduan &amp; Moderasi">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon" style="position: relative;">
                                    <i data-lucide="file-text"></i>
                                    <span class="sidebar-rail-notif-dot"></span>
                                </div>
                                <span class="sidebar-item-label">Aduan &amp; Moderasi</span>
                            </div>
                            <span id="sidebar-abuse-badge" class="sidebar-badge-counter sidebar-badge-notif">3</span>
                        </a>

                        <!-- Manajemen Pengguna -->
                        <a href="#users" onclick="switchAdminTab('users')" id="nav-tab-users" class="sidebar-nav-item" title="Manajemen Pengguna">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="users"></i>
                                </div>
                                <span class="sidebar-item-label">Manajemen Pengguna</span>
                            </div>
                        </a>

                        <!-- Log Audit Moderasi -->
                        <a href="#audit" onclick="switchAdminTab('audit')" id="nav-tab-audit" class="sidebar-nav-item" title="Log Audit Moderasi">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="history"></i>
                                </div>
                                <span class="sidebar-item-label">Log Audit Moderasi</span>
                            </div>
                        </a>

                        <!-- Kategori Barang -->
                        <a href="#categories" onclick="switchAdminTab('categories')" id="nav-tab-categories" class="sidebar-nav-item" title="Kategori Barang">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="layers"></i>
                                </div>
                                <span class="sidebar-item-label">Kategori Barang</span>
                            </div>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-divider"></div>

                <!-- 4. Navigasi Tambahan (Web Civitas) -->
                <div class="sidebar-secondary-group">
                    <a href="{{ route('home') }}" class="sidebar-nav-item" title="Kembali ke Halaman Civitas / Web Civitas">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon">
                                <i data-lucide="shield-check"></i>
                            </div>
                            <span class="sidebar-item-label">Web Civitas</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Bagian Bawah: Profile Pengguna (Card besar Laporkan Barang dikecualikan) -->
            <div class="sidebar-bottom-container">
                <div class="sidebar-footer">
                    <a href="javascript:void(0)" onclick="openAdminSettingsModal()" class="sidebar-user-card" title="Buka Profil Administrator">
                        <div class="sidebar-user-left">
                            <div class="sidebar-user-avatar">
                                <i data-lucide="user"></i>
                            </div>
                            <div class="sidebar-user-info">
                                <span class="sidebar-user-name" id="admin-user-display-name">Zidan Maulana</span>
                                <span class="sidebar-user-role" id="admin-user-display-email">Civitas Akademika UMB</span>
                            </div>
                        </div>
                        <i data-lucide="chevrons-up-down" class="sidebar-user-chevrons"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- =======================================================================
             MAIN AREA: Content (Top Navbar Removed on Desktop per Revision)
             ======================================================================= -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- Mobile-only Bar for Toggling Sidebar on Phones/Tablets -->
            <header class="lg:hidden h-14 bg-white border-b border-[#eaecf0] sticky top-0 z-30 flex items-center justify-between px-4">
                <div class="flex items-center gap-2.5">
                    <button type="button" onclick="toggleAdminSidebar()" class="p-1.5 text-[#6b7280] hover:text-[#111827] rounded-lg border border-[#eaecf0]" aria-label="Buka Menu Admin">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <span class="font-bold text-base text-[#1572b8] tracking-tight">Find<span class="text-[#0c487b]">UMB</span> Admin</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleAdminNotifications()" class="relative p-1.5 text-[#6b7280]">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <span id="header-notif-indicator" class="absolute top-1 right-1 w-2 h-2 rounded-full bg-rose-500"></span>
                    </button>
                    <a href="{{ route('home') }}" class="p-1.5 text-[#6b7280]" title="Web Civitas">
                        <i data-lucide="home" class="w-4 h-4"></i>
                    </a>
                </div>
            </header>

            <!-- Screen Reader & Compatibility Elements -->
            <div class="sr-only" aria-hidden="true">
                <span id="top-breadcrumb-title">Overview</span>
                <span>Mode Admin Aktif</span>
                <input type="text" id="admin-global-search" oninput="if(typeof handleAdminGlobalSearch==='function') handleAdminGlobalSearch(this.value)">
                <a href="{{ route('home') }}">Kembali ke Halaman Civitas</a>
                <div id="admin-user-dropdown" class="hidden"></div>
            </div>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-7 max-w-[1400px] w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Floating Global Toast -->
    <div id="admin-toast" class="admin-toast-popup" role="status" aria-live="polite">
        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#1572b8] shrink-0"></i>
        <span id="admin-toast-message" class="truncate">Operasi berhasil dilakukan.</span>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js for High-End Eye-Catching Spline Line Charts -->
    <script src="{{ asset('js/chart.umd.min.js') }}"></script>
    <script>
        if (typeof Chart === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
        }
    </script>
    <script>
        lucide.createIcons();

        function toggleDesktopSidebar() {
            const wrapper = document.getElementById('admin-layout-wrapper');
            if (wrapper) {
                wrapper.classList.toggle('sidebar-collapsed');
                const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
                localStorage.setItem('findumb_admin_sidebar_collapsed', isCollapsed ? '1' : '0');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('findumb_admin_sidebar_collapsed') === '1';
            const wrapper = document.getElementById('admin-layout-wrapper');
            if (wrapper && isCollapsed) {
                wrapper.classList.add('sidebar-collapsed');
            }
        });

        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function toggleAdminDropdownMenu() {
            document.getElementById('admin-user-dropdown').classList.toggle('hidden');
        }

        window.addEventListener('click', (e) => {
            const dropdown = document.getElementById('admin-user-dropdown');
            if (dropdown && !dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleAdminDropdownMenu()"]')) {
                dropdown.classList.add('hidden');
            }
        });

        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('admin-global-search');
                if (searchInput) { searchInput.focus(); searchInput.select(); }
            }
        });

        function showAdminToast(msg) {
            const toast = document.getElementById('admin-toast');
            const msgEl = document.getElementById('admin-toast-message');
            msgEl.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }

        function toggleAdminNotifications() {
            showAdminToast('Terdapat 3 aduan civitas baru yang memerlukan tinjauan admin.');
            switchAdminTab('moderation');
        }

        function handleAdminSignOut() {
            if (confirm('Keluar dari Mode Administrator?')) {
                window.location.href = "{{ route('home') }}";
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
