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
           Fitur Utama Clean Split Layout (Sesuai Referensi)
           ---------------------------------------------------- */
        .features-section {
            position: relative;
            padding: 5.5rem 1.5rem 6.5rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
            scroll-margin-top: 6.5rem;
        }

        .features-container {
            max-width: 1160px;
            margin: 0 auto;
        }

        /* 2-Column Split Layout */
        .features-layout {
            display: grid;
            grid-template-columns: 1fr 1.28fr;
            gap: 4.5rem;
            align-items: center;
        }

        /* Left Column: Heading & CTA */
        .features-intro {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .features-pill-tag {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            color: #0b192c;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.38rem 1.15rem;
            border-radius: 9999px;
            border: 1px solid rgba(15, 23, 42, 0.22);
            margin-bottom: 1.35rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .features-main-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: clamp(2.15rem, 3.2vw, 2.85rem);
            font-weight: 700;
            color: #0b192c;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 1.15rem;
        }

        .features-main-desc {
            font-size: 0.975rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0 0 2.25rem 0;
            max-width: 440px;
        }

        .features-cta-wrap {
            display: flex;
            align-items: center;
        }

        /* Clean CTA Button (Tanpa Arrow Sesuai Instruksi User) */
        .features-cta-btn {
            background: linear-gradient(135deg, #106ec2 0%, #1572b8 50%, #1e87db 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            padding: 0.82rem 2.2rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 22px -3px rgba(16, 110, 194, 0.38);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .features-cta-btn:hover {
            background: linear-gradient(135deg, #0e5ea8 0%, #1263a0 50%, #1773be 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 26px -3px rgba(16, 110, 194, 0.48);
            color: #ffffff;
        }

        /* Right Column: 2x2 Clean Cards Grid */
        .features-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        /* Base Feature Box (Light Style) */
        .feature-box {
            background: #f8fafc;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 22px;
            padding: 2.15rem 1.75rem 1.95rem 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 215px;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            box-shadow: 0 2px 10px -2px rgba(15, 23, 42, 0.02);
        }

        .feature-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -6px rgba(16, 110, 194, 0.1);
            border-color: rgba(53, 165, 234, 0.45);
        }

        /* Featured Box: Hero Blue Background (Top Left) */
        .feature-box-primary {
            background: linear-gradient(145deg, #106ec2 0%, #1579cc 55%, #1f8ee2 100%);
            border: 1px solid rgba(16, 110, 194, 0.15);
            box-shadow: 0 12px 30px -5px rgba(16, 110, 194, 0.32);
        }

        .feature-box-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px -6px rgba(16, 110, 194, 0.44);
        }

        /* Circular Icon Badge */
        .feature-box-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #106ec2;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2.5rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px -2px rgba(16, 110, 194, 0.28);
        }

        .feature-box-icon svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        /* Primary Card Icon Badge: Translucent White */
        .feature-box-primary .feature-box-icon {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            backdrop-filter: blur(4px);
            box-shadow: none;
        }

        /* Card Content Body */
        .feature-box-body {
            display: flex;
            flex-direction: column;
        }

        .feature-box-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0b192c;
            margin-bottom: 0.45rem;
            line-height: 1.3;
        }

        .feature-box-desc {
            font-size: 0.8875rem;
            color: #64748b;
            line-height: 1.55;
            margin: 0;
        }

        /* Primary Card Typography */
        .feature-box-primary .feature-box-title {
            color: #ffffff;
        }

        .feature-box-primary .feature-box-desc {
            color: rgba(255, 255, 255, 0.88);
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .features-layout {
                grid-template-columns: 1fr;
                gap: 3.5rem;
            }
            .features-intro {
                max-width: 600px;
                margin: 0 auto;
                text-align: center;
                align-items: center;
            }
            .features-main-desc {
                margin-left: auto;
                margin-right: auto;
            }
            .features-cards-grid {
                max-width: 640px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .features-cards-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .feature-box {
                padding: 1.75rem 1.5rem;
                min-height: auto;
            }
            .feature-box-icon {
                margin-bottom: 1.75rem;
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

        <!-- 3. Fitur Utama Section (Clean Split Layout Sesuai Referensi) -->
        <section id="features" class="features-section">
            <div class="features-container">
                <div class="features-layout">
                    <!-- Left Column: Intro & CTA -->
                    <div class="features-intro">
                        <div class="features-pill-tag">Fitur Utama</div>
                        <h2 class="features-main-title">
                            Solusi terpadu penanganan barang hilang di kampus
                        </h2>
                        <p class="features-main-desc">
                            Platform resmi civitas Universitas Mercu Buana untuk melaporkan, melacak, serta mengembalikan barang tertinggal dengan alur verifikasi resmi yang aman dan terpercaya.
                        </p>
                        <div class="features-cta-wrap">
                            <a href="{{ route('home') }}" class="features-cta-btn">
                                Buka Aplikasi
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: 2x2 Clean Cards Grid -->
                    <div class="features-cards-grid">
                        <!-- Card 1: Featured (Hero Blue Background) -->
                        <div class="feature-box feature-box-primary">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="12" y1="18" x2="12" y2="12"/>
                                    <line x1="9" y1="15" x2="15" y2="15"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Pelaporan Cepat</h3>
                                <p class="feature-box-desc">
                                    Publikasikan laporan kehilangan atau penemuan barang secara instan dalam hitungan menit.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2: Validasi Pos Satpam (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <polyline points="9 12 11 14 15 10"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Validasi Pos Satpam</h3>
                                <p class="feature-box-desc">
                                    Barang bernilai tinggi dapat dititipkan dan diverifikasi langsung oleh petugas keamanan kampus.
                                </p>
                            </div>
                        </div>

                        <!-- Card 3: Pencarian Cerdas (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    <line x1="11" y1="8" x2="11" y2="14"/>
                                    <line x1="8" y1="11" x2="14" y2="11"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Pencarian Cerdas</h3>
                                <p class="feature-box-desc">
                                    Filter presisi berdasarkan lokasi gedung, area umum, lantai fakultas, hingga kategori barang.
                                </p>
                            </div>
                        </div>

                        <!-- Card 4: Klaim & Kontak Aman (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Klaim & Kontak Aman</h3>
                                <p class="feature-box-desc">
                                    Validasi bukti kepemilikan khusus sebelum serah terima dengan data kontak pelapor yang terlindungi.
                                </p>
                            </div>
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
