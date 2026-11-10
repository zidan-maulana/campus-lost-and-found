<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>UMBFind — Layanan Pelaporan & Pencarian Barang Kampus</title>
    <meta name="description" content="Platform resmi pelaporan dan penemuan barang hilang civitas Universitas Mercu Buana. Cepat, aman, dan saling terhubung.">
    <meta name="theme-color" content="#0c487b">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            color: var(--color-blue-brand);
            line-height: 1.15;
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
                font-size: 1.18rem;
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
                        linear-gradient(180deg, #106ec2 0%, #197fce 32%, #35a5ea 58%, #6ec2f4 72%, #aee0fc 84%, #e8f6ff 93%, #ffffff 100%);
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
            padding: 0.82rem 1.85rem;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 18px rgba(10, 48, 84, 0.18);
            transition: background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
            text-decoration: none;
            transform: none;
            line-height: 1.25;
            box-sizing: border-box;
            height: 46px;
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
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
            flex-shrink: 0;
            margin: 0;
        }

        /* Outline Secondary Button: Buka Aplikasi */
        .hero-outline-btn {
            background: transparent;
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.82rem 1.85rem;
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
            box-sizing: border-box;
            height: 46px;
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
            /* 1. Sembunyikan navbar di mobile agar layar bersih & lega */
            .landing-header {
                display: none !important;
            }
            .hero-section {
                padding-top: 3.75rem;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            /* 2. Headline Hero Lebih Ringkas & Berjarak Nyaman dari Frame Kanan Kiri */
            .hero-title {
                font-size: clamp(1.3rem, 4.3vw, 1.55rem);
                font-weight: 600;
                line-height: 1.25;
                letter-spacing: -0.025em;
                margin-bottom: 0.75rem;
                max-width: 310px;
                padding: 0;
            }
            /* 3. Subtitle Hero Ringkas & Proporsional */
            .hero-subtitle {
                font-size: 0.8125rem;
                line-height: 1.52;
                margin-bottom: 1.6rem;
                padding: 0;
                max-width: 325px;
                color: rgba(255, 255, 255, 0.9);
            }
            /* 4. Button Sizing Seimbang, Proporsional & Presisi */
            .hero-actions-wrap {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                width: 100%;
                max-width: 310px;
                margin: 0 auto;
                padding: 0;
            }
            .hero-download-btn,
            .hero-outline-btn {
                flex: 1 1 0;
                min-width: 0;
                height: 42px;
                padding: 0 0.95rem;
                font-size: 0.8125rem;
                font-weight: 600;
                border-radius: 9999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                white-space: nowrap;
                box-sizing: border-box;
                gap: 0.45rem;
            }
            .hero-download-btn svg {
                width: 15px;
                height: 15px;
                margin: 0;
                stroke-width: 2.2;
                flex-shrink: 0;
            }
            .hero-mockup-stage {
                margin-top: 1.75rem;
            }
            .hero-mockup-wrapper {
                max-width: 86%;
                transform: translateX(-5%);
            }
        }

        /* ----------------------------------------------------
           Fitur Utama Clean Split Layout (Sesuai Referensi)
           ---------------------------------------------------- */
        .features-section {
            position: relative;
            padding: 5.5rem 1.5rem 6.5rem 1.5rem;
            background: #ffffff;
            border-top: none;
            scroll-margin-top: 6.5rem;
        }

        .features-container {
            max-width: 1160px;
            margin: 0 auto;
        }

        /* 2-Column Split Layout: Align Stretch agar Kiri & Kanan Sejajar Atas-Bawah */
        .features-layout {
            display: grid;
            grid-template-columns: 1.05fr 1.25fr;
            gap: 4.5rem;
            align-items: stretch;
        }

        /* Left Column: Heading & CTA Sejajar Atas-Bawah dengan Cards */
        .features-intro {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            height: 100%;
        }

        .features-intro-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .features-main-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: clamp(2.1rem, 3vw, 2.55rem);
            font-weight: 700;
            color: #0b192c;
            line-height: 1.22;
            letter-spacing: -0.03em;
            margin: 0 0 1.15rem 0;
            max-width: 520px;
        }

        .features-main-desc {
            font-size: 0.975rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
            max-width: 440px;
        }

        .features-cta-wrap {
            display: flex;
            align-items: center;
            margin-top: 2.5rem;
        }

        /* CTA Button: Warna Sama Persis dengan Button Masuk di Navbar, Tanpa Shadow & Tanpa Arrow */
        .features-cta-btn {
            background: linear-gradient(135deg, #0c5ea8 0%, #1572b8 55%, #2aa5e0 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.8rem 2.2rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            transition: background 0.2s ease;
        }

        .features-cta-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 55%, #2499d3 100%);
            color: #ffffff;
            box-shadow: none;
            transform: none;
        }

        /* Right Column: 2x2 Clean Cards Grid */
        .features-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        /* Base Feature Box (Tanpa Hover Effect & Tanpa Shadow Berlebih) */
        .feature-box {
            background: #f8fafc;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 22px;
            padding: 2.15rem 1.6rem 1.95rem 1.6rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 215px;
            box-shadow: none;
            transform: none;
        }

        .feature-box:hover {
            transform: none;
            box-shadow: none;
            border-color: rgba(226, 232, 240, 0.85);
        }

        /* Featured Box: Pelaporan Cepat (Warna Sama Persis dengan Card Laporkan Barang Sidebar) */
        .feature-box-primary {
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            border: none;
            box-shadow: none;
            transform: none;
        }

        .feature-box-primary:hover {
            transform: none;
            box-shadow: none;
            border-color: transparent;
        }

        /* Circular Icon Badge: Menggunakan Warna/Gradasi Sama dengan Card Laporkan Barang Sidebar */
        .feature-box-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2.5rem;
            flex-shrink: 0;
            box-shadow: none;
        }

        .feature-box-icon svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        /* Primary Card Icon Badge: White Shape dengan Ikon Gradasi */
        .feature-box-primary .feature-box-icon {
            background: #ffffff;
            color: #0c559c;
            backdrop-filter: none;
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
                align-items: flex-start;
            }
            .features-intro {
                height: auto;
                justify-content: flex-start;
                gap: 1.75rem;
                max-width: 600px;
                margin: 0 auto;
                text-align: center;
                align-items: center;
            }
            .features-intro-content {
                align-items: center;
            }
            .features-main-desc {
                margin-left: auto;
                margin-right: auto;
            }
            .features-cta-wrap {
                margin-top: 0;
            }
            .features-cards-grid {
                max-width: 640px;
                margin: 0 auto;
            }
        }

        @media (max-width: 640px) {
            .features-section {
                padding: 3.25rem 1.5rem 3.75rem 1.5rem;
            }
            .features-layout {
                gap: 1.75rem;
            }
            .features-intro {
                margin-bottom: 0;
                gap: 0.85rem;
            }
            .features-main-title {
                font-size: clamp(1.25rem, 4.2vw, 1.45rem);
                line-height: 1.25;
                letter-spacing: -0.025em;
                margin-bottom: 0.6rem;
                text-align: center;
                max-width: 290px;
                margin-left: auto;
                margin-right: auto;
            }
            .features-main-desc {
                font-size: 0.8125rem;
                line-height: 1.52;
                text-align: center;
                max-width: 325px;
                margin: 0 auto;
            }
            .features-cta-wrap {
                margin-top: 0.65rem;
                display: flex;
                justify-content: center;
            }
            .features-cta-btn {
                height: 38px;
                padding: 0 1.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8125rem;
                font-weight: 600;
                border-radius: 9999px;
            }
            .features-cards-grid {
                grid-template-columns: 1fr;
                gap: 0.85rem;
                max-width: 320px;
                margin: 0 auto;
            }
            .feature-box {
                padding: 1.25rem 1.15rem;
                border-radius: 18px;
                min-height: auto;
            }
            .feature-box-icon {
                width: 36px;
                height: 36px;
                margin-bottom: 1rem;
            }
            .feature-box-icon svg {
                width: 17px;
                height: 17px;
            }
            .feature-box-title {
                font-size: 0.975rem;
                margin-bottom: 0.35rem;
            }
            .feature-box-desc {
                font-size: 0.8125rem;
                line-height: 1.48;
            }
        }

        /* ----------------------------------------------------
           FAQ Section (Split Layout Sesuai Referensi Acuan)
           ---------------------------------------------------- */
        .faq-section {
            position: relative;
            padding: 6rem 1.5rem 7.5rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            scroll-margin-top: 6.5rem;
        }

        .faq-container {
            max-width: 1160px;
            margin: 0 auto;
        }

        .faq-layout {
            display: grid;
            grid-template-columns: 1fr 1.65fr;
            gap: 4.5rem;
            align-items: flex-start;
        }

        /* Left Column: Heading and Category Tabs */
        .faq-sidebar {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .faq-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: clamp(2.15rem, 3.2vw, 2.75rem);
            font-weight: 700;
            color: #0b192c;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin: 0 0 2.75rem 0;
        }

        /* Category Selector List */
        .faq-nav-tabs {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            width: 100%;
            max-width: 260px;
        }

        .faq-tab-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.8rem 1.45rem;
            border-radius: 9999px;
            border: none;
            background: transparent;
            color: #64748b;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .faq-tab-btn:hover {
            color: #0c559c;
            background: #f8fafc;
        }

        /* Shape Biru Kiri: Gradient Blue Sesuai Button & Card */
        .faq-tab-btn.active {
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: none;
        }

        /* Right Column: Clean Accordion List */
        .faq-content {
            display: flex;
            flex-direction: column;
        }

        .faq-group {
            display: none;
            flex-direction: column;
        }

        .faq-group.active {
            display: flex;
        }

        .faq-item {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem 0;
            transition: border-color 0.2s ease;
        }

        .faq-item:first-child {
            padding-top: 0.25rem;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question-btn {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-align: left;
            gap: 1.5rem;
        }

        .faq-question-text {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #0b192c;
            line-height: 1.45;
            transition: color 0.2s ease;
        }

        .faq-item.open .faq-question-text,
        .faq-question-btn:hover .faq-question-text {
            color: #0c559c;
        }

        /* Toggle Icon: Geometric + to — */
        .faq-toggle-icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #475569;
            transition: color 0.2s ease;
        }

        .faq-item.open .faq-toggle-icon {
            color: #0b192c;
        }

        .faq-toggle-svg {
            width: 18px;
            height: 18px;
        }

        .faq-vertical-line {
            transition: transform 0.22s ease, opacity 0.22s ease;
            transform-origin: center;
        }

        .faq-item.open .faq-vertical-line {
            transform: scaleY(0);
            opacity: 0;
        }

        /* Answer Body with Smooth Height Transition */
        .faq-answer {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.28s ease, margin-top 0.35s ease;
        }

        .faq-item.open .faq-answer {
            max-height: 400px;
            opacity: 1;
            margin-top: 0.85rem;
        }

        .faq-answer p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.68;
            margin: 0;
            max-width: 680px;
        }

        /* FAQ Responsive */
        @media (max-width: 992px) {
            .faq-layout {
                grid-template-columns: 1fr;
                gap: 3.5rem;
            }
            .faq-sidebar {
                align-items: center;
                text-align: center;
            }
            .faq-title {
                margin-bottom: 2rem;
                text-align: center;
            }
            .faq-nav-tabs {
                flex-direction: row;
                max-width: 100%;
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .faq-tab-btn {
                width: auto;
                padding: 0.65rem 1.15rem;
            }
        }

        @media (max-width: 640px) {
            .faq-section {
                padding: 3.5rem 1.5rem 4rem 1.5rem;
            }
            .faq-sidebar {
                align-items: center;
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .faq-title {
                font-size: clamp(1.3rem, 4.4vw, 1.55rem);
                line-height: 1.25;
                letter-spacing: -0.025em;
                margin-bottom: 1.25rem;
                text-align: center;
                max-width: 290px;
                margin-left: auto;
                margin-right: auto;
            }
            .faq-title br {
                display: block;
            }
            /* Tab Pills Horizontal Smooth Scroll & Centered */
            .faq-nav-tabs {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                gap: 0.35rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                width: 100%;
                padding: 0.2rem 0.2rem 0.4rem 0.2rem;
                margin-bottom: 1.65rem;
            }
            .faq-nav-tabs::-webkit-scrollbar {
                display: none;
            }
            .faq-tab-btn {
                flex-shrink: 0;
                white-space: nowrap;
                padding: 0.45rem 0.75rem;
                font-size: 0.75rem;
                font-weight: 500;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 9999px;
            }
            .faq-content {
                max-width: 330px;
                margin: 0 auto;
                width: 100%;
            }
            .faq-item {
                padding: 1rem 0;
            }
            .faq-question-btn {
                padding: 0.25rem 0;
                gap: 0.75rem;
                word-break: normal;
                overflow-wrap: break-word;
                hyphens: none;
            }
            .faq-question-text {
                font-size: 0.885rem;
                line-height: 1.4;
            }
            .faq-toggle-icon {
                width: 22px;
                height: 22px;
            }
            .faq-toggle-svg {
                width: 15px;
                height: 15px;
            }
            .faq-answer p {
                font-size: 0.8rem;
                line-height: 1.55;
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

        /* ----------------------------------------------------
           5. Minimalist Clean Footer (Sesuai Referensi Acuan Graphy)
           ---------------------------------------------------- */
        .landing-footer {
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            color: #64748b;
            font-size: 0.875rem;
            position: relative;
        }

        .footer-container {
            max-width: 1160px;
            margin: 0 auto;
            padding: 5rem 1.5rem 3.5rem 1.5rem;
        }

        .footer-top-layout {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 4.5rem;
        }

        /* Left Column: Brand, Desc, Socials */
        .footer-brand-side {
            max-width: 380px;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            transition: opacity 0.15s ease;
        }

        .footer-brand:hover {
            opacity: 0.9;
        }

        .footer-brand-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            display: block;
        }

        .footer-brand-name {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
            color: var(--color-blue-brand);
            line-height: 1.15;
        }

        .footer-brand-name span {
            color: var(--color-navy-brand);
        }

        .footer-brand-desc {
            font-size: 0.875rem;
            line-height: 1.6;
            color: #64748b;
            margin: 0;
        }

        .footer-social-row {
            display: flex;
            align-items: center;
            gap: 1.15rem;
            margin-top: 0.35rem;
        }

        .footer-social-btn {
            color: #334155;
            transition: color 0.15s ease, transform 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .footer-social-btn:hover {
            color: #0b192c;
            transform: translateY(-1px);
        }

        /* Right Columns: Essential Categories Only */
        .footer-columns-side {
            display: flex;
            gap: 5rem;
        }

        .footer-column {
            display: flex;
            flex-direction: column;
            min-width: 140px;
        }

        .footer-col-heading {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0b192c;
            margin: 0 0 1.25rem 0;
            letter-spacing: -0.01em;
        }

        .footer-col-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .footer-nav-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.15s ease;
            white-space: nowrap;
        }

        .footer-nav-link:hover {
            color: #0b192c;
        }

        /* Divider & Bottom Section */
        .footer-divider-line {
            height: 1px;
            background-color: #f1f5f9;
            margin: 3.5rem 0 2rem 0;
            border: none;
        }

        .footer-bottom-layout {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .footer-copyright {
            margin: 0;
        }

        .footer-bottom-links {
            display: flex;
            align-items: center;
            gap: 1.75rem;
        }

        .footer-legal-anchor {
            color: #64748b;
            font-size: 0.8125rem;
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: #cbd5e1;
            transition: color 0.15s ease, text-decoration-color 0.15s ease;
        }

        .footer-legal-anchor:hover {
            color: #0b192c;
            text-decoration-color: #0b192c;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .footer-top-layout {
                flex-direction: column;
                gap: 2.75rem;
            }
            .footer-brand-side {
                max-width: 520px;
            }
            .footer-columns-side {
                width: 100%;
                justify-content: flex-start;
                gap: 4rem;
            }
        }

        @media (max-width: 640px) {
            .footer-container {
                padding: 2.75rem 1.5rem 1.75rem 1.5rem;
                max-width: 340px;
                margin: 0 auto;
            }
            .footer-brand-side {
                gap: 0.95rem;
            }
            .footer-brand-logo {
                width: 28px;
                height: 28px;
            }
            .footer-brand-name {
                font-size: 1.15rem;
            }
            .footer-brand-desc {
                font-size: 0.8rem;
                line-height: 1.52;
                max-width: 325px;
            }
            .footer-social-row {
                gap: 1rem;
                margin-top: 0.15rem;
            }
            .footer-social-btn svg {
                width: 16px;
                height: 16px;
            }
            .footer-columns-side {
                display: flex;
                flex-direction: row;
                gap: 3rem;
            }
            .footer-column {
                min-width: auto;
            }
            .footer-col-heading {
                font-size: 0.8125rem;
                margin-bottom: 0.85rem;
            }
            .footer-col-list {
                gap: 0.65rem;
            }
            .footer-nav-link {
                white-space: normal;
                word-break: break-word;
                font-size: 0.8rem;
            }
            .footer-divider-line {
                margin: 1.85rem 0 1.25rem 0;
            }
            .footer-bottom-layout {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                font-size: 0.75rem;
            }
            .footer-bottom-links {
                flex-wrap: wrap;
                gap: 1rem;
            }
            .footer-legal-anchor {
                font-size: 0.75rem;
            }
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
                <a href="#faq" class="nav-link-item">Tanya Jawab</a>
            </div>

            <!-- Action Buttons Group (Login) -->
            <div class="nav-actions">
                <a href="{{ (auth()->check()) ? route('home') : route('login') }}" class="nav-login-btn">
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
                Platform resmi civitas Universitas Mercu Buana<br class="block sm:hidden">
                untuk lapor dan temukan barang tertinggal.
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
                <a href="{{ (auth()->check()) ? route('home') : route('login') }}" class="hero-outline-btn">
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
                    <!-- Left Column: Intro & CTA (Sejajar Atas-Bawah dengan Cards, Headtext Max 2 Baris, Subtext Max 3 Baris) -->
                    <div class="features-intro">
                        <div class="features-intro-content">
                            <h2 class="features-main-title">
                                Solusi Temukan Barang<br>Hilang di Area Kampus
                            </h2>
                            <p class="features-main-desc">
                                Platform resmi civitas UMB untuk lapor, lacak,<br>
                                dan amankan barang tertinggal secara aman.
                            </p>
                        </div>
                        <div class="features-cta-wrap">
                            <a href="{{ (auth()->check()) ? route('home') : route('login') }}" class="features-cta-btn">
                                Buka Aplikasi
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: 2x2 Clean Cards Grid (Deskripsi Max 2 Baris, Icon Unik, Tanpa Hover Effect) -->
                    <div class="features-cards-grid">
                        <!-- Card 1: Featured (Warna sama persis dengan Card Laporkan Barang Sidebar) -->
                        <div class="feature-box feature-box-primary">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="url(#feature-primary-icon-grad)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <defs>
                                        <linearGradient id="feature-primary-icon-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#0c559c" />
                                            <stop offset="52%" stop-color="#1572b8" />
                                            <stop offset="100%" stop-color="#2aa5e0" />
                                        </linearGradient>
                                    </defs>
                                    <path d="M22 2L11 13"/>
                                    <path d="M22 2L15 22L11 13L2 9L22 2Z"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Pelaporan Cepat</h3>
                                <p class="feature-box-desc">
                                    Lapor barang hilang atau temuan secara cepat dan mudah.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2: Verifikasi Klaim (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="4"/>
                                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                                    <circle cx="12" cy="14" r="1.5" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Verifikasi Klaim</h3>
                                <p class="feature-box-desc">
                                    Penemu memverifikasi pemilik sah lewat pertanyaan rahasia sebelum barang diserahkan.
                                </p>
                            </div>
                        </div>

                        <!-- Card 3: Pencarian Cerdas (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Pencarian Cerdas</h3>
                                <p class="feature-box-desc">
                                    Filter pencarian menurut gedung, area, dan kategori.
                                </p>
                            </div>
                        </div>

                        <!-- Card 4: Klaim & Kontak Aman (Light Style) -->
                        <div class="feature-box">
                            <div class="feature-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="m16 11 2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="feature-box-body">
                                <h3 class="feature-box-title">Klaim & Kontak Aman</h3>
                                <p class="feature-box-desc">
                                    Klaim ciri khusus dengan kontak pelapor terlindungi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Frequently Asked Questions (FAQ) Section (Sesuai Referensi Acuan) -->
        <section id="faq" class="faq-section">
            <div class="faq-container">
                <div class="faq-layout">
                    <!-- Left Column: Title & Category Tabs (Tanpa Badge) -->
                    <div class="faq-sidebar">
                        <h2 class="faq-title">
                            Pertanyaan yang <br class="faq-br-desktop">sering diajukan
                        </h2>
                        <div class="faq-nav-tabs" role="tablist">
                            <button type="button" class="faq-tab-btn active" onclick="switchFAQTab('general', this)" role="tab" aria-selected="true">
                                Umum
                            </button>
                            <button type="button" class="faq-tab-btn" onclick="switchFAQTab('account', this)" role="tab" aria-selected="false">
                                Akun &amp; Keamanan
                            </button>
                            <button type="button" class="faq-tab-btn" onclick="switchFAQTab('reporting', this)" role="tab" aria-selected="false">
                                Pelaporan &amp; Klaim
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Accordion Questions & Answers -->
                    <div class="faq-content">
                        <!-- Group 1: Umum (Active by Default) -->
                        <div id="faq-group-general" class="faq-group active">
                            <!-- Question 1 (Open by Default Sesuai Referensi) -->
                            <div class="faq-item open">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Untuk apa platform UMBFind digunakan?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        UMBFind adalah platform resmi civitas akademika Universitas Mercu Buana untuk melaporkan, menelusuri, dan mengamankan barang hilang atau temuan di seluruh gedung dan area kampus secara terpusat, aman, dan transparan.
                                    </p>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Siapa saja yang dapat menggunakan platform ini?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Seluruh civitas akademika Universitas Mercu Buana, mencakup mahasiswa aktif, dosen, staf administrasi, hingga petugas keamanan (satpam) kampus Meruya maupun kampus lainnya.
                                    </p>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Apakah layanan pelaporan ini dipungut biaya?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Tidak ada biaya sama sekali. UMBFind merupakan fasilitas resmi kampus yang disediakan secara gratis untuk mempermudah civitas menjaga rasa aman dan saling peduli.
                                    </p>
                                </div>
                            </div>

                            <!-- Question 4 -->
                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Bagaimana jika barang tertinggal di area sekitar kampus?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Fokus utama sistem adalah gedung dan fasilitas kampus UMB. Namun jika barang tertinggal di gerbang kampus, halte terdekat, atau kantin luar, Anda tetap dapat melaporkannya dengan memilih area umum terdekat dan menyertakan deskripsi spesifik.
                                    </p>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Apakah platform ini bisa diakses lewat smartphone?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Bisa. UMBFind dirancang sebagai Progressive Web App (PWA) yang ringan dan responsif, dapat diakses langsung lewat browser smartphone atau disimpan ke Home Screen tanpa repot mengunduh dari app store.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Group 2: Akun & Keamanan -->
                        <div id="faq-group-account" class="faq-group">
                            <div class="faq-item open">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Bagaimana cara masuk ke akun UMBFind?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Civitas dapat masuk menggunakan akun email kampus terdaftar atau nomor identitas resmi (NIM untuk mahasiswa, NIP untuk dosen/staf) yang tersinkronisasi dengan sistem autentikasi kampus.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Apakah nomor kontak dan data pribadi saya aman?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Data pribadi seperti nomor telepon tidak ditampilkan bebas ke publik. Proses verifikasi dan komunikasi awal antar civitas dilakukan secara terlindungi di dalam platform UMBFind.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Bagaimana jika saya lupa kata sandi akun?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Anda dapat menggunakan opsi 'Lupa Kata Sandi' pada menu Masuk untuk menerima instruksi pemulihan melalui email kampus Anda, atau mendatangi unit bantuan IT/Admin kampus.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Apakah tamu atau masyarakat luar kampus bisa melapor?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Pelaporan memerlukan akun dengan email kampus yang sudah diverifikasi. Tamu atau pengunjung umum diarahkan menghubungi petugas keamanan kampus, dan petugas yang memiliki akun dapat mencatatkan temuan tersebut ke sistem.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Group 3: Pelaporan & Klaim -->
                        <div id="faq-group-reporting" class="faq-group">
                            <div class="faq-item open">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Bagaimana langkah melaporkan barang yang ditemukan?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Klik tombol 'Buat Laporan', pilih opsi 'Barang Temuan', cantumkan detail lokasi gedung/ruangan dan foto barang. Anda juga bisa langsung membawanya ke Pos Satpam terdekat untuk penitipan aman.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Bagaimana cara membuktikan klaim kepemilikan barang?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Pemilik wajib memberikan bukti spesifik yang valid, seperti ciri khusus yang tidak ditampilkan di foto publik, bukti nota/struk pembelian, kartu identitas civitas, atau membuka kunci perangkat (untuk gawai).
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Apakah barang berharga aman dititipkan di Pos Satpam?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        Penitipan fisik di luar aplikasi sepenuhnya menjadi tanggung jawab penemu. UMBFind hanya mencatat keterangan tempat penyimpanan pada laporan barang temuan, dan menyarankan serah terima dilakukan di area kampus yang ramai.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button type="button" class="faq-question-btn" onclick="toggleFAQ(this)">
                                    <span class="faq-question-text">Berapa lama batas waktu penyimpanan barang temuan?</span>
                                    <span class="faq-toggle-icon">
                                        <svg class="faq-toggle-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                            <line x1="12" y1="5" x2="12" y2="19" class="faq-vertical-line"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </span>
                                </button>
                                <div class="faq-answer">
                                    <p>
                                        UMBFind tidak mengatur batas waktu penyimpanan fisik barang. Laporan barang temuan tetap tayang sampai diklaim dan diserahterimakan, atau dibatalkan oleh penemu. Kebijakan penitipan fisik mengikuti tata tertib kampus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- 5. Footer Section (Persis Sesuai Referensi Layout Graphy) -->
    <footer class="landing-footer">
        <div class="footer-container">
            <!-- Top Layout: Brand (Left) & Columns (Right) -->
            <div class="footer-top-layout">
                <!-- Left: Logo, Description & Social Icons -->
                <div class="footer-brand-side">
                    <a href="{{ route('landing') }}" class="footer-brand" aria-label="Beranda UMBFind">
                        <picture>
                            <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo UMBFind" class="footer-brand-logo" width="32" height="32" loading="lazy">
                        </picture>
                        <span class="footer-brand-name"><span>UMB</span>Find</span>
                    </a>
                    
                    <p class="footer-brand-desc">
                        Platform resmi civitas Universitas Mercu Buana<br class="block sm:hidden">
                        untuk mencari dan melaporkan barang hilang.
                    </p>

                    <!-- Social Media / Campus Icons (X, Instagram, LinkedIn, GitHub) -->
                    <div class="footer-social-row">
                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/univmercubuana" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="X (Twitter) Mercu Buana">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="https://instagram.com/univmercubuana" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Instagram Mercu Buana">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                            </svg>
                        </a>
                        <!-- LinkedIn -->
                        <a href="https://linkedin.com/school/universitas-mercu-buana" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="LinkedIn Mercu Buana">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 8.76c-.95 0-1.72-.77-1.72-1.72s.77-1.72 1.72-1.72 1.72.77 1.72 1.72-.77 1.72-1.72 1.72m1.4 9.74v-8.37H5.06v8.37z"/>
                            </svg>
                        </a>
                        <!-- GitHub / Portal -->
                        <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="GitHub Repository">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right: Essential, Functional Columns Only (Tanpa Filler) -->
                <div class="footer-columns-side">
                    <!-- Column 1: Platform -->
                    <div class="footer-column">
                        <h3 class="footer-col-heading">Platform</h3>
                        <ul class="footer-col-list">
                            <li><a href="#features" class="footer-nav-link">Fitur Utama</a></li>
                            <li><a href="{{ route('search') }}" class="footer-nav-link">Cari Barang</a></li>
                            <li><a href="{{ (auth()->check()) ? route('home') : route('login') }}" class="footer-nav-link">Lapor Barang</a></li>
                            <li><a href="javascript:void(0)" onclick="handlePWAInstall()" class="footer-nav-link">Aplikasi PWA</a></li>
                        </ul>
                    </div>

                    <!-- Column 2: Bantuan & Info -->
                    <div class="footer-column">
                        <h3 class="footer-col-heading">Bantuan</h3>
                        <ul class="footer-col-list">
                            <li><a href="#faq" class="footer-nav-link">Tanya Jawab (FAQ)</a></li>
                            <li><a href="mailto:admin.lostfound@mercubuana.ac.id" class="footer-nav-link">admin.lostfound@mercubuana.ac.id</a></li>
                            <li><a href="https://www.mercubuana.ac.id" target="_blank" rel="noopener noreferrer" class="footer-nav-link">Universitas Mercu Buana</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Divider Line -->
            <hr class="footer-divider-line">

            <!-- Bottom Row: Copyright (Left) & Legal Links (Right) -->
            <div class="footer-bottom-layout">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} UMBFind. All rights reserved.
                </p>
                <div class="footer-bottom-links">
                    <a href="#faq" class="footer-legal-anchor">Privacy Policy</a>
                    <a href="#faq" class="footer-legal-anchor">Terms of Service</a>
                    <a href="javascript:void(0)" onclick="handlePWAInstall()" class="footer-legal-anchor">Cookies Settings</a>
                </div>
            </div>
        </div>
    </footer>

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
                <a href="{{ (auth()->check()) ? route('home') : route('login') }}" class="nav-btn-app" style="flex: 1; justify-content: center; padding: 0.75rem;">
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
        });

        function handlePWAInstall() {
            // Check if native Chrome/Edge/Android PWA prompt is ready
            if (deferredPWAInstallPrompt) {
                deferredPWAInstallPrompt.prompt();
                deferredPWAInstallPrompt.userChoice.then((choiceResult) => {
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

        /* FAQ Interactive Logic: Tab Switching & Accordion Toggle */
        function switchFAQTab(category, btn) {
            document.querySelectorAll('.faq-tab-btn').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');

            document.querySelectorAll('.faq-group').forEach(group => {
                group.classList.remove('active');
            });
            const targetGroup = document.getElementById('faq-group-' + category);
            if (targetGroup) {
                targetGroup.classList.add('active');
            }
        }

        function toggleFAQ(button) {
            const item = button.closest('.faq-item');
            if (!item) return;

            const isOpen = item.classList.contains('open');
            const group = item.closest('.faq-group');

            // Tutup item lain dalam grup yang sama untuk pengalaman akordion yang rapi
            if (group) {
                group.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('open');
                    }
                });
            }

            if (isOpen) {
                item.classList.remove('open');
            } else {
                item.classList.add('open');
            }
        }
    </script>
</body>
</html>
