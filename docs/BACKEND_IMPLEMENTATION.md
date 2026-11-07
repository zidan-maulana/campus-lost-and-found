# Catatan Implementasi Backend — Campus Lost & Found

Dokumen ini mencatat keputusan implementasi backend yang dibuat di luar dokumentasi perancangan, beserta alasannya. Semua perubahan ini **tidak mengubah tampilan antarmuka** yang sudah ada.

## 1. Basis Data: Supabase (PostgreSQL) menggantikan MySQL

- Sesuai instruksi pemilik proyek, basis data menggunakan Supabase PostgreSQL 17, bukan MySQL seperti rancangan awal.
- Koneksi: **Session pooler** (port 5432) untuk migration dan query transaksional.
- Seluruh migration bersifat portabel (enum menjadi `VARCHAR + CHECK`, partial unique index didukung Postgres & SQLite untuk pengujian).
- `.env` berisi kredensial nyata dan **tidak masuk version control**; `.env.example` memuat templat aman.

### Tabel tambahan di luar rancangan awal (`DATABASE_DESIGN.md`)

| Tabel | Alasan |
|---|---|
| `saved_reports` | Halaman "Laporan Tersimpan" sudah menjadi bagian antarmuka yang ada; datanya disimpan di server agar sinkron antarperangkat, bukan hanya localStorage. |
| Kolom `users.preferences` | Menampung preferensi halaman Pengaturan yang sudah ada di UI. |
| Kolom `users.nim`, `phone`, `avatar_path`, `last_seen_at` | Diperlukan kontrak data profil yang sudah diminta antarmuka. |

### Kolom laporan tambahan (null-able)

`brand`, `campus_location`, `storage_note`, `incident_time` — melengkapi kontrak data kartu laporan di UI tanpa mengubah tampilan. `location` tetap dipakai sebagai lokasi spesifik.

## 2. Arsitektur

```
app/
├── Enums/            # Status laporan, klaim, akun (nilai sesuai SYSTEM_DESIGN 10)
├── Models/           # 10 model + relasi + kontrak toUiArray() untuk UI
├── Services/
│   ├── ClaimService.php        # state machine klaim + transaksi + row lock
│   ├── HandoverService.php     # konfirmasi serah terima dua arah
│   ├── ChatService.php         # privacy chat + retensi 30 hari
│   ├── ReportService.php       # CRUD laporan + batasan status
│   ├── NotificationService.php # notifikasi dalam aplikasi
│   └── ModerationService.php   # DEC-026 (peran pengklaim vs penemu)
├── Http/
│   ├── Controllers/            # halaman + API (prefix /api)
│   └── Middleware/             # active (status akun), admin (peran)
└── Console/Commands/           # scheduler (pengingat 72j, expiry 120j, purge chat 30h)
```

- Semua mutasi status dibungkus `DB::transaction` + `lockForUpdate` untuk menangani konkurensi (matriks transisi SYSTEM_DESIGN 10).
- Partial unique index di database menjaga: **1 klaim aktif per pengguna** dan **1 klaim `in_handover` per laporan** (BR-04/BR-05) meskipun aplikasi tidak sempat memeriksanya.
- Rute API berada dalam sesi yang sama (web middleware + CSRF), sehingga tidak memerlukan Sanctum/token terpisah.

## 3. Sambungan ke UI yang sudah ada (tanpa mengubah markup)

- File baru: `public/js/findumb-api.js` (jembatan) + 2 baris `script` di `layouts/app.blade.php` (CSRF/config + pemuatan file).
- Jembatan mengambil data dari `/api/*`, lalu **menimpa fungsi sumber data milik halaman** (`getUserReports`, `getStoredNotifications`, `getSavedList`, dll.) sehingga renderer asli halaman menggambar data server dengan struktur yang sama persis.
- Daftar kartu laporan diisi ulang dengan menyalin kartu pertama sebagai template (struktur kelas identik).
- Formulir laporan (`report-lost`/`report-found`) dikirim ke `/api/reports` (multipart, mendukung foto sesuai DEC-021).
- Bookmark, notifikasi, dan percakapan memakai endpoint API; fallback berperilaku tenang bila API tidak dapat dijangkau.

## 4. Autentikasi

- Registrasi memvalidasi **domain email kampus** (BR-01): `CAMPUS_EMAIL_DOMAINS` (DEC-016, konfigurabel).
- Verifikasi email diwajibkan untuk fitur inti (BR-02); tautan verifikasi dikirim ke log pada lingkungan lokal.
- Akun `suspended` ditolak masuk dan diputus sesi aktifnya (DEC-026).
- Halaman baru: `/login`, `/register`, `/email/verify` — halaman pendukung yang memang belum ada sebelumnya (UI/UX 6.1–6.3).

## 5. Dashboard Administrator

- Area `/admin` (middleware `admin`) berisi: tinjauan laporan penyalahgunaan, penyembunyian/penampilan laporan, peringatan/penghentian/reaktivasi akun, dan audit trail.
- Tampilan sengaja dibuat sederhana dan konsisten dengan sistem desain FindUMB.
- Administrator tetap **tidak menentukan kepemilikan barang** (BR-06) — tidak ada tindakan klaim di area ini.

## 6. Scheduler (crontab)

```
* * * * * cd /path/proyek && php artisan schedule:run >> /dev/null 2>&1
```

| Perintah | Jadwal | Aturan |
|---|---|---|
| `findumb:claim-reminders` | tiap jam | pengingat 72 jam (DEC-012) |
| `findumb:expire-claims` | tiap jam | kedaluwarsa 120 jam (DEC-024), idempoten |
| `findumb:purge-chat` | 02:00 | hapus pesan 30 hari (BR-09) |

## 7. Pengujian

- `php artisan test` — 44 pengujian (119 assertions) memakai SQLite in-memory; mencakup aturan klaim, serah terima, chat, moderasi, otorisasi, dan bentuk kontrak API.
- Verifikasi fungsional terhadap Supabase juga dijalankan (klaim, siklus 120 jam, serah terima dua pihak, moderasi peran) dan lulus.

## 8. Penyimpanan Foto: Supabase Storage

- Foto laporan & avatar diunggah ke **Supabase Storage** (S3-compatible) via disk `supabase` (`config/filesystems.php`).
- Endpoint S3: `https://<ref>.supabase.co/storage/v1/s3`; URL publik: `.../storage/v1/object/public/<bucket>/<path>`.
- Saat kredensial belum diisi, aplikasi otomatis kembali ke disk lokal `public` (mode pengembangan) — tidak ada error.
- Verifikasi: `php artisan findumb:storage-check` (cek koneksi, buat bucket bila perlu, uji tulis-hapus).
- Persyaratan di Dashboard Supabase: **S3 Access Keys** (Storage → Settings → S3 Access Keys) dan bucket **PUBLIC**.

## 9. Batasan yang disengaja (sesuai ruang lingkup)

- Notifikasi push, lampiran gambar dalam chat, dan integrasi layanan eksternal tidak diimplementasikan (di luar ruang lingkup versi awal).
- PWA (manifest + service worker) belum dikerjakan pada tahap ini; spesifikasi tetap di `docs/PWA_SPECIFICATION.md`.
- Status `hidden` tidak dipulihkan otomatis saat akun reaktivasikan (DEC-026) — prosedur manual ditunda ke fase moderasi lanjutan.