# Catatan Progres Pengembangan — Campus Lost & Found

Dokumen ini mencatat riwayat dan status implementasi bertahap proyek Campus Lost & Found Universitas Mercu Buana.

---

## Ringkasan Status Fase

| Fase | Deskripsi | Status | Keterangan |
|---|---|---|---|
| **Fase 1** | Inisialisasi Proyek Laravel & Setup Fondasi | **Selesai** | Kerangka Laravel 12 terpasang, Blade layout & landing page aktif, konfigurasi MySQL siap. |
| **Fase 2** | Autentikasi Pengguna & Profil Civitas | **Selesai** | Registrasi domain kampus, verifikasi email, login/logout, middleware status akun. |
| **Fase 3** | Modul Laporan Barang (Lost & Found) | **Selesai** | Skema `reports`, form laporan, pencarian publik, filter kategori (API + halaman). |
| **Fase 4** | Alur Klaim, Chat Privat & State Machine | **Selesai** | Skema `claims`, `claim_status_histories`, alur 120 jam, pengingat 72 jam, chat privat. |
| **Fase 5** | Konfirmasi Serah Terima Barang (Handover) | **Selesai** | Verifikasi dua arah, pembatalan handover, resolusi laporan, kunci chat. |
| **Fase 6** | Moderasi & Pelaporan Penyalahgunaan | **Selesai** | Admin dashboard, suspensi akun berbasis peran (DEC-026), laporan `hidden`. |
| **Fase 7** | PWA & Optimasi Aksesibilitas | *Belum Dimulai* | Manifest + service worker menyusul berdasarkan `PWA_SPECIFICATION.md`. |
| **Fase 8** | Pengujian Terpadu & Deployment | *Sebagian* | 44 test otomatis lulus; deployment server belum dilakukan. |

---

## Riwayat Pelaksanaan

### Fase 1: Inisialisasi Proyek Laravel & Setup Fondasi (2026-10-24)
- **Status:** Selesai.
- **Komponen Teknis:**
  - PHP Runtime: PHP 8.2.34
  - Composer: 2.10.3
  - Framework: Laravel 12.70.1
  - Template Engine: Blade Server-Side Rendering
  - Client Database: MySQL 8.4.3
- **Hasil:**
  - Struktur direktori Laravel lengkap terpasang di root repository tanpa menimpa dokumentasi.
  - `.env` dan `.env.example` dikonfigurasi aman dengan parameter MySQL (`campus_lost_and_found`). `.env` terabaikan oleh git (`.gitignore`).
  - Layout dasar Blade [`resources/views/layouts/app.blade.php`](file:///c:/Documents/Projects/campus-lost-and-found/resources/views/layouts/app.blade.php) dan halaman awal [`resources/views/welcome.blade.php`](file:///c:/Documents/Projects/campus-lost-and-found/resources/views/welcome.blade.php) telah dibuat menggunakan Vanilla CSS modern.
  - Pengujian `php artisan test` sukses (2 passed, HTTP 200).
  - Server lokal dev `php artisan serve` terverifikasi merespons HTTP 200 OK.
  - Tidak ada fitur bisnis atau migrasi tabel bisnis yang dibuat sebelum waktunya.

### Fase 2–6: Implementasi Backend (2026-11-05)
- **Status:** Fase 2 (Auth), 3 (Laporan), 4 (Klaim & Chat), 5 (Serah Terima), 6 (Moderasi) selesai.
- **Keputusan utama:** Basis data berpindah dari MySQL ke **Supabase (PostgreSQL 17)** sesuai instruksi pemilik proyek. Detail lengkap di [`docs/BACKEND_IMPLEMENTATION.md`](docs/BACKEND_IMPLEMENTATION.md).
- **Komponen Teknis:**
  - 15 migration (10 tabel bisnis + `saved_reports` + kolom tambahan) tervalidasi di SQLite lokal dan berhasil dijalankan di Supabase.
  - 10 model Eloquent, 6 service (klaim, handover, chat, laporan, notifikasi, moderasi), controller halaman + API, 3 middleware, 3 perintah scheduler.
  - Partial unique index di database untuk BR-04/BR-05 (1 klaim aktif per pengguna; 1 `in_handover` per laporan).
  - Antarmuka yang sudah ada **tidak diubah**; data dihidupkan melalui `public/js/findumb-api.js` + 2 baris script di layout.
  - Halaman baru: `/login`, `/register`, `/email/verify`, dan `/admin` (dashboard moderasi).
- **Validasi:**
  - `php artisan test` — 44 pengujian / 119 assertions lulus (SQLite in-memory).
  - Migration + seed berhasil dijalankan di Supabase (10 laporan, 5 pengguna, 3 klaim, chat, notifikasi).
  - Alur bisnis diverifikasi langsung terhadap Supabase: aturan 1 klaim aktif, penolakan larangan klaim ulang, serah terima dua pihak, kedaluwarsa 120 jam, moderasi per peran.
- **Masalah yang tersisa:**
  - PWA (Fase 7) belum dikerjakan.
  - Deployment ke server belum dilakukan.
  - DEC-016 (domain email), DEC-019 (retensi pasca-moderasi), DEC-021 (validasi foto), DEC-023 (browser) masih `Needs Clarification`; nilai sementara dikonfigurasi di `.env`.
