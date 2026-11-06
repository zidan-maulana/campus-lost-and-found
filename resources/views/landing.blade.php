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
           Fitur Utama Bento Grid Section (Persis Sesuai Referensi)
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
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(191, 219, 254, 0.8);
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

        /* 3-Column Bento Grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        /* Base Bento Card */
        .bento-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 24px;
            padding: 1.25rem;
            box-shadow: 0 10px 30px -5px rgba(12, 85, 156, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .bento-card:hover {
            box-shadow: 0 16px 36px -8px rgba(12, 85, 156, 0.12), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
            border-color: rgba(147, 197, 253, 0.8);
        }

        /* Card Upper Visual Window */
        .bento-visual {
            width: 100%;
            height: 165px;
            border-radius: 18px;
            background: linear-gradient(180deg, #f3f8fe 0%, #f9fbff 100%);
            border: 1px solid rgba(219, 234, 254, 0.75);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.15rem;
        }

        .bento-visual svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Card Lower Content */
        .bento-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1;
        }

        .bento-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 9999px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid rgba(219, 234, 254, 0.9);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
        }

        .bento-icon-badge svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.2;
        }

        .bento-card-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.35rem;
            line-height: 1.3;
        }

        .bento-card-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
        }

        /* Card 4: Wide Verification Card (Span 2 Cols) */
        .bento-card-wide {
            grid-column: 1 / span 2;
            grid-row: 2;
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 1.5rem;
            align-items: center;
            padding: 1.25rem 1.5rem 1.25rem 1.25rem;
        }

        .bento-card-wide .bento-visual {
            height: 100%;
            min-height: 165px;
            margin-bottom: 0;
        }

        /* Card 5: Tall Reputasi / User Card (Col 3, Span 2 Rows) */
        .bento-card-tall {
            grid-column: 3;
            grid-row: 2 / span 2;
            display: flex;
            flex-direction: column;
        }

        .bento-card-tall .bento-visual {
            height: 230px;
            flex-shrink: 0;
        }

        /* Card 6: Wide CTA Banner (Span 2 Cols under Card 4) */
        .bento-card-banner {
            grid-column: 1 / span 2;
            grid-row: 3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1.6rem 2rem;
            background: linear-gradient(90deg, #ffffff 0%, #f6faff 65%, #eff6ff 100%);
            border: 1px solid rgba(219, 234, 254, 0.95);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px -5px rgba(12, 85, 156, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .bento-card-banner::before {
            content: '';
            position: absolute;
            right: 15%;
            top: 50%;
            transform: translateY(-50%);
            width: 240px;
            height: 140px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.22) 0%, transparent 70%);
            pointer-events: none;
        }

        .bento-banner-content {
            position: relative;
            z-index: 2;
        }

        .bento-banner-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .bento-banner-title span {
            color: #1d4ed8;
        }

        .bento-banner-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .bento-banner-btn {
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.72rem 1.65rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 10px 24px -4px rgba(37, 99, 235, 0.45);
            transition: background 0.2s ease, box-shadow 0.2s ease;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .bento-banner-btn:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 12px 28px -4px rgba(37, 99, 235, 0.55);
            color: #ffffff;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .bento-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .bento-card-wide {
                grid-column: 1 / span 2;
                grid-row: auto;
            }
            .bento-card-tall {
                grid-column: 1 / span 2;
                grid-row: auto;
                display: grid;
                grid-template-columns: 1.15fr 1fr;
                gap: 1.5rem;
                align-items: center;
            }
            .bento-card-tall .bento-visual {
                height: 100%;
                min-height: 165px;
                margin-bottom: 0;
            }
            .bento-card-banner {
                grid-column: 1 / span 2;
                grid-row: auto;
            }
        }

        @media (max-width: 640px) {
            .bento-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .bento-card-wide,
            .bento-card-tall {
                grid-column: 1;
                display: flex;
                flex-direction: column;
            }
            .bento-card-wide .bento-visual,
            .bento-card-tall .bento-visual {
                height: 160px;
                min-height: auto;
                margin-bottom: 1.15rem;
            }
            .bento-card-banner {
                grid-column: 1;
                flex-direction: column;
                align-items: flex-start;
                gap: 1.25rem;
                padding: 1.5rem;
            }
            .bento-banner-btn {
                width: 100%;
                justify-content: center;
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

                <!-- 3-Column Bento Grid Matching Reference -->
                <div class="bento-grid">
                    <!-- Card 1: Proteksi & Privasi Pelapor (3D Padlock) -->
                    <div class="bento-card">
                        <div class="bento-visual">
                            <svg viewBox="0 0 320 165" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="card1Glow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.38"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="card1Padlock" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#2563eb"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                    <linearGradient id="card1Shackle" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#60a5fa"/>
                                        <stop offset="50%" stop-color="#2563eb"/>
                                        <stop offset="100%" stop-color="#1e40af"/>
                                    </linearGradient>
                                    <filter id="card1Shadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#1e3a8a" flood-opacity="0.22"/>
                                    </filter>
                                </defs>
                                <!-- Binary Matrix Background -->
                                <g font-family="monospace" font-size="10" fill="rgba(37,99,235,0.22)" letter-spacing="3">
                                    <text x="28" y="32">0 1 0 1</text>
                                    <text x="28" y="48">1 0 1 0</text>
                                    <text x="28" y="64">0 1 1 0</text>
                                    <text x="28" y="80">1 0 0 1</text>
                                    <text x="28" y="96">0 1 0 1</text>
                                    <text x="28" y="112">1 0 1 0</text>
                                    <text x="28" y="128">0 0 1 1</text>

                                    <text x="244" y="32">1 0 0 1</text>
                                    <text x="244" y="48">0 1 1 0</text>
                                    <text x="244" y="64">1 0 1 0</text>
                                    <text x="244" y="80">0 1 0 1</text>
                                    <text x="244" y="96">1 1 0 0</text>
                                    <text x="244" y="112">0 1 0 1</text>
                                    <text x="244" y="128">1 0 1 0</text>
                                </g>
                                <!-- Left Ghost Padlock -->
                                <rect x="44" y="76" width="48" height="34" rx="8" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.6"/>
                                <path d="M 56 76 V 62 A 12 12 0 0 1 80 62 V 76" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.6"/>
                                <!-- Right Ghost Padlock -->
                                <rect x="228" y="76" width="48" height="34" rx="8" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.6"/>
                                <path d="M 240 76 V 62 A 12 12 0 0 1 264 62 V 76" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.6"/>
                                <!-- Center Glowing Aura -->
                                <circle cx="160" cy="85" r="54" fill="url(#card1Glow)"/>
                                <!-- 3D Unlocked Shackle (Open up & right) -->
                                <path d="M 144 72 V 42 A 16 16 0 0 1 176 42 V 30" fill="none" stroke="url(#card1Shackle)" stroke-width="8" stroke-linecap="round" filter="url(#card1Shadow)"/>
                                <!-- 3D Padlock Body -->
                                <rect x="128" y="66" width="64" height="48" rx="12" fill="url(#card1Padlock)" filter="url(#card1Shadow)"/>
                                <rect x="131" y="68" width="58" height="3" rx="1.5" fill="rgba(255, 255, 255, 0.4)"/>
                                <circle cx="160" cy="86" r="4" fill="#0f172a"/>
                                <path d="M 158 86 L 162 86 L 161 96 L 159 96 Z" fill="#0f172a"/>
                            </svg>
                        </div>
                        <div class="bento-content">
                            <div class="bento-icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 9.9-1"/>
                                </svg>
                            </div>
                            <h3 class="bento-card-title">Proteksi & Privasi Pelapor</h3>
                            <p class="bento-card-desc">Identitas dan nomor kontak civitas aman terlindungi, terhindar dari spam dan penyalahgunaan.</p>
                        </div>
                    </div>

                    <!-- Card 2: Validasi Keamanan Pos Satpam (3D Shield) -->
                    <div class="bento-card">
                        <div class="bento-visual">
                            <svg viewBox="0 0 320 165" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="card2Glow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.38"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="card2Shield" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#2563eb"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                    <filter id="card2Shadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="8" stdDeviation="8" flood-color="#1e3a8a" flood-opacity="0.25"/>
                                    </filter>
                                </defs>
                                <!-- Perspective Curved Stage Wireframe -->
                                <path d="M 24 160 Q 160 115 296 160" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.2"/>
                                <path d="M 50 148 Q 160 108 270 148" fill="none" stroke="rgba(37, 99, 235, 0.14)" stroke-width="1"/>
                                <path d="M 80 138 Q 160 102 240 138" fill="none" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1"/>
                                <path d="M 110 130 Q 160 98 210 130" fill="none" stroke="rgba(37, 99, 235, 0.1)" stroke-width="1"/>
                                <!-- Perspective rays -->
                                <path d="M 160 105 V 160" stroke="rgba(37, 99, 235, 0.15)" stroke-width="1"/>
                                <path d="M 132 108 Q 110 132 80 160" stroke="rgba(37, 99, 235, 0.14)" stroke-width="1"/>
                                <path d="M 188 108 Q 210 132 240 160" stroke="rgba(37, 99, 235, 0.14)" stroke-width="1"/>
                                <path d="M 110 115 Q 70 138 35 160" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1"/>
                                <path d="M 210 115 Q 250 138 285 160" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1"/>
                                <!-- Floating Soft Shadow -->
                                <ellipse cx="160" cy="122" rx="28" ry="7" fill="rgba(30, 64, 175, 0.25)" filter="blur(5px)"/>
                                <!-- Glowing Blue Aura -->
                                <circle cx="160" cy="65" r="50" fill="url(#card2Glow)"/>
                                <!-- 3D Royal Blue Shield -->
                                <path d="M 132 26 Q 160 22 188 26 V 68 Q 188 105 160 118 Q 132 105 132 68 Z" fill="url(#card2Shield)" filter="url(#card2Shadow)"/>
                                <path d="M 160 24 Q 174 25 188 26 V 68 Q 188 105 160 118 Z" fill="rgba(15, 23, 42, 0.14)"/>
                                <path d="M 135 28 Q 160 24.5 185 28" fill="none" stroke="rgba(255, 255, 255, 0.55)" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="bento-content">
                            <div class="bento-icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <h3 class="bento-card-title">Validasi Keamanan Kampus</h3>
                            <p class="bento-card-desc">Barang berharga diamankan dan diserahterimakan melalui pos satpam resmi universitas.</p>
                        </div>
                    </div>

                    <!-- Card 3: Pencarian Cerdas & Filter Kampus (3D Magnifying Glass & Curtain) -->
                    <div class="bento-card">
                        <div class="bento-visual">
                            <svg viewBox="0 0 320 165" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="card3Glow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.38"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="card3Rim" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                    <linearGradient id="card3Handle" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#60a5fa"/>
                                        <stop offset="100%" stop-color="#1e40af"/>
                                    </linearGradient>
                                    <filter id="card3Shadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#1e3a8a" flood-opacity="0.22"/>
                                    </filter>
                                </defs>
                                <!-- Pleated Curtain Curves spreading downwards -->
                                <path d="M 40 165 C 40 100, 68 35, 95 20" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.2"/>
                                <path d="M 68 165 C 68 105, 95 40, 120 20" fill="none" stroke="rgba(37, 99, 235, 0.14)" stroke-width="1.2"/>
                                <path d="M 100 165 C 100 110, 122 45, 142 20" fill="none" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1.2"/>
                                <path d="M 220 165 C 220 110, 198 45, 178 20" fill="none" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1.2"/>
                                <path d="M 252 165 C 252 105, 225 40, 200 20" fill="none" stroke="rgba(37, 99, 235, 0.14)" stroke-width="1.2"/>
                                <path d="M 280 165 C 280 100, 252 35, 225 20" fill="none" stroke="rgba(37, 99, 235, 0.16)" stroke-width="1.2"/>
                                <!-- Glowing Blue Center Aura -->
                                <circle cx="155" cy="58" r="48" fill="url(#card3Glow)"/>
                                <!-- 3D Magnifying Glass Tilted -->
                                <g filter="url(#card3Shadow)">
                                    <circle cx="155" cy="58" r="30" fill="rgba(235, 245, 255, 0.75)" stroke="url(#card3Rim)" stroke-width="7"/>
                                    <path d="M 137 46 A 23 23 0 0 1 170 36" fill="none" stroke="rgba(255, 255, 255, 0.85)" stroke-width="2.6" stroke-linecap="round"/>
                                    <path d="M 177 80 L 204 107" stroke="url(#card3Handle)" stroke-width="8" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="bento-content">
                            <div class="bento-icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="22" y1="12" x2="18" y2="12"/>
                                    <line x1="6" y1="12" x2="2" y2="12"/>
                                    <line x1="12" y1="6" x2="12" y2="2"/>
                                    <line x1="12" y1="22" x2="12" y2="18"/>
                                </svg>
                            </div>
                            <h3 class="bento-card-title">Pencarian Cerdas & Lokasi</h3>
                            <p class="bento-card-desc">Filter presisi berdasarkan kategori barang, lokasi gedung kampus, hingga rentang tanggal.</p>
                        </div>
                    </div>

                    <!-- Card 4: Klaim Terverifikasi Civitas (Wide Card - 2 Columns) -->
                    <div class="bento-card bento-card-wide">
                        <div class="bento-visual">
                            <svg viewBox="0 0 340 165" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="card4Glow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="card4Avatar" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#bfdbfe"/>
                                        <stop offset="100%" stop-color="#93c5fd"/>
                                    </linearGradient>
                                    <filter id="card4Shadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#1e3a8a" flood-opacity="0.16"/>
                                    </filter>
                                </defs>
                                <!-- Binary matrix background -->
                                <g font-family="monospace" font-size="9" fill="rgba(37,99,235,0.2)" letter-spacing="3">
                                    <text x="24" y="32">0 1 0 1</text>
                                    <text x="24" y="46">1 0 1 0</text>
                                    <text x="24" y="60">0 1 1 0</text>
                                    <text x="24" y="74">1 0 0 1</text>
                                    <text x="24" y="88">0 1 0 1</text>
                                    <text x="24" y="102">1 0 1 0</text>

                                    <text x="268" y="32">1 0 0 1</text>
                                    <text x="268" y="46">0 1 1 0</text>
                                    <text x="268" y="60">1 0 1 0</text>
                                    <text x="268" y="74">0 1 0 1</text>
                                    <text x="268" y="88">1 1 0 0</text>
                                    <text x="268" y="102">0 1 0 1</text>
                                </g>
                                <!-- Concentric radar scan rings -->
                                <circle cx="170" cy="62" r="60" fill="none" stroke="rgba(37, 99, 235, 0.12)" stroke-width="1" stroke-dasharray="4 4"/>
                                <circle cx="170" cy="62" r="42" fill="none" stroke="rgba(37, 99, 235, 0.18)" stroke-width="1"/>
                                <!-- Vertical scanline -->
                                <line x1="170" y1="12" x2="170" y2="115" stroke="rgba(37, 99, 235, 0.3)" stroke-width="1.2"/>
                                <!-- Glowing Blue Center Aura -->
                                <circle cx="170" cy="62" r="40" fill="url(#card4Glow)"/>
                                <!-- Avatar with silhouette -->
                                <circle cx="170" cy="62" r="26" fill="url(#card4Avatar)" filter="url(#card4Shadow)"/>
                                <circle cx="170" cy="54" r="7.5" fill="#1e40af"/>
                                <path d="M 154 77 C 154 69, 161 66, 170 66 C 179 66, 186 69, 186 77 Z" fill="#1e40af"/>
                                <!-- Floating Verified Badge Pill -->
                                <g filter="url(#card4Shadow)">
                                    <rect x="95" y="112" width="150" height="26" rx="13" fill="#ffffff" stroke="rgba(191, 219, 254, 0.9)" stroke-width="1.2"/>
                                    <text x="122" y="129" font-family="'Plus Jakarta Sans', sans-serif" font-size="11" font-weight="700" fill="#0f172a">Civitas Terverifikasi</text>
                                    <circle cx="108" cy="125" r="6" fill="#2563eb"/>
                                    <path d="M 105.5 125 L 107.2 126.8 L 110.8 123.2" fill="none" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="bento-content">
                            <div class="bento-icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <h3 class="bento-card-title">Klaim Terverifikasi Civitas</h3>
                            <p class="bento-card-desc">Mencegah salah ambil dengan pencocokan identitas civitas dan bukti ciri khusus sebelum serah terima dilakukan.</p>
                        </div>
                    </div>

                    <!-- Card 5: Apresiasi & Budaya Jujur (Tall Card - Col 3, Span 2 Rows) -->
                    <div class="bento-card bento-card-tall">
                        <div class="bento-visual">
                            <svg viewBox="0 0 320 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="card5Glow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.38"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </radialGradient>
                                    <linearGradient id="card5Trophy" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                    <filter id="card5Shadow" x="-20%" y="-20%" width="140%" height="140%">
                                        <feDropShadow dx="0" dy="5" stdDeviation="7" flood-color="#1e3a8a" flood-opacity="0.22"/>
                                    </filter>
                                </defs>
                                <!-- Binary matrix stream -->
                                <g font-family="monospace" font-size="10" fill="rgba(37,99,235,0.22)" letter-spacing="3">
                                    <text x="32" y="40">0 1 0 1</text>
                                    <text x="32" y="60">1 0 1 0</text>
                                    <text x="32" y="80">0 1 1 0</text>
                                    <text x="32" y="100">1 0 0 1</text>
                                    <text x="32" y="120">0 1 0 1</text>
                                    <text x="32" y="140">1 0 1 0</text>
                                    <text x="32" y="160">0 0 1 1</text>
                                    <text x="32" y="180">1 1 0 0</text>

                                    <text x="240" y="40">1 0 0 1</text>
                                    <text x="240" y="60">0 1 1 0</text>
                                    <text x="240" y="80">1 0 1 0</text>
                                    <text x="240" y="100">0 1 0 1</text>
                                    <text x="240" y="120">1 1 0 0</text>
                                    <text x="240" y="140">0 1 0 1</text>
                                    <text x="240" y="160">1 0 1 0</text>
                                    <text x="240" y="180">0 0 1 1</text>
                                </g>
                                <!-- Soft Center Aura -->
                                <circle cx="160" cy="100" r="55" fill="url(#card5Glow)"/>
                                <!-- Center User Avatar -->
                                <g filter="url(#card5Shadow)">
                                    <circle cx="160" cy="95" r="28" fill="rgba(191, 219, 254, 0.85)" stroke="#2563eb" stroke-width="2"/>
                                    <circle cx="160" cy="87" r="8" fill="#1e40af"/>
                                    <path d="M 144 111 C 144 102, 151 99, 160 99 C 169 99, 176 102, 176 111 Z" fill="#1e40af"/>
                                </g>
                                <!-- Left Trophy Icon -->
                                <g filter="url(#card5Shadow)">
                                    <path d="M 82 86 H 110 V 104 C 110 114, 82 114, 82 104 Z" fill="rgba(37, 99, 235, 0.12)" stroke="url(#card5Trophy)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M 96 114 V 124 M 86 124 H 106" stroke="url(#card5Trophy)" stroke-width="2.5" stroke-linecap="round"/>
                                    <path d="M 82 90 H 74 C 71 90, 71 100, 82 100" fill="none" stroke="url(#card5Trophy)" stroke-width="2.2" stroke-linecap="round"/>
                                    <path d="M 110 90 H 118 C 121 90, 121 100, 110 100" fill="none" stroke="url(#card5Trophy)" stroke-width="2.2" stroke-linecap="round"/>
                                </g>
                                <!-- Right Trophy Icon -->
                                <g filter="url(#card5Shadow)">
                                    <path d="M 210 86 H 238 V 104 C 238 114, 210 114, 210 104 Z" fill="rgba(37, 99, 235, 0.12)" stroke="url(#card5Trophy)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M 224 114 V 124 M 214 124 H 234" stroke="url(#card5Trophy)" stroke-width="2.5" stroke-linecap="round"/>
                                    <path d="M 210 90 H 202 C 199 90, 199 100, 210 100" fill="none" stroke="url(#card5Trophy)" stroke-width="2.2" stroke-linecap="round"/>
                                    <path d="M 238 90 H 246 C 249 90, 249 100, 238 100" fill="none" stroke="url(#card5Trophy)" stroke-width="2.2" stroke-linecap="round"/>
                                </g>
                            </svg>
                        </div>
                        <div class="bento-content">
                            <div class="bento-icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <h3 class="bento-card-title">Apresiasi & Budaya Jujur</h3>
                            <p class="bento-card-desc">Sistem pencatatan riwayat transparan untuk menumbuhkan kepedulian dan integritas di lingkungan civitas.</p>
                        </div>
                    </div>

                    <!-- Card 6: CTA Banner (Span 2 Cols under Card 4) -->
                    <div class="bento-card-banner">
                        <div class="bento-banner-content">
                            <h3 class="bento-banner-title">Kehilangan atau Menemukan <span>Barang di Kampus?</span></h3>
                            <p class="bento-banner-desc">Gunakan UMBFind sekarang untuk pelaporan cepat dan pencarian langsung via web browser.</p>
                        </div>
                        <a href="{{ route('home') }}" class="bento-banner-btn">
                            <span>Buka Aplikasi</span>
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
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
