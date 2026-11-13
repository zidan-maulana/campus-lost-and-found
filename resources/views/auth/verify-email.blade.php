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
        .btn {
            display: inline-block; width: 100%; padding: 13px; border: none; cursor: pointer; font-family: inherit;
            background: var(--primary-gradient); color: #fff; font-size: 14px; font-weight: 700;
            border-radius: var(--radius-md); margin-bottom: 10px;
        }
        .btn-ghost {
            display: inline-block; width: 100%; padding: 12px; font-family: inherit; font-size: 13.5px; font-weight: 700;
            color: var(--primary); background: #f0f7fd; border: none; border-radius: var(--radius-md);
            text-decoration: none; text-align: center;
        }
        .alert { padding: 11px 13px; border-radius: 12px; font-size: 12.5px; margin-bottom: 16px; text-align: left; }
        .alert-ok { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .meta { font-size: 12px; color: var(--text-muted); margin-top: 14px; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">✉️</div>
    <h1>Verifikasi Email Kampus</h1>
    <p>
        Kami telah mengirim tautan verifikasi ke <span class="email">{{ auth()->user()->email }}</span>.
        Buka tautan tersebut untuk mengaktifkan fitur inti FindUMB.
    </p>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">Kirim Ulang Tautan Verifikasi</button>
    </form>

    <a href="{{ route('home') }}" class="btn-ghost">Lihat Katalog Laporan</a>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
        @csrf
        <button type="submit" style="background:none;border:none;color:#94a3b8;font-size:12px;cursor:pointer;font-family:inherit;">
            Keluar dari akun ini
        </button>
    </form>

    <p class="meta">Tautan verifikasi berlaku terbatas. Periksa juga folder spam jika belum menerima email.</p>
</div>
</body>
</html>
