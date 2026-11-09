<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — FindUMB</title>
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
        .auth-wrap { width: 100%; max-width: 440px; }
        .auth-brand { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 18px; }
        .auth-brand img { width: 42px; height: 42px; border-radius: 12px; }
        .auth-brand span { font-size: 22px; font-weight: 800; letter-spacing: -0.02em; }
        .auth-brand span b { color: var(--primary); }
        .auth-card {
            background: #fff; border-radius: var(--radius-xl); padding: 24px 22px;
            box-shadow: var(--shadow-elevated); border: 1px solid var(--border-subtle);
        }
        .auth-card h1 { margin: 0 0 6px; font-size: 20px; font-weight: 800; }
        .auth-card p.sub { margin: 0 0 18px; font-size: 13px; color: var(--text-secondary); line-height: 1.55; }
        .row { display: flex; gap: 10px; }
        .row .field { flex: 1; }
        .field { margin-bottom: 13px; }
        .field label { display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px; color: var(--text-secondary); }
        .field input {
            width: 100%; padding: 12px 14px; font-size: 14px; font-family: inherit;
            border: 1.5px solid var(--border-subtle); border-radius: var(--radius-md); background: #fff; color: var(--text-main);
        }
        .field input:focus { outline: none; border-color: var(--primary); }
        .field .hint { font-size: 11.5px; color: var(--text-muted); margin-top: 5px; line-height: 1.45; }
        .btn-primary {
            width: 100%; padding: 13px; border: none; cursor: pointer; font-family: inherit;
            background: var(--primary-gradient); color: #fff; font-size: 14.5px; font-weight: 700;
            border-radius: var(--radius-md); margin-top: 4px;
        }
        .alert { padding: 11px 13px; border-radius: 12px; font-size: 12.5px; margin-bottom: 14px; line-height: 1.5; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .checkline { display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: var(--text-secondary); margin: 6px 0 10px; line-height: 1.5; }
        .checkline input { width: 16px; height: 16px; margin-top: 2px; accent-color: var(--primary); }
        .auth-foot { text-align: center; font-size: 13px; color: var(--text-secondary); margin-top: 16px; }
        .auth-foot a { color: var(--primary); font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-brand">
        <img src="{{ asset('images/logo.png') }}" alt="FindUMB">
        <span>Find<b>UMB</b></span>
    </div>

    <div class="auth-card">
        <h1>Daftar Akun Civitas</h1>
        <p class="sub">Registrasi hanya untuk email resmi kampus. Akun perlu diverifikasi sebelum dapat membuat laporan atau mengajukan klaim.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="field">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama sesuai KTM" required autofocus>
            </div>

            <div class="row">
                <div class="field">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim') }}" placeholder="41520xxxxx">
                </div>
                <div class="field">
                    <label for="phone">No. WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="field">
                <label for="email">Email Kampus</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="nama@student.mercubuana.ac.id" required>
                @if (! empty($domains))
                    <div class="hint">Domain yang diizinkan: {{ implode(', ', $domains) }}</div>
                @endif
            </div>

            <div class="row">
                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Ulangi Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                </div>
            </div>

            <label class="checkline">
                <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                Saya menyetujui aturan penggunaan dan kebijakan privasi FindUMB.
            </label>

            <button type="submit" class="btn-primary">Buat Akun</button>
        </form>
    </div>

    <p class="auth-foot">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
</div>
</body>
</html>
