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
           Modern Seamless Navigation Bar (Sesuai Referensi, Tanpa Shape)
           ---------------------------------------------------- */
        .landing-header {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: none;
            transition: all 0.2s ease;
        }

        .landing-nav {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.5rem;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            color: var(--color-blue-brand);
        }

        .nav-brand-text span {
            color: var(--color-navy-brand);
        }

        .nav-links {
            display: none;
            align-items: center;
            gap: 2.25rem;
        }

        @media (min-width: 820px) {
            .nav-links {
                display: flex;
            }
        }

        .nav-link-item {
            font-size: 0.9125rem;
            font-weight: 400;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease, font-weight 0.15s ease;
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
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .nav-login-btn {
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            color: #ffffff;
            font-size: 0.885rem;
            font-weight: 500;
            padding: 0.62rem 1.45rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.25;
            transition: background 0.2s ease;
            box-shadow: none;
            letter-spacing: -0.01em;
            transform: none;
        }

        .nav-login-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
            color: #ffffff;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 480px) {
            .landing-nav {
                padding: 0.75rem 1rem;
            }
            .nav-brand-logo {
                width: 30px;
                height: 30px;
            }
            .nav-brand-text {
                font-size: 1.1rem;
            }
            .nav-actions {
                gap: 0.45rem;
            }
            .nav-login-btn {
                padding: 0.52rem 1rem;
                font-size: 0.825rem;
            }
        }

        /* ----------------------------------------------------
           Hero Section Styling
           ---------------------------------------------------- */
        .hero-section {
            position: relative;
            padding-top: 3.5rem;
            padding-bottom: 0;
            background: #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Top Pill Badge */
        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.38rem 1.1rem;
            border-radius: 9999px;
            margin-bottom: 1.5rem;
            box-shadow: none;
        }

        /* Big Hero Headline */
        .hero-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(2.35rem, 4.2vw, 3.65rem);
            font-weight: 600;
            line-height: 1.16;
            letter-spacing: -0.03em;
            color: #0b192c;
            margin: 0 0 2rem 0;
            padding: 0 1rem;
            max-width: 1040px;
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
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.84rem 1.85rem;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: none;
            transition: background 0.2s ease;
            text-decoration: none;
            transform: none;
            line-height: 1.25;
        }

        .hero-download-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
            transform: none;
            box-shadow: none;
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
            color: #1572b8;
            border: 1.5px solid #1572b8;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.84rem 1.85rem;
            border-radius: 9999px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            text-decoration: none;
            transform: none;
            line-height: 1.25;
        }

        .hero-outline-btn:hover {
            background: rgba(21, 114, 184, 0.06);
            border-color: #0c559c;
            color: #0c559c;
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
            transform: translateX(-8%);
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
                transform: translateX(-7%);
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
            <!-- Top Pill Badge -->
            <div class="hero-badge-pill">
                <span>Now available as Progressive Web App (PWA)</span>
            </div>

            <!-- Big Main Headline (2 Baris Presisi & Langsung Dipahami Sekali Baca) -->
            <h1 class="hero-title">
                Cara tercepat temukan<br>
                barang hilang di area kampus.
            </h1>

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
