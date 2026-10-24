# Database Design — Campus Lost & Found

**Status:** Draft v0.1  
**Platform:** Laravel + MySQL  
**Tujuan:** Mendefinisikan rancangan awal struktur database untuk aplikasi Campus Lost & Found.

---

## 1. Tujuan

Dokumen ini menjadi acuan untuk merancang struktur database Campus Lost & Found. Struktur harus mendukung laporan barang hilang dan ditemukan, proses klaim, komunikasi pribadi, konfirmasi serah terima, notifikasi, serta moderasi.

Rancangan ini masih berada pada tingkat desain. Detail yang bergantung pada keputusan bisnis yang belum final harus ditinjau kembali sebelum implementasi migration.

## 2. Prinsip Perancangan

1. Menggunakan MySQL sebagai sistem manajemen database.
2. Mengikuti konvensi Laravel jika sesuai, termasuk penggunaan primary key dan timestamp.
3. Menggunakan foreign key untuk menjaga integritas relasi.
4. Menyimpan riwayat status penting untuk membantu pelacakan proses.
5. Memisahkan data transaksi dari isi pesan chat agar pesan dapat dihapus tanpa menghilangkan riwayat serah terima.
6. Membatasi akses data berdasarkan kepemilikan laporan, hubungan klaim, dan hak administrator.
7. Tidak menghapus data transaksi penting hanya karena isi chat telah melewati masa retensinya.

## 3. Entitas Utama

Rancangan awal mencakup tabel berikut:

| Tabel | Fungsi |
|---|---|
| `users` | Menyimpan akun pengguna dan administrator |
| `item_categories` | Menyimpan kategori barang |
| `reports` | Menyimpan laporan barang hilang atau ditemukan |
| `claims` | Menyimpan pengajuan klaim terhadap laporan barang ditemukan |
| `claim_status_histories` | Mencatat perubahan status klaim |
| `chat_messages` | Menyimpan pesan pribadi dalam proses klaim |
| `handover_confirmations` | Menyimpan konfirmasi serah terima dari masing-masing pihak |
| `notifications` | Menyimpan notifikasi di dalam aplikasi |
| `abuse_reports` | Menyimpan laporan penyalahgunaan |
| `moderation_actions` | Mencatat tindakan moderasi administrator |

Tabel di atas merupakan rancangan awal. Nama kolom, enum status, indeks, dan kebutuhan tabel tambahan akan ditinjau sebelum pembuatan migration.

## 4. Rancangan Tabel

### 4.1 `users`

Menyimpan identitas akun pengguna dan administrator.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `name` | Nama pengguna |
| `email` | Alamat email; harus unik |
| `password` | Hash kata sandi |
| `role` | Peran akun, misalnya `user` atau `admin` |
| `email_verified_at` | Waktu verifikasi email |
| `status` | Status akun, misalnya aktif atau ditangguhkan |
| `created_at` | Waktu pembuatan |
| `updated_at` | Waktu pembaruan |

Catatan:
- Kata sandi tidak boleh disimpan dalam bentuk teks biasa.
- Akun harus memenuhi aturan verifikasi email yang ditetapkan untuk kampus.
- Domain email resmi Universitas Mercu Buana belum ditentukan dalam dokumen ini (DEC-016).
- Nilai status akun: `'active'` (aktif normal) dan `'suspended'` (ditangguhkan/dinonaktifkan oleh administrator).
- **Dampak Penonaktifan Akun Berdasarkan Peran (rekomendasi DEC-026):**
  - **Pengklaim Dinonaktifkan:** Seluruh klaim aktif miliknya diubah menjadi `cancelled` (`cancelled_by = admin_id`). Jika klaim sedang `in_handover`, laporan terkait dikembalikan ke status `open` agar penemu tidak terhambat.
  - **Penemu Dinonaktifkan:** Seluruh laporan aktif miliknya (`open` atau `in_handover`) diubah ke status `hidden`. Seluruh klaim aktif terkait diubah menjadi `cancelled` (`cancelled_by = admin_id` atau sistem), membebaskan pengklaim tanpa penalti.
  - **Reaktivasi Akun:** Mengembalikan status akun menjadi `active` tidak menghidupkan kembali klaim, serah terima, atau laporan yang telah berstatus terminal (*immutable*). Pengguna yang dipulihkan dapat membuat laporan baru atau mengajukan klaim baru pada laporan yang masih `open`.

### 4.2 `item_categories`

Menyimpan daftar kategori barang untuk membantu pengguna mengelompokkan laporan.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `name` | Nama kategori |
| `description` | Deskripsi kategori, opsional |
| `is_active` | Menandakan kategori masih dapat digunakan |
| `created_at` | Waktu pembuatan |
| `updated_at` | Waktu pembaruan |

Nama kategori harus dikelola agar tidak terjadi duplikasi yang tidak disengaja.

### 4.3 `reports`

Menyimpan laporan barang hilang atau ditemukan.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `user_id` | Foreign key ke `users.id`, yaitu pembuat laporan |
| `category_id` | Foreign key ke `item_categories.id` |
| `type` | Jenis laporan: `'lost'` (barang hilang) atau `'found'` (barang ditemukan) |
| `title` | Judul laporan |
| `description` | Deskripsi barang dan informasi terkait |
| `location` | Lokasi kehilangan atau penemuan |
| `incident_date` | Tanggal kejadian, jika diketahui |
| `image_path` | Lokasi penyimpanan foto, jika tersedia (maksimal 1 foto) |
| `status` | Status laporan: `'open'`, `'in_handover'`, `'resolved'`, `'cancelled'`, `'hidden'` |
| `created_at` | Waktu pembuatan |
| `updated_at` | Waktu pembaruan |

Catatan:
- `user_id` mengacu pada pengguna yang membuat laporan.
- Nilai status laporan:
  - `'open'`: Laporan aktif dan dapat dicari/dilihat. Untuk barang temuan, dapat menerima pengajuan klaim baru. Laporan kembali menjadi `'open'` jika serah terima dibatalkan (oleh pihak manapun) atau jika akun pengklaim yang sedang serah terima dinonaktifkan oleh administrator (rekomendasi DEC-026).
  - `'in_handover'`: Salah satu klaim barang temuan telah disetujui untuk proses serah terima. Laporan terkunci dari perubahan data atau pemilihan klaim lain. Klaim lain pada laporan tersebut tetap berstatus `submitted` (mengantre).
  - `'resolved'`: Serah terima telah dikonfirmasi oleh kedua pihak. Laporan ditutup dan bersifat permanen.
  - `'cancelled'`: Laporan dibatalkan oleh pembuatnya (hanya diizinkan jika status laporan bukan `in_handover`).
  - `'hidden'`: Laporan disembunyikan oleh administrator karena pelanggaran konten atau karena akun penemu dinonaktifkan (`users.status = 'suspended'`).
- `image_path` hanya menyimpan referensi lokasi file pada penyimpanan aplikasi.

### 4.4 `claims`

Menyimpan pengajuan klaim oleh pengguna terhadap laporan barang ditemukan.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `report_id` | Foreign key ke `reports.id` |
| `claimant_id` | Foreign key ke `users.id`, yaitu pihak yang mengajukan klaim |
| `status` | Status klaim: `'submitted'`, `'in_handover'`, `'completed'`, `'rejected'`, `'cancelled'`, `'expired'` |
| `cancellation_reason` | Alasan pembatalan (wajib diisi saat pembatalan serah terima), teks/nullable |
| `cancelled_by` | Foreign key ke `users.id` (rekomendasi desain), mencatat pihak yang membatalkan klaim, nullable |
| `response_notified_at` | Waktu pengiriman notifikasi 72 jam tanpa respons, timestamp/nullable |
| `submitted_at` | Waktu pengajuan klaim |
| `resolved_at` | Waktu penyelesaian serah terima atau status terminal, nullable |
| `created_at` | Waktu pembuatan record |
| `updated_at` | Waktu pembaruan record |

Catatan:
- Klaim hanya berlaku untuk laporan barang ditemukan (`type = 'found'`).
- Nilai status klaim dikelompokkan menjadi:
  1. **Status Aktif (Menahan hak klaim baru pengguna):**
     - `'submitted'`: Klaim baru diajukan, menunggu respons atau persetujuan penemu.
     - `'in_handover'`: Klaim telah dipilih oleh penemu untuk melanjutkan ke proses serah terima.
  2. **Status Terminal (Melepaskan pembatasan hak klaim pengguna):**
     - `'completed'`: Serah terima berhasil dan telah dikonfirmasi oleh kedua pihak.
     - `'rejected'`: Klaim ditolak oleh penemu sebelum tahap serah terima.
     - `'cancelled'`: Klaim dibatalkan oleh pengklaim, penemu (saat serah terima), admin (penonaktifan akun), atau sistem (laporan selesai/dibatalkan/disembunyikan).
     - `'expired'`: Klaim kedaluwarsa otomatis oleh scheduler sistem setelah 120 jam (5 hari) sejak klaim dibuat (`submitted_at` / `created_at`) tanpa respons dari penemu.
- **Integritas Aturan Bisnis & Pembedaan Hak Klaim Ulang:**
  - Status terminal bersifat permanen (*immutable*) dan tidak dapat diubah kembali menjadi aktif melalui transisi biasa.
  - **Identifikasi Aktor Pembatal (`cancelled_by`):** Untuk mendukung aturan klaim ulang yang akurat dan auditabilitas, tabel `claims` menyediakan kolom `cancelled_by` bertipe foreign key ke `users.id` (nullable, selain pencatatan historis di `claim_status_histories.changed_by`). Kolom ini membedakan secara langsung pihak yang memicu status `cancelled`:
    1. **Dibatalkan oleh Pengklaim (`cancelled_by = claimant_id`):** Merujuk ke `users.id` milik pengklaim. Terjadi jika pengklaim membatalkan pada tahap `submitted` atau membatalkan proses serah terima (`in_handover`). Pengklaim **dilarang mengajukan klaim ulang** pada `report_id` yang sama untuk mencegah spam dan looping.
    2. **Dibatalkan oleh Penemu (`cancelled_by = finder_id`):** Merujuk ke `users.id` milik penemu. Terjadi jika penemu membatalkan proses serah terima (`in_handover`). Pengklaim **diperbolehkan mengajukan klaim kembali** pada `report_id` yang sama selama status laporan masih `open`, karena kegagalan serah terima bukan disebabkan oleh mundurnya pengklaim.
    3. **Dibatalkan oleh Administrator (`cancelled_by = admin_id`):** Merujuk ke `users.id` milik administrator (yaitu ID akun pada tabel `users` yang memiliki `role = 'admin'`, bukan tabel admin terpisah). Terjadi jika administrator menonaktifkan akun pengklaim/penemu atau menyembunyikan laporan karena pelanggaran moderasi (rekomendasi DEC-026).
    4. **Dibatalkan Otomatis oleh Sistem / Scheduler (`cancelled_by IS NULL`):** Bernilai `NULL` untuk seluruh tindakan pembatalan otomatis oleh sistem atau tugas terjadwal (scheduler). Nilai `NULL` ini menjamin bahwa tindakan otomatis sistem (seperti penutupan klaim mengantre saat serah terima lain selesai, atau kedaluwarsa 120 jam) **tidak pernah dicatat seolah-olah dilakukan oleh pengklaim atau penemu**.
  - **Pencatatan Alasan dan Waktu Pembatalan:** Alasan pembatalan dicatat secara tekstual pada `cancellation_reason` (wajib diisi oleh pengguna pada modal pembatalan serah terima, atau diisi alasan moderasi/keterangan sistem), dan waktu pembatalan dicatat presisi pada timestamp `updated_at`.
  - **Nasib Klaim Mengantre (`submitted`) saat Serah Terima Dibatalkan (rekomendasi DEC-025):**
    - Seluruh klaim mengantre tetap bertahan dalam status `'submitted'` ketika serah terima pada laporan dibatalkan dan status laporan kembali `'open'`.
    - Penemu dapat langsung memilih klaim mengantre berikutnya tanpa memaksa pengklaim membuat pengajuan ulang.
    - Pengklaim mengantre dapat membatalkan secara sukarela kapan saja (`cancelled`) jika tidak ingin menunggu.
  - **Timer Kedaluwarsa Klaim Tidak Direset:** Kolom `submitted_at` / `created_at` menjadi acuan mutlak penghitungan 72 jam notifikasi dan 120 jam kedaluwarsa otomatis. Pembatalan serah terima yang sedang berjalan tidak mengatur ulang (*reset*) timer klaim lain yang tetap menunggu (`submitted`), dan pemilihan klaim berikutnya juga tidak mereset timer klaim tersebut.
  - **Klaim Kedaluwarsa (`expired`):** Pengklaim **diperbolehkan mengajukan klaim kembali** pada `report_id` yang sama selama status laporan masih `open`.
  - Jaminan bahwa hanya satu klaim per laporan yang berstatus `in_handover` ditegakkan melalui transaksi database dan locking level server (`SELECT ... FOR UPDATE`).

### 4.5 `claim_status_histories`

Mencatat perubahan status klaim untuk keperluan pelacakan dan audit.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `claim_id` | Foreign key ke `claims.id` |
| `changed_by` | Foreign key ke `users.id`, nullable (bernilai `NULL` jika perubahan dilakukan otomatis oleh sistem/scheduler) |
| `old_status` | Status sebelum perubahan |
| `new_status` | Status setelah perubahan |
| `notes` | Keterangan perubahan atau alasan pembatalan |
| `created_at` | Waktu perubahan |

Catatan:
- Riwayat perubahan tidak menggantikan status terkini pada tabel `claims`.
- Perubahan status harus mengikuti transisi yang diizinkan pada state machine resmi.
- Sistem harus mencatat pihak yang melakukan aksi: `changed_by` berisi `users.id` jika dipicu oleh pengguna (penemu/pengklaim) atau admin, dan wajib `NULL` jika dipicu otomatis oleh sistem/scheduler.

### 4.6 `chat_messages`

Menyimpan pesan teks pribadi antara penemu dan pengklaim.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `claim_id` | Foreign key ke `claims.id` |
| `sender_id` | Foreign key ke `users.id` |
| `message` | Isi pesan teks |
| `created_at` | Waktu pengiriman |
| `updated_at` | Waktu pembaruan |

Catatan:
- Chat hanya dapat diakses oleh penemu dan pengklaim pada klaim terkait.
- Pesan baru hanya boleh dikirim ketika status klaim aktif (`submitted` atau `in_handover`).
- Begitu klaim mencapai status terminal (`completed`, `rejected`, `cancelled`, `expired`), chat otomatis dikunci menjadi hanya-baca.
- Pesan dihapus otomatis 30 hari setelah serah terima dikonfirmasi oleh kedua pihak, kecuali ditahan untuk peninjauan laporan penyalahgunaan.

### 4.7 `handover_confirmations`

Mencatat konfirmasi serah terima dari masing-masing pihak.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `claim_id` | Foreign key ke `claims.id` |
| `user_id` | Foreign key ke `users.id` |
| `confirmed_at` | Waktu konfirmasi |
| `created_at` | Waktu pembuatan |
| `updated_at` | Waktu pembaruan |

Catatan:
- Satu klaim hanya melibatkan dua pihak sah: penemu (`reports.user_id`) dan pengklaim (`claims.claimant_id`).
- **Constraint Unik Mutlak di Database:** Wajib memiliki `UNIQUE KEY unique_claim_user (claim_id, user_id)` untuk mencegah satu pihak melakukan konfirmasi ganda akibat klik berulang atau pengulangan jaringan.
- Transaksi dinyatakan selesai hanya jika terdapat dua baris konfirmasi sah dari dua pihak yang berbeda pada klaim terkait.

### 4.8 `notifications`

Menyimpan notifikasi yang ditampilkan di dalam aplikasi.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `user_id` | Foreign key ke `users.id`, penerima notifikasi |
| `type` | Jenis notifikasi |
| `title` | Judul notifikasi |
| `body` | Ringkasan notifikasi |
| `data` | Metadata tambahan, jika diperlukan |
| `read_at` | Waktu notifikasi dibaca |
| `created_at` | Waktu pembuatan |

Catatan:
- Notifikasi dapat berkaitan dengan klaim baru, pesan baru, perubahan status, atau konfirmasi serah terima.
- Notifikasi hanya ditampilkan kepada penerima yang berhak.
- Notifikasi dalam aplikasi tidak sama dengan push notification.
- Push notification saat aplikasi tertutup tidak termasuk kebutuhan saat ini.

### 4.9 `abuse_reports`

Menyimpan laporan penyalahgunaan dari pengguna.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `reporter_id` | Foreign key ke `users.id`, pelapor |
| `target_type` | Jenis objek yang dilaporkan, misalnya laporan barang atau pesan |
| `target_id` | Identitas objek yang dilaporkan |
| `reason` | Alasan laporan |
| `details` | Penjelasan tambahan, jika tersedia |
| `status` | Status peninjauan |
| `reviewed_by` | Foreign key ke `users.id`, administrator peninjau |
| `reviewed_at` | Waktu peninjauan |
| `created_at` | Waktu laporan dibuat |
| `updated_at` | Waktu pembaruan |

Catatan:
- Pelaporan harus dapat mencakup laporan barang dan pesan chat.
- `target_type` dan `target_id` merupakan rancangan relasi polimorfik; validasi keberadaan objek tetap diperlukan.
- Hanya administrator berwenang yang dapat meninjau laporan penyalahgunaan.
- Aturan penanganan laporan yang merujuk pesan yang kemudian dihapus harus diputuskan sebelum implementasi.

### 4.10 `moderation_actions`

Mencatat tindakan administrator atas pelanggaran.

| Kolom | Tujuan |
|---|---|
| `id` | Primary key |
| `admin_id` | Foreign key ke `users.id` |
| `target_user_id` | Pengguna yang menjadi sasaran tindakan, jika ada |
| `abuse_report_id` | Foreign key ke `abuse_reports.id`, jika berkaitan dengan laporan penyalahgunaan |
| `action_type` | Jenis tindakan moderasi |
| `reason` | Alasan tindakan |
| `created_at` | Waktu tindakan |

Catatan:
- Tindakan dapat mencakup menyembunyikan konten, memberi peringatan, atau menangguhkan akun.
- Tindakan harus menyimpan alasan agar dapat diaudit.
- Administrator tidak berwenang memutuskan siapa pemilik barang dalam proses klaim.
- Penentuan apakah tindakan moderasi juga perlu menyimpan status sebelum dan sesudah perubahan akan ditinjau sebelum implementasi.

## 5. Relasi Antarentitas

Relasi utama yang direncanakan:

1. Satu `users` dapat memiliki banyak `reports`.
2. Satu `item_categories` dapat digunakan oleh banyak `reports`.
3. Satu `reports` dapat memiliki banyak `claims`.
4. Satu `users` dapat mengajukan banyak `claims` sebagai pengklaim, dengan pembatasan bisnis yang berlaku.
5. Satu `claims` dapat memiliki banyak `claim_status_histories`.
6. Satu `claims` dapat memiliki banyak `chat_messages`.
7. Satu `claims` dapat memiliki paling banyak dua konfirmasi serah terima, masing-masing dari pihak yang berbeda.
8. Satu `users` dapat menerima banyak `notifications`.
9. Satu `users` dapat membuat banyak `abuse_reports`.
10. Satu administrator dapat meninjau banyak `abuse_reports` dan mencatat banyak `moderation_actions`.

## 6. Aturan Integritas Data

Integritas data dijamin melalui kombinasi pembagian tanggung jawab antara constraint database dan transaksi aplikasi:

### 6.1 Integritas yang Dijamin Tingkat Database (Database Constraints)
- **Keunikan Email:** `UNIQUE KEY (email)` pada tabel `users`.
- **Pencegahan Konfirmasi Ganda:** `UNIQUE KEY unique_claim_user (claim_id, user_id)` pada tabel `handover_confirmations`. Satu user tidak dapat mengonfirmasi lebih dari 1 kali pada klaim yang sama.
- **Integritas Relasi:** Foreign key constraints dengan proteksi `RESTRICT` atau `CASCADE` yang terukur agar tidak meninggalkan data yatim (*orphan records*).
- **Integritas Nilai Kolom:** Penggunaan `ENUM` atau validasi `VARCHAR` ketat pada status laporan (`'open'`, `'in_handover'`, `'resolved'`, `'cancelled'`, `'hidden'`) dan status klaim (`'submitted'`, `'in_handover'`, `'completed'`, `'rejected'`, `'cancelled'`, `'expired'`).

### 6.2 Integritas yang Dijamin Melalui Transaksi Aplikasi (Application & DB Transactions)
- **Maksimal Satu Klaim Serah Terima per Laporan:** Database MySQL standar tidak dapat dengan mudah membatasi hanya satu baris `status = 'in_handover'` per `report_id` secara parsial tanpa filtered index. Maka, pemilihan klaim menuju `in_handover` wajib dieksekusi di dalam `DB::transaction()` menggunakan *pessimistic locking* (`SELECT ... FOR UPDATE`) pada record `reports` dan `claims` terkait.
- **Batasan Satu Klaim Aktif per Pengguna:** Verifikasi bahwa pengguna tidak memiliki klaim lain berstatus `submitted` atau `in_handover` dilakukan di level server pada Form Request / Service Class dengan perlindungan transaksi saat menyimpan klaim baru.
- **Pencegahan Klaim Berulang:** Pengecekan riwayat klaim sebelumnya oleh pengguna yang sama pada laporan yang sama (`report_id`, `claimant_id`) untuk memastikan tidak ada re-klaim jika riwayat berstatus `rejected` atau `cancelled`.
- **Penyelesaian Serah Terima Dua Pihak:** Pembaruan status klaim menjadi `completed`, laporan menjadi `resolved`, dan pembatalan klaim antrean lainnya dijalankan secara atomik dalam satu blok transaksi.

## 7. Indeks dan Performa

Kandidat indeks yang direncanakan:

- Indeks unik pada `users.email`.
- Indeks unik pada `handover_confirmations(claim_id, user_id)` (*wajib untuk integritas*).
- Indeks gabungan pada `claims(claimant_id, status)` (*mempercepat verifikasi apakah pengguna memiliki klaim aktif*).
- Indeks gabungan pada `claims(report_id, claimant_id)` (*mempercepat verifikasi riwayat klaim berulang*).
- Indeks gabungan pada `claims(report_id, status)` (*mempercepat daftar klaim per laporan dan pengecekan klaim terpilih*).
- Indeks pada `reports.type`, `reports.category_id`, `reports.status`, dan `reports.incident_date` (untuk filter pencarian).
- Indeks pada `chat_messages.claim_id` dan `chat_messages.created_at` (untuk urutan percakapan).
- Indeks pada `notifications.user_id` dan `notifications.read_at` (untuk status belum dibaca).
- Indeks pada `abuse_reports.status` dan `abuse_reports.created_at`.

## 8. Keamanan dan Privasi

- Password harus disimpan sebagai hash (Bcrypt / Argon2id).
- Validasi dan otorisasi dilakukan di sisi server.
- Pengguna tidak boleh membaca chat milik klaim pengguna lain.
- Data akun, pesan, dan laporan hanya boleh diakses sesuai hak akses.
- Foto barang harus divalidasi berdasarkan jenis file dan ukuran yang diizinkan (maksimal 1 foto, 2 MB).
- Isi pesan harus dihapus sesuai kebijakan retensi (30 hari setelah serah terima selesai), dengan pengecualian yang telah ditetapkan untuk proses moderasi yang belum selesai.
- Informasi transaksi yang dibutuhkan untuk audit (status, waktu konfirmasi) harus dipertahankan tanpa mempertahankan isi pesan yang sudah waktunya dihapus.

## 9. Hal yang Belum Diputuskan

Sebelum migration dibuat, keputusan berikut perlu diklarifikasi:

1. Domain email resmi yang digunakan untuk verifikasi mahasiswa.
2. Batas retensi pesan setelah peninjauan moderasi selesai.
3. Batas ukuran dan format foto laporan (misal 1 foto, maks 2 MB, format JPEG/PNG/WebP).
4. Apakah `abuse_reports` perlu memakai foreign key khusus atau relasi polimorfik untuk target laporan dan pesan.
5. Apakah dibutuhkan tabel tambahan untuk menyimpan metadata penghapusan pesan atau audit retensi.

## 10. Tahap Berikutnya

Setelah dokumen ini ditinjau:

1. Selaraskan entitas dan aturan dengan PRD, SRS, serta SYSTEM_DESIGN.
2. Putuskan pertanyaan terbuka yang memengaruhi struktur database.
3. Tetapkan status dan transisi untuk laporan, klaim, akun, dan moderasi.
4. Rancang migration Laravel berdasarkan desain yang disepakati.
5. Buat pengujian untuk relasi, otorisasi, konsistensi transaksi, dan kebijakan retensi.

**Prinsip utama:** database harus menjaga konsistensi proses klaim dan serah terima tanpa memberikan administrator kewenangan untuk menentukan kepemilikan barang.