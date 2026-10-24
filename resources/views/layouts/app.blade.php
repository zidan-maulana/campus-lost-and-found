<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Platform resmi Campus Lost &amp; Found Universitas Mercu Buana - Layanan pelaporan dan pencarian barang hilang atau temuan civitas kampus.">
    <meta name="theme-color" content="#0f172a">

    <title>@yield('title', config('app.name', 'Campus Lost & Found — Universitas Mercu Buana'))</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Core Design System & Tokens -->
    <style>
        :root {
            --font-display: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            --color-bg-main: #090d16;
            --color-bg-card: rgba(18, 24, 38, 0.75);
            --color-bg-card-hover: rgba(26, 35, 54, 0.9);
            --color-border: rgba(255, 255, 255, 0.08);
            --color-border-glow: rgba(59, 130, 246, 0.35);

            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-accent: #f59e0b;
            --color-emerald: #10b981;
            
            --color-text-main: #f8fafc;
            --color-text-muted: #94a3b8;
            --color-text-dim: #64748b;

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-pill: 9999px;

            --shadow-subtle: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
            --shadow-glow: 0 0 25px -5px rgba(59, 130, 246, 0.25);
            --transition-smooth: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-bg-main);
            color: var(--color-text-main);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(59, 130, 246, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(245, 158, 11, 0.08) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Header & Navigation */
        .site-header {
            width: 100%;
            border-bottom: 1px solid var(--color-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(9, 13, 22, 0.85);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: var(--color-text-main);
        }

        .brand-logo-badge {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.15rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-text-group {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--color-text-main);
        }

        .brand-sub {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .phase-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #93c5fd;
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 0.35rem 0.85rem;
            border-radius: var(--radius-pill);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: var(--color-emerald);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--color-emerald);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.9); }
        }

        /* Main Container */
        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        /* Footer */
        .site-footer {
            border-top: 1px solid var(--color-border);
            padding: 2rem 1.5rem;
            background: rgba(9, 13, 22, 0.9);
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            font-size: 0.85rem;
            color: var(--color-text-dim);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-link {
            color: var(--color-text-muted);
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .footer-link:hover {
            color: var(--color-primary);
        }

        @media (max-width: 640px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            .footer-container {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header" id="main-header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="brand-link" id="brand-home-link">
                <div class="brand-logo-badge">UMB</div>
                <div class="brand-text-group">
                    <span class="brand-title">Campus Lost &amp; Found</span>
                    <span class="brand-sub">Universitas Mercu Buana</span>
                </div>
            </a>
            <div class="header-actions">
                <span class="phase-badge" id="app-phase-indicator">
                    <span class="status-dot"></span>
                    Fase 1: Foundation Setup
                </span>
            </div>
        </div>
    </header>

    <main class="main-content" id="app-main-content">
        @yield('content')
    </main>

    <footer class="site-footer" id="main-footer">
        <div class="footer-container">
            <p>&copy; {{ date('Y') }} Campus Lost &amp; Found — Universitas Mercu Buana. Seluruh hak cipta dilindungi.</p>
            <div class="footer-links">
                <span class="footer-link">Laravel 12 / PHP 8.2</span>
                <span class="footer-link">Blade SSR</span>
                <span class="footer-link">MySQL Prepared</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
