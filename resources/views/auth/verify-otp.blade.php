<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email — FindUMB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1572b8; --primary-gradient: linear-gradient(135deg, #0e5090 0%, #1572b8 60%, #29a0dc 100%);
            --bg-page: #f1f5f9; --text-main: #0f172a; --text-secondary: #475569; --text-muted: #94a3b8;
            --border-subtle: #e2e8f0; --radius-md: 14px; --radius-xl: 24px;
            --shadow-elevated: 0 4px 14px rgba(15, 23, 42, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 24px; background: var(--bg-page); color: var(--text-main);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .card {
            width: 100%; max-width: 440px; background: #fff; border-radius: var(--radius-xl);
            padding: 28px 24px; box-shadow: var(--shadow-elevated); border: 1px solid var(--border-subtle);
            text-align: center;
        }
        .icon-wrap {
            width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 20px;
            background: #f0f7fd; display: flex; align-items: center; justify-content: center; font-size: 28px;
        }
        h1 { margin: 0 0 8px; font-size: 19px; font-weight: 800; }
        p { margin: 0 0 18px; font-size: 13.5px; color: var(--text-secondary); line-height: 1.6; }
        .email { font-weight: 700; color: var(--text-main); }
        .otp-input {
            width: 100%; padding: 16px 12px; margin-bottom: 6px; text-align: center;
            font-family: 'SFMono-Regular', ui-monospace, Menlo, Consolas, monospace;
            font-size: 28px; font-weight: 700; letter-spacing: 10px; text-indent: 10px;
            color: var(--text-main); background: #f8fafc;
            border: 2px solid var(--border-subtle); border-radius: var(--radius-md);
            outline: none; transition: border-color .15s ease, background .15s ease;
        }
        .otp-input:focus { border-color: var(--primary); background: #fff; }
        .otp-input.is-error { border-color: #dc2626; background: #fef2f2; }
        .btn {
            display: inline-block; width: 100%; padding: 13px; border: none; cursor: pointer; font-family: inherit;
            background: var(--primary-gradient); color: #fff; font-size: 14px; font-weight: 700;
            border-radius: var(--radius-md); margin-bottom: 10px;
        }
        .btn:disabled { opacity: .6; cursor: not-allowed; }
        .btn-ghost {
            display: inline-block; width: 100%; padding: 12px; font-family: inherit; font-size: 13.5px; font-weight: 700;
            color: var(--primary); background: #f0f7fd; border: none; border-radius: var(--radius-md);
            text-decoration: none; text-align: center; cursor: pointer;
        }
        .alert { padding: 11px 13px; border-radius: 12px; font-size: 12.5px; margin-bottom: 16px; text-align: left; }
        .alert-ok { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-err { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .meta { font-size: 12px; color: var(--text-muted); margin-top: 14px; }
        .divider { border: 0; border-top: 1px solid var(--border-subtle); margin: 18px 0 14px; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">✉️</div>
    <h1>Verifikasi Email Kampus</h1>
    <p>
        Kode verifikasi 6 digit sudah dikirim ke
        <span class="email">{{ auth()->user()->email }}</span>.
        Masukkan kodenya di bawah ini untuk mengaktifkan fitur inti FindUMB.
    </p>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-err">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('verification.otp.post') }}" id="otp-form" autocomplete="off">
        @csrf
        <input
            type="text"
            name="otp"
            id="otp-input"
            class="otp-input @error('otp') is-error @enderror"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="6"
            placeholder="••••••"
            autocomplete="one-time-code"
            aria-label="Kode OTP 6 digit"
            value="{{ old('otp') }}"
            autofocus
            required
        >
        <button type="submit" class="btn" id="otp-submit">Verifikasi Kode</button>
    </form>

    <form method="POST" action="{{ route('verification.otp.resend') }}">
        @csrf
        <button type="submit" class="btn-ghost">Kirim Ulang Kode OTP</button>
    </form>

    <hr class="divider">

    <a href="{{ route('home') }}" class="btn-ghost" style="background:#fff;color:var(--text-secondary);">Lihat Katalog Laporan</a>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
        @csrf
        <button type="submit" style="background:none;border:none;color:#94a3b8;font-size:12px;cursor:pointer;font-family:inherit;">
            Keluar dari akun ini
        </button>
    </form>

    <p class="meta">Kode berlaku terbatas. Periksa juga folder spam jika belum menerima email.</p>
</div>

<script>
    (function () {
        var input = document.getElementById('otp-input');
        var form = document.getElementById('otp-form');
        var submit = document.getElementById('otp-submit');

        // Hanya angka, maksimal 6.
        input.addEventListener('input', function () {
            var digits = this.value.replace(/\D+/g, '').slice(0, 6);
            if (this.value !== digits) this.value = digits;
            submit.disabled = digits.length !== 6;
            if (digits.length === 6) this.classList.remove('is-error');
        });

        // Tempel kode dari email ("123 456" / "123-456") tetap kebaca.
        input.addEventListener('paste', function (e) {
            var text = (e.clipboardData || window.clipboardData).getData('text') || '';
            var digits = text.replace(/\D+/g, '').slice(0, 6);
            if (digits) {
                e.preventDefault();
                this.value = digits;
                submit.disabled = digits.length !== 6;
                if (digits.length === 6) form.requestSubmit();
            }
        });

        // Auto-submit begitu 6 digit terisi.
        input.addEventListener('keyup', function () {
            if (this.value.length === 6 && !submit.dataset.sent) {
                submit.dataset.sent = '1';
                form.requestSubmit();
            }
        });

        submit.disabled = input.value.replace(/\D+/g, '').length !== 6;
        input.focus();
    })();
</script>
</body>
</html>
