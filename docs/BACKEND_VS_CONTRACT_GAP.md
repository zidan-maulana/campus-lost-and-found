# Penyesuaian Backend terhadap Kontrak Front-End

Dokumen ini mencatat bagaimana backend menyesuaikan diri dengan `docs/API_CONTRACT.md`
yang disusun tim front-end (commit `9e2ad19`, branch `main` Zidan), termasuk deviasi
yang disengaja beserta alasannya.

Status: **selesai diimplementasikan & teruji** (70 test otomatis lulus).
Tanggal: 2026-11-08.

---

## 1. Ringkasan Keputusan

| No | Keputusan | Alasan |
| :-- | :--- | :--- |
| 1 | Endpoint baru di bawah prefix `/api/v1` | Kontrak §2 mewajibkan `/api/v1/*`. Endpoint lama `/api/*` tetap hidup agar `public/js/findumb-api.js` tidak rusak. |
| 2 | Envelope `{ success, message, data, meta }` + `pagination` | Kontrak §2.2–2.3. Error memakai `{ success: false, message, errors, code }` (§2.4). |
| 3 | Autentikasi memakai session cookie, bukan Sanctum token | Sanctum belum terpasang; klien web berjalan di domain yang sama sehingga cookie + `X-CSRF-TOKEN` (§2.1) cukup. Respons login mengembalikan `token: null` dan `auth_mode: "session"`. |
| 4 | Parameter `campus` dipetakan ke kolom `campus_location` | Tidak membuat kolom baru agar tidak ada dua sumber kebenaran. Nilai dinormalisasi ke slug enum (`meruya`, `menteng`, `pejaten`, `warung_buncit`); data lama berlabel `"Kampus Meruya"` tetap terbaca. |
| 5 | Field `image` (kontrak) dan `photo` (UI lama) sama-sama diterima | Menjaga kompatibilitas form Blade yang sudah berjalan. |
| 6 | `proof_question` tidak pernah dikirim ke publik | Kontrak §5.2 *blind information*. Hanya pemilik laporan yang menerima field ini. |
| 7 | Bukti klaim (`proof_answer`, `claim_reason`, `proof_attachment`) hanya untuk dua pihak terkait | Kontrak §5.2 & §6.1. Pengguna lain menerima objek klaim tanpa field bukti. |
| 8 | Verifikasi email memakai **kode OTP 6 digit** | Layar `auth/verify-otp.blade.php` milik front-end butuh OTP. Tautan bertanda tangan bawaan Laravel tetap tersedia sebagai jalur alternatif. |
| 9 | Area admin dilindungi `auth` + role admin (tanpa `verified`) | Kontrak §9 hanya menyebut `auth` + `role:admin`; akun admin hasil seeding tidak selalu punya `email_verified_at`. |

---

## 2. Peta Endpoint

Legenda: **✅** sesuai kontrak · **⚠️** sesuai dengan deviasi terdokumentasi · **➕** tambahan di luar kontrak

### Autentikasi (`/api/v1/auth`)

| Endpoint | Status | Controller |
| :--- | :--- | :--- |
| `POST /auth/login` | ⚠️ (tanpa Sanctum token) | `Api\V1\AuthController@login` |
| `POST /auth/register` | ✅ | `Api\V1\AuthController@register` |
| `GET /auth/me` | ✅ | `Api\V1\AuthController@me` |
| `POST /auth/logout` | ➕ | `Api\V1\AuthController@logout` |

### Laporan (`/api/v1/reports`)

| Endpoint | Status | Controller |
| :--- | :--- | :--- |
| `GET /reports` | ✅ filter `type`, `category_id`, `campus`, `month`, `year`, `status`, `q`, `page`, `per_page`; default `status=open` | `Api\V1\ReportController@index` |
| `GET /reports/{id}` | ✅ termasuk aturan *blind information* | `Api\V1\ReportController@show` |
| `POST /reports` | ✅ `multipart/form-data`, `image`, `proof_question`, `storage_note` | `Api\V1\ReportController@store` |
| `POST /reports/{id}/cancel` | ✅ | `Api\V1\ReportController@cancel` |

### Klaim & serah terima (`/api/v1/claims`)

| Endpoint | Status | Controller |
| :--- | :--- | :--- |
| `POST /reports/{id}/claims` | ✅ `proof_answer`, `claim_reason`, `proof_attachment` | `Api\V1\ClaimController@store` |
| `POST /claims/{id}/accept` | ✅ | `Api\V1\ClaimController@accept` |
| `POST /claims/{id}/reject` | ✅ `rejection_reason` (min 10 karakter) | `Api\V1\ClaimController@reject` |
| `POST /claims/{id}/handover/cancel` | ✅ `cancellation_reason` (min 10 karakter) | `Api\V1\ClaimController@handoverCancel` |
| `POST /claims/{id}/handover/confirm` | ✅ atomik dua pihak | `Api\V1\ClaimController@handoverConfirm` |

### Chat, notifikasi, aktivitas

| Endpoint | Status | Controller |
| :--- | :--- | :--- |
| `GET /chats` | ✅ termasuk `unread_count` per percakapan | `Api\V1\ChatController@index` |
| `GET /claims/{id}/messages` | ✅ `{ claim_id, item_title, status, is_readonly, messages[] }` | `Api\V1\ChatController@messages` |
| `POST /claims/{id}/messages` | ✅ ditolak 403 bila klaim terminal | `Api\V1\ChatController@send` |
| `GET /notifications` | ✅ | `Api\V1\NotificationController@index` |
| `PATCH /notifications/{id}/read` | ✅ | `Api\V1\NotificationController@markRead` |
| `POST /notifications/read-all` | ✅ | `Api\V1\NotificationController@markAllRead` |
| `GET /user/activities` | ✅ (summary + reports + claims) | `Api\V1\UserController@activities` |
| `GET /user/profile` · `PUT /user/profile` | ✅ | `Api\V1\UserController@show` / `@update` |
| `PUT /user/settings` | ✅ | `Api\V1\UserController@updateSettings` |

### Admin (`/api/v1/admin`)

| Endpoint | Status | Controller |
| :--- | :--- | :--- |
| `GET /admin/overview` | ✅ | `Api\V1\AdminController@overview` |
| `PATCH /admin/reports/{id}/status` | ✅ + audit `moderation_actions` | `Api\V1\AdminController@updateReportStatus` |
| `PATCH /admin/users/{id}/status` | ✅ DEC-026 | `Api\V1\AdminController@updateUserStatus` |
| `GET/POST/PUT/DELETE /admin/categories` | ✅ hapus ditolak bila kategori masih dipakai | `Api\V1\AdminController@categories` dst. |

---

## 3. Deviasi & Catatan Teknis

### 3.1 `token` pada respons login

Kontrak §4.1 mencontohkan `data.token` berisi token Sanctum. Sanctum tidak dipasang pada
proyek ini, sehingga:

- Field `token` selalu `null`.
- Ditambahkan `auth_mode: "session"` agar klien tidak salah asumsi.
- Klien **wajib** mengirim cookie sesi (fetch dengan `credentials: 'same-origin'`) dan
  header `X-CSRF-TOKEN` untuk metode non-GET, sesuai kontrak §2.1.

Jika kelak butuh mobile client / token stateless, langkahnya: `composer require laravel/sanctum`,
tambahkan trait `HasApiTokens` pada `User`, lalu kembalikan `$user->createToken('web')->plainTextToken`.

### 3.2 `security_post_id` — SELESAI (DEC-028)

Kontrak menyebut pos satpam sebagai entitas, tetapi belum ada tabel `security_posts`.
Konsep ini juga tidak ditemukan di PRD, SRS, DATABASE_DESIGN, SYSTEM_DESIGN,
UI_UX_SPECIFICATION, maupun DEVELOPMENT_PLAN.

**Keputusan (DEC-028, 2026-11-10):** konsep pos satpam dihapus. `reports.storage_location`
dan `reports.security_post_id` di-drop lewat migrasi `2026_11_10_000016`, lokasi penyimpanan
dicatat pada `reports.storage_note` sebagai teks bebas, dan label dashboard `in_handover`
dikoreksi menjadi "Menunggu Serah Terima".

### 3.3 `campus` vs `campus_location`

Kolom database tetap `campus_location`; API menerima/mengirim slug `campus`.
Pemetaan label ↔ slug ada di `config/findumb.php` (`campuses`).
Filter `campus` mencocokkan slug **dan** label lama, sehingga laporan yang dibuat sebelum
penyesuaian tetap muncul di hasil pencarian.

### 3.4 OTP verifikasi email

| Aspek | Nilai |
| :--- | :--- |
| Format | 6 digit angka |
| Masa berlaku | 15 menit |
| Batas percobaan | 5 kali per kode |
| Penyimpanan | Hash (kolom `users.email_otp_hash`), bukan teks biasa |
| Rate limit | 10 percobaan/menit (verifikasi), 6/menit (kirim ulang) |
| Nama route | `verification.notice` (GET `/verify-otp`), `verification.otp.post`, `verification.otp.resend` |

`verification.notice` sengaja dipakai untuk GET `/verify-otp` karena middleware `verified`
bawaan Laravel mengarahkan pengguna ke nama route tersebut.

### 3.5 Endpoint lama `/api/*`

`routes/web.php` masih memuat 13 endpoint `/api/*` bergaya lama (dipakai `findumb-api.js`).
Semua endpoint itu **tidak** memakai envelope v1. Rencana: hapus setelah seluruh view
front-end memakai `/api/v1`. Sampai saat itu keduanya berjalan berdampingan.

### 3.6 Nama route yang diselaraskan

| Sebelum (backend) | Sesudah | Dipakai oleh |
| :--- | :--- | :--- |
| `login.store` | `login.post` | `resources/views/auth/login.blade.php` |
| `register.store` | `register.post` | `resources/views/auth/register.blade.php` |
| — | `landing` (GET `/landing`) | `resources/views/landing.blade.php` |

Perubahan pada sisi front-end hanya dua string nama route pada atribut `action` form;
markup, kelas CSS, dan layout tidak diubah.

---

## 4. Perubahan Basis Data

Dua migrasi baru (belum dijalankan di Supabase — jalankan `php artisan migrate`):

| Migrasi | Isi |
| :--- | :--- |
| `2026_11_08_000013_align_schema_with_api_contract` | `reports.proof_question`, `reports.storage_location`, `reports.security_post_id`, `claims.proof_answer`, `claims.claim_reason`, `claims.proof_attachment`, `claims.rejection_reason` |
| `2026_11_08_000014_add_email_otp_to_users_table` | `users.email_otp_hash`, `users.email_otp_expires_at`, `users.email_otp_attempts` |

Keduanya aditif (nullable) sehingga aman dijalankan pada data yang sudah ada.

---

## 5. Validasi yang Dijalankan

1. `php artisan test` — **70 test, 294 assertion, semua lulus** (44 test lama + 26 test kontrak baru di `tests/Feature/ApiV1ContractTest.php`).
2. Migrasi dijalankan pada SQLite bersih: 15 migrasi lama + 2 migrasi baru sukses.
3. Uji HTTP nyata (`php artisan serve` + curl) untuk alur lengkap:
   registrasi → OTP → verifikasi → buat laporan temuan → katalog publik →
   klaim dengan bukti → accept penemu → chat → konfirmasi serah terima dua pihak →
   laporan `resolved` → notifikasi → aktivitas → admin overview/kategori/moderasi.
4. Pemeriksaan kebocoran data: `proof_question` tidak muncul pada respons publik;
   klaim pengguna lain tidak memuat `proof_answer`.

---

## 6. Yang Belum Dikerjakan

- Menjalankan `php artisan migrate` pada database Supabase (menunggu persetujuan).
- Memindahkan `public/js/findumb-api.js` dari `/api/*` ke `/api/v1` + envelope baru.
- Menghapus endpoint `/api/*` lama setelah migrasi front-end selesai.
- ~~Tabel master `security_posts` (§3.2).~~ **Dibatalkan oleh DEC-028** — konsep pos satpam dihapus.
- Sanctum token bila dibutuhkan klien non-browser (§3.1).
- Konfigurasi SMTP nyata: `MAIL_MAILER=log` masih dipakai, sehingga kode OTP hanya
  tertulis di `storage/logs/laravel.log`.
