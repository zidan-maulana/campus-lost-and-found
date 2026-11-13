<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi UMBFind</title>
</head>
<body style="margin:0;padding:24px;background:#F4F7FB;font-family:'Segoe UI',Arial,sans-serif;color:#1F2937;">
    <div style="max-width:520px;margin:0 auto;background:#FFFFFF;border-radius:18px;padding:32px;border:1px solid #E5EAF2;">
        <p style="margin:0 0 8px;font-size:14px;letter-spacing:.12em;text-transform:uppercase;color:#2E7DFF;font-weight:700;">UMBFind</p>
        <h1 style="margin:0 0 16px;font-size:22px;color:#0F172A;">Verifikasi Email Kampus</h1>

        <p style="margin:0 0 12px;font-size:15px;line-height:1.6;">
            Halo {{ $name }}, gunakan kode berikut untuk menyelesaikan verifikasi akun UMBFind Anda:
        </p>

        <p style="margin:24px 0;text-align:center;">
            <span style="display:inline-block;padding:16px 28px;border-radius:14px;background:#EAF2FF;color:#1D4ED8;font-size:30px;font-weight:800;letter-spacing:.35em;">
                {{ $code }}
            </span>
        </p>

        <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#4B5563;">
            Kode ini berlaku <strong>15 menit</strong> dan hanya dapat dipakai satu kali.
            Jangan bagikan kode ini kepada siapa pun, termasuk pihak yang mengaku sebagai admin.
        </p>

        <p style="margin:24px 0 0;font-size:13px;color:#9CA3AF;">
            Jika Anda tidak merasa mendaftar di UMBFind, abaikan email ini.
        </p>
    </div>
</body>
</html>
