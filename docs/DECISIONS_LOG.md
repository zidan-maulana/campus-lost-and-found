# Decisions Log — Campus Lost & Found

**Status:** Draft v0.1  
**Proyek:** Campus Lost & Found  
**Tujuan:** Mencatat keputusan desain, kebutuhan, dan implementasi yang memengaruhi proyek.

---

## 1. Tujuan dan Aturan

Dokumen ini menjadi catatan keputusan resmi proyek.

Setiap keputusan yang berdampak pada kebutuhan, arsitektur, database, keamanan, pengalaman pengguna, atau ruang lingkup harus dicatat di sini.

Aturan pencatatan:

1. Jangan menganggap usulan sebagai keputusan final sebelum disetujui.
2. Setiap keputusan harus memiliki status yang jelas.
3. Keputusan baru yang mengubah keputusan lama harus mencatat alasan perubahan.
4. Jika dokumen saling bertentangan, jangan memilih salah satu secara sepihak. Laporkan konflik dan minta klarifikasi.
5. Coding agent tidak boleh mengubah keputusan bisnis atau aturan keamanan tanpa persetujuan.
6. Setelah keputusan disepakati, perbarui dokumen lain yang terdampak.

## 2. Status Keputusan

Gunakan status berikut:

- **Proposed:** Usulan yang masih menunggu persetujuan.
- **Accepted:** Keputusan telah disepakati.
- **Rejected:** Usulan tidak digunakan.
- **Superseded:** Keputusan lama telah digantikan keputusan baru.
- **Needs Clarification:** Informasi belum cukup untuk mengambil keputusan.

## 3. Keputusan yang Telah Ditetapkan

### DEC-001 — Ruang Lingkup Aplikasi

**Status:** Accepted

**Keputusan:** Aplikasi berfokus pada pelaporan barang hilang dan ditemukan dalam lingkungan Universitas Mercu Buana.

**Alasan:** Membatasi ruang lingkup agar sesuai dengan kebutuhan proyek UTS dan konteks penggunaan kampus.

### DEC-002 — Teknologi Utama

**Status:** Accepted

**Keputusan:** Aplikasi menggunakan Laravel, Blade, dan MySQL, dengan PWA sebagai kemampuan tambahan.

**Alasan:** Menjaga arsitektur tetap terintegrasi dan sesuai dengan ruang lingkup proyek.

### DEC-003 — Pendekatan Pengembangan

**Status:** Accepted

**Keputusan:** Pengembangan dilakukan secara bertahap menggunakan Antigravity sebagai coding agent, dengan dokumentasi proyek sebagai sumber acuan utama.

**Alasan:** Mempermudah pemeriksaan perubahan, mengurangi asumsi yang tidak disepakati, dan menjaga konsistensi implementasi.

### DEC-004 — Peran Pengguna

**Status:** Accepted

**Keputusan:** Aplikasi memiliki pengguna terverifikasi dan administrator. Administrator bertugas melakukan moderasi, bukan menentukan kepemilikan barang.

**Alasan:** Memisahkan pengelolaan keamanan platform dari kesepakatan antara pihak yang menemukan barang dan pihak yang mengajukan klaim.

### DEC-005 — Kesepakatan Kepemilikan

**Status:** Accepted

**Keputusan:** Kepemilikan barang ditangani melalui komunikasi dan kesepakatan antara penemu dan pengklaim. Administrator tidak berwenang menentukan siapa pemilik barang dalam proses klaim.

**Alasan:** Menjaga peran administrator tetap pada moderasi platform.

### DEC-006 — Banyak Pengajuan Klaim

**Status:** Accepted

**Keputusan:** Beberapa pengguna dapat mengajukan klaim terhadap satu laporan barang ditemukan, tetapi hanya satu klaim yang dapat melanjutkan proses serah terima pada satu waktu.

**Alasan:** Memberikan kesempatan pengajuan tanpa menjalankan beberapa proses serah terima secara bersamaan untuk laporan yang sama.

### DEC-007 — Pembatasan Klaim Baru dan Pelepasan Status Terminal

**Status:** Accepted

**Keputusan:** Pengguna tidak boleh mengajukan klaim terhadap barang temuan lain selama memiliki klaim yang berstatus aktif (`submitted` atau `in_handover`). Begitu klaim mencapai status terminal (`completed`, `rejected`, `cancelled`, atau `expired`), pembatasan tersebut otomatis terlepas dan pengguna bebas mengajukan klaim pada laporan barang temuan lain. Status terminal bersifat permanen dan tidak dapat diubah kembali menjadi aktif.

**Alasan:** Mencegah pengguna memonopoli atau melakukan spam klaim pada banyak barang sekaligus, namun tidak mengunci pengguna yang klaimnya gagal, ditolak, atau dibatalkan.

### DEC-008 — Konfirmasi Serah Terima

**Status:** Accepted

**Keputusan:** Serah terima dinyatakan selesai hanya setelah penemu dan pengklaim sama-sama mengonfirmasi bahwa serah terima telah dilakukan.

**Alasan:** Mencegah satu pihak menyelesaikan transaksi secara sepihak.

### DEC-009 — Chat Privat

**Status:** Accepted

**Keputusan:** Komunikasi menggunakan chat teks privat di dalam aplikasi dan hanya dapat diakses oleh kedua pihak yang terlibat dalam klaim.

**Alasan:** Menjaga komunikasi tetap terhubung dengan proses klaim dan membatasi akses terhadap percakapan.

### DEC-010 — Penutupan Chat

**Status:** Accepted

**Keputusan:** Setelah kedua pihak mengonfirmasi serah terima, chat ditutup dan tidak dapat menerima pesan baru.

**Alasan:** Menandai berakhirnya komunikasi untuk transaksi tersebut.

### DEC-011 — Retensi Pesan

**Status:** Accepted

**Keputusan:** Isi pesan dihapus otomatis 30 hari setelah kedua pihak mengonfirmasi serah terima. Pesan yang terkait dengan laporan penyalahgunaan yang belum selesai ditinjau dikecualikan sampai peninjauan selesai.

**Alasan:** Membatasi penyimpanan isi komunikasi sambil mempertahankan kesempatan peninjauan penyalahgunaan.

**Catatan:** Kebijakan penghapusan setelah peninjauan moderasi selesai masih perlu diklarifikasi. Metadata transaksi seperti status dan waktu konfirmasi tetap dipertahankan.

### DEC-012 — Notifikasi

**Status:** Accepted

**Keputusan:** Versi awal menyediakan notifikasi di dalam aplikasi, tanpa push notification ketika aplikasi tertutup.

**Alasan:** Membatasi kompleksitas implementasi notifikasi pada versi awal.

### DEC-013 — Moderasi

**Status:** Accepted

**Keputusan:** Pengguna dapat melaporkan konten atau pesan. Administrator dapat meninjau laporan, menyembunyikan konten, memberikan peringatan, atau menangguhkan akun untuk pelanggaran serius atau berulang. Tindakan dan alasannya harus dicatat.

**Alasan:** Menyediakan mekanisme penanganan penyalahgunaan yang dapat ditinjau kembali.

### DEC-014 — PWA dan Akses Offline

**Status:** Accepted

**Keputusan:** Aplikasi mendukung instalasi PWA dan pengalaman offline dasar. Tindakan transaksi seperti klaim, chat, dan konfirmasi serah terima tetap membutuhkan internet.

**Alasan:** Memberikan kemudahan akses tanpa mengorbankan konsistensi data transaksi.

### DEC-015 — Implementasi Bertahap

**Status:** Accepted

**Keputusan:** Coding agent tidak boleh langsung membangun seluruh aplikasi. Pengerjaan dilakukan per fase dengan pemeriksaan hasil dan persetujuan sebelum melanjutkan ke tahap yang memerlukan keputusan pengguna.

**Alasan:** Mengurangi risiko perubahan yang terlalu luas dan memudahkan pengujian.

### DEC-017 — Penanganan Klaim Ditolak, Dibatalkan, dan Tanpa Respons

**Status:** Accepted

**Keputusan:**
1. Penemu berhak menolak klaim (`rejected`) yang belum memasuki tahap serah terima.
2. Pengklaim berhak membatalkan klaimnya sendiri (`cancelled`) sebelum tahap serah terima.
3. Jika penemu tidak merespons selama 72 jam sejak klaim dibuat, sistem mengirim notifikasi dalam aplikasi kepada pengklaim. Pengklaim memiliki waktu tambahan 48 jam untuk menunggu atau membatalkan klaim secara sukarela (`cancelled`). Jika penemu tetap tidak merespons dan klaim tidak dibatalkan hingga total 120 jam (5 hari) sejak pembuatan klaim, sistem secara otomatis mengubah status klaim menjadi `expired`.
4. Seluruh status terminal (`rejected`, `cancelled`, `expired`) langsung melepaskan pembatasan klaim pengguna sehingga dapat mengajukan klaim pada barang lain.
5. Pengguna yang klaimnya ditolak (`rejected`) atau yang membatalkan klaimnya sendiri secara sukarela (`cancelled` oleh pengklaim) dilarang mengajukan klaim ulang pada laporan barang yang sama untuk mencegah spam. Pengecualian berlaku untuk klaim yang kedaluwarsa otomatis (`expired`) serta klaim yang dibatalkan oleh penemu saat serah terima (`cancelled` oleh penemu sesuai DEC-018), di mana pengklaim diperbolehkan mengajukan klaim kembali pada laporan yang sama selama laporan tersebut masih berstatus `open`.

**Alasan:** Menghilangkan kebuntuan (*deadlock*) pengguna, memberdayakan kedua belah pihak secara adil, dan mencegah eksploitasi pengajuan klaim berulang tanpa merugikan pengklaim yang tidak mendapat respons.

### DEC-018 — Penanganan Serah Terima yang Terhenti dan Kebijakan Klaim Ulang

**Status:** Accepted

**Keputusan:**
1. Jika proses serah terima terhenti atau salah satu pihak tidak hadir/menolak konfirmasi fisik, kedua belah pihak memiliki hak eksplisit untuk membatalkan proses serah terima melalui tombol antarmuka aplikasi.
2. Pembatalan memerlukan dialog konfirmasi peringatan dan input alasan pembatalan. Waktu pembatalan, alasan pembatalan, serta identitas pihak yang membatalkan (`cancelled_by`) wajib dicatat oleh sistem.
3. Dampak pembatalan serah terima dibedakan secara tegas berdasarkan pihak yang membatalkan:
   - **Jika pengklaim membatalkan serah terima:**
     - Status klaim berubah menjadi `cancelled` (terminal).
     - Status laporan kembali menjadi `open` (selama tidak dibatalkan pemilik atau disembunyikan admin).
     - Pengklaim dibebaskan dari pembatasan klaim aktif.
     - **Pengklaim dilarang mengajukan klaim ulang pada laporan yang sama** guna mencegah looping atau penyalahgunaan.
     - Pengklaim tetap dapat mengajukan klaim pada laporan barang temuan lain.
   - **Jika penemu membatalkan serah terima:**
     - Status klaim berubah menjadi `cancelled` (terminal).
     - Status laporan kembali menjadi `open` (selama masih memenuhi syarat).
     - Pengklaim dibebaskan dari pembatasan klaim aktif.
     - **Pengklaim diperbolehkan mengajukan klaim kembali pada laporan yang sama** selama laporan masih `open` dan tidak ada pembatasan lain yang berlaku (karena pembatalan dilakukan oleh penemu, bukan kesalahan pengklaim).
     - Pengklaim juga dapat mengajukan klaim pada laporan barang temuan lain.
4. Sistem membedakan kedua kasus tersebut melalui pencatatan aktor pembatal (`cancelled_by`).
5. Tidak ada batas waktu otomatis untuk serah terima yang terhenti sebelum disepakati secara terpisah.

**Alasan:** Menghindari laporan barang dan akun pengguna terkunci selamanya jika pertemuan fisik tidak terlaksana, serta memperlakukan pengklaim secara adil berdasarkan pihak yang menyebabkan pembatalan serah terima.

### DEC-020 — Kebijakan Akses Pengunjung Tanpa Login

**Status:** Accepted

**Keputusan:**
1. **Hak Akses Pengunjung Tanpa Login (*Guest*):**
   - Pengunjung yang belum login diperbolehkan melihat daftar laporan barang hilang dan barang temuan publik.
   - Pengunjung yang belum login diperbolehkan melihat halaman detail umum laporan yang dipublikasikan.
   - Pengunjung yang belum login diperbolehkan melakukan pencarian kata kunci dan penyaringan (*filter*) laporan publik.
2. **Batasan Pengunjung Tanpa Login:**
   - Pengunjung tanpa login dilarang mengajukan klaim barang temuan.
   - Pengunjung tanpa login dilarang mengirim pesan chat privat atau berinteraksi dengan pengguna lain.
   - Pengunjung tanpa login dilarang membuat laporan baru atau melakukan tindakan lain yang memerlukan akun terverifikasi.
3. **Persyaratan Klaim dan Interaksi Terlindungi:**
   - Untuk mengajukan klaim atau melakukan tindakan yang dilindungi, pengguna wajib memenuhi 3 syarat kumulatif:
     1. Login ke akun.
     2. Memiliki alamat email resmi kampus yang telah diverifikasi (login saja tidak cukup tanpa verifikasi email kampus).
     3. Akun berada dalam status aktif dan tidak sedang ditangguhkan/dinonaktifkan oleh administrator.

**Alasan:** Memberikan transparansi publik dan mempermudah civitas kampus maupun masyarakat umum untuk menemukan barang yang hilang atau melaporkan temuan tanpa hambatan registrasi di awal, sembari tetap menjaga keamanan transaksi, validitas klaim, dan privasi komunikasi dengan mewajibkan akun terverifikasi email kampus untuk tindakan interaktif.

### DEC-022 — Status dan Transisi Siklus Hidup Laporan dan Klaim

**Status:** Accepted

**Keputusan:**
1. Status Laporan Barang (`reports.status`): `'open'`, `'in_handover'`, `'resolved'`, `'cancelled'`, `'hidden'`.
2. Status Klaim (`claims.status`):
   - Status Aktif: `'submitted'`, `'in_handover'`.
   - Status Terminal: `'completed'`, `'rejected'`, `'cancelled'`, `'expired'`.
3. Transisi status laporan dan klaim diselaraskan secara resmi di seluruh dokumen (SRS, System Design, Database Design, UI/UX).

**Alasan:** Menghilangkan dependensi melingkar antardokumen dan memberikan spesifikasi konkret untuk migrasi database serta state machine aplikasi.

### DEC-024 — Kebijakan Kedaluwarsa Klaim Tanpa Respons (120 Jam)

**Status:** Accepted

**Keputusan:**
1. **Notifikasi 72 Jam:** Sistem mengirimkan notifikasi dalam aplikasi kepada pengklaim jika penemu belum merespons klaim setelah 72 jam sejak klaim dibuat.
2. **Masa Tambahan 48 Jam:** Setelah notifikasi 72 jam, pengklaim diberikan waktu tambahan 48 jam untuk menunggu respons penemu atau membatalkan klaim secara sukarela.
3. **Kedaluwarsa Otomatis 120 Jam:** Jika penemu tetap tidak merespons dan pengklaim belum membatalkan klaim, sistem secara otomatis mengubah status klaim dari `submitted` menjadi `expired` tepat setelah total 120 jam (5 hari) terhitung sejak waktu pembuatan klaim (`submitted_at` / `created_at`).
4. **Dasar Penghitungan Waktu:** Batas waktu dihitung berdasarkan timestamp pembuatan klaim, bukan waktu pengiriman notifikasi.
5. **Pelepasan Hak Klaim:** Status `expired` langsung membebaskan pengklaim dari pembatasan klaim aktif, sehingga ia dapat segera mengajukan klaim pada laporan barang temuan lain.
6. **Hak Klaim Ulang pada Laporan yang Sama:** Pengklaim diperbolehkan mengajukan klaim kembali pada laporan yang sama selama laporan tersebut masih berstatus `open`. Kedaluwarsa adalah batas waktu otomatis sistem dan bukan pelanggaran pengklaim, sehingga tidak ada sanksi atau larangan klaim ulang akibat klaim kedaluwarsa.
7. **Perlindungan Pembatalan:** Jika pengklaim membatalkan klaim sebelum batas 120 jam, status berubah menjadi `cancelled`. Proses otomatis kedaluwarsa tidak boleh menimpa status tersebut.
8. **Perlindungan Transisi Aktif:** Jika penemu merespons atau status klaim berubah dari `submitted` (misalnya menjadi `in_handover` atau `rejected`) sebelum batas 120 jam, proses otomatis kedaluwarsa tidak boleh mengubah klaim tersebut menjadi `expired`.
9. **Idempotensi Eksekusi:** Tugas terjadwal kedaluwarsa harus aman dijalankan berulang kali (*idempotent*) dan hanya menargetkan klaim dengan status `submitted` yang telah melampaui ambang batas 120 jam.

**Alasan:** Memberikan kepastian batas waktu bagi pengklaim tanpa mengorbankan haknya untuk mencari barang temuan miliknya.

## 4. Keputusan yang Masih Menunggu Klarifikasi

### DEC-016 — Domain Email Kampus

**Status:** Needs Clarification

**Pertanyaan:** Domain email apa yang secara resmi diizinkan untuk registrasi dan verifikasi pengguna?

**Dampak:** Validasi email dan mekanisme verifikasi akun.

**Aturan sementara:** Jangan mengarang domain atau membatasi registrasi berdasarkan domain yang belum dikonfirmasi.

### DEC-019 — Retensi Pesan Setelah Moderasi

**Status:** Needs Clarification

**Pertanyaan:** Berapa lama pesan yang terkait laporan penyalahgunaan harus dipertahankan setelah peninjauan moderasi selesai?

**Dampak:** Penghapusan pesan otomatis, penanganan bukti moderasi, dan kebijakan retensi data.

**Aturan sementara:** Jangan menghapus pesan yang masih terkait laporan penyalahgunaan yang belum selesai ditinjau.


### DEC-021 — Validasi Foto

**Status:** Needs Clarification

**Pertanyaan:** Format file dan batas ukuran foto apa yang diizinkan?

**Dampak:** Validasi formulir, penyimpanan file, keamanan unggahan, dan pengalaman pengguna.

**Aturan sementara:** Tetapkan validasi yang aman sebelum fitur unggah diaktifkan (usulan: 1 file, maks 2 MB, JPEG/PNG/WebP).

### DEC-023 — Target Browser dan Perangkat

**Status:** Needs Clarification

**Pertanyaan:** Browser dan perangkat minimum apa yang harus didukung?

**Dampak:** Pengujian responsivitas, kompatibilitas PWA, dan strategi implementasi.

**Aturan sementara:** Lakukan pengujian dasar pada lingkungan yang tersedia dan dokumentasikan keterbatasannya.

### DEC-025 — Status Klaim Mengantre Selama dan Pasca Pembatalan Serah Terima

**Status:** Proposed (Menunggu Persetujuan Pemilik Proyek)

**Tanggal:** 2026-10-24

**Konteks:** Satu laporan barang temuan dapat memiliki beberapa klaim berstatus `submitted`. Ketika salah satu klaim dipilih untuk serah terima (`in_handover`), laporan dan klaim terpilih beralih ke `in_handover`. Perlu kepastian aturan mengenai nasib klaim-klaim lain yang masih `submitted` ketika proses serah terima tersebut dibatalkan (baik oleh pengklaim maupun oleh penemu).

**Pilihan yang dipertimbangkan:**
1. *Membatalkan seluruh klaim mengantre secara otomatis saat serah terima dibatalkan (atau saat serah terima dimulai).*  
   *Konsekuensi:* Pengklaim yang sudah mengantre secara sah kehilangan antreannya secara sepihak dan harus mengajukan ulang; rentan memicu kebingungan hak klaim jika tidak sengaja tercatat sebagai pembatalan sukarela.
2. *(Rekomendasi Utama) Mempertahankan klaim mengantre dalam status `submitted`.*  
   Ketika serah terima dibatalkan dan laporan kembali `open`, klaim-klaim mengantre tetap sah dalam status `submitted`. Penemu dapat langsung meninjau dan memilih klaim mengantre berikutnya tanpa memaksa pengklaim mengajukan ulang. Pengklaim mengantre tetap memiliki hak membatalkan klaimnya secara sukarela (`cancelled`) jika tidak ingin menunggu, atau klaimnya akan kedaluwarsa (`expired`) setelah 120 jam sejak dibuat.

**Rekomendasi Keputusan:**
1. Selama satu klaim berada dalam status `in_handover`, klaim-klaim lain pada laporan yang sama tetap berstatus `submitted` (mengantre).
2. Jika serah terima dibatalkan (baik oleh pengklaim maupun oleh penemu) dan laporan kembali berstatus `open`, seluruh klaim lain yang berstatus `submitted` **tetap bertahan dalam status `submitted`** (tidak dibatalkan).
3. Penemu dapat langsung meninjau dan memilih klaim mengantre berikutnya untuk melanjutkan proses serah terima baru.
4. Pengklaim mengantre tetap terikat batasan 1 klaim aktif di seluruh sistem selama klaimnya `submitted`, namun memiliki hak eksplisit untuk membatalkan klaimnya sendiri kapan saja (`cancelled`) guna melepaskan hak klaimnya.
5. **Timer Kedaluwarsa 120 Jam Tidak Direset:** Penghitungan batas waktu kedaluwarsa 120 jam dihitung mutlak dari timestamp pembuatan klaim awal (`submitted_at` / `created_at`). Pembatalan serah terima yang sedang berjalan tidak mengatur ulang (*reset*) timer klaim lain yang tetap menunggu (`submitted`). Pemilihan klaim berikutnya menuju serah terima juga tidak mereset timer klaim tersebut. Jika penemu tidak merespons atau tidak memilih klaim mengantre dalam kurun 120 jam sejak klaim dibuat, klaim otomatis menjadi `expired` dan membebaskan pengklaim tanpa penalti. Scheduler bersifat idempoten, mengabaikan klaim yang telah beralih status secara sah, dan aman terhadap konflik transaksi.
6. Apabila serah terima diselesaikan dengan sukses (`resolved`), maka seluruh klaim mengantre tersebut otomatis diakhiri menjadi `cancelled` tanpa sanksi.

**Alasan:** Menjaga keadilan bagi pengklaim yang sah, mencegah pengklaim kehilangan antrean secara tidak sengaja, dan memungkinkan penemu melanjutkan proses pengembalian barang secara efisien ke kandidat berikutnya.

**Dampak:** PRD (PR-04, PR-06), SRS (FR-06, FR-07), System Design (Alur & Matriks Transisi), Database Design (`claims`, `reports`), UI/UX (banner status antrean).

### DEC-026 — Dampak Penonaktifan dan Reaktivasi Akun Berdasarkan Peran Pengguna

**Status:** Proposed (Menunggu Persetujuan Pemilik Proyek)

**Tanggal:** 2026-10-24

**Konteks:** Dokumentasi sebelumnya belum membedakan secara spesifik dampak penonaktifan akun (`users.status = 'suspended'`) oleh administrator berdasarkan peran pengguna (apakah sebagai pengklaim atau sebagai penemu) serta dampaknya terhadap klaim mengantre, proses serah terima aktif, laporan yang dimiliki, dan kondisi saat akun diaktifkan kembali.

**Pilihan yang dipertimbangkan:**
1. *Menerapkan aturan seragam (membatalkan semua laporan dan klaim milik pengguna).*  
   *Konsekuensi:* Jika penemu dinonaktifkan, laporan dibatalkan begitu saja dan dapat merugikan pengklaim lain atau menghilangkan jejak barang temuan; jika pengklaim dinonaktifkan saat serah terima, penemu bisa ikut dirugikan jika laporan ditutup permanen.
2. *(Rekomendasi Utama) Diferensiasi berbasis peran dengan memanfaatkan status eksisting (`hidden`, `cancelled`, `open`) dan prinsip ketetapan status terminal (immutability).*

**Rekomendasi Keputusan:**
1. **Dampak Penonaktifan Akun Pengklaim (Claimant Suspended):**
   - Klaim berstatus `submitted`: diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`). Status laporan tetap `open`. Klaim mengantre lainnya tidak terpengaruh.
   - Klaim berstatus `in_handover`: diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`). Status laporan terkait **dikembalikan dari `in_handover` menjadi `open`**, sehingga penemu tidak dirugikan dan dapat memproses klaim mengantre lainnya atau menerima klaim baru.
   - Hak klaim: akun yang dinonaktifkan diblokir dari mengajukan klaim baru di seluruh sistem.
   - Chat: percakapan terkait klaim dikunci menjadi hanya-baca (*read-only*).
2. **Dampak Penonaktifan Akun Penemu (Finder Suspended):**
   - Laporan berstatus `open`: diubah statusnya menjadi `hidden` (menggunakan status yang sudah ada dalam desain, tanpa menambah enum baru).
   - Laporan berstatus `in_handover`: diubah statusnya menjadi `hidden`. Klaim yang sedang `in_handover` diubah menjadi `cancelled` oleh admin/sistem.
   - Seluruh klaim mengantre (`submitted`) pada seluruh laporan milik penemu yang dinonaktifkan otomatis diubah menjadi `cancelled` oleh admin/sistem (`cancelled_by = admin_id` atau sistem). Seluruh pengklaim langsung terbebas dari batasan klaim aktif tanpa penalti atau catatan pelanggaran.
   - Chat: seluruh percakapan terkait laporan penemu dikunci (*read-only*), riwayat pesan ditahan untuk kepentingan investigasi moderasi jika diperlukan.
3. **Kebijakan Reaktivasi Akun (Account Reactivation) & Pemisahan Status Akun vs Laporan:**
   - **Pemisahan Status Akun dan Status Laporan:** Status akun (`users.status`) dan status laporan (`reports.status`) merupakan dua hal yang berbeda. Pengaktifan kembali akun penemu (`active`) tidak otomatis mengubah status laporan yang `hidden` menjadi `open` atau mempublikasikannya kembali.
   - Jika administrator mengaktifkan kembali akun yang sebelumnya dinonaktifkan (`users.status = 'active'`), seluruh klaim dan laporan terdahulu yang telah berstatus terminal (`cancelled`, `completed`, `rejected`, `expired`, `hidden`) **bersifat permanen dan tidak dihidupkan kembali secara otomatis** (*no automatic revival*).
   - Pengguna yang dipulihkan haknya dapat kembali membuat laporan baru atau mengajukan klaim baru pada laporan yang masih `open` sesuai prosedur normal.
   - **Keputusan Ditunda (Non-blocker):** Prosedur dan kebijakan publikasi ulang laporan `hidden` milik penemu (apakah memerlukan peninjauan manual terpisah oleh admin atau verifikasi fisik ulang barang) belum disahkan dan secara resmi ditunda ke fase moderasi lanjutan, sehingga tidak menghambat implementasi awal.

**Alasan:** Menjaga integritas data transaksi, melindungi hak pengguna lain yang tidak bersalah dari dampak penonaktifan pihak lain, mencegah status transaksi menggantung (*deadlock*), dan mencegah timbulnya data anomali (*ghost transactions*) jika akun dipulihkan di kemudian hari.

**Dampak:** PRD (PR-04, PR-08), SRS (FR-06, FR-07, FR-12), System Design (Section 7 & Matriks Transisi Section 10), Database Design (`users`, `reports`, `claims`), UI/UX (Modal konfirmasi admin & banner status akun nonaktif).

### DEC-027 — Satu Barang, Satu Laporan Aktif (Penutupan Laporan Hilang & Verifikasi Klaim)

**Status:** Accepted

**Tanggal:** 2026-11-09

**Konteks:** PRD hanya menyebut pengguna "dapat membuat laporan hilang maupun temuan" (PR-02) dan klaim hanya berlaku pada laporan temuan (PR-04). Tidak ada aturan yang mengikat keduanya, sehingga satu barang dapat menghasilkan dua laporan aktif sekaligus: laporan hilang milik pemilik dan laporan temuan milik penemu. Laporan hilang tersebut tidak pernah ditutup karena satu-satunya jalur penutupan laporan di PRD adalah serah terima dua arah (PR-06) yang hanya menyentuh laporan temuan. Akibatnya katalog publik menampilkan barang yang sama dua kali, dan laporan yang sudah selesai (`resolved`) tetap tampil di daftar sehingga menambah kebisingan. Selain itu parameter `reference_report_id` pada pembuatan laporan temuan hanya dipakai untuk notifikasi dan tidak pernah disimpan, sehingga hubungan antar laporan tidak dapat ditelusuri. Verifikasi klaim pun masih lemah: form klaim web mengirim permintaan kosong padahal kolom `reports.proof_question`, `claims.proof_answer`, dan `claims.proof_attachment` sudah tersedia di skema.

**Pilihan yang dipertimbangkan:**
1. *Mengubah PR-04 agar klaim diajukan langsung pada laporan hilang (penemu mengklaim "saya menemukan ini").*  
   *Konsekuensi:* menghapus kebutuhan laporan temuan, tetapi membalik seluruh model peran (`isFinder`/`isClaimant`), memaksa migrasi data, dan mengubah banyak aturan terminal klaim. Biaya dan risiko regresi paling tinggi.
2. *Membiarkan dua laporan, tetapi menutup laporan hilang otomatis saat laporan temuan yang terhubung dibuat, menyembunyikan laporan selesai dari katalog, mencegah duplikat, dan menambahkan verifikasi klaim.*  
   *Konsekuensi:* PR-02/PR-04 tetap sah, perubahan aditif, dan tidak ada data lama yang perlu dimigrasi.
3. *Hanya menyembunyikan laporan selesai dari katalog tanpa menutup laporan hilang.*  
   *Konsekuensi:* masalah utama (satu barang dua laporan aktif) tidak selesai.

**Keputusan:** Opsi 2.
1. `reports.reference_report_id` (nullable, FK ke `reports.id`) menyimpan hubungan laporan temuan → laporan hilang. Kolom ini sekarang benar-benar diisi saat laporan temuan dibuat dengan `reference_report_id`.
2. Saat laporan temuan dibuat dari laporan hilang yang masih `open`, laporan hilang tersebut langsung diubah menjadi `resolved` dan pemiliknya dinotifikasi. Label tampilan untuk kondisi ini adalah **"Sudah Ketemu"** (bukan "Selesai"), karena serah terima belum tentu terjadi.
3. Bila laporan temuan tersebut dibatalkan, laporan hilang yang tertaut dibuka kembali menjadi `open`.
4. Katalog publik (beranda, pencarian, `/api/reports`) hanya menampilkan laporan berstatus `open` dan `in_handover` (scope `listed`). Laporan `resolved` tetap dapat dibuka melalui tautan detail langsung, dan tetap muncul di "Laporan Saya".
5. Laporan temuan ganda dari pengguna yang sama untuk judul barang yang sama (case-insensitive) selama masih aktif ditolak di server.
6. Formulir laporan temuan menyediakan pencarian laporan hilang untuk dihubungkan, dan kolom pertanyaan verifikasi (`proof_question`).
7. Klaim web wajib menyertakan alasan klaim (minimal 10 karakter); bila penemu memasang `proof_question`, jawabannya wajib diisi; foto bukti (`proof_attachment`) opsional (JPEG/PNG/WebP, maks 2 MB). Jawaban dan foto hanya dapat dilihat oleh penemu dan pengklaim.
8. Pemilik laporan **hilang** dapat menutup laporannya sendiri ("Tandai Selesai" / `POST /api/reports/{id}/resolve`) ketika barangnya sudah kembali tanpa lewat serah terima di aplikasi. Endpoint ini khusus laporan hilang; laporan temuan tetap hanya ditutup lewat serah terima dua arah. Status `resolved` bersifat permanen (BR-13) sehingga tombol "Buka Kembali" dihapus.
9. Tombol "Tandai Selesai", "Edit Laporan", dan "Hapus Laporan" pada halaman "Laporan Saya" menulis ke server (dulu hanya mengubah `localStorage` sehingga status tidak pernah berubah setelah halaman dimuat ulang). "Hapus Laporan" memanggil endpoint pembatalan (`cancelled`), bukan menghapus baris.

**Alasan:** Menutup celah spam tanpa merombak PR-04. Satu barang hanya menghasilkan satu laporan aktif di katalog, laporan yang sudah beres hilang dari daftar, dan klaim asal-asalan tersaring oleh pertanyaan verifikasi serta bukti foto — semua memakai kolom yang sudah ada sehingga tidak menambah tabel baru selain satu foreign key.

**Dampak:** PRD (PR-02, PR-04, PR-06), SRS (FR-03, FR-06), Database Design (`reports.reference_report_id`), UI/UX (label status "Sudah Ketemu", modal klaim, panel hubungkan laporan), Chat (kartu keputusan klaim menampilkan bukti pengklaim).

**Catatan:** Lampiran gambar di dalam chat tetap di luar ruang lingkup (PRD 5.2). Foto bukti klaim bukan lampiran chat, melainkan bagian dari data klaim.

## 5. Template Keputusan Baru

Gunakan format berikut untuk setiap keputusan baru:

### DEC-XXX — Judul Keputusan

**Status:** Proposed / Accepted / Rejected / Superseded / Needs Clarification

**Tanggal:** YYYY-MM-DD

**Konteks:** Masalah atau kebutuhan yang melatarbelakangi keputusan.

**Pilihan yang dipertimbangkan:** Alternatif yang relevan.

**Keputusan:** Pilihan yang disepakati, jika sudah ada.

**Alasan:** Mengapa pilihan tersebut digunakan.

**Dampak:** Dokumen, database, antarmuka, atau fitur yang terpengaruh.

**Persetujuan:** Pihak yang menyetujui keputusan, jika relevan.

## 6. Aturan Pembaruan

Setiap kali ada keputusan baru:

1. Tambahkan entri baru tanpa menghapus riwayat keputusan sebelumnya.
2. Ubah status keputusan lama menjadi `Superseded` jika memang digantikan.
3. Jelaskan alasan perubahan.
4. Perbarui dokumen terkait.
5. Beri tahu coding agent tentang perubahan yang memengaruhi implementasi.

Jangan menggunakan dokumen ini untuk menyatakan fitur sudah selesai. Keputusan desain dan status implementasi harus dicatat secara terpisah.

## 7. Status Dokumentasi

Dokumen-dokumen proyek yang telah disusun:

- `AGENTS.md`
- `docs/PRD.md`
- `docs/SRS.md`
- `docs/SYSTEM_DESIGN.md`
- `docs/DATABASE_DESIGN.md`
- `docs/UI_UX_SPECIFICATION.md`
- `docs/PWA_SPECIFICATION.md`
- `docs/DEVELOPMENT_PLAN.md`
- `docs/DECISIONS_LOG.md`

**Catatan:** Dokumen telah disusun sebagai rancangan awal. Sebelum implementasi, lakukan pemeriksaan konsistensi dan klarifikasi keputusan yang memengaruhi keamanan, struktur database, atau alur bisnis.

**Prinsip utama:** keputusan harus dapat dilacak, perubahan harus dijelaskan, dan coding agent tidak boleh mengubah aturan proyek secara sepihak.