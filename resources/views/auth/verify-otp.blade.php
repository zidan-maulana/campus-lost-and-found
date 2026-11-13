<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Verifikasi OTP — UMBFind</title>
    <meta name="description" content="Verifikasi kode OTP email resmi kampus Universitas Mercu Buana.">
    <meta name="theme-color" content="#0c487b">

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

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
                            navy: { 900: '#0b192c', 800: '#0c487b', 700: '#0c559c' },
                            brand: { blue: '#1572b8', sky: '#2aa5e0', soft: '#e0f2fe' }
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
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #1572b8 0%, #38bdf8 45%, #e0f2fe 75%, #ffffff 100%);
            background-attachment: fixed;
            color: #1e293b;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle Soft Cloud Overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(circle at 15% 25%, rgba(255, 255, 255, 0.4) 0%, transparent 45%),
                radial-gradient(circle at 85% 70%, rgba(255, 255, 255, 0.45) 0%, transparent 50%),
                radial-gradient(circle at 50% 10%, rgba(255, 255, 255, 0.25) 0%, transparent 60%);
            pointer-events: none;
            z-index: 1;
        }

        /* Main Compact Card */
        .otp-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 28px;
            padding: 1.85rem 1.65rem 1.75rem 1.65rem;
            box-shadow: 0 16px 40px -8px rgba(12, 72, 123, 0.16), 0 0 0 1px rgba(226, 232, 240, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Header Navigation */
        .otp-header {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
            min-height: 36px;
        }

        .otp-back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .otp-back-btn:hover {
            background: #f8fafc;
            color: #0c487b;
            border-color: #cbd5e1;
        }

        .otp-back-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Titles */
        .otp-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #0b192c;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .otp-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            line-height: 1.45;
            margin-bottom: 0.5rem;
        }

        .otp-email-badge {
            display: inline-block;
            font-weight: 600;
            color: #0c559c;
            background: #e0f2fe;
            padding: 0.2rem 0.65rem;
            border-radius: 6px;
            word-break: break-all;
            margin-bottom: 1.35rem;
            font-size: 0.8rem;
        }

        /* 6 Digits Container */
        .otp-inputs-grid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.45rem;
            width: 100%;
            margin-bottom: 1.35rem;
        }

        .otp-digit {
            flex: 1;
            min-width: 0;
            height: 48px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            text-align: center;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #0b192c;
            background: #ffffff;
            transition: all 0.18s ease;
            outline: none;
        }

        .otp-digit:focus {
            border-color: #1572b8;
            box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.15);
            background: #f8fafc;
        }

        .otp-digit.filled {
            border-color: #0c559c;
            background: #f0f7fd;
        }

        /* Submit Button */
        .otp-submit-btn {
            width: 100%;
            height: 42px;
            border-radius: 9999px;
            border: none;
            background: linear-gradient(135deg, #1572b8 0%, #2aa5e0 100%);
            color: #ffffff;
            font-size: 0.885rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(21, 114, 184, 0.28);
            transition: all 0.18s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.15rem;
        }

        .otp-submit-btn:hover {
            filter: brightness(1.06);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(21, 114, 184, 0.35);
        }

        /* Resend Section */
        .otp-resend-wrap {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }

        .otp-resend-btn {
            color: #1572b8;
            font-weight: 600;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 0.8rem;
        }

        .otp-resend-btn:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }

        .otp-resend-btn:not(:disabled):hover {
            text-decoration: underline;
        }

        /* Alerts */
        .otp-alert-error {
            width: 100%;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.775rem;
            margin-bottom: 1rem;
            text-align: left;
        }

        .otp-alert-success {
            width: 100%;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #059669;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.775rem;
            margin-bottom: 1rem;
            text-align: left;
        }

        @media (max-width: 480px) {
            .otp-card {
                max-width: 325px;
                padding: 1.65rem 1.25rem;
                border-radius: 24px;
            }

            .otp-inputs-grid {
                gap: 0.35rem;
            }

            .otp-digit {
                height: 44px;
                font-size: 1.2rem;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="otp-card">
        <!-- Header: Back Button & Centered Title -->
        <div class="otp-header">
            <a href="{{ route('register') }}" class="otp-back-btn" title="Kembali ke Pendaftaran" aria-label="Kembali ke Pendaftaran">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            <h1 class="otp-title">Verifikasi Kode OTP</h1>
        </div>
        <p class="otp-subtitle">
            Masukkan 6 digit kode yang dikirim ke:
        </p>
        <span class="otp-email-badge">
            {{ session('pending_email', $pending_email ?? 'email kampus Anda') }}
        </span>

        <!-- Validation / Session Feedback -->
        @if ($errors->any())
            <div class="otp-alert-error" role="alert">
                <ul class="list-disc list-inside space-y-0.5 m-0 p-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="otp-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Verifikasi OTP -->
        <form id="form-otp" method="POST" action="{{ route('verification.otp.post') }}" style="width: 100%;">
            @csrf
            <input type="hidden" name="otp" id="full-otp-input" value="">

            <!-- 6 Digit Input Boxes -->
            <div class="otp-inputs-grid" id="otp-grid">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="0" autocomplete="one-time-code" required autofocus>
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="1">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="2">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="3">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="4">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="5">
            </div>

            <!-- Tombol Verifikasi -->
            <button type="submit" class="otp-submit-btn" id="btn-submit-otp">
                Verifikasi Akun
            </button>
        </form>

        <!-- Kirim Ulang Kode -->
        <div class="otp-resend-wrap">
            <span>Tidak menerima kode?</span>
            <form method="POST" action="{{ route('verification.otp.resend') }}" style="display: inline;">
                @csrf
                <button type="submit" class="otp-resend-btn" id="btn-resend-otp" disabled>
                    Kirim Ulang (<span id="otp-timer">00:59</span>)
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript: OTP Interactivity (Auto-tab, Backspace, Paste) -->
    <script>
        const digits = Array.from(document.querySelectorAll('.otp-digit'));
        const fullOtpInput = document.getElementById('full-otp-input');
        const formOtp = document.getElementById('form-otp');

        function updateFullOtp() {
            const val = digits.map(d => d.value.trim()).join('');
            fullOtpInput.value = val;
            return val;
        }

        digits.forEach((digit, idx) => {
            // Auto advance on typing
            digit.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val ? val[0] : '';

                if (e.target.value) {
                    e.target.classList.add('filled');
                    if (idx < digits.length - 1) {
                        digits[idx + 1].focus();
                    }
                } else {
                    e.target.classList.remove('filled');
                }

                const currentOtp = updateFullOtp();
                if (currentOtp.length === 6) {
                    document.getElementById('btn-submit-otp').focus();
                }
            });

            // Handle backspace
            digit.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (!digit.value && idx > 0) {
                        digits[idx - 1].focus();
                        digits[idx - 1].value = '';
                        digits[idx - 1].classList.remove('filled');
                    } else {
                        digit.value = '';
                        digit.classList.remove('filled');
                    }
                    updateFullOtp();
                }
            });

            // Handle paste 6-digit
            digit.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
                if (pasteData.length > 0) {
                    for (let i = 0; i < digits.length; i++) {
                        if (i < pasteData.length) {
                            digits[i].value = pasteData[i];
                            digits[i].classList.add('filled');
                        }
                    }
                    const nextIndex = Math.min(pasteData.length, digits.length - 1);
                    digits[nextIndex].focus();
                    const fullVal = updateFullOtp();
                    if (fullVal.length === 6) {
                        document.getElementById('btn-submit-otp').focus();
                    }
                }
            });
        });

        // Form submit check
        formOtp.addEventListener('submit', (e) => {
            const val = updateFullOtp();
            if (val.length < 6) {
                e.preventDefault();
                alert('Silakan lengkapi 6 digit kode OTP terlebih dahulu.');
                const firstEmpty = digits.find(d => !d.value);
                if (firstEmpty) firstEmpty.focus();
            }
        });

        // 60-Second Countdown Timer for Resend
        let timeLeft = 59;
        const timerDisplay = document.getElementById('otp-timer');
        const resendBtn = document.getElementById('btn-resend-otp');

        const countdownInterval = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Kirim Ulang Sekarang';
            } else {
                const sec = timeLeft < 10 ? '0' + timeLeft : timeLeft;
                timerDisplay.textContent = `00:${sec}`;
            }
        }, 1000);
    </script>
</body>
</html>

