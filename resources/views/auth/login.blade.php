<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Masuk &amp; Daftar — UMBFind</title>
    <meta name="description" content="Masuk ke platform resmi pelaporan barang hilang civitas Universitas Mercu Buana.">
    <meta name="theme-color" content="#0c487b">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Plus+Jakarta+Sans:ital,wght@0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

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
            --color-blue-sky: #2aa5e0;
            --color-blue-soft: #e0f2fe;
            --color-text-main: #0f172a;
            --color-text-muted: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: radial-gradient(ellipse at 50% 15%, rgba(255, 255, 255, 0.28) 0%, transparent 60%),
                        linear-gradient(180deg, #106ec2 0%, #197fce 32%, #38a5ea 62%, #75c6f6 82%, #bfe5fd 94%, #f1f9ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            color: var(--color-text-main);
            -webkit-font-smoothing: antialiased;
            box-sizing: border-box;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle ambient cloud/glow shapes in background (clean, no 3D) */
        .ambient-glow-1 {
            position: absolute;
            top: 5%;
            left: 8%;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, transparent 70%);
            pointer-events: none;
            filter: blur(40px);
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: 8%;
            right: 6%;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.35) 0%, transparent 70%);
            pointer-events: none;
            filter: blur(50px);
        }

        /* Main Auth Card Container (Clean, Elegant, Rounded 26px) */
        .auth-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 26px;
            box-shadow: 0 20px 48px -12px rgba(12, 72, 123, 0.2), 0 4px 16px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.95);
            padding: 2rem 1.85rem;
            box-sizing: border-box;
            transition: all 0.25s ease;
        }

        /* Card Header Row (Back Button & Centered Title) */
        .auth-header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.15rem;
        }

        .auth-back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.18s ease;
            cursor: pointer;
        }

        .auth-back-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0b192c;
            transform: translateY(-50%) translateX(-2px);
        }

        .auth-back-btn svg {
            width: 15px;
            height: 15px;
            stroke-width: 2.4;
        }

        .auth-title {
            margin: 0;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0b192c;
            letter-spacing: -0.025em;
            text-align: center;
            line-height: 1.25;
        }

        /* Segmented Tab Pill Control (Masuk / Daftar) */
        .auth-segmented-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 1.35rem;
        }

        .auth-segmented-pill {
            background: #f1f5f9;
            border-radius: 9999px;
            padding: 3px;
            display: inline-flex;
            gap: 3px;
            box-sizing: border-box;
            border: 1px solid rgba(226, 232, 240, 0.6);
        }

        .auth-segment-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-family: 'Inter', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.38rem 1.35rem;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1.25;
            user-select: none;
        }

        .auth-segment-btn:hover:not(.active) {
            color: #0b192c;
        }

        .auth-segment-btn.active {
            background: linear-gradient(135deg, #0c5ea8 0%, #1572b8 55%, #2aa5e0 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(12, 94, 168, 0.26);
        }

        /* Form Controls */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .auth-input-group {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .auth-input-icon {
            position: absolute;
            left: 0.95rem;
            color: #94a3b8;
            pointer-events: none;
            width: 16px;
            height: 16px;
            stroke-width: 2;
            transition: color 0.15s ease;
        }

        .auth-input {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0 0.85rem 0 2.65rem;
            font-size: 0.84rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #ffffff;
            box-sizing: border-box;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
            outline: none;
        }

        .auth-input.has-toggle {
            padding-right: 2.65rem;
        }

        .auth-input::placeholder {
            color: #94a3b8;
            font-size: 0.835rem;
        }

        .auth-input:focus {
            border-color: #1572b8;
            box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
        }

        .auth-input-group:focus-within .auth-input-icon {
            color: #1572b8;
        }

        .auth-password-toggle {
            position: absolute;
            right: 0.65rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.35rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .auth-password-toggle:hover {
            color: #475569;
        }

        .auth-password-toggle svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }

        /* Checkbox & Forgot Password Row */
        .auth-extras-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -0.2rem;
            margin-bottom: 0.2rem;
            font-size: 0.8rem;
        }

        .auth-remember-label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
            font-weight: 400;
        }

        .auth-remember-input {
            width: 15px;
            height: 15px;
            border-radius: 4px;
            border: 1.5px solid #cbd5e1;
            cursor: pointer;
            accent-color: #1572b8;
        }

        .auth-forgot-link {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .auth-forgot-link:hover {
            color: #0c5ea8;
            text-decoration: underline;
        }

        /* Primary Submit Button */
        .auth-submit-btn {
            width: 100%;
            height: 44px;
            border-radius: 9999px;
            background: linear-gradient(135deg, #0c5ea8 0%, #1572b8 55%, #2aa5e0 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 0.885rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 3px 14px rgba(12, 94, 168, 0.25);
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.15rem;
        }

        .auth-submit-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 55%, #2499d3 100%);
            box-shadow: 0 5px 18px rgba(12, 94, 168, 0.3);
        }

        .auth-submit-btn:active {
            transform: scale(0.99);
        }

        /* Divider "Atau" */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin: 0.35rem 0;
        }

        .auth-divider-line {
            flex: 1;
            height: 1px;
            background-color: #e2e8f0;
        }

        .auth-divider-text {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        /* Social Login Button ("Masuk dengan Google") */
        .auth-social-btn {
            width: 100%;
            height: 42px;
            border-radius: 9999px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            font-size: 0.835rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            text-decoration: none;
            box-sizing: border-box;
        }

        .auth-social-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .auth-google-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        /* Alert Notifications */
        .auth-alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.785rem;
            margin-bottom: 1rem;
            line-height: 1.45;
        }

        .auth-alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.785rem;
            margin-bottom: 1rem;
            line-height: 1.45;
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            body {
                padding: 1.5rem 1.65rem;
            }
            .auth-card {
                padding: 1.65rem 1.25rem;
                border-radius: 22px;
                max-width: 325px;
                margin: 0 auto;
            }
            .auth-title {
                font-size: 1.35rem;
            }
            .auth-back-btn {
                width: 30px;
                height: 30px;
            }
            .auth-back-btn svg {
                width: 14px;
                height: 14px;
            }
            .auth-segment-btn {
                padding: 0.35rem 1.15rem;
                font-size: 0.775rem;
            }
            .auth-input {
                height: 42px;
                font-size: 0.8125rem;
                border-radius: 11px;
            }
            .auth-submit-btn {
                height: 42px;
                font-size: 0.84rem;
            }
            .auth-social-btn {
                height: 40px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Soft ambient background glows -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Centered Modern Auth Card (Sesuai Referensi Acuan) -->
    <div class="auth-card">
        
        <!-- Top Row: Circular Back Arrow & Centered Heading -->
        <div class="auth-header">
            <a href="{{ route('landing') }}" class="auth-back-btn" title="Kembali ke Beranda" aria-label="Kembali ke Beranda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            <h1 class="auth-title" id="auth-main-title">
                @if(($mode ?? 'login') === 'register')
                    Daftar Akun
                @else
                    Selamat Datang
                @endif
            </h1>
        </div>

        <!-- Segmented Tab Pills (Masuk / Daftar) -->
        <div class="auth-segmented-wrap">
            <div class="auth-segmented-pill" role="tablist">
                <button type="button" 
                        class="auth-segment-btn {{ ($mode ?? 'login') === 'login' ? 'active' : '' }}" 
                        id="tab-btn-login" 
                        onclick="switchAuthTab('login')" 
                        role="tab" 
                        aria-selected="{{ ($mode ?? 'login') === 'login' ? 'true' : 'false' }}">
                    Masuk
                </button>
                <button type="button" 
                        class="auth-segment-btn {{ ($mode ?? 'login') === 'register' ? 'active' : '' }}" 
                        id="tab-btn-register" 
                        onclick="switchAuthTab('register')" 
                        role="tab" 
                        aria-selected="{{ ($mode ?? 'login') === 'register' ? 'true' : 'false' }}">
                    Daftar
                </button>
            </div>
        </div>

        <!-- Validation / Session Feedback -->
        @if ($errors->any())
            <div class="auth-alert-error" role="alert">
                <ul class="list-disc list-inside space-y-0.5 m-0 p-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="auth-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form 1: Mode Masuk (Login) -->
        <form id="form-login" 
              method="POST" 
              action="{{ route('login.post') }}" 
              class="auth-form" 
              style="display: {{ ($mode ?? 'login') === 'login' ? 'flex' : 'none' }};">
            @csrf

            <!-- Alamat Email -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="auth-input" 
                       placeholder="Masukkan email kampus (@mercubuana.ac.id)" 
                       required 
                       autocomplete="email">
            </div>

            <!-- Kata Sandi -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input type="password" 
                       name="password" 
                       id="login-password-input" 
                       class="auth-input has-toggle" 
                       placeholder="Masukkan kata sandi" 
                       required 
                       autocomplete="current-password">
                <button type="button" 
                        class="auth-password-toggle" 
                        onclick="togglePasswordVisibility('login-password-input', this)" 
                        title="Tampilkan / Sembunyikan Kata Sandi"
                        aria-label="Tampilkan / Sembunyikan Kata Sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <!-- Ingat saya & Lupa kata sandi -->
            <div class="auth-extras-row">
                <label class="auth-remember-label">
                    <input type="checkbox" name="remember" class="auth-remember-input" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
                <a href="javascript:void(0)" onclick="alert('Silakan hubungi admin IT UMB atau pos satpam untuk pemulihan akun civitas.')" class="auth-forgot-link">
                    Lupa kata sandi?
                </a>
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="auth-submit-btn">
                Masuk
            </button>

            <!-- Divider Atau -->
            <div class="auth-divider">
                <span class="auth-divider-line"></span>
                <span class="auth-divider-text">Atau</span>
                <span class="auth-divider-line"></span>
            </div>

            <!-- Masuk dengan Google -->
            <a href="javascript:void(0)" onclick="demoGoogleAuth()" class="auth-social-btn">
                <svg class="auth-google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.35 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </form>

        <!-- Form 2: Mode Daftar (Register) -->
        <form id="form-register" 
              method="POST" 
              action="{{ route('register.post') }}" 
              class="auth-form" 
              style="display: {{ ($mode ?? 'login') === 'register' ? 'flex' : 'none' }};">
            @csrf

            <!-- Nama Lengkap -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       class="auth-input" 
                       placeholder="Masukkan nama lengkap" 
                       required 
                       autocomplete="name">
            </div>

            <!-- Alamat Email Kampus -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="auth-input" 
                       placeholder="Masukkan alamat email (@mercubuana.ac.id)" 
                       required 
                       autocomplete="email">
            </div>

            <!-- Kata Sandi Baru -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input type="password" 
                       name="password" 
                       id="register-password-input" 
                       class="auth-input has-toggle" 
                       placeholder="Buat kata sandi baru" 
                       required 
                       autocomplete="new-password">
                <button type="button" 
                        class="auth-password-toggle" 
                        onclick="togglePasswordVisibility('register-password-input', this)" 
                        title="Tampilkan / Sembunyikan Kata Sandi"
                        aria-label="Tampilkan / Sembunyikan Kata Sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <!-- Konfirmasi Kata Sandi (Tulis Ulang) -->
            <div class="auth-input-group">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <input type="password" 
                       name="password_confirmation" 
                       id="register-password-confirm-input" 
                       class="auth-input has-toggle" 
                       placeholder="Tulis ulang kata sandi" 
                       required 
                       autocomplete="new-password">
                <button type="button" 
                        class="auth-password-toggle" 
                        onclick="togglePasswordVisibility('register-password-confirm-input', this)" 
                        title="Tampilkan / Sembunyikan Konfirmasi Kata Sandi"
                        aria-label="Tampilkan / Sembunyikan Konfirmasi Kata Sandi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit" class="auth-submit-btn">
                Daftar Sekarang
            </button>

            <!-- Divider Atau -->
            <div class="auth-divider">
                <span class="auth-divider-line"></span>
                <span class="auth-divider-text">Atau</span>
                <span class="auth-divider-line"></span>
            </div>

            <!-- Daftar dengan Google -->
            <a href="javascript:void(0)" onclick="demoGoogleAuth()" class="auth-social-btn">
                <svg class="auth-google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.35 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                </svg>
                <span>Daftar dengan Google</span>
            </a>
        </form>

    </div>

    <script>
        // Switch between Masuk and Daftar tabs seamlessly
        function switchAuthTab(targetMode) {
            const btnLogin = document.getElementById('tab-btn-login');
            const btnRegister = document.getElementById('tab-btn-register');
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');
            const mainTitle = document.getElementById('auth-main-title');

            if (targetMode === 'register') {
                btnLogin.classList.remove('active');
                btnLogin.setAttribute('aria-selected', 'false');
                btnRegister.classList.add('active');
                btnRegister.setAttribute('aria-selected', 'true');

                formLogin.style.display = 'none';
                formRegister.style.display = 'flex';

                mainTitle.textContent = 'Daftar Akun';
                if (window.history && window.history.pushState) {
                    window.history.pushState(null, '', '{{ route("register") }}');
                }
            } else {
                btnRegister.classList.remove('active');
                btnRegister.setAttribute('aria-selected', 'false');
                btnLogin.classList.add('active');
                btnLogin.setAttribute('aria-selected', 'true');

                formRegister.style.display = 'none';
                formLogin.style.display = 'flex';

                mainTitle.textContent = 'Selamat Datang';
                if (window.history && window.history.pushState) {
                    window.history.pushState(null, '', '{{ route("login") }}');
                }
            }
        }

        // Toggle password visibility (show/hide)
        function togglePasswordVisibility(inputId, toggleBtn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
            } else {
                input.type = 'password';
                toggleBtn.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            }
        }

        // Instant demo Google authentication for preview convenience
        function demoGoogleAuth() {
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput && !emailInput.value) {
                emailInput.value = 'fauzi.mahasiswa@student.mercubuana.ac.id';
            }
            const passInput = document.getElementById('login-password-input');
            if (passInput && !passInput.value) {
                passInput.value = 'password123';
            }
            document.getElementById('form-login').submit();
        }
    </script>
</body>
</html>

