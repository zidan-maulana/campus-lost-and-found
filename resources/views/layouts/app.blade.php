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

    <!-- Google Fonts: Inter, Nunito, Quicksand, & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Nunito:wght@700;800;900&family=Quicksand:wght@600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

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
        /* Mobile defaults (Hidden on mobile) */
        .desktop-sidebar,
        .header-desktop-search,
        .desktop-profile-btn,
        .sidebar-collapse-btn,
        .sidebar-rail-sparkle-btn,
        .sidebar-rail-expand-btn,
        .messages-filter-dropdown-wrapper {
            display: none;
        }

        .app-layout-wrapper {
            display: contents;
        }

        /* Responsive Desktop Breakpoints (min-width: 1024px) */
        @media (min-width: 1024px) {
            body {
                display: block;
                width: 100%;
                background-color: #ffffff;
            }

            .app-layout-wrapper {
                display: flex !important;
                width: 100%;
                min-height: 100vh;
                background-color: #ffffff;
            }

            /* Desktop Left Sidebar (Reference Pointsale Concept) */
            .desktop-sidebar {
                display: flex !important;
                flex-direction: column;
                justify-content: space-between;
                width: 260px;
                min-width: 260px;
                max-width: 260px;
                height: 100vh;
                position: sticky;
                top: 0;
                background: #ffffff;
                border-right: 1px solid #f1f5f9;
                padding: 1.25rem 0.95rem;
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
            }

            .desktop-sidebar::-webkit-scrollbar {
                display: none;
            }

            .sidebar-top-container {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            /* 1. Header Row */
            .sidebar-header-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 0.25rem;
                min-height: 40px;
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

            /* Keyframes: Transisi logo dengan icon open slide setiap 4 detik */
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
                padding: 0.6rem 0.75rem;
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

            /* Active Item Styling: Shape biru sesuai warna card besar beranda, tanpa shadow */
            .sidebar-nav-item.active {
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
                border: none !important;
                box-shadow: none !important; /* Hapus shadow pada button option yang dipilih */
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
                font-weight: 700 !important;
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
                font-size: 0.7rem;
                font-weight: 700;
                color: #64748b;
                min-width: 20px;
                height: 18px;
                padding: 1.5px 0.42rem 0 0.42rem;
                border-radius: 9999px;
                background: #f1f5f9;
                line-height: 1;
                box-sizing: border-box;
                text-align: center;
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

            /* Container bawah sidebar untuk card & account */
            .sidebar-bottom-container {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
                margin-top: auto;
            }

            /* 3. Card Lapor Barang (Posisi tepat diatas account profile): Shape biru senada */
            .sidebar-card-widget {
                width: 100%;
                margin: 1.15rem 0 0 0; /* Memberi jarak lega dari button Pusat Bantuan */
            }

            .sidebar-card-expanded {
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
                border: none !important;
                border-radius: 16px;
                padding: 1rem 0.95rem;
                display: flex;
                flex-direction: column;
                gap: 0.6rem;
                box-sizing: border-box;
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(12, 85, 156, 0.16) !important;
            }

            .sidebar-card-header {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Circle Shape Icon + sejajar dan balance dengan title */
            .sidebar-card-plus-circle {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #ffffff;
                color: #0c559c;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            }

            .sidebar-card-plus-circle svg,
            .sidebar-card-plus-circle .lucide {
                width: 9.5px;
                height: 9.5px;
                stroke-width: 2.8;
                color: #0c559c;
                stroke: #0c559c;
            }

            .sidebar-card-main-title {
                font-size: 0.9rem;
                font-weight: 700;
                color: #ffffff;
                line-height: 1.25;
                margin: 0;
            }

            /* Deskripsi dibatasi maksimal 2 baris */
            .sidebar-card-desc {
                font-size: 0.75rem;
                color: rgba(255, 255, 255, 0.92);
                line-height: 1.4;
                margin: 0;
                white-space: normal;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .sidebar-card-action-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.45rem;
                background: #ffffff !important;
                color: #0c559c !important;
                border: none !important;
                border-radius: 10px; /* Dikurangi agar seimbang dengan card utama (16px) */
                padding: 0.55rem 0.85rem;
                font-family: var(--font-main);
                font-size: 0.825rem;
                font-weight: 700;
                cursor: pointer;
                transition: var(--transition);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                width: 100%;
            }

            .sidebar-card-action-btn:hover {
                background: #f8fafc !important;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
            }

            .sidebar-card-action-btn svg, .sidebar-card-action-btn .lucide {
                width: 15px;
                height: 15px;
                stroke-width: 2.5;
                color: #0c559c;
                stroke: #0c559c;
            }

            /* Single Sparkle button for collapsed rail */
            .sidebar-rail-sparkle-btn {
                display: none;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
                border: none !important;
                color: #ffffff !important;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: var(--transition);
                box-shadow: 0 4px 12px rgba(12, 85, 156, 0.28) !important;
                margin: 0.35rem auto;
            }

            .sidebar-rail-sparkle-btn:hover {
                transform: scale(1.06);
                box-shadow: 0 6px 16px rgba(12, 85, 156, 0.35) !important;
            }

            .sidebar-rail-sparkle-btn svg, .sidebar-rail-sparkle-btn .lucide {
                width: 20px;
                height: 20px;
                stroke-width: 2.2;
                color: #ffffff !important;
                stroke: #ffffff !important;
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

            /* Main Content Shell Width */
            .app-shell {
                flex: 1;
                min-width: 0;
                width: calc(100% - 260px);
                max-width: 100%;
                background-color: #ffffff;
                box-shadow: none;
                padding-bottom: 3.5rem;
                margin: 0;
                min-height: 100vh;
                transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            /* Sembunyikan bottom navigation bar mobile pada desktop */
            .bottom-nav-wrapper {
                display: none !important;
            }

            /* Desktop Top Header Full Width */
            .app-header {
                width: 100%;
                padding: 1rem clamp(1.5rem, 3.5vw, 3.5rem);
                background: #ffffff;
                border-bottom: none !important; /* Hapus bottom line persis dibawah search bar */
                position: sticky;
                top: 0;
                z-index: 50;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
            }

            /* Hide duplicate brand logo in header on desktop */
            .app-header .brand-logo-area {
                display: none !important;
            }

            /* Hide top header on Desktop Home & Search pages because they have dedicated search bars */
            body.page-home .app-header,
            body.page-search .app-header {
                display: none !important;
            }

            /* Collapsed State (Mini-rail 64px persis seperti di referensi) */
            .app-layout-wrapper.sidebar-collapsed .desktop-sidebar,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .desktop-sidebar {
                width: 64px !important;
                min-width: 64px !important;
                max-width: 64px !important;
                padding: 1.15rem 0.4rem !important;
                align-items: center;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-brand-name,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-brand-name,
            .app-layout-wrapper.sidebar-collapsed .sidebar-brand-title-link,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-brand-title-link,
            .app-layout-wrapper.sidebar-collapsed .sidebar-collapse-btn,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-collapse-btn,
            .app-layout-wrapper.sidebar-collapsed .sidebar-item-label,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-item-label,
            .app-layout-wrapper.sidebar-collapsed .sidebar-badge-counter,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-badge-counter,
            .app-layout-wrapper.sidebar-collapsed .sidebar-section-title,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-section-title,
            .app-layout-wrapper.sidebar-collapsed .sidebar-card-expanded,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-card-expanded,
            .app-layout-wrapper.sidebar-collapsed .sidebar-user-info,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-user-info,
            .app-layout-wrapper.sidebar-collapsed .sidebar-user-chevrons,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-user-chevrons {
                display: none !important;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-header-row,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-header-row {
                justify-content: center;
                padding: 0;
            }

            /* Transition animation pada logo HANYA berlaku ketika sidebar tertutup (collapsed) */
            .app-layout-wrapper.sidebar-collapsed .sidebar-logo-face-logo,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-logo-face-logo {
                animation: logoSlideOut 4s ease-in-out infinite;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-logo-face-icon,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-logo-face-icon {
                animation: iconSlideIn 4s ease-in-out infinite;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-logo-btn:hover .sidebar-logo-face-logo,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-logo-btn:hover .sidebar-logo-face-logo {
                animation: none;
                opacity: 0;
                transform: scale(0.7);
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-logo-btn:hover .sidebar-logo-face-icon,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-logo-btn:hover .sidebar-logo-face-icon {
                animation: none;
                opacity: 1;
                transform: scale(1.08);
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-logo-btn,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-logo-btn {
                margin: 0 auto;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-brand,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-brand {
                padding: 0;
                justify-content: center;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-nav-item,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-nav-item {
                width: 44px;
                height: 44px;
                padding: 0;
                justify-content: center;
                margin: 0 auto;
                border-radius: 12px;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-nav-item.active,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-nav-item.active {
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
                border: none !important;
                box-shadow: none !important; /* Hapus shadow */
                color: #ffffff !important;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-nav-item.active .sidebar-item-icon,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-nav-item.active .sidebar-item-icon {
                color: #ffffff !important;
            }

            /* Indikator notifikasi merah pada mini-rail */
            .sidebar-rail-notif-dot {
                display: none;
                position: absolute;
                top: -2px;
                right: -2px;
                width: 7px;
                height: 7px;
                background-color: #ef4444;
                border-radius: 50%;
                border: 1.5px solid #ffffff;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-rail-notif-dot,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-rail-notif-dot {
                display: block;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-rail-sparkle-btn,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-rail-sparkle-btn {
                display: flex !important;
            }

            .app-layout-wrapper.sidebar-collapsed .sidebar-user-card,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .sidebar-user-card {
                width: 44px;
                height: 44px;
                padding: 0;
                justify-content: center;
                margin: 0 auto;
                background: transparent;
                border-color: transparent;
            }

            .app-layout-wrapper.sidebar-collapsed .app-shell,
            .desktop-sidebar-pre-collapsed .app-layout-wrapper .app-shell {
                width: calc(100% - 64px) !important;
                max-width: calc(100% - 64px) !important;
            }

            /* Header Desktop Search Bar Full Width */
            .header-desktop-search {
                display: flex !important;
                align-items: center;
                gap: 0.75rem;
                flex: 1;
                width: 100% !important;
                max-width: 100% !important;
                height: 48px;
                background: #f8fafc;
                border: 1.5px solid #e2e8f0;
                border-radius: 14px;
                padding: 0 0.65rem 0 1.15rem;
                transition: var(--transition);
                box-sizing: border-box;
            }

            .header-desktop-search:focus-within {
                background: #ffffff;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
            }

            .header-search-icon {
                color: #94a3b8;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .header-search-icon svg, .header-search-icon .lucide {
                width: 20px;
                height: 20px;
                stroke-width: 2.2;
            }

            .header-search-input {
                flex: 1;
                border: none;
                background: transparent;
                font-family: var(--font-main);
                font-size: 0.925rem;
                color: #0f172a;
                outline: none;
                height: 100%;
            }

            .header-search-input::placeholder {
                color: #94a3b8;
                font-size: 0.9rem;
            }

            /* Calendar button inside header search: gunakan biru yang sama seperti button sidebar ketika terpilih */
            .header-search-calendar-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%) !important;
                border: none !important;
                border-radius: 10px;
                color: #ffffff !important;
                cursor: pointer;
                transition: var(--transition);
                padding: 0;
                flex-shrink: 0;
                box-shadow: none !important;
            }

            .header-search-calendar-btn:hover {
                opacity: 0.92;
                transform: scale(1.02);
            }

            .header-search-calendar-btn svg, .header-search-calendar-btn .lucide {
                width: 17px;
                height: 17px;
                stroke-width: 2.2;
                color: #ffffff !important;
                stroke: #ffffff !important;
            }

            /* Kurangi radius dari ketiga icon disebelah search bar agar balance dengan radius search bar (14px) */
            .app-header .icon-circle-btn {
                border-radius: 12px !important;
            }

            .app-header .icon-circle-btn .badge-notification-dot {
                top: 8px;
                right: 8px;
            }

            .desktop-profile-btn {
                display: flex !important;
            }

            /* Header Messages Clear Search Button */
            .header-messages-clear-btn {
                background: transparent;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 0.3rem;
                border-radius: 50%;
                transition: var(--transition);
                margin-right: 0.25rem;
                flex-shrink: 0;
            }

            .header-messages-clear-btn:hover {
                color: #0f172a;
                background: #e2e8f0;
            }

            .header-messages-clear-btn svg, .header-messages-clear-btn .lucide {
                width: 17px;
                height: 17px;
                stroke-width: 2.2;
            }

            /* Messages Header Filter Dropdown (Menggantikan Icon Notif & Akun pada Pesan Masuk) */
            .messages-filter-dropdown-wrapper {
                position: relative;
                display: inline-block !important;
            }

            .messages-filter-dropdown-btn {
                display: inline-flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                height: 48px;
                padding: 0 1.15rem;
                background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
                border: 1.5px solid transparent;
                border-radius: 14px;
                font-family: var(--font-main);
                font-size: 0.9rem;
                font-weight: 600;
                color: #ffffff;
                cursor: pointer;
                transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
                white-space: nowrap;
                user-select: none;
                box-shadow: none !important;
                box-sizing: border-box;
            }

            .messages-filter-dropdown-btn:hover {
                background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
                box-shadow: none !important;
                transform: translateY(-0.5px);
            }

            .messages-filter-dropdown-btn.open {
                background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
                box-shadow: none !important;
            }

            .dropdown-btn-content {
                display: flex;
                align-items: center;
                gap: 0.55rem;
            }

            .dropdown-filter-text {
                font-size: 0.9rem;
                font-weight: 600;
                color: #ffffff;
            }

            .dropdown-filter-badge {
                font-size: 0.725rem;
                font-weight: 700;
                padding: 0.12rem 0.52rem;
                border-radius: var(--radius-pill);
                background-color: #ffffff !important;
                color: #0c559c !important;
                line-height: 1.2;
                transition: all 0.2s ease;
            }

            .messages-filter-dropdown-btn:hover .dropdown-filter-badge,
            .messages-filter-dropdown-btn.open .dropdown-filter-badge {
                background-color: #ffffff !important;
                color: #0c559c !important;
            }

            .dropdown-chevron-icon {
                width: 16px;
                height: 16px;
                stroke-width: 2.4;
                color: #ffffff;
                stroke: #ffffff;
                opacity: 0.92;
                transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
            }

            .messages-filter-dropdown-btn.open .dropdown-chevron-icon {
                transform: rotate(180deg);
                opacity: 1;
            }

            /* Dropdown Menu Popup */
            .messages-filter-menu {
                position: absolute;
                top: calc(100% + 8px);
                right: 0;
                min-width: 220px;
                background: #ffffff;
                border: 1.5px solid #e2e8f0;
                border-radius: 16px;
                padding: 6px;
                box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.14), 0 4px 12px -2px rgba(15, 23, 42, 0.06);
                display: none;
                flex-direction: column;
                gap: 3px;
                z-index: 120;
                transform-origin: top right;
                animation: filterMenuIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }

            @keyframes filterMenuIn {
                from {
                    opacity: 0;
                    transform: translateY(-8px) scale(0.96);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .messages-filter-menu.open {
                display: flex;
            }

            .messages-filter-option {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: 0.6rem 0.8rem;
                border-radius: 10px;
                border: none;
                background: transparent;
                cursor: pointer;
                font-family: var(--font-main);
                font-size: 0.825rem;
                font-weight: 500;
                color: #334155;
                transition: all 0.15s ease;
                width: 100%;
                text-align: left;
            }

            .messages-filter-option:hover {
                background: #f8fafc;
                color: #0f172a;
            }

            .messages-filter-option.active {
                background: #f0f7ff;
                color: var(--primary-dark);
                font-weight: 600;
            }

            .filter-option-left {
                display: flex;
                align-items: center;
                gap: 0.6rem;
            }

            .option-icon-wrap {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .option-icon-wrap svg, .option-icon-wrap .lucide {
                width: 15px;
                height: 15px;
                stroke-width: 2.2;
            }

            .option-icon-wrap.option-all {
                background: #f1f5f9;
                color: #475569;
            }

            .option-icon-wrap.option-active {
                background: #fef3c7;
                color: #b45309;
            }

            .option-icon-wrap.option-completed {
                background: #dcfce7;
                color: #15803d;
            }

            .messages-filter-option.active .option-icon-wrap.option-all {
                background: var(--primary-light);
                color: var(--primary);
            }

            .filter-option-right {
                display: flex;
                align-items: center;
                gap: 0.45rem;
            }

            .option-count {
                font-size: 0.7rem;
                font-weight: 700;
                padding: 0.12rem 0.5rem;
                border-radius: var(--radius-pill);
                background-color: #ef4444 !important;
                color: #ffffff !important;
                min-width: 18px;
                text-align: center;
                line-height: 1.2;
            }

            .option-count.count-active,
            .option-count.count-completed {
                background-color: #ef4444 !important;
                color: #ffffff !important;
            }

            /* Desktop Centered Modals (transform from mobile bottom sheet to sleek center modal) */
            .modal-overlay {
                align-items: center;
                padding: 1.5rem;
            }

            .modal-sheet {
                border-radius: 20px;
                max-width: 520px;
                transform: scale(0.95);
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            }

            .modal-overlay.active .modal-sheet {
                transform: scale(1);
            }

            .modal-pill-drag {
                display: none;
            }

            .guide-modal-sheet {
                max-width: 620px;
                border-radius: 20px;
            }
        }

        /* ----------------------------------------------------
           Global Image Detail Lightbox Modal (Lihat Detail Foto)
           ---------------------------------------------------- */
        .image-lightbox-backdrop {
            position: fixed;
            inset: 0;
            z-index: 300;
            background: rgba(10, 15, 29, 0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.24s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.24s;
            padding: 1.5rem;
        }

        .image-lightbox-backdrop.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .image-lightbox-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1000px;
            height: 100%;
            max-height: 92vh;
            transform: scale(0.96);
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .image-lightbox-backdrop.active .image-lightbox-wrapper {
            transform: scale(1);
        }

        .image-lightbox-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.85rem;
            flex-shrink: 0;
        }

        .image-lightbox-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: #f8fafc;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .image-lightbox-title-wrap svg, .image-lightbox-title-wrap .lucide {
            width: 18px;
            height: 18px;
            color: #38bdf8;
        }

        .image-lightbox-title {
            max-width: 520px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .image-lightbox-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .image-lightbox-btn {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #ffffff;
            height: 38px;
            padding: 0 0.95rem;
            border-radius: 9999px;
            font-family: var(--font-main);
            font-size: 0.825rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .image-lightbox-btn:hover {
            background: rgba(255, 255, 255, 0.26);
            border-color: rgba(255, 255, 255, 0.45);
            transform: translateY(-1px);
        }

        .image-lightbox-btn svg, .image-lightbox-btn .lucide {
            width: 16px;
            height: 16px;
            stroke-width: 2.2;
        }

        .image-lightbox-btn-close {
            width: 38px;
            padding: 0;
            justify-content: center;
            border-radius: 50%;
        }

        .image-lightbox-btn-close:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .image-lightbox-stage {
            flex: 1;
            width: 100%;
            min-height: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            cursor: zoom-in;
            background: rgba(15, 23, 42, 0.4);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .image-lightbox-stage.is-zoomed {
            cursor: zoom-out;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .image-lightbox-stage::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .image-lightbox-stage::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }

        .image-lightbox-stage::-webkit-scrollbar-track {
            background: transparent;
        }

        .image-lightbox-stage::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 9999px;
        }

        .image-lightbox-stage::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .image-lightbox-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
        }

        .image-lightbox-stage.is-zoomed .image-lightbox-img {
            transform: scale(2);
            cursor: zoom-out;
            max-width: none;
            max-height: none;
        }

        .image-lightbox-footer {
            padding-top: 0.75rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.775rem;
            flex-shrink: 0;
        }

        .image-lightbox-footer kbd {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 4px;
            padding: 0.1rem 0.4rem;
            font-family: inherit;
            font-size: 0.75rem;
            color: #ffffff;
        }

        .desktop-detail-zoom-pill {
            position: absolute;
            bottom: 12px;
            right: 14px;
            z-index: 4;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .desktop-detail-hero-box:hover .desktop-detail-zoom-pill,
        .detail-hero-wrapper:hover .desktop-detail-zoom-pill {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(255, 255, 255, 0.45);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.28);
        }

        .desktop-detail-zoom-pill svg, .desktop-detail-zoom-pill .lucide {
            width: 13.5px;
            height: 13.5px;
            stroke-width: 2.2;
        }
    </style>
    @stack('styles')
    <script>
        (function() {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const queryExpanded = urlParams.get('sidebar') === 'expanded' || urlParams.get('expanded') === '1';
                if (window.innerWidth >= 1024 && !queryExpanded && localStorage.getItem('findumb_desktop_sidebar_collapsed') !== '0') {
                    document.documentElement.classList.add('desktop-sidebar-pre-collapsed');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body class="{{ (request()->routeIs('home') || request()->is('/')) ? 'page-home' : '' }} {{ (request()->routeIs('search') || request()->is('search*')) ? 'page-search' : '' }}">
    <div class="app-layout-wrapper {{ request()->get('sidebar') === 'expanded' || request()->get('expanded') == '1' ? '' : 'sidebar-collapsed' }}">
        <!-- Desktop Sidebar (Visible on desktop min-width: 1024px - Pointsale Concept) -->
        <aside class="desktop-sidebar" id="desktop-sidebar" aria-label="Sidebar Navigasi Desktop">
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
                        <a href="{{ url('/') }}" class="sidebar-brand-title-link" title="Beranda Find UMB">
                            <span class="sidebar-brand-name">Find<span>UMB</span></span>
                        </a>
                    </div>
                    <button type="button" class="sidebar-collapse-btn" onclick="toggleDesktopSidebar()" title="Ciutkan Sidebar" aria-label="Ciutkan Sidebar">
                        <i data-lucide="panel-left"></i>
                    </button>
                </div>

                <!-- 2. Komunikasi Cepat (Pesan Masuk & Notifikasi dengan Indikator Merah) -->
                <div class="sidebar-links-group">
                    <a href="{{ route('messages') }}" class="sidebar-nav-item {{ request()->is('messages*') ? 'active' : '' }}" title="Pesan Masuk Civitas (12 pesan)">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon" style="position: relative;">
                                <i data-lucide="inbox"></i>
                                <span class="sidebar-rail-notif-dot"></span>
                            </div>
                            <span class="sidebar-item-label">Pesan Masuk</span>
                        </div>
                        <span class="sidebar-badge-counter sidebar-badge-notif">12</span>
                    </a>
                    <a href="{{ route('notifications') }}" class="sidebar-nav-item {{ request()->is('notifications*') ? 'active' : '' }}" title="Notifikasi (15+ pemberitahuan)">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon" style="position: relative;">
                                <i data-lucide="bell"></i>
                                <span class="sidebar-rail-notif-dot"></span>
                            </div>
                            <span class="sidebar-item-label">Notifikasi</span>
                        </div>
                        <span class="sidebar-badge-counter sidebar-badge-notif">15+</span>
                    </a>
                </div>

                <div class="sidebar-divider"></div>

                <!-- 3. Navigasi Menu Utama -->
                <div class="sidebar-menu-section">
                    <div class="sidebar-section-title">Menu</div>
                    <nav class="sidebar-nav-list">
                        <!-- Beranda -->
                        <a href="{{ url('/') }}" class="sidebar-nav-item {{ request()->is('/') ? 'active' : '' }}" title="Beranda">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="layout-grid"></i>
                                </div>
                                <span class="sidebar-item-label">Beranda</span>
                            </div>
                        </a>

                        <!-- Cari Laporan -->
                        <a href="{{ route('search') }}" class="sidebar-nav-item {{ request()->is('search*') ? 'active' : '' }}" title="Cari Laporan">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="compass"></i>
                                </div>
                                <span class="sidebar-item-label">Cari Laporan</span>
                            </div>
                        </a>

                        <!-- Laporan Saya -->
                        <a href="{{ route('my.reports') }}" class="sidebar-nav-item {{ request()->is('my-reports*') ? 'active' : '' }}" title="Laporan Saya">
                            <div class="sidebar-item-left">
                                <div class="sidebar-item-icon">
                                    <i data-lucide="file-text"></i>
                                </div>
                                <span class="sidebar-item-label">Laporan Saya</span>
                            </div>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-divider"></div>

                <!-- 4. Navigasi Tambahan (Buku Panduan, Pengaturan, Pusat Bantuan, Panel Admin) -->
                <div class="sidebar-secondary-group">
                    <button type="button" class="sidebar-nav-item" onclick="openGuideModal()" title="Buku Panduan &amp; Informasi">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon">
                                <i data-lucide="book-open"></i>
                            </div>
                            <span class="sidebar-item-label">Buku Panduan</span>
                        </div>
                    </button>
                    <a href="{{ route('settings') }}" class="sidebar-nav-item {{ request()->is('settings*') ? 'active' : '' }}" title="Pengaturan Sistem">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon">
                                <i data-lucide="settings"></i>
                            </div>
                            <span class="sidebar-item-label">Pengaturan</span>
                        </div>
                    </a>
                    <button type="button" class="sidebar-nav-item" onclick="openGuideModal()" title="Pusat Bantuan &amp; Kontak Pos Keamanan">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon">
                                <i data-lucide="help-circle"></i>
                            </div>
                            <span class="sidebar-item-label">Pusat Bantuan</span>
                        </div>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->is('admin*') ? 'active' : '' }}" title="Dashboard Administrator FindUMB">
                        <div class="sidebar-item-left">
                            <div class="sidebar-item-icon">
                                <i data-lucide="shield-check"></i>
                            </div>
                            <span class="sidebar-item-label">Panel Admin</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Bagian Bawah: Card Lapor Barang (Tepat di atas account profile) & Profile Pengguna -->
            <div class="sidebar-bottom-container">
                <!-- 5. Card Lapor Barang Widget -->
                <div class="sidebar-card-widget">
                    <!-- Expanded Card -->
                    <div class="sidebar-card-expanded">
                        <div class="sidebar-card-header">
                            <div class="sidebar-card-plus-circle" aria-hidden="true">
                                <i data-lucide="plus"></i>
                            </div>
                            <h4 class="sidebar-card-main-title">Laporkan Barang</h4>
                        </div>
                        <p class="sidebar-card-desc">Bantu civitas UMB amankan dan temukan barang hilang.</p>
                        <button type="button" class="sidebar-card-action-btn" onclick="openReportModal(event)">
                            <span>Buat Laporan</span>
                        </button>
                    </div>

                    <!-- Collapsed Single Sparkle Button (Circle) -->
                    <button type="button" class="sidebar-rail-sparkle-btn" onclick="openReportModal(event)" title="Laporkan Barang">
                        <i data-lucide="plus"></i>
                    </button>
                </div>

                <!-- 6. Footer User Profile Card -->
                <div class="sidebar-footer">
                    <a href="{{ route('profile') }}" class="sidebar-user-card" title="Buka Profil Saya">
                        <div class="sidebar-user-left">
                            <div class="sidebar-user-avatar">
                                <i data-lucide="user"></i>
                            </div>
                            <div class="sidebar-user-info">
                                <span class="sidebar-user-name">Zidan Maulana</span>
                                <span class="sidebar-user-role">Civitas Akademika UMB</span>
                            </div>
                        </div>
                        <i data-lucide="chevrons-up-down" class="sidebar-user-chevrons"></i>
                    </a>
                </div>
            </div>
        </aside>

        <div class="app-shell @yield('app_shell_class')" id="app">
            @hasSection('header')
                @yield('header')
            @else
                <!-- Top App Header (Mobile & Desktop) -->
                <header class="app-header">
                    <a href="{{ url('/') }}" class="brand-logo-area" id="brand-logo">
                        <picture>
                            <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Find UMB" class="brand-logo-img" width="38" height="38" loading="eager">
                        </picture>
                        <div class="brand-name">Find<span>UMB</span></div>
                    </a>

                    <!-- Desktop Top Search Bar (Menggantikan Navigasi di Header pada Desktop) -->
                    @if(request()->routeIs('messages') || request()->is('messages*'))
                        <div class="header-desktop-search header-messages-search" id="header-desktop-search">
                            <span class="header-search-icon">
                                <i data-lucide="search"></i>
                            </span>
                            <input type="text" id="desktop-messages-search-input" class="header-search-input" placeholder="Cari percakapan, nama, atau barang..." autocomplete="off">
                            <button type="button" class="header-messages-clear-btn" id="btn-clear-desktop-messages" aria-label="Hapus Pencarian" style="display: none;" onclick="clearDesktopMessagesSearch()">
                                <i data-lucide="x"></i>
                            </button>
                        </div>
                    @else
                        <form action="{{ route('search') }}" method="GET" class="header-desktop-search" id="header-desktop-search">
                            <span class="header-search-icon">
                                <i data-lucide="search"></i>
                            </span>
                            <input type="text" name="q" id="header-search-input" class="header-search-input" placeholder="Cari barang hilang atau temuan di kampus..." autocomplete="off">
                            <button type="button" class="search-calendar-btn header-search-calendar-btn" aria-label="Filter Bulan" title="Filter Bulan" onclick="window.location.href='{{ url('/search?open_month=1') }}';">
                                <i data-lucide="calendar"></i>
                            </button>
                        </form>
                    @endif

                    <div class="header-actions">
                        @if(request()->routeIs('messages') || request()->is('messages*'))
                            <!-- Dropdown Option Button (Menggantikan Icon Notif & Akun pada Pesan Masuk) -->
                            <div class="messages-filter-dropdown-wrapper" id="messages-filter-dropdown-wrapper">
                                <button type="button" 
                                        class="messages-filter-dropdown-btn" 
                                        id="btn-messages-filter-dropdown" 
                                        aria-haspopup="true" 
                                        aria-expanded="false" 
                                        onclick="toggleMessagesFilterDropdown(event)">
                                    <span class="dropdown-btn-content">
                                        <span class="dropdown-filter-text" id="messages-dropdown-label">Semua</span>
                                        <span class="dropdown-filter-badge" id="messages-dropdown-badge">5</span>
                                    </span>
                                    <i data-lucide="chevron-down" class="dropdown-chevron-icon" id="messages-dropdown-chevron"></i>
                                </button>

                                <div class="messages-filter-menu" id="messages-filter-menu" role="menu">
                                    <button type="button" 
                                            class="messages-filter-option active" 
                                            data-filter="all" 
                                            role="menuitem" 
                                            onclick="selectMessagesFilter('all', 'Semua')">
                                        <div class="filter-option-left">
                                            <span class="option-icon-wrap option-all">
                                                <i data-lucide="layers"></i>
                                            </span>
                                            <span class="option-label">Semua</span>
                                        </div>
                                        <div class="filter-option-right">
                                            <span class="option-count" id="dropdown-count-all">5</span>
                                        </div>
                                    </button>

                                    <button type="button" 
                                            class="messages-filter-option" 
                                            data-filter="active" 
                                            role="menuitem" 
                                            onclick="selectMessagesFilter('active', 'Klaim Aktif')">
                                        <div class="filter-option-left">
                                            <span class="option-icon-wrap option-active">
                                                <i data-lucide="handshake"></i>
                                            </span>
                                            <span class="option-label">Klaim Aktif</span>
                                        </div>
                                        <div class="filter-option-right">
                                            <span class="option-count count-active" id="dropdown-count-active">3</span>
                                        </div>
                                    </button>

                                    <button type="button" 
                                            class="messages-filter-option" 
                                            data-filter="completed" 
                                            role="menuitem" 
                                            onclick="selectMessagesFilter('completed', 'Selesai')">
                                        <div class="filter-option-left">
                                            <span class="option-icon-wrap option-completed">
                                                <i data-lucide="check-circle-2"></i>
                                            </span>
                                            <span class="option-label">Selesai</span>
                                        </div>
                                        <div class="filter-option-right">
                                            <span class="option-count count-completed" id="dropdown-count-completed">2</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @else
                            @if(request()->routeIs('home', 'search') || request()->is('/') || request()->is('search*'))
                            <button type="button" class="icon-circle-btn" id="btn-guide-info" onclick="openGuideModal()" aria-label="Buku Panduan &amp; Informasi" title="Buku Panduan &amp; Informasi">
                                <i data-lucide="book-open" style="width: 19px; height: 19px;"></i>
                            </button>
                            @endif
                            <a href="{{ route('notifications') }}" class="icon-circle-btn" id="btn-notification" aria-label="Notifikasi" style="text-decoration: none;" title="Pusat Notifikasi">
                                <i data-lucide="bell" style="width: 19px; height: 19px;"></i>
                                <span class="badge-notification-dot" id="header-notif-dot"></span>
                            </a>
                            <a href="{{ route('profile') }}" class="icon-circle-btn desktop-profile-btn" id="btn-desktop-profile" aria-label="Profil Pengguna" title="Profil Saya" style="text-decoration: none;">
                                <i data-lucide="user" style="width: 19px; height: 19px;"></i>
                            </a>
                        @endif
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

        <!-- Global Image Detail Lightbox Modal (Lihat Detail Foto Penuh) -->
        <div class="image-lightbox-backdrop" id="modal-image-lightbox" onclick="closeImageLightboxOnBackdrop(event)" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="image-lightbox-wrapper" onclick="event.stopPropagation()">
                <div class="image-lightbox-header">
                    <div class="image-lightbox-title-wrap">
                        <i data-lucide="image"></i>
                        <span class="image-lightbox-title" id="lightbox-image-title">Detail Foto Barang</span>
                    </div>
                    <div class="image-lightbox-actions">
                        <button type="button" class="image-lightbox-btn" id="lightbox-btn-zoom" onclick="toggleLightboxZoom()" title="Perbesar / Perkecil" aria-label="Perbesar / Perkecil">
                            <i data-lucide="zoom-in" id="lightbox-zoom-icon"></i>
                            <span id="lightbox-zoom-label">Perbesar</span>
                        </button>
                        <button type="button" class="image-lightbox-btn image-lightbox-btn-close" onclick="closeImageLightbox()" title="Tutup (Esc)" aria-label="Tutup Pratinjau Foto">
                            <i data-lucide="x"></i>
                        </button>
                    </div>
                </div>

                <div class="image-lightbox-stage" id="lightbox-stage" onclick="toggleLightboxZoom()" title="Klik untuk memperbesar / memperkecil">
                    <img src="" alt="Detail Foto Barang" class="image-lightbox-img" id="lightbox-img">
                </div>

                <div class="image-lightbox-footer">
                    <span>Klik foto untuk memperbesar • Tekan <kbd>Esc</kbd> atau klik luar untuk menutup</span>
                </div>
            </div>
        </div>
    </div>
    </div><!-- end .app-layout-wrapper -->

    <!-- Lucide Icons Library (Local with CDN fallback) -->
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <script>
        if (typeof lucide === 'undefined') {
            document.write('<script src="https://unpkg.com/lucide@latest"><\/script>');
        }
    </script>
    <script>
        // Global Image Detail Lightbox Modal Handlers
        function openImageLightbox(src, title) {
            if (!src) return;
            const lightbox = document.getElementById('modal-image-lightbox');
            const img = document.getElementById('lightbox-img');
            const titleEl = document.getElementById('lightbox-image-title');
            const stage = document.getElementById('lightbox-stage');

            if (img) img.src = src;
            if (titleEl) titleEl.textContent = title ? `Detail Foto: ${title}` : 'Detail Foto Barang';

            if (stage) stage.classList.remove('is-zoomed');
            const zoomLabel = document.getElementById('lightbox-zoom-label');
            if (zoomLabel) zoomLabel.textContent = 'Perbesar';
            const zoomIcon = document.getElementById('lightbox-zoom-icon');
            if (zoomIcon) zoomIcon.setAttribute('data-lucide', 'zoom-in');

            if (lightbox) {
                lightbox.classList.add('active');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeImageLightbox() {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (lightbox) {
                lightbox.classList.remove('active');
                lightbox.setAttribute('aria-hidden', 'true');
                const stage = document.getElementById('lightbox-stage');
                if (stage) stage.classList.remove('is-zoomed');
                const otherModalActive = document.querySelector('.modal-backdrop.active, .modal-overlay.active');
                if (!otherModalActive) {
                    document.body.style.overflow = '';
                }
            }
        }

        function closeImageLightboxOnBackdrop(e) {
            if (e.target.id === 'modal-image-lightbox') {
                closeImageLightbox();
            }
        }

        function toggleLightboxZoom() {
            const stage = document.getElementById('lightbox-stage');
            if (!stage) return;
            const isZoomed = stage.classList.toggle('is-zoomed');
            const zoomLabel = document.getElementById('lightbox-zoom-label');
            const zoomIcon = document.getElementById('lightbox-zoom-icon');

            if (zoomLabel) zoomLabel.textContent = isZoomed ? 'Perkecil' : 'Perbesar';
            if (zoomIcon) zoomIcon.setAttribute('data-lucide', isZoomed ? 'zoom-out' : 'zoom-in');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const lightbox = document.getElementById('modal-image-lightbox');
                if (lightbox && lightbox.classList.contains('active')) {
                    e.stopPropagation();
                    closeImageLightbox();
                }
            }
        });

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

        // Desktop Sidebar Toggle (Open/Close with State Persistence)
        function toggleDesktopSidebar() {
            const wrapper = document.querySelector('.app-layout-wrapper');
            if (!wrapper) return;

            wrapper.classList.toggle('sidebar-collapsed');
            const isCollapsed = wrapper.classList.contains('sidebar-collapsed');

            const sidebar = document.getElementById('desktop-sidebar');
            if (sidebar) {
                sidebar.setAttribute('aria-hidden', isCollapsed ? 'true' : 'false');
            }

            try {
                localStorage.setItem('findumb_desktop_sidebar_collapsed', isCollapsed ? '1' : '0');
            } catch (e) {}

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Restore desktop sidebar collapse preference (defaults to collapsed unless explicitly expanded)
            try {
                if (window.innerWidth >= 1024) {
                    const urlParams = new URLSearchParams(window.location.search);
                    const queryExpanded = urlParams.get('sidebar') === 'expanded' || urlParams.get('expanded') === '1';
                    const isExpanded = queryExpanded || localStorage.getItem('findumb_desktop_sidebar_collapsed') === '0';
                    const wrapper = document.querySelector('.app-layout-wrapper');
                    if (wrapper) {
                        if (isExpanded) {
                            wrapper.classList.remove('sidebar-collapsed');
                        } else {
                            wrapper.classList.add('sidebar-collapsed');
                        }
                        const sidebar = document.getElementById('desktop-sidebar');
                        if (sidebar) {
                            sidebar.setAttribute('aria-hidden', wrapper.classList.contains('sidebar-collapsed') ? 'true' : 'false');
                        }
                    }
                }
                document.documentElement.classList.remove('desktop-sidebar-pre-collapsed');
            } catch(e) {}

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
