# System Design
# Campus Lost & Found

**Versi:** 0.1  
**Status:** Draf  
**Acuan:** `docs/PRD.md` dan `docs/SRS.md`

## 1. Tujuan Dokumen

Dokumen ini menjelaskan rancangan arsitektur tingkat tinggi Campus Lost & Found, komponen utama aplikasi, pembagian tanggung jawab, alur komunikasi, serta prinsip teknis yang digunakan selama pengembangan.

Rancangan ini menjadi acuan sebelum struktur kode Laravel dan skema basis data ditetapkan secara terperinci.

## 2. Prinsip Perancangan

Pengembangan sistem mengikuti prinsip berikut:

1. **Sederhana:** gunakan fitur bawaan Laravel selama sudah memenuhi kebutuhan.
2. **Terstruktur:** pisahkan tanggung jawab antarkomponen.
3. **Aman:** validasi dan pemeriksaan hak akses dilakukan di server.
4. **Konsisten:** perubahan status klaim dan serah terima harus menjaga integritas data.
5. **Mudah diuji:** aturan bisnis penting harus dapat diuji secara otomatis.
6. **Sesuai ruang lingkup:** hindari layanan eksternal dan arsitektur kompleks yang tidak diperlukan.
7. **Berbasis dokumentasi:** implementasi harus mengikuti kebutuhan yang sudah disetujui.

## 3. Teknologi Utama

| Komponen | Teknologi | Tanggung jawab |
|---|---|---|
| Bahasa pemrograman | PHP | Menjalankan logika aplikasi backend. |
| Kerangka kerja | Laravel | Routing, autentikasi, validasi, logika aplikasi, dan akses data. |
| Tampilan | Blade | Merender halaman HTML di sisi server. |
| Antarmuka | HTML, CSS, JavaScript | Menyediakan interaksi dan tampilan responsif. |
| Basis data | MySQL | Menyimpan akun, laporan, klaim, pesan, notifikasi, dan data moderasi. |
| PWA | Web App Manifest dan Service Worker | Mendukung pemasangan aplikasi dan cache yang dibatasi. |
| Pengujian | Fasilitas pengujian Laravel | Memeriksa aturan bisnis, hak akses, dan perilaku sistem. |
| Version control | Git dan GitHub | Mengelola riwayat perubahan kode serta dokumentasi. |

Versi PHP, Laravel, dan MySQL yang digunakan akan ditetapkan saat pemeriksaan lingkungan pengembangan. Pilih versi yang kompatibel satu sama lain dan masih didukung.

## 4. Gambaran Arsitektur

Sistem menggunakan arsitektur aplikasi web berbasis Laravel dengan proses utama di sisi server.

Alur umum:

1. Pengguna membuka aplikasi melalui browser.
2. Browser mengirim permintaan HTTP ke aplikasi Laravel.
3. Laravel memeriksa autentikasi, validasi, dan hak akses.
4. Controller mengoordinasikan proses yang dibutuhkan.
5. Logika bisnis menjalankan aturan aplikasi.
6. Model Eloquent berinteraksi dengan MySQL.
7. Laravel mengembalikan halaman Blade atau respons yang sesuai.
8. Browser menampilkan hasil kepada pengguna.

Service Worker menangani kemampuan PWA yang diizinkan, seperti cache aset statis. Service Worker tidak menggantikan validasi atau otorisasi yang dilakukan server.

## 5. Komponen Aplikasi

### 5.1 Lapisan Routing

Bertanggung jawab untuk:
- Menerima permintaan HTTP dan mengarahkan ke controller yang sesuai.
- **Rute Publik (Bebas Akses / Guest):**
  - Melihat daftar laporan barang publik (beranda), melihat detail umum laporan barang yang dipublikasikan, serta mencari dan memfilter laporan publik. Rute ini tidak mewajibkan autentikasi pengguna.
- **Rute Terproteksi Pengguna Terverifikasi:**
  - Membuat laporan, mengajukan klaim, mengakses percakapan chat privat, dan melakukan konfirmasi/pembatalan serah terima. Dilindungi oleh middleware autentikasi (`auth`) dan verifikasi email kampus (`verified`), serta pemeriksaan status akun aktif. Login saja tanpa verifikasi email kampus ditolak dari rute ini.
- **Rute Administrator:**
  - Mengakses dashboard moderasi dan melakukan tindakan penanganan konten/akun yang dilaporkan. Dilindungi oleh middleware otorisasi administrator.

### 5.2 Lapisan Controller

Bertanggung jawab untuk:
- Menerima data permintaan.
- Memicu validasi.
- Memanggil proses bisnis yang relevan.
- Menentukan respons atau halaman yang dikembalikan.

Controller tidak boleh menjadi tempat penumpukan seluruh logika bisnis.

### 5.3 Lapisan Logika Bisnis

Menangani aturan yang membutuhkan konsistensi, antara lain:
- Pengajuan dan pemilihan klaim.
- Transisi status klaim.
- Pembatasan klaim per pengguna.
- Konfirmasi serah terima kedua pihak.
- Penutupan laporan dan chat.
- Retensi serta penghapusan pesan.
- Tindakan moderasi.

Logika bisnis dapat ditempatkan pada service atau kelas khusus apabila kompleksitasnya memang membenarkan pemisahan tersebut. Tidak semua operasi sederhana harus dibuat menjadi service terpisah.

### 5.4 Lapisan Model dan Basis Data

Laravel Eloquent digunakan untuk mengakses data MySQL.

Tanggung jawabnya meliputi:
- Mengelola data aplikasi.
- Mendefinisikan hubungan antarmodel.
- Membantu menjaga konsistensi data.
- Mendukung query yang terstruktur.

Struktur tabel, kolom, indeks, dan constraint ditentukan lebih lanjut dalam `DATABASE_DESIGN.md`.

### 5.5 Lapisan Tampilan

Blade digunakan untuk menghasilkan halaman HTML di sisi server.

Tampilan dibagi berdasarkan fungsi halaman, seperti:
- Beranda dan daftar barang.
- Formulir laporan.
- Detail barang.
- Chat dan proses klaim.
- Aktivitas pengguna.
- Dashboard administrator.
- Halaman autentikasi.

Komponen tampilan dapat digunakan kembali jika memiliki fungsi dan pola visual yang sama.

### 5.6 Komponen PWA

Komponen PWA mencakup:
- Web App Manifest.
- Service Worker.
- Aturan cache aset statis.
- Tampilan atau pesan ketika pengguna offline.

Cache tidak boleh membocorkan halaman privat, percakapan, atau data administrator kepada pengguna lain. Klaim, chat, dan konfirmasi serah terima tetap memerlukan koneksi ke server.

Aturan implementasi lebih terperinci ditetapkan dalam `PWA_SPECIFICATION.md`.

## 6. Pembagian Modul Fungsional

### Modul Autentikasi
Menangani registrasi, verifikasi email, login, logout, dan pemeriksaan status akun.

### Modul Laporan Barang
Menangani pembuatan, pengubahan, pembatalan, pencarian, filter, dan detail laporan.

### Modul Klaim
Menangani pengajuan klaim, pemilihan klaim yang akan dilanjutkan, status klaim, serta aturan pembatasan klaim per pengguna.

### Modul Chat
Menangani percakapan teks privat yang terhubung dengan klaim.

### Modul Serah Terima
Menangani konfirmasi dari penemu dan pengklaim serta penyelesaian proses serah terima.

### Modul Notifikasi
Mencatat dan menampilkan notifikasi dalam aplikasi.

### Modul Moderasi
Menangani laporan penyalahgunaan, peninjauan konten, tindakan administrator, dan pencatatan tindakan.

### Modul Retensi Pesan
Menangani penghapusan pesan sesuai jadwal dan pengecualian ketika pesan masih diperlukan untuk peninjauan moderasi.

## 7. Alur Utama Sistem

### 7.1 Pelaporan Barang

1. Pengguna terverifikasi membuka formulir laporan.
2. Pengguna mengisi data barang.
3. Server memvalidasi data.
4. Sistem menyimpan laporan dan menghubungkannya dengan pemilik.
5. Laporan ditampilkan sesuai status dan kebijakan visibilitas.
6. **Pembatalan Laporan oleh Pemilik:**
   - Pemilik laporan barang temuan dapat membatalkan laporan jika status laporan masih `open` (belum berada dalam status `in_handover`).
   - Pembatalan laporan secara otomatis mengubah seluruh klaim yang masih mengantre (`submitted`) menjadi status terminal `cancelled`, sehingga hak klaim para pengklaim langsung terlepas tanpa dianggap sebagai pelanggaran.

### 7.2 Proses Klaim

1. Pengguna terverifikasi membuka detail laporan barang temuan berstatus `open`.
2. Sistem memvalidasi kelayakan:
   - Pengguna telah login, memiliki email resmi kampus yang terverifikasi, dan akun berstatus aktif.
   - Pengguna bukan pembuat laporan barang temuan.
   - Pengguna tidak sedang memiliki klaim lain yang berstatus aktif (`submitted` atau `in_handover`).
   - Pengguna belum pernah ditolak (`rejected`) oleh penemu atau membatalkan sendiri klaimnya (`cancelled` oleh pengklaim) pada laporan barang yang sama. Pengklaim yang klaim sebelumnya berstatus `expired` atau dibatalkan oleh penemu saat serah terima tetap berhak mengajukan klaim kembali.
3. Pengguna mengirimkan pengajuan klaim.
4. Server menyimpan klaim dengan status awal `submitted`. Hak pengajuan klaim baru bagi pengguna tersebut terkunci sementara selama klaim berstatus aktif.
5. Penemu dan pengklaim dapat berkomunikasi melalui chat privat internal.
6. **Cabang Transisi Klaim:**
   - **Penolakan oleh Penemu:** Penemu dapat menolak klaim yang belum disepakati. Status klaim berubah menjadi `rejected` (terminal), chat ditutup, dan hak klaim pengklaim langsung terlepas.
   - **Pembatalan oleh Pengklaim:** Pengklaim dapat membatalkan klaimnya sendiri kapan saja sebelum serah terima disepakati. Status klaim berubah menjadi `cancelled` (terminal), pencatatan pembatal dicatat, chat ditutup, dan hak klaim pengklaim langsung terlepas.
   - **Klaim Tanpa Respons dan Kedaluwarsa Otomatis (Aturan 120 Jam):**
     1. Jika klaim berstatus `submitted` selama 72 jam sejak dibuat tanpa respons penemu, sistem mengirimkan notifikasi dalam aplikasi kepada pengklaim.
     2. Pengklaim memiliki masa tenggang 48 jam tambahan untuk menunggu respons atau membatalkan klaim secara sukarela (`cancelled`) melalui antarmuka untuk melepaskan hak klaimnya.
     3. Jika penemu tetap tidak merespons dan pengklaim belum membatalkan klaim, tugas terjadwal sistem (*scheduled task*) secara otomatis mengubah status klaim menjadi `expired` tepat setelah total **120 jam (5 hari) sejak klaim dibuat** (`submitted_at` / `created_at`).
     4. **Prinsip Keamanan dan Idempotensi Scheduler:**
        - Scheduler hanya menargetkan klaim yang secara spesifik masih berstatus `submitted` dan `created_at <= NOW() - INTERVAL 120 HOUR`.
        - Jika pengklaim telah membatalkan klaim (`cancelled`), atau penemu telah merespons/menolak (`rejected`), atau status telah beralih ke `in_handover`, scheduler tidak akan mengubah status klaim tersebut.
        - Perubahan status menjadi `expired` langsung membebaskan pengklaim dari pembatasan klaim aktif, dan pengklaim tetap berhak mengajukan klaim kembali pada laporan yang sama jika laporan masih berstatus `open`.
   - **Pemilihan Menuju Serah Terima:** Jika penemu dan pengklaim mencapai kesepakatan awal, penemu memilih klaim tersebut untuk serah terima. Server menggunakan transaksi basis data dengan penguncian (*pessimistic lock*) untuk memastikan hanya satu klaim yang beralih ke status `in_handover` dan status laporan beralih ke `in_handover`.

### 7.3 Serah Terima Barang

1. Penemu dan pengklaim melakukan pertemuan fisik untuk serah terima barang.
2. Kedua belah pihak masing-masing memberikan konfirmasi serah terima melalui aplikasi.
3. **Penyelesaian Berhasil:**
   - Setelah konfirmasi dari kedua pihak tercatat sah, status klaim berubah menjadi `completed` (terminal).
   - Status laporan barang berubah menjadi `resolved`.
   - Klaim-klaim lain yang masih menunggu (`submitted`) pada laporan tersebut otomatis diubah menjadi `cancelled`.
   - Percakapan chat dikunci menjadi hanya-baca (*read-only*).
   - Waktu dan status konfirmasi disimpan permanen.
   - Pesan chat dijadwalkan untuk dihapus setelah 30 hari.
4. **Penanganan Serah Terima Terhenti / Dibatalkan dan Hak Klaim Ulang:**
   - Jika proses serah terima fisik gagal, barang tidak cocok, atau salah satu pihak tidak hadir/menghilang, pihak yang bersangkutan dapat memilih **"Batalkan Serah Terima"**.
   - Sistem mewajibkan dialog konfirmasi, pengisian alasan pembatalan, serta mencatat aktor pembatal (`cancelled_by`).
   - Status klaim yang sedang berjalan berubah menjadi `cancelled` (terminal), form chat dinonaktifkan (hanya-baca), dan hak klaim aktif pengklaim terlepas.
   - Status laporan barang dikembalikan dari `in_handover` menjadi `open` (selama tidak dibatalkan pemilik atau disembunyikan admin), sehingga penemu dapat memilih klaim lain yang mengantre atau menerima klaim baru.
   - **Nasib Klaim Mengantre (`submitted`) Pasca Pembatalan Serah Terima (Sesuai Rekomendasi DEC-025):**
     - Seluruh klaim lain yang berstatus `submitted` **tetap bertahan dalam status `submitted`** (tidak dibatalkan secara otomatis).
     - Begitu laporan kembali berstatus `open`, penemu dapat langsung mengevaluasi klaim-klaim mengantre tersebut untuk dipilih menuju serah terima berikutnya atau ditolak.
     - Pengklaim mengantre tetap terikat aturan 1 klaim aktif global, namun berhak membatalkan klaimnya sendiri secara sukarela kapan saja (`cancelled`) jika tidak ingin menunggu.
     - **Timer Kedaluwarsa 120 Jam Tidak Direset:** Batas waktu kedaluwarsa 120 jam (`submitted_at` / `created_at`) dihitung mutlak dari waktu pembuatan klaim awal secara independen; pembatalan serah terima yang sedang berjalan tidak mengatur ulang (*reset*) timer klaim lain yang tetap menunggu (`submitted`), dan pemilihan klaim berikutnya juga tidak mereset timer klaim tersebut.
   - **Konsekuensi Hak Klaim Ulang Bagi Pihak Handover:**
     - **Jika pengklaim membatalkan serah terima:** Pengklaim dilarang mengajukan klaim ulang pada laporan yang sama guna mencegah looping proses serah terima. Pengklaim bebas mengklaim laporan lain.
     - **Jika penemu membatalkan serah terima:** Pengklaim diperbolehkan mengajukan klaim kembali pada laporan yang sama selama laporan berstatus `open` karena kegagalan serah terima bukan disebabkan oleh mundurnya pengklaim. Pengklaim juga bebas mengklaim laporan lain.
   - Pembatalan sepihak tidak dianggap sebagai pembuktian kepemilikan dan dicatat dalam riwayat status serta audit log.

### 7.4 Moderasi dan Penonaktifan Akun

1. Pengguna mengirim laporan penyalahgunaan.
2. Sistem menyimpan laporan untuk ditinjau administrator.
3. Administrator memeriksa konten atau akun yang dilaporkan.
4. Administrator mengambil tindakan moderasi yang sesuai (peringatan, sembunyikan laporan `hidden`, atau penonaktifan akun `suspended`).
5. Sistem mencatat identitas admin, waktu, dan alasan tindakan di `moderation_actions`.
6. **Dampak Penonaktifan Akun Berdasarkan Peran (Sesuai Rekomendasi DEC-026):**
   - **Jika Pengklaim Dinonaktifkan (Claimant Suspended):**
     - Klaim `submitted`: otomatis diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`). Laporan tetap `open`. Klaim mengantre lainnya tidak terpengaruh.
     - Klaim `in_handover`: otomatis diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`). Status laporan terkait **dikembalikan dari `in_handover` menjadi `open`**, sehingga penemu tidak dirugikan dan dapat melanjutkan proses dengan klaim mengantre lainnya atau menerima klaim baru.
     - Chat terkait klaim dikunci menjadi hanya-baca (*read-only*).
   - **Jika Penemu Dinonaktifkan (Finder Suspended):**
     - Seluruh laporan aktif milik penemu (`open` maupun `in_handover`) diubah menjadi `hidden` (menggunakan status yang sudah ada dalam rancangan sistem).
     - Seluruh klaim aktif (`submitted` maupun `in_handover`) pada seluruh laporan tersebut otomatis diubah menjadi `cancelled` oleh admin/sistem (`cancelled_by = admin_id` atau sistem). Seluruh pengklaim langsung terbebas dari batasan klaim aktif tanpa sanksi atau catatan pelanggaran.
     - Seluruh percakapan chat terkait laporan penemu dikunci (*read-only*), riwayat pesan ditahan untuk kepentingan investigasi jika diperlukan.
   - **Kebijakan Reaktivasi Akun (Account Reactivation) & Pemisahan Status Akun vs Laporan:**
     - **Pemisahan Status:** Status akun (`users.status`) dan status laporan (`reports.status`) merupakan dua hal yang berbeda. Pengaktifan kembali akun penemu (`active`) tidak otomatis mengubah status laporan yang `hidden` menjadi `open` atau mempublikasikannya kembali.
     - Jika administrator mengaktifkan kembali akun yang ditangguhkan (`users.status = 'active'`), seluruh klaim dan laporan yang telah berada di status terminal (`cancelled`, `completed`, `rejected`, `expired`, `hidden`) **bersifat permanen dan tidak dihidupkan kembali secara otomatis** (*no automatic revival*).
     - Pengguna yang dipulihkan dapat membuat laporan baru atau mengajukan klaim baru pada laporan yang masih `open` sesuai batas normal. Prosedur publikasi ulang laporan `hidden` ditunda ke fase moderasi lanjutan dan tidak menghambat implementasi awal.

Moderasi tidak menentukan kepemilikan barang.

## 8. Keamanan dan Integritas Data

- Gunakan middleware untuk membatasi akses berdasarkan autentikasi dan peran.
- Periksa kepemilikan objek pada setiap operasi yang membutuhkan otorisasi.
- Jangan mengandalkan ID pada URL sebagai bukti hak akses.
- Validasi semua input di sisi server.
- Gunakan perlindungan CSRF untuk formulir yang relevan.
- Gunakan transaksi basis data untuk operasi yang mengubah beberapa data terkait.
- Tangani permintaan bersamaan pada proses klaim dan serah terima.
- Cegah konfirmasi berulang menyebabkan perubahan status yang tidak konsisten.
- Simpan rahasia aplikasi dalam konfigurasi lingkungan, bukan di repository.
- Terapkan kebijakan retensi pesan melalui mekanisme yang dapat dijalankan dan dipantau.

## 9. Pengujian dan Validasi

Pengujian arsitektur dan integrasi harus mencakup:

- Alur autentikasi dan verifikasi email.
- Hak akses pengguna dan administrator.
- Pembuatan serta perubahan laporan.
- Pengajuan klaim secara bersamaan.
- Pembatasan klaim per pengguna.
- Privasi chat.
- Konfirmasi serah terima oleh kedua pihak.
- Konsistensi status laporan dan klaim.
- Notifikasi.
- Moderasi dan pencatatan tindakan.
- Penghapusan pesan terjadwal.
- Perilaku cache PWA dan akses offline.

## 10. Matriks Transisi Status Sistem (Acuan Utama)

Bagian ini merupakan referensi tunggal kebenaran (*single source of truth*) mengenai seluruh transisi siklus hidup status laporan dan klaim pada aplikasi Campus Lost & Found.

### 10.1 Klasifikasi Status Keputusan

Setiap aturan transisi dalam matriks diklasifikasikan ke dalam tiga kategori:
1. **Aturan yang Sudah Disetujui (Accepted):** Aturan resmi yang telah disahkan melalui keputusan terdahulu (DEC-018, DEC-020, DEC-022, DEC-024).
2. **Rekomendasi yang Menunggu Persetujuan (Proposed):** Aturan baru yang diajukan untuk menyelesaikan ambiguitas klaim mengantre (DEC-025) dan penonaktifan/reaktivasi akun berbasis peran (DEC-026).
3. **Keputusan yang Dapat Ditunda (Postponed):** Detail lanjutan yang tidak menghambat implementasi tahap awal (misal: prosedur unhide manual laporan pasca reaktivasi penemu, batas waktu otomatis pertemuan fisik serah terima).

### 10.2 Tabel Matriks Transisi Status

| No | Skenario Alur Transisi | Status Awal (Klaim / Laporan) | Aktor / Pemicu | Kondisi Izin (Prakondisi) | Status Akhir (Klaim / Laporan) | Nasib Klaim Mengantre Lain | Pelepasan Batasan Klaim | Hak Klaim Ulang (Laporan Sama) | Status Chat & Retensi | Pencatatan Audit (Aktor, Alasan, Waktu) | Penanganan Konflik / Konkurensi | Status Keputusan |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| 1 | Penolakan klaim oleh penemu | `submitted` / `open` | Penemu | Laporan `open`, klaim `submitted`, aktor adalah penemu sah | `rejected` / `open` | Tetap `submitted` (tidak terpengaruh) | Ya (pengklaim langsung bebas) | **Dilarang** (mencegah spam) | Chat dikunci (*read-only*), input dinonaktifkan | Waktu penolakan, `changed_by = finder_id`, log riwayat status | `SELECT ... FOR UPDATE`; jika status berubah sebelum penolakan, aksi ditolak | **Disetujui** (DEC-022) |
| 2 | Pembatalan klaim sukarela oleh pengklaim | `submitted` / `open` | Pengklaim | Klaim `submitted`, aktor adalah pemilik klaim sah | `cancelled` / `open` | Tetap `submitted` (tidak terpengaruh) | Ya (pengklaim langsung bebas) | **Dilarang** (pembatalan sukarela menutup klaim barang ini) | Chat dikunci (*read-only*), input dinonaktifkan | Waktu pembatalan, `cancelled_by = claimant_id`, log status | `SELECT ... FOR UPDATE`; jika penemu memilih serah terima bersamaan, status pemenang dipertahankan | **Disetujui** (DEC-018) |
| 3 | Kedaluwarsa klaim otomatis (120 Jam) | `submitted` / `open` | Scheduler sistem | Klaim tepat `submitted`, umur >= 120 jam sejak dibuat, penemu belum merespons | `expired` / `open` | Tetap `submitted` (masing-masing klaim punya timer sendiri) | Ya (pengklaim langsung bebas) | **Diperbolehkan** selama laporan masih `open` | Chat dikunci (*read-only*) | Waktu eksekusi cron, `changed_by = NULL` (sistem), log status | Idempotent query `WHERE status = 'submitted' AND created_at <= ...` dengan row lock | **Disetujui** (DEC-024) |
| 4 | Pemilihan klaim menuju serah terima | `submitted` / `open` | Penemu | Laporan `open`, klaim `submitted`, belum ada klaim `in_handover` | `in_handover` / `in_handover` | **Tetap `submitted` (mengantre)** | Tidak (klaim terpilih & mengantre tetap aktif) | N/A (klaim sedang berlangsung) | Chat klaim terpilih aktif + panel serah terima; chat klaim lain tetap *read-only* | Waktu pemilihan, `changed_by = finder_id`, lock transaksi | Transaksi atomic database mengunci baris laporan; cegah 2 klaim masuk serah terima bersamaan | **Disetujui** (DEC-022); Nasib mengantre: **Rekomendasi (DEC-025)** |
| 5 | Pembatalan serah terima oleh pengklaim | `in_handover` / `in_handover` | Pengklaim | Klaim & laporan `in_handover`, dialog konfirmasi disetujui, alasan diisi | `cancelled` / `open` | **Tetap `submitted` (siap diproses penemu)** | Ya (pengklaim dibebaskan untuk klaim lain) | **Dilarang** bagi pengklaim ini (pembatalan sepihak) | Chat klaim ini dikunci (*read-only*), form handover ditutup | Waktu, alasan wajib (`cancellation_reason`), `cancelled_by = claimant_id` | `SELECT ... FOR UPDATE`; jika konfirmasi kedua masuk sebelum pembatalan, konfirmasi menang | **Disetujui** (DEC-018); Nasib mengantre: **Rekomendasi (DEC-025)** |
| 6 | Pembatalan serah terima oleh penemu | `in_handover` / `in_handover` | Penemu | Klaim & laporan `in_handover`, dialog konfirmasi disetujui, alasan diisi | `cancelled` / `open` | **Tetap `submitted` (siap diproses penemu)** | Ya (pengklaim dibebaskan) | **Diperbolehkan** bagi pengklaim ini jika laporan masih `open` | Chat klaim ini dikunci (*read-only*), form handover ditutup | Waktu, alasan wajib (`cancellation_reason`), `cancelled_by = finder_id` | Database lock memastikan konsistensi pembatalan dengan klaim mengantre | **Disetujui** (DEC-018); Nasib mengantre: **Rekomendasi (DEC-025)** |
| 7 | Penyelesaian serah terima sukses | `in_handover` / `in_handover` | Penemu & Pengklaim (keduanya) | Terdapat 2 konfirmasi sah dari penemu dan pengklaim | `completed` / `resolved` | **Seluruh klaim mengantre beralih `cancelled`** otomatis oleh sistem | Ya (seluruh pengklaim mengantre terbebas seketika) | N/A (laporan ditutup permanen) | Seluruh chat dikunci (*read-only*), pesan dijadwalkan hapus 30 hari | Waktu & record kedua konfirmasi permanen, log status `completed` & `resolved` | `UNIQUE KEY (claim_id, user_id)` cegah double-confirm; transaksi mass-update klaim mengantre | **Disetujui** (DEC-018, DEC-022) |
| 8 | Pembatalan laporan oleh pemilik/penemu | `submitted` / `open` | Pemilik laporan | Laporan milik pengguna, status `open` (dilarang jika `in_handover`) | Seluruh klaim `cancelled` / Laporan `cancelled` | Seluruh klaim mengantre otomatis beralih `cancelled` tanpa sanksi | Ya (seluruh pengklaim terbebas seketika) | N/A (laporan dibatalkan) | Seluruh chat dikunci (*read-only*) | Waktu pembatalan, alasan pembatalan laporan, log status | Validasi status `reports.status = 'open'`; tolak jika status telah beralih ke `in_handover` | **Disetujui** (DEC-022) |
| 9 | Laporan disembunyikan administrator | `submitted` atau `in_handover` / `open` atau `in_handover` | Administrator | Tindakan moderasi pelanggaran konten/laporan palsu | Seluruh klaim `cancelled` / Laporan `hidden` | Seluruh klaim aktif beralih `cancelled` tanpa sanksi pengklaim | Ya (seluruh pengklaim terbebas seketika) | N/A (laporan disembunyikan) | Chat dikunci (*read-only*), pesan ditahan jika ada investigasi moderasi | Waktu, `admin_id`, alasan moderasi di `moderation_actions` | Override administratif berperingkat tertinggi dalam transaksi database | **Disetujui** (DEC-022) |
| 10 | Penutupan klaim mengantre saat klaim lain sukses | `submitted` / `in_handover` | Sistem otomatis | Klaim lain pada laporan yang sama mencapai status `completed` | `cancelled` / `resolved` | Seluruh klaim mengantre diubah serentak ke `cancelled` | Ya (bebas tanpa penalti/sanksi) | N/A (laporan `resolved`) | Chat dikunci (*read-only*) | Log sistem penutupan otomatis klaim mengantre, `cancelled_by = NULL` | Dijalankan dalam satu transaksi database dengan konfirmasi serah terima final | **Disetujui** (DEC-022) |
| 11 | Penonaktifan akun pengklaim (klaim `submitted`) | `submitted` / `open` | Administrator | Akun pengklaim diubah statusnya menjadi `suspended` oleh admin | Klaim `cancelled` / Laporan `open` | Klaim pengklaim lain tetap `submitted` (tidak terganggu) | Akun ditangguhkan (tidak dapat klaim di manapun) | Dilarang selama akun tidak aktif | Chat dikunci (*read-only*) | Waktu, `admin_id`, `cancelled_by = admin_id`, log moderasi | Transaksi admin memperbarui status akun dan klaim terkait secara atomic | **Rekomendasi (DEC-026)** |
| 12 | Penonaktifan akun pengklaim (klaim `in_handover`) | `in_handover` / `in_handover` | Administrator | Akun pengklaim diubah statusnya menjadi `suspended` saat sedang serah terima | Klaim `cancelled` / Laporan **kembali `open`** | **Tetap `submitted` (penemu dapat memproses klaim lain)** | Akun ditangguhkan (tidak dapat klaim di manapun) | Dilarang selama akun tidak aktif | Chat dikunci (*read-only*), form serah terima ditutup | Waktu, `admin_id`, `cancelled_by = admin_id`, log status dan moderasi | Laporan dikembalikan ke `open` dalam satu transaksi agar penemu tidak terkunci | **Rekomendasi (DEC-026)** |
| 13 | Penonaktifan akun penemu (laporan `open`) | `submitted` / `open` | Administrator | Akun penemu diubah statusnya menjadi `suspended` oleh admin | Seluruh klaim `cancelled` / Laporan `hidden` | Seluruh klaim mengantre otomatis `cancelled` tanpa sanksi pengklaim | Ya (seluruh pengklaim langsung bebas dari batas klaim) | N/A (laporan disembunyikan) | Chat dikunci (*read-only*) | Waktu, `admin_id`, `cancelled_by = admin_id`, log moderasi | Seluruh laporan dan klaim terkait diperbarui dalam satu transaksi database | **Rekomendasi (DEC-026)** |
| 14 | Penonaktifan akun penemu (laporan `in_handover`) | `in_handover` / `in_handover` | Administrator | Akun penemu diubah statusnya menjadi `suspended` saat proses serah terima aktif | Klaim `cancelled` / Laporan `hidden` | Seluruh klaim mengantre (jika ada) otomatis `cancelled` | Ya (seluruh pengklaim langsung bebas tanpa sanksi) | N/A (laporan disembunyikan) | Chat dikunci (*read-only*), pesan ditahan untuk peninjauan | Waktu, `admin_id`, `cancelled_by = admin_id`, log moderasi | Batalkan serah terima dan sembunyikan laporan serentak | **Rekomendasi (DEC-026)** |
| 15 | Pengaktifan kembali akun (Reaktivasi) | Terminal (`cancelled`/`hidden`) | Administrator | Admin memulihkan akun pengguna menjadi `active` | Tetap Terminal (*immutable*) / Akun `active` | Tidak ada pemulihan klaim/laporan otomatis | Ya (pengguna dipulihkan haknya untuk membuat/klaim baru) | Sesuai aturan normal laporan `open` yang tersedia | Chat lama tetap terminal / read-only sesuai retensi | Waktu pemulihan akun di `moderation_actions` | Status terminal transaksi masa lalu tidak disentuh (*no resurrection of state*) | **Rekomendasi (DEC-026)** |
| 16 | Konflik konkurensi: Scheduler vs Aksi Pengguna | `submitted` / `open` | Scheduler vs Pengguna | Eksekusi otomatis 120 jam bersamaan dengan pembatalan/penolakan/handover | Status pemenang yang sah / Laporan sesuai transisi | Mengikuti aturan transisi status pemenang | Mengikuti aturan status pemenang | Mengikuti aturan status pemenang | Chat dikunci (*read-only*) | Log scheduler atau log aksi pengguna sesuai pemenang lock | `SELECT ... FOR UPDATE` baris klaim; jika status bukan `submitted`, proses dibatalkan aman (0 rows) | **Disetujui** (DEC-024) |
| 17 | Konflik konkurensi: Pembatalan vs Konfirmasi Serah Terima | `in_handover` / `in_handover` | Pengguna A vs Pengguna B | Salah satu pihak membatalkan bersamaan dengan pihak lain mengonfirmasi serah terima | `completed` / `resolved` ATAU `cancelled` / `open` | Sesuai transisi yang memenangkan lock database | Sesuai transisi pemenang | Sesuai transisi pemenang | Sesuai transisi pemenang | Log transaksi yang berhasil dieksekusi pertama kali | Transaksi database mengecek status klaim dan jumlah konfirmasi; jika 2 konfirmasi sah, batalkan pembatalan | **Disetujui** (DEC-018) |

### 10.3 Ringkasan Keputusan yang Dapat Ditunda

Hal-hal berikut diidentifikasi dapat ditunda secara eksplisit tanpa menghambat implementasi tahap awal:
1. **Batas Waktu Otomatis Serah Terima:** Penentuan durasi otomatis pembatalan serah terima yang terhenti (telah disepakati pada DEC-018 untuk ditunda).
2. **Prosedur Restorasi Manual Laporan `hidden` Pasca Reaktivasi Penemu:** Alur kerja detail jika penemu yang akunnya diaktifkan kembali ingin memulihkan laporan lama yang disembunyikan (dapat ditangani kasus per kasus oleh admin via dashboard moderasi lanjutan).
3. **Format Notifikasi Email Eksternal:** Notifikasi saat penonaktifan akun cukup menggunakan notifikasi dalam aplikasi dan pesan flash pada layar login pada fase awal.

## 11. Batasan Arsitektur

Untuk versi awal:
- Gunakan satu aplikasi Laravel.
- Gunakan MySQL sebagai basis data utama.
- Gunakan Blade sebagai sistem tampilan utama.
- Jangan menambahkan microservices tanpa kebutuhan yang jelas.
- Jangan mewajibkan layanan pihak ketiga untuk fitur inti.
- Jangan menambahkan antrean, cache server terpisah, atau layanan realtime eksternal sebelum manfaat dan kebutuhannya terbukti.

Implementasi harus tetap sederhana, tetapi tidak boleh mengorbankan keamanan atau integritas transaksi.

## 12. Hal yang Masih Perlu Ditetapkan

1. Versi PHP, Laravel, dan MySQL yang digunakan (ditetapkan saat inisialisasi lingkungan).
2. Mekanisme autentikasi dan pengiriman email verifikasi pada lingkungan lokal/pengujian.
3. Spesifikasi interval client-side polling untuk pembaruan chat (misal setiap 4-5 detik) pada antarmuka Blade.
4. Jadwal serta mekanisme penghapusan pesan otomatis (Laravel Task Scheduling / Cron).
5. Strategi cache PWA dan batasan penyimpanan data lokal untuk daftar laporan publik.
6. Strategi deployment dan konfigurasi lingkungan produksi.

Keputusan yang memengaruhi kebutuhan produk harus dicatat dalam `DECISIONS_LOG.md`. Detail tabel dan relasi harus dijelaskan dalam `DATABASE_DESIGN.md`.

---

**Catatan status:** Dokumen ini merupakan rancangan arsitektur tingkat tinggi. Detail implementasi ditetapkan melalui dokumen teknis terkait dan tidak boleh bertentangan dengan PRD serta SRS yang telah disepakati.