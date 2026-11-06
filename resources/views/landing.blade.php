<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>UMBFind — Layanan Pelaporan & Pencarian Barang Kampus</title>
    <meta name="description" content="Platform resmi pelaporan dan penemuan barang hilang civitas Universitas Mercu Buana. Cepat, aman, dan saling terhubung.">
    <meta name="theme-color" content="#0c487b">

    <!-- Favicon & PWA Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/icons/search.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via local Vite or CDN build) -->
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            navy: {
                                900: '#0b192c',
                                800: '#0c487b',
                                700: '#0c559c',
                            },
                            brand: {
                                blue: '#1572b8',
                                sky: '#2aa5e0',
                                soft: '#e0f2fe',
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif],
                            heading: ['Plus Jakarta Sans', 'Inter', sans-serif]
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        :root {
            --color-navy-dark: #0b192c;
            --color-navy-brand: #0c487b;
            --color-blue-brand: #1572b8;
            --color-blue-soft: #e0f2fe;
            --color-text-main: #0f172a;
            --color-text-muted: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            color: var(--color-text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ----------------------------------------------------
           Modern Seamless Navigation Bar (Floating White Pill)
           ---------------------------------------------------- */
        .landing-header {
            position: fixed;
            top: 1.25rem;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 0 1rem;
            pointer-events: none;
            transition: all 0.2s ease;
        }

        .landing-nav {
            max-width: 860px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.72rem 0.65rem 0.72rem 0.95rem;
            background: #ffffff;
            border-radius: 9999px;
            box-shadow: 0 12px 32px -4px rgba(16, 110, 194, 0.18), 0 4px 12px -2px rgba(16, 110, 194, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.95);
            pointer-events: auto;
        }

        .nav-brand {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.65rem;
            text-decoration: none;
            transition: opacity 0.15s ease;
        }

        .nav-brand:hover {
            opacity: 0.9;
        }

        .nav-brand-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            display: block;
        }

        .nav-brand-text {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: -0.025em;
            color: var(--color-blue-brand);
        }

        .nav-brand-text span {
            color: var(--color-navy-brand);
        }

        .nav-links {
            flex: 0 0 auto;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }

        @media (min-width: 820px) {
            .nav-links {
                display: flex;
            }
        }

        .nav-link-item {
            font-size: 0.9125rem;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .nav-link-item:hover {
            color: var(--color-blue-brand);
        }

        .nav-link-item.active {
            color: var(--color-navy-brand);
            font-weight: 700;
        }

        /* Action Buttons on Right */
        .nav-actions {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .nav-login-btn {
            background: linear-gradient(135deg, #0c5ea8 0%, #1572b8 55%, #2aa5e0 100%);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.65rem 1.6rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.25;
            transition: background 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 14px rgba(12, 94, 168, 0.24);
            letter-spacing: -0.01em;
            transform: none;
        }

        .nav-login-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 55%, #2499d3 100%);
            color: #ffffff;
            transform: none;
            box-shadow: 0 6px 18px rgba(12, 94, 168, 0.3);
        }

        @media (max-width: 480px) {
            .landing-header {
                top: 0.85rem;
                padding: 0 0.75rem;
            }
            .landing-nav {
                padding: 0.58rem 0.55rem 0.58rem 0.85rem;
            }
            .nav-brand-logo {
                width: 30px;
                height: 30px;
            }
            .nav-brand-text {
                font-size: 1.08rem;
            }
            .nav-login-btn {
                padding: 0.5rem 1.15rem;
                font-size: 0.825rem;
            }
        }

        /* ----------------------------------------------------
           Hero Section Styling (Vibrant Royal Blue Gradient)
           ---------------------------------------------------- */
        .hero-section {
            position: relative;
            padding-top: 10rem;
            padding-bottom: 0;
            background: radial-gradient(ellipse at 50% 18%, rgba(255, 255, 255, 0.12) 0%, transparent 60%),
                        linear-gradient(180deg, #106ec2 0%, #197fce 35%, #35a5ea 72%, #ffffff 100%);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Big Hero Headline */
        .hero-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(2.35rem, 4.2vw, 3.65rem);
            font-weight: 600;
            line-height: 1.16;
            letter-spacing: -0.03em;
            color: #ffffff;
            margin: 0 0 1.15rem 0;
            padding: 0 1rem;
            max-width: 1040px;
            text-shadow: 0 2px 16px rgba(12, 72, 123, 0.18);
        }

        /* Hero Subheadline (Maksimal 2 Baris Elegan) */
        .hero-subtitle {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(0.95rem, 1.55vw, 1.075rem);
            font-weight: 400;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.92);
            max-width: 680px;
            margin: 0 auto 2.35rem auto;
            padding: 0 1.25rem;
            text-shadow: 0 1px 12px rgba(12, 72, 123, 0.15);
        }

        /* CTA Actions */
        .hero-actions-wrap {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            flex-wrap: wrap;
            z-index: 20;
            position: relative;
        }

        .hero-download-btn {
            background: #ffffff;
            color: #0c5ea8;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.84rem 1.85rem;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 4px 18px rgba(10, 48, 84, 0.18);
            transition: background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
            text-decoration: none;
            transform: none;
            line-height: 1.25;
        }

        .hero-download-btn:hover {
            background: #f8fafc;
            color: #094780;
            transform: none;
            box-shadow: 0 6px 22px rgba(10, 48, 84, 0.22);
        }

        .hero-download-btn:active {
            transform: translateY(0);
        }

        .hero-download-btn svg {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
            flex-shrink: 0;
        }

        /* Outline Secondary Button: Buka Aplikasi */
        .hero-outline-btn {
            background: transparent;
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.84rem 1.85rem;
            border-radius: 9999px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            transition: background 0.15s ease, border-color 0.15s ease;
            text-decoration: none;
            transform: none;
            line-height: 1.25;
        }

        .hero-outline-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: #ffffff;
            color: #ffffff;
            transform: none;
            box-shadow: none;
        }

        /* ----------------------------------------------------
           Hero Mockup Stage (Hand holding Phone Mockup)
           ---------------------------------------------------- */
        .hero-mockup-stage {
            position: relative;
            width: 100%;
            max-width: 1200px;
            margin-top: 3.75rem;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            overflow: visible;
        }

        /* Mockup Container (Ukuran Lebih Besar & Transisi Fade Horizontal Alami) */
        .hero-mockup-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: center;
            margin-bottom: -1px;
            transform: translateX(-11%);
        }

        .hero-phone-img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.07));
            user-select: none;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: clamp(1.95rem, 6.8vw, 2.55rem);
                font-weight: 600;
                line-height: 1.18;
                margin-bottom: 1.65rem;
            }
            .hero-mockup-stage {
                margin-top: 2.5rem;
            }
            .hero-mockup-wrapper {
                max-width: 100%;
                transform: translateX(-9%);
            }
        }

        /* ----------------------------------------------------
           Fitur Utama 3-Card Grid Section (Tema Kampus UMBFind)
           ---------------------------------------------------- */
        .features-section {
            position: relative;
            padding: 5rem 1.5rem 6.5rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
            scroll-margin-top: 6.5rem;
        }

        .features-container {
            max-width: 1140px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 3.5rem auto;
        }

        .features-pill-tag {
            display: inline-flex;
            align-items: center;
            background: #e0f2fe;
            color: #106ec2;
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(16, 110, 194, 0.22);
            margin-bottom: 1rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .features-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: clamp(2rem, 3.5vw, 2.75rem);
            font-weight: 700;
            color: #0b192c;
            line-height: 1.22;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
        }

        .features-subtitle {
            font-size: 1.05rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0 auto;
        }

        /* Clean, Symmetrical 3-Column Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        /* Base Feature Card */
        .feature-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 24px;
            padding: 1.35rem;
            box-shadow: 0 10px 30px -5px rgba(16, 110, 194, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px -8px rgba(16, 110, 194, 0.12), 0 4px 12px -2px rgba(16, 110, 194, 0.04);
            border-color: rgba(53, 165, 234, 0.55);
        }

        /* Card Upper Visual Window */
        .feature-visual {
            width: 100%;
            height: 180px;
            border-radius: 18px;
            background: linear-gradient(180deg, #f2f8fe 0%, #f9fcff 100%);
            border: 1px solid rgba(200, 225, 252, 0.75);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .feature-visual svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Card Lower Content */
        .feature-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1;
        }

        .feature-card-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0b192c;
            margin-bottom: 0.45rem;
            line-height: 1.3;
        }

        .feature-card-desc {
            font-size: 0.8875rem;
            color: #64748b;
            line-height: 1.55;
            margin: 0;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                max-width: 520px;
                margin: 0 auto;
            }
        }

        /* PWA Install Instructions Modal (for iOS or Unsupported browsers) */
        .pwa-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .pwa-modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .pwa-modal-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            padding: 1.75rem;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.2);
            position: relative;
            transform: scale(0.95);
            transition: transform 0.2s ease;
        }

        .pwa-modal-overlay.active .pwa-modal-card {
            transform: scale(1);
        }
    </style>
</head>
<body>

    <!-- 1. Modern Seamless Navigation Bar (Sesuai Referensi, Tanpa Shape) -->
    <header class="landing-header">
        <nav class="landing-nav" aria-label="Navigasi Utama">
            <!-- Brand Logo & Wordmark -->
            <a href="{{ route('landing') }}" class="nav-brand">
                <picture>
                    <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UMBFind" class="nav-brand-logo" width="36" height="36" loading="eager">
                </picture>
                <span class="nav-brand-text"><span>UMB</span>Find</span>
            </a>

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="{{ route('landing') }}" class="nav-link-item active">Beranda</a>
                <a href="#features" class="nav-link-item">Fitur Utama</a>
                <a href="#security" class="nav-link-item">Keamanan</a>
                <a href="#faq" class="nav-link-item">Tanya Jawab</a>
            </div>

            <!-- Action Buttons Group (Login) -->
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="nav-login-btn">
                    Masuk
                </a>
            </div>
        </nav>
    </header>

    <!-- 2. Hero Section (Persis Sesuai Layout Referensi) -->
    <main>
        <section class="hero-section">
            <!-- Big Main Headline (2 Baris Presisi & Langsung Dipahami Sekali Baca) -->
            <h1 class="hero-title">
                Cara tercepat temukan<br>
                barang hilang di area kampus.
            </h1>

            <!-- Hero Subtitle (Penjelas Ringkas, Maksimal 2 Baris Presisi) -->
            <p class="hero-subtitle">
                Platform resmi civitas Universitas Mercu Buana untuk melaporkan<br class="hidden sm:inline">
                dan menemukan barang tertinggal secara cepat, aman, dan terpercaya.
            </p>

            <!-- Pill Action Group (Unduh Aplikasi & Buka Aplikasi) -->
            <div class="hero-actions-wrap">
                <button type="button" class="hero-download-btn" id="btn-pwa-install" onclick="handlePWAInstall()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    <span>Unduh Aplikasi</span>
                </button>
                <a href="{{ route('home') }}" class="hero-outline-btn">
                    <span>Buka Aplikasi</span>
                </a>
            </div>

            <!-- Centerpiece Smartphone Mockup Stage -->
            <div class="hero-mockup-stage">
                <!-- Hand holding iPhone Phone Mockup (New Version 2 with Uniform Horizontal Fade) -->
                <div class="hero-mockup-wrapper">
                    <picture>
                        <source srcset="{{ asset('images/phone-mockup-2.webp') }}" type="image/webp">
                        <img src="{{ asset('images/phone-mockup-2.png') }}" alt="Mockup Aplikasi UMBFind di Ponsel" class="hero-phone-img" width="1199" height="1110" loading="eager">
                    </picture>
                </div>
            </div>
        </section>

        <!-- 3. Fitur Utama Bento Grid Section (Persis Sesuai Referensi) -->
        <section id="features" class="features-section">
            <div class="features-container">
                <!-- Section Header -->
                <div class="features-header">
                    <div class="features-pill-tag">Fitur Unggulan</div>
                    <h2 class="features-title">Dirancang Khusus untuk Keamanan Kampus</h2>
                    <p class="features-subtitle">
                        Solusi terintegrasi pelaporan dan penemuan barang hilang dengan alur verifikasi resmi dan perlindungan data civitas.
                    </p>
                </div>

                <!-- 3 Feature Cards Grid (Symmetrical 3-Column Layout) -->
                <div class="features-grid">
                    <!-- Card 1: Pelaporan Cepat & Privasi Aman -->
                    <div class="feature-card">
                        <div class="feature-visual">
                            <svg viewBox="0 0 340 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="heroGlow1" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#35a5ea" stop-opacity="0.32"/>
                                        <stop offset="100%" stop-color="#106ec2" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="heroGrad1" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#106ec2"/>
                                        <stop offset="50%" stop-color="#197fce"/>
                                        <stop offset="100%" stop-color="#35a5ea"/>
                                    </linearGradient>
                                    <filter id="heroShadow1" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#106ec2" flood-opacity="0.12"/>
                                    </filter>
                                </defs>
                                <!-- Soft decorative background dots -->
                                <g fill="rgba(16, 110, 194, 0.12)">
                                    <circle cx="36" cy="28" r="1.5"/><circle cx="52" cy="28" r="1.5"/><circle cx="68" cy="28" r="1.5"/>
                                    <circle cx="36" cy="44" r="1.5"/><circle cx="52" cy="44" r="1.5"/><circle cx="68" cy="44" r="1.5"/>
                                    <circle cx="272" cy="28" r="1.5"/><circle cx="288" cy="28" r="1.5"/><circle cx="304" cy="28" r="1.5"/>
                                    <circle cx="272" cy="44" r="1.5"/><circle cx="288" cy="44" r="1.5"/><circle cx="304" cy="44" r="1.5"/>
                                </g>
                                <!-- Central Blue Aura -->
                                <circle cx="170" cy="85" r="55" fill="url(#heroGlow1)"/>
                                <!-- Floating Report Item Preview Card -->
                                <g filter="url(#heroShadow1)">
                                    <rect x="62" y="30" width="216" height="106" rx="14" fill="#ffffff" stroke="rgba(53, 165, 234, 0.35)" stroke-width="1.2"/>
                                    <!-- Card Header Bar -->
                                    <rect x="76" y="44" width="60" height="7" rx="3.5" fill="#e2e8f0"/>
                                    <rect x="204" y="42" width="60" height="18" rx="9" fill="#e0f2fe"/>
                                    <text x="212" y="54.5" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="700" fill="#106ec2">Terverifikasi</text>
                                    <!-- Item Thumbnail Box -->
                                    <rect x="76" y="62" width="46" height="46" rx="8" fill="#f0f7ff" stroke="rgba(53, 165, 234, 0.25)" stroke-width="1"/>
                                    <!-- Bag / Item Icon -->
                                    <path d="M 93 76 V 73 A 6 6 0 0 1 105 73 V 76 M 86 76 H 112 V 94 H 86 Z" fill="none" stroke="#106ec2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <!-- Item Text Rows -->
                                    <rect x="132" y="66" width="95" height="8" rx="4" fill="#0b192c" opacity="0.85"/>
                                    <rect x="132" y="80" width="75" height="6" rx="3" fill="#94a3b8"/>
                                    <rect x="132" y="92" width="55" height="6" rx="3" fill="#cbd5e1"/>
                                </g>
                                <!-- Floating Privacy Lock Shield Badge on Corner -->
                                <g filter="url(#heroShadow1)">
                                    <rect x="180" y="94" width="96" height="26" rx="13" fill="url(#heroGrad1)"/>
                                    <!-- Vector Lock Icon -->
                                    <rect x="192" y="106" width="9" height="7" rx="1.5" fill="#ffffff"/>
                                    <path d="M 194 106 V 104 A 2.5 2.5 0 0 1 199 104 V 106" fill="none" stroke="#ffffff" stroke-width="1.3"/>
                                    <text x="206" y="110.5" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="700" fill="#ffffff">Kontak Aman</text>
                                </g>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-card-title">Pelaporan Cepat & Privasi Aman</h3>
                            <p class="feature-card-desc">Laporkan barang hilang atau temuan dalam hitungan menit dengan data kontak yang terproteksi privasinya.</p>
                        </div>
                    </div>

                    <!-- Card 2: Titip Validasi Pos Satpam -->
                    <div class="feature-card">
                        <div class="feature-visual">
                            <svg viewBox="0 0 340 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="heroGlow2" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#35a5ea" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#106ec2" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="heroGrad2" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#106ec2"/>
                                        <stop offset="50%" stop-color="#197fce"/>
                                        <stop offset="100%" stop-color="#35a5ea"/>
                                    </linearGradient>
                                    <filter id="heroShadow2" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#106ec2" flood-opacity="0.14"/>
                                    </filter>
                                </defs>
                                <!-- Perspective Security Checkpoint Stage Lines in Hero Blue -->
                                <path d="M 34 168 Q 170 125 306 168" fill="none" stroke="rgba(53, 165, 234, 0.2)" stroke-width="1.2"/>
                                <path d="M 60 156 Q 170 118 280 156" fill="none" stroke="rgba(53, 165, 234, 0.16)" stroke-width="1"/>
                                <path d="M 90 146 Q 170 112 250 146" fill="none" stroke="rgba(53, 165, 234, 0.14)" stroke-width="1"/>
                                <path d="M 120 138 Q 170 108 220 138" fill="none" stroke="rgba(53, 165, 234, 0.12)" stroke-width="1"/>
                                <path d="M 170 115 V 170" stroke="rgba(53, 165, 234, 0.18)" stroke-width="1"/>
                                <path d="M 142 118 Q 120 142 90 170" stroke="rgba(53, 165, 234, 0.15)" stroke-width="1"/>
                                <path d="M 198 118 Q 220 142 250 170" stroke="rgba(53, 165, 234, 0.15)" stroke-width="1"/>
                                <!-- Floating Drop Shadow under Shield -->
                                <ellipse cx="170" cy="128" rx="34" ry="7" fill="rgba(16, 110, 194, 0.18)" filter="blur(5px)"/>
                                <!-- Central Blue Aura -->
                                <circle cx="170" cy="68" r="50" fill="url(#heroGlow2)"/>
                                <!-- 3D Security Checkpoint Shield in Hero Blue -->
                                <path d="M 142 24 Q 170 20 198 24 V 68 Q 198 106 170 120 Q 142 106 142 68 Z" fill="url(#heroGrad2)" filter="url(#heroShadow2)"/>
                                <!-- Right Bevel Shading for 3D depth -->
                                <path d="M 170 22 Q 184 23 198 24 V 68 Q 198 106 170 120 Z" fill="rgba(11, 25, 44, 0.15)"/>
                                <!-- Specular light highlight -->
                                <path d="M 145 26 Q 170 22.5 195 26" fill="none" stroke="rgba(255, 255, 255, 0.6)" stroke-width="2"/>
                                <!-- Checkpoint Checkmark Symbol inside shield -->
                                <path d="M 160 66 L 167 73 L 181 57" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <!-- Floating Campus Security Pill Badge -->
                                <g filter="url(#heroShadow2)">
                                    <rect x="93" y="112" width="154" height="26" rx="13" fill="#ffffff" stroke="rgba(53, 165, 234, 0.35)" stroke-width="1.2"/>
                                    <circle cx="109" cy="125" r="5.5" fill="#e0f2fe"/>
                                    <path d="M 106.5 125 L 108.5 127 L 111.5 123" fill="none" stroke="#106ec2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <text x="120" y="128.5" font-family="'Plus Jakarta Sans', sans-serif" font-size="9" font-weight="700" fill="#0b192c">Pos Satpam Resmi UMB</text>
                                </g>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-card-title">Titip Validasi Pos Satpam</h3>
                            <p class="feature-card-desc">Barang bernilai tinggi dapat dititipkan dan diverifikasi langsung oleh petugas keamanan kampus.</p>
                        </div>
                    </div>

                    <!-- Card 3: Pencarian Cerdas & Lokasi -->
                    <div class="feature-card">
                        <div class="feature-visual">
                            <svg viewBox="0 0 340 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="heroGlow3" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#35a5ea" stop-opacity="0.32"/>
                                        <stop offset="100%" stop-color="#106ec2" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="heroGrad3" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#106ec2"/>
                                        <stop offset="50%" stop-color="#197fce"/>
                                        <stop offset="100%" stop-color="#35a5ea"/>
                                    </linearGradient>
                                    <filter id="heroShadow3" x="-25%" y="-25%" width="150%" height="150%">
                                        <feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="#106ec2" flood-opacity="0.10"/>
                                    </filter>
                                </defs>
                                <!-- Radar locator concentric rings centered at (165, 75) -->
                                <circle cx="165" cy="75" r="68" fill="none" stroke="rgba(53, 165, 234, 0.15)" stroke-width="1.2" stroke-dasharray="4 4"/>
                                <circle cx="165" cy="75" r="48" fill="none" stroke="rgba(53, 165, 234, 0.22)" stroke-width="1.2"/>
                                <circle cx="165" cy="75" r="28" fill="none" stroke="rgba(53, 165, 234, 0.28)" stroke-width="1"/>
                                <!-- Central Blue Aura -->
                                <circle cx="165" cy="75" r="45" fill="url(#heroGlow3)"/>
                                
                                <!-- Floating Campus Building Location Tag 1 (Top Left) -->
                                <g filter="url(#heroShadow3)">
                                    <rect x="38" y="28" width="102" height="24" rx="12" fill="#ffffff" stroke="rgba(53, 165, 234, 0.35)" stroke-width="1"/>
                                    <!-- Vector Pin Icon -->
                                    <g transform="translate(48, 34)">
                                        <path d="M 4 0 C 1.8 0 0 1.8 0 4 C 0 7 4 11 4 11 C 4 11 8 7 8 4 C 8 1.8 6.2 0 4 0 Z" fill="#106ec2"/>
                                        <circle cx="4" cy="4" r="1.4" fill="#ffffff"/>
                                    </g>
                                    <text x="61" y="44" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="700" fill="#106ec2">Gedung E Lt. 3</text>
                                </g>

                                <!-- Floating Campus Building Location Tag 2 (Top Right) -->
                                <g filter="url(#heroShadow3)">
                                    <rect x="198" y="24" width="98" height="24" rx="12" fill="#ffffff" stroke="rgba(53, 165, 234, 0.35)" stroke-width="1"/>
                                    <!-- Vector Pin Icon -->
                                    <g transform="translate(208, 30)">
                                        <path d="M 4 0 C 1.8 0 0 1.8 0 4 C 0 7 4 11 4 11 C 4 11 8 7 8 4 C 8 1.8 6.2 0 4 0 Z" fill="#106ec2"/>
                                        <circle cx="4" cy="4" r="1.4" fill="#ffffff"/>
                                    </g>
                                    <text x="221" y="40" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="700" fill="#106ec2">Kantin Utama</text>
                                </g>

                                <!-- Floating Campus Building Location Tag 3 (Bottom Left) -->
                                <g filter="url(#heroShadow3)">
                                    <rect x="48" y="118" width="100" height="24" rx="12" fill="#ffffff" stroke="rgba(53, 165, 234, 0.35)" stroke-width="1"/>
                                    <!-- Vector Pin Icon -->
                                    <g transform="translate(58, 124)">
                                        <path d="M 4 0 C 1.8 0 0 1.8 0 4 C 0 7 4 11 4 11 C 4 11 8 7 8 4 C 8 1.8 6.2 0 4 0 Z" fill="#106ec2"/>
                                        <circle cx="4" cy="4" r="1.4" fill="#ffffff"/>
                                    </g>
                                    <text x="71" y="134" font-family="'Plus Jakarta Sans', sans-serif" font-size="8.5" font-weight="700" fill="#106ec2">Lab Komputer</text>
                                </g>

                                <!-- 3D Royal Blue Magnifying Glass Tilted -->
                                <g filter="url(#heroShadow3)">
                                    <circle cx="165" cy="75" r="28" fill="rgba(235, 246, 255, 0.85)" stroke="url(#heroGrad3)" stroke-width="6.5"/>
                                    <path d="M 148 63 A 21 21 0 0 1 179 53" fill="none" stroke="rgba(255, 255, 255, 0.85)" stroke-width="2.5" stroke-linecap="round"/>
                                    <path d="M 185 95 L 211 121" stroke="url(#heroGrad3)" stroke-width="7.5" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-card-title">Pencarian Cerdas & Lokasi</h3>
                            <p class="feature-card-desc">Temukan barang berdasarkan lokasi gedung fakultas, area kampus, hingga kategori secara instan.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- PWA Install Guide Modal (iOS Safari & Desktop) -->
    <div id="pwa-guide-modal" class="pwa-modal-overlay" onclick="closePWAModal(event)">
        <div class="pwa-modal-card" onclick="event.stopPropagation()">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="nav-brand-icon" style="width: 36px; height: 36px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="font-weight: 700; font-size: 1.05rem; color: #0b192c; line-height: 1.2;">Pasang UMBFind PWA</h3>
                        <p style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">Cepat, ringan, tanpa unduh Play Store</p>
                    </div>
                </div>
                <button type="button" onclick="closePWAModal()" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1rem; margin-bottom: 1.25rem;">
                <div style="font-weight: 600; font-size: 0.875rem; color: #0f172a; margin-bottom: 0.5rem;">📱 Untuk Pengguna iPhone / iPad (Safari):</div>
                <ol style="font-size: 0.825rem; color: #475569; padding-left: 1.25rem; line-height: 1.6; margin: 0;">
                    <li>Buka halaman ini di browser <strong>Safari</strong>.</li>
                    <li>Ketuk ikon <strong>Bagikan (Share)</strong> di bar bawah browser.</li>
                    <li>Gulir ke bawah dan pilih <strong>"Tambah ke Layar Utama" (Add to Home Screen)</strong>.</li>
                    <li>Aplikasi UMBFind siap dibuka langsung dari layar ponsel Anda!</li>
                </ol>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('home') }}" class="nav-btn-app" style="flex: 1; justify-content: center; padding: 0.75rem;">
                    Buka Versi Web Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript: PWA Intelligent Handler -->
    <script>
        let deferredPWAInstallPrompt = null;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPWAInstallPrompt = e;
            console.log('PWA installation prompt is ready.');
        });

        function handlePWAInstall() {
            // Check if native Chrome/Edge/Android PWA prompt is ready
            if (deferredPWAInstallPrompt) {
                deferredPWAInstallPrompt.prompt();
                deferredPWAInstallPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('Pengguna menyetujui instalasi PWA UMBFind');
                    }
                    deferredPWAInstallPrompt = null;
                });
            } else {
                // If on iOS or standalone desktop browser, show modal instructions
                const modal = document.getElementById('pwa-guide-modal');
                if (modal) {
                    modal.classList.add('active');
                }
            }
        }

        function closePWAModal(e) {
            const modal = document.getElementById('pwa-guide-modal');
            if (modal) {
                modal.classList.remove('active');
            }
        }
    </script>
</body>
</html>
