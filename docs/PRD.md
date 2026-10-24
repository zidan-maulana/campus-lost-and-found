# Product Requirements Document (PRD)
# Campus Lost & Found

**Versi:** 0.1  
**Status:** Draf  
**Terakhir diperbarui:** 23 Oktober 2026

## 1. Ringkasan Produk

Campus Lost & Found adalah aplikasi web untuk membantu sivitas akademika Universitas Mercu Buana melaporkan barang hilang, mempublikasikan barang temuan, mencari barang, dan berkomunikasi untuk mengatur proses pengembalian barang.

Aplikasi menyediakan mekanisme klaim agar orang yang merasa kehilangan barang dapat menghubungi penemu melalui proses yang terstruktur. Proses serah terima dinyatakan selesai setelah kedua pihak memberikan konfirmasi.

Aplikasi dikembangkan menggunakan Laravel, Blade, MySQL, dan Progressive Web App (PWA).

## 2. Latar Belakang Masalah

Informasi barang hilang dan barang temuan di lingkungan kampus dapat tersebar melalui percakapan pribadi, grup pesan, atau pengumuman yang sulit ditelusuri kembali.

Kondisi tersebut dapat menyulitkan mahasiswa atau anggota sivitas akademika untuk menemukan informasi barang yang relevan dan menghubungi pihak yang tepat.

Campus Lost & Found dirancang untuk menyediakan satu platform terpusat yang mempermudah pencarian informasi, pengajuan klaim, komunikasi, dan pencatatan serah terima barang.

## 3. Tujuan Produk

1. Memusatkan informasi laporan barang hilang dan barang temuan.
2. Mempermudah pencarian barang berdasarkan kata kunci dan filter.
3. Menyediakan proses klaim dan komunikasi antara pihak terkait.
4. Mencatat konfirmasi serah terima dari kedua pihak.
5. Menyediakan mekanisme pelaporan penyalahgunaan dan moderasi.
6. Menyediakan pengalaman penggunaan yang responsif dan dapat dipasang sebagai PWA.

## 4. Target Pengguna

### 4.1 Pengguna Terdaftar

Pengguna merupakan anggota komunitas Universitas Mercu Buana yang mendaftar menggunakan alamat email resmi universitas dan menyelesaikan verifikasi email.

Pengguna dapat:
- Membuat laporan barang hilang.
- Membuat laporan barang temuan.
- Mencari dan melihat laporan barang.
- Mengajukan klaim terhadap barang temuan.
- Berkomunikasi dengan pihak terkait melalui chat privat.
- Mengonfirmasi serah terima barang.
- Melaporkan konten atau pesan yang dianggap melanggar aturan.

### 4.2 Administrator

Administrator bertugas menjaga keamanan dan ketertiban platform.

Administrator dapat:
- Meninjau laporan penyalahgunaan.
- Memeriksa konten yang dilaporkan sesuai kebutuhan moderasi.
- Menyembunyikan konten yang melanggar aturan.
- Memberikan peringatan kepada pengguna.
- Menangguhkan akun untuk pelanggaran berat atau berulang.
- Mencatat tindakan moderasi beserta alasannya.

Administrator **tidak bertugas menentukan kepemilikan barang atau memutuskan kebenaran klaim**. Proses klaim ditentukan melalui kesepakatan antara pihak yang terlibat.

## 5. Ruang Lingkup Produk

### 5.1 Fitur yang Termasuk

- Registrasi dengan email resmi universitas.
- Verifikasi email sebelum mengakses fitur inti.
- Login dan logout.
- Pembuatan, pengubahan, dan pembatalan laporan barang.
- Pencarian dan filter laporan.
- Pengajuan serta pengelolaan klaim.
- Chat privat berbasis teks.
- Konfirmasi serah terima oleh kedua pihak.
- Notifikasi di dalam aplikasi.
- Pelaporan penyalahgunaan dan moderasi administrator.
- PWA yang responsif, dapat dipasang, dan mendukung kemampuan offline dasar.

### 5.2 Fitur di Luar Ruang Lingkup

Versi awal tidak mencakup:
- Aplikasi native Android atau iOS.
- Notifikasi push ketika aplikasi ditutup.
- Chat melalui layanan eksternal.
- Panggilan suara atau video.
- Lampiran gambar atau berkas di dalam chat.
- Penentuan kepemilikan barang secara otomatis oleh administrator.
- Sistem pembayaran atau transaksi jual beli.
- Integrasi kecerdasan buatan untuk mengenali atau mencocokkan barang secara otomatis.

## 6. Persyaratan Produk Tingkat Tinggi

### PR-01 — Registrasi dan Autentikasi

Pengguna harus mendaftar menggunakan email resmi Universitas Mercu Buana dan memverifikasi alamat email sebelum dapat menggunakan fitur inti.

Pengunjung tanpa login (*guest*) dapat melihat daftar laporan barang hilang dan temuan, membuka detail umum laporan yang dipublikasikan, serta mencari dan menyaring laporan. Namun, tindakan yang dilindungi—seperti membuat laporan, mengajukan klaim, mengirim pesan chat privat, dan mengonfirmasi serah terima—mewajibkan pengguna untuk login, memiliki email kampus yang terverifikasi, dan akun berstatus aktif. Login saja tanpa verifikasi email kampus tidak memberikan akses ke fitur-fitur yang dilindungi tersebut.

Domain email resmi yang diizinkan harus dikonfirmasi sebelum implementasi.

### PR-02 — Laporan Barang

Pengguna dapat membuat laporan barang hilang maupun barang temuan.

Laporan memuat informasi yang relevan, seperti:
- Jenis laporan.
- Nama atau deskripsi barang.
- Kategori barang.
- Lokasi kehilangan atau penemuan.
- Tanggal kejadian.
- Foto barang apabila diperlukan.

Pengguna hanya dapat mengubah atau membatalkan laporan miliknya sendiri. Perubahan dan pembatalan dibatasi ketika terdapat klaim aktif yang sedang diproses.

### PR-03 — Pencarian Barang

Pengguna dapat mencari laporan berdasarkan kata kunci dan menyaring hasil berdasarkan:
- Jenis laporan.
- Kategori barang.
- Lokasi.
- Tanggal kejadian.

Fitur pencarian dan filter dapat diakses baik oleh pengunjung tanpa login maupun pengguna yang telah terautentikasi.

### PR-04 — Klaim Barang Temuan

Pengguna dapat mengajukan klaim pada laporan barang temuan. Satu barang temuan dapat menerima klaim dari beberapa pengguna.

Namun, hanya satu klaim yang dapat dipilih untuk melanjutkan proses serah terima pada satu waktu. Klaim-klaim lain yang telah masuk tetap berada dalam status menunggu (`submitted`) sebagai antrean.

Keputusan untuk melanjutkan klaim didasarkan pada kesepakatan antara penemu dan pihak yang mengklaim. Administrator tidak menentukan kepemilikan barang.

**Persyaratan pengajuan klaim:**
- Pengguna wajib login dengan akun yang memiliki email resmi kampus yang telah diverifikasi dan berstatus aktif.
- Pengguna bukan pembuat laporan barang temuan terkait.
- Pengguna tidak sedang memiliki klaim lain yang berstatus aktif (`submitted` atau `in_handover`).

**Aturan pembatasan klaim:**
- Satu pengguna hanya boleh memiliki satu klaim aktif berjalan pada satu waktu di seluruh sistem.
- Status klaim dikelompokkan menjadi dua kategori:
  1. **Status Aktif:** klaim yang sedang menunggu tanggapan (`submitted`) atau sedang berada dalam proses serah terima (`in_handover`). Selama memiliki klaim aktif, pengguna dilarang mengajukan klaim pada barang temuan lain.
  2. **Status Terminal:** klaim yang telah selesai atau berakhir, yaitu `completed` (selesai serah terima), `rejected` (ditolak penemu), `cancelled` (dibatalkan pengklaim/penemu/sistem), dan `expired` (kedaluwarsa).
- **Pelepasan pembatasan klaim:** ketika sebuah klaim mencapai status terminal, klaim tersebut tidak lagi menghalangi pengguna untuk mengajukan klaim pada laporan barang temuan lain. Status terminal bersifat permanen dan tidak dapat diubah kembali menjadi status aktif melalui transisi biasa.
- **Hak pengklaim mengantre:** Pengklaim yang klaimnya berstatus `submitted` saat klaim lain berada dalam `in_handover` tetap memiliki hak membatalkan klaimnya sendiri kapan saja secara sukarela (`cancelled`) jika tidak ingin menunggu, guna melepaskan hak klaim aktifnya lebih awal.

**Hak penolakan dan pembatalan:**
- Penemu (*finder*) berhak menolak klaim (`rejected`) yang belum memasuki proses serah terima.
- Pengklaim (*claimant*) berhak membatalkan klaim miliknya sendiri (`cancelled`) sebelum proses serah terima dimulai.
- Penolakan dan pembatalan langsung mengakhiri klaim menjadi status terminal dan melepaskan pembatasan klaim pengguna.

**Penanganan klaim tanpa respons:**
- Jika penemu tidak merespons pengajuan klaim selama **72 jam**, sistem mengirimkan notifikasi dalam aplikasi kepada pengklaim bahwa klaim belum mendapat respons penemu.
- Setelah notifikasi tersebut, pengklaim memiliki waktu tambahan **48 jam** untuk menunggu atau membatalkan klaimnya secara sukarela (`cancelled`) agar hak klaimnya terbebas lebih awal.
- Jika penemu tetap tidak merespons dan pengklaim tidak membatalkannya, sistem secara otomatis mengubah status klaim menjadi `expired` setelah total **120 jam (5 hari) sejak klaim dibuat**.
- Batas waktu dihitung berdasarkan waktu pembuatan klaim awal (`created_at` / `submitted_at`), bukan waktu pengiriman notifikasi. Pembatalan serah terima yang sedang berjalan atau pemilihan klaim antrean berikutnya tidak mengatur ulang (*reset*) timer klaim lain yang tetap menunggu (`submitted`).
- Proses kedaluwarsa otomatis tidak akan mengubah status klaim jika penemu telah merespons (status berubah ke `in_handover` atau `rejected`) atau pengklaim telah membatalkannya (`cancelled`) sebelum batas waktu 120 jam terlampaui.

**Pencegahan klaim berulang vs hak klaim ulang pada barang yang sama:**
- Pengguna yang klaimnya telah ditolak (`rejected`) atau yang secara sukarela membatalkan klaimnya sendiri (`cancelled` oleh pengklaim, baik pada tahap `submitted` maupun saat `in_handover`) tidak diperbolehkan mengajukan klaim ulang pada laporan barang temuan yang sama untuk mencegah spam dan penyalahgunaan.
- Sebaliknya, pengklaim **diperbolehkan mengajukan klaim kembali** pada laporan barang temuan yang sama selama laporan tersebut masih berstatus `open` dalam kondisi:
  1. Klaim sebelumnya berakhir kedaluwarsa otomatis (`expired`) karena penemu tidak merespons hingga 120 jam (kedaluwarsa adalah batas waktu sistem, bukan kesalahan pengklaim).
  2. Proses serah terima dibatalkan oleh penemu (`cancelled` oleh penemu), di mana kegagalan serah terima bukan disebabkan oleh pembatalan sepihak dari pengklaim.
- Pembatasan ini tidak melarang pengguna untuk mengajukan klaim pada laporan barang temuan lain setelah klaim sebelumnya berakhir menjadi status terminal.

### PR-05 — Chat Privat

Aplikasi menyediakan chat berbasis teks antara pihak yang terlibat dalam klaim untuk:
- Bertukar informasi terkait barang.
- Meminta informasi pendukung.
- Mengatur waktu dan lokasi serah terima.

Riwayat chat hanya dapat diakses oleh pihak yang berhak. Administrator dapat meninjau konteks pesan yang relevan untuk menangani laporan penyalahgunaan sesuai kebijakan privasi.

Chat tidak mendukung lampiran, panggilan suara, atau panggilan video.

### PR-06 — Serah Terima Barang

Penyerahan barang memerlukan konfirmasi dari kedua pihak.

Konfirmasi dari satu pihak saja belum menandakan serah terima selesai dan tidak boleh menutup laporan.

Setelah kedua pihak mengonfirmasi:
- Proses klaim ditandai selesai (`completed`).
- Laporan barang terkait ditutup (`resolved`).
- Klaim lain yang masih menunggu untuk laporan tersebut otomatis diakhiri.
- Klaim baru untuk laporan tersebut tidak dapat diajukan.
- Chat ditutup dan tidak dapat menerima pesan baru (hanya-baca).
- Waktu dan status konfirmasi tetap disimpan.

**Penanganan serah terima yang terhenti dan kebijakan klaim ulang:**
- Jika proses serah terima terhenti atau tidak dapat diselesaikan secara fisik (misalnya salah satu pihak tidak hadir atau tidak memberikan konfirmasi), kedua belah pihak memiliki mekanisme untuk membatalkan proses serah terima tersebut.
- Pembatalan proses serah terima memerlukan tindakan eksplisit, dialog konfirmasi peringatan, serta pencatatan alasan pembatalan dan identitas pihak yang membatalkan (`cancelled_by`).
- Pembatalan serah terima mengakhiri klaim yang sedang diproses dengan status terminal `cancelled`, chat terkait dinonaktifkan (hanya-baca), dan laporan barang dibuka kembali (`open`) selama memenuhi syarat.
- **Konsekuensi hak klaim ulang dibedakan berdasarkan pihak yang membatalkan:**
  - Jika **pengklaim** yang membatalkan serah terima: pengklaim dibebaskan dari klaim aktif, namun **dilarang mengajukan klaim ulang** pada laporan yang sama.
  - Jika **penemu** yang membatalkan serah terima: pengklaim dibebaskan dari klaim aktif dan **diperbolehkan mengajukan klaim kembali** pada laporan yang sama jika status laporan masih `open`.
- **Nasib klaim lain yang mengantre:**
  - Jika serah terima selesai berhasil (`completed`/`resolved`), seluruh klaim lain yang berstatus `submitted` otomatis diakhiri menjadi `cancelled` tanpa sanksi bagi pengklaim.
  - Jika serah terima dibatalkan (oleh pihak manapun) dan laporan kembali `open`, seluruh klaim lain yang berstatus `submitted` **tetap bertahan dalam status `submitted`** (tidak dibatalkan), sehingga penemu dapat langsung memproses klaim mengantre berikutnya tanpa memaksa pengklaim mengajukan ulang (rekomendasi DEC-025).
- Pembatalan sepihak tidak boleh dianggap sebagai bukti hukum bahwa salah satu pihak memiliki atau tidak memiliki barang.
- Batas waktu otomatis untuk serah terima yang terhenti tidak diterapkan sebelum disetujui secara terpisah.

### PR-07 — Notifikasi Dalam Aplikasi

Aplikasi menyediakan notifikasi di dalam aplikasi untuk:
- Klaim baru.
- Pesan baru.
- Perubahan status klaim.
- Konfirmasi serah terima.

Notifikasi push saat aplikasi ditutup tidak termasuk versi awal.

### PR-08 — Moderasi dan Penonaktifan Akun

Pengguna dapat melaporkan laporan barang atau pesan yang dianggap melanggar aturan.

Administrator dapat meninjau laporan, menyembunyikan konten yang melanggar (`hidden`), memberikan peringatan, atau menangguhkan akun pengguna (`suspended`) sesuai tingkat pelanggaran.

Setiap tindakan moderasi harus dicatat bersama alasan tindakannya.

**Dampak penonaktifan akun berdasarkan peran pengguna (rekomendasi DEC-026):**
- **Penonaktifan akun pengklaim:** Klaim aktif miliknya diubah menjadi `cancelled`. Jika pengklaim sedang menjalani serah terima (`in_handover`), status laporan terkait dikembalikan menjadi `open` agar penemu tidak terkunci dan dapat memproses klaim mengantre lainnya. Chat terkait dikunci (*read-only*).
- **Penonaktifan akun penemu:** Seluruh laporan aktif miliknya diubah statusnya menjadi `hidden` (menggunakan status yang sudah ada). Seluruh klaim aktif (`submitted` maupun `in_handover`) pada seluruh laporan tersebut otomatis diakhiri menjadi `cancelled` oleh admin/sistem tanpa membebani penalti pada para pengklaim, sehingga hak klaim para pengklaim langsung terbebas.
- **Kebijakan reaktivasi akun & pemisahan status:** Status akun (`users.status`) dan status laporan (`reports.status`) merupakan dua hal yang berbeda. Pengaktifan kembali akun penemu (`users.status = 'active'`) tidak otomatis mengubah status laporan yang `hidden` menjadi `open` atau mempublikasikannya kembali. Seluruh status transaksi, laporan, dan klaim masa lalu yang telah terminal (`cancelled`, `completed`, `rejected`, `expired`, `hidden`) **bersifat permanen dan tidak dihidupkan kembali secara otomatis** (*no automatic revival*). Pengguna yang dipulihkan dapat membuat laporan baru atau mengajukan klaim baru pada laporan yang masih `open`. Prosedur dan kebijakan publikasi ulang laporan `hidden` ditunda ke fase moderasi lanjutan dan tidak menghambat implementasi awal.

### PR-09 — Retensi Pesan Chat

Pesan chat dihapus secara otomatis 30 hari setelah serah terima dikonfirmasi oleh kedua pihak.

Penghapusan pesan tidak menghapus catatan status klaim dan waktu konfirmasi serah terima.

Pesan yang berkaitan dengan laporan penyalahgunaan yang belum selesai ditinjau dikecualikan sementara dari penghapusan. Batas retensi setelah peninjauan selesai harus ditentukan sebelum implementasi.

### PR-10 — PWA

Aplikasi harus:
- Memiliki tampilan responsif untuk perangkat desktop dan seluler.
- Dapat dipasang sebagai PWA pada browser yang mendukung.
- Mendukung akses offline dasar untuk aset atau halaman yang aman untuk disimpan.
- Memerlukan koneksi internet untuk klaim, chat, dan konfirmasi serah terima.

Dukungan offline tidak boleh membuat pengguna mengira perubahan transaksi berhasil ketika server belum menerimanya.

## 7. Halaman Utama

Proyek menetapkan enam halaman utama:

1. **Beranda / Daftar Barang** — menampilkan daftar laporan, pencarian, dan filter.
2. **Formulir Laporan** — membuat atau mengubah laporan barang.
3. **Detail Barang** — menampilkan informasi barang dan tindakan klaim.
4. **Chat dan Proses Klaim** — menampilkan status klaim, percakapan, dan konfirmasi serah terima.
5. **Aktivitas Saya** — menampilkan laporan milik pengguna, klaim, dan notifikasi.
6. **Dashboard Admin** — menangani laporan penyalahgunaan dan tindakan moderasi.

Halaman login, registrasi, dan verifikasi email merupakan halaman pendukung dan tidak dihitung sebagai bagian dari enam halaman utama.

## 8. Persyaratan Nonfungsional

### Keamanan
- Pengguna hanya boleh mengakses data dan tindakan sesuai hak aksesnya.
- Validasi dilakukan di sisi server.
- Kata sandi disimpan menggunakan mekanisme hashing yang aman.
- Data autentikasi dan konfigurasi rahasia tidak boleh masuk ke repository.

### Privasi
- Chat bersifat privat bagi pihak yang berhak.
- Akses administrator terhadap pesan dibatasi pada kebutuhan moderasi.
- Kebijakan retensi pesan harus diterapkan secara konsisten.

### Kemudahan Penggunaan
- Antarmuka harus responsif dan mudah dipahami.
- Status laporan dan klaim harus ditampilkan secara jelas.
- Pesan validasi dan kesalahan harus mudah dimengerti.

### Pemeliharaan
- Aplikasi mengikuti konvensi Laravel.
- Struktur kode harus mudah dipahami dan diuji.
- Perubahan skema basis data dikelola menggunakan migration.

### Keandalan
- Aksi yang mengubah status klaim atau serah terima harus divalidasi oleh server.
- Konfirmasi kedua pihak harus ditangani secara konsisten.
- Kegagalan jaringan tidak boleh ditampilkan sebagai keberhasilan transaksi.

## 9. Kriteria Keberhasilan Produk

Produk dianggap memenuhi tujuan awal apabila:
1. Pengguna dapat mendaftar dan memverifikasi email sesuai aturan.
2. Pengguna dapat membuat, mencari, dan melihat laporan barang.
3. Pengguna dapat mengajukan dan memproses klaim sesuai aturan bisnis.
4. Chat privat berfungsi bagi pihak yang berhak.
5. Serah terima hanya dinyatakan selesai setelah konfirmasi kedua pihak.
6. Administrator dapat menangani laporan penyalahgunaan.
7. Penghapusan pesan mengikuti kebijakan retensi yang disepakati.
8. Aplikasi dapat dijalankan secara responsif dan dipasang sebagai PWA.
9. Fungsi utama memiliki pengujian yang relevan.

## 10. Batasan dan Asumsi

- Aplikasi ditujukan untuk komunitas Universitas Mercu Buana.
- Teknologi utama adalah Laravel, Blade, dan MySQL.
- PWA merupakan kemampuan tambahan, bukan aplikasi native.
- Pengguna bertanggung jawab memberikan informasi laporan yang benar.
- Platform tidak menjamin kebenaran klaim atau kepemilikan barang.
- Keputusan kepemilikan dilakukan oleh pihak yang terlibat, bukan administrator.

## 11. Instruksi Pengembangan

- Gunakan dokumen ini sebagai acuan ruang lingkup produk.
- Jangan menambah atau mengubah aturan bisnis tanpa persetujuan.
- Detail teknis harus dijabarkan pada dokumen rancangan yang sesuai.
- Jika ada kebutuhan yang belum ditentukan, catat sebagai hal yang perlu diputuskan.
- Jangan memulai implementasi fitur sebelum dokumentasi yang relevan selesai ditinjau.

## 12. Hal yang Perlu Diputuskan

1. Domain email resmi Universitas Mercu Buana yang diizinkan untuk registrasi akun.
2. Lama penyimpanan pesan yang ditahan karena laporan penyalahgunaan setelah peninjauan moderasi selesai.
3. Aturan cache PWA untuk data daftar laporan publik dan mekanisme penghapusan pesan otomatis.
4. Batas format dan ukuran file foto laporan (misalnya 1 file foto, maksimal 2 MB, format JPEG/PNG/WebP).

---

**Catatan status:** Dokumen ini masih berupa draf. Persyaratan yang belum diputuskan harus diselesaikan sebelum fitur terkait diimplementasikan.