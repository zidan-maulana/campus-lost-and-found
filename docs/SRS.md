# Software Requirements Specification (SRS)
# Campus Lost & Found

**Versi:** 0.1  
**Status:** Draf  
**Acuan utama:** `docs/PRD.md`

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini mendefinisikan kebutuhan fungsional, kebutuhan nonfungsional, aturan bisnis, validasi, dan kriteria penerimaan Campus Lost & Found.

Dokumen menjadi acuan untuk perancangan sistem, implementasi, dan pengujian aplikasi.

### 1.2 Ruang Lingkup Sistem

Sistem mendukung pelaporan barang hilang dan barang temuan, pencarian barang, klaim, chat privat, konfirmasi serah terima, notifikasi dalam aplikasi, moderasi, serta kemampuan PWA dasar.

Teknologi yang digunakan adalah Laravel, Blade, dan MySQL.

### 1.3 Istilah

- **Laporan barang hilang:** laporan pengguna mengenai barang miliknya yang hilang.
- **Laporan barang temuan:** laporan pengguna mengenai barang temuan di lingkungan kampus.
- **Penemu:** pengguna terverifikasi yang membuat laporan barang temuan.
- **Pengklaim:** pengguna terverifikasi yang mengajukan klaim terhadap laporan barang temuan.
- **Klaim aktif:** klaim yang sedang berjalan dan belum mencapai hasil akhir, yaitu status `submitted` (menunggu tanggapan penemu) atau `in_handover` (proses serah terima sedang berlangsung). Selama memiliki klaim aktif, pengguna dilarang mengajukan klaim pada barang lain.
- **Klaim terminal:** klaim yang telah selesai atau berakhir permanen, yaitu `completed` (serah terima sukses dikonfirmasi kedua pihak), `rejected` (ditolak penemu), `cancelled` (dibatalkan pengklaim/serah terima batal), atau `expired` (kedaluwarsa karena tidak direspons). Status terminal langsung melepaskan pembatasan hak klaim pengguna.
- **Status laporan:** keadaan siklus hidup laporan barang (`open`, `in_handover`, `resolved`, `cancelled`, `hidden`), yang berdiri sendiri terpisah dari status klaim.
- **Serah terima:** proses fisik penyerahan barang yang wajib dikonfirmasi oleh kedua pihak di dalam aplikasi.
- **Moderasi:** tindakan administrator terhadap konten atau perilaku yang melanggar aturan platform.
- **PWA:** aplikasi web responsif yang mendukung pemasangan dan kemampuan offline dasar.

## 2. Aktor dan Hak Akses

### 2.1 Pengunjung

Pengunjung yang belum login dapat:
- Melihat daftar laporan barang hilang dan barang temuan publik.
- Melihat halaman detail umum laporan barang yang dipublikasikan.
- Melakukan pencarian berdasarkan kata kunci dan penyaringan (*filter*) laporan publik.
- Melihat halaman login dan registrasi.
- Melakukan registrasi akun.
- Memverifikasi email melalui mekanisme yang disediakan.

Pengunjung yang belum login **tidak dapat**:
- Mengajukan klaim pada barang temuan.
- Mengirim pesan dalam chat privat atau berinteraksi dengan pengguna lain.
- Membuat, mengubah, atau membatalkan laporan barang.
- Mengonfirmasi serah terima barang.
- Melaporkan dugaan penyalahgunaan.

### 2.2 Pengguna Terdaftar dan Terverifikasi

Untuk mengajukan klaim atau melakukan tindakan yang dilindungi dalam sistem, pengguna wajib memenuhi tiga persyaratan kumulatif:
1. Telah login ke akun terdaftar.
2. Memiliki alamat email resmi kampus yang telah diverifikasi (login saja tidak cukup tanpa status verifikasi email kampus).
3. Akun berada dalam status aktif (tidak sedang ditangguhkan atau dinonaktifkan oleh administrator).

Pengguna yang memenuhi persyaratan tersebut dapat:
- Membuat laporan barang hilang dan barang temuan.
- Melihat, mencari, dan memfilter laporan.
- Mengubah atau membatalkan laporan miliknya sesuai aturan.
- Mengajukan dan mengelola klaim sesuai hak akses.
- Mengirim dan membaca pesan dalam chat privat yang berhak diaksesnya.
- Mengonfirmasi atau membatalkan proses serah terima.
- Melaporkan dugaan penyalahgunaan.

### 2.3 Administrator

Administrator dapat:
- Melihat laporan penyalahgunaan.
- Meninjau konten dan pesan yang relevan dengan laporan.
- Menyembunyikan konten yang melanggar.
- Memberikan peringatan.
- Menangguhkan akun sesuai tingkat pelanggaran.
- Melihat catatan tindakan moderasi.

Administrator tidak dapat menentukan kepemilikan barang atau memutuskan klaim atas nama penemu maupun pengklaim.

## 3. Kebutuhan Fungsional

### FR-01 — Registrasi

**Deskripsi:** Sistem menyediakan pendaftaran akun menggunakan email resmi universitas.

**Persyaratan:**
- Sistem menerima nama, email, dan kata sandi sesuai aturan validasi.
- Email harus menggunakan domain resmi yang diizinkan.
- Email yang telah terdaftar tidak dapat digunakan untuk membuat akun kedua.
- Kata sandi harus disimpan menggunakan hashing yang aman.
- Pengguna harus menyelesaikan verifikasi email sebelum menggunakan fitur inti.

**Kriteria penerimaan:**
- Registrasi dengan data valid menghasilkan akun.
- Email di luar domain yang diizinkan ditolak.
- Email duplikat ditolak.
- Akun yang belum terverifikasi tidak dapat melakukan tindakan inti.

**Catatan:** Domain email resmi dan ketentuan kekuatan kata sandi harus ditetapkan sebelum implementasi.

### FR-02 — Login dan Logout

**Deskripsi:** Pengguna dapat masuk dan keluar dari sistem.

**Persyaratan:**
- Sistem memverifikasi kredensial pengguna.
- Login gagal menghasilkan pesan kesalahan yang aman.
- Pengguna yang belum terverifikasi tidak memperoleh akses ke fitur inti.
- Logout mengakhiri sesi autentikasi pengguna.

**Kriteria penerimaan:**
- Kredensial valid dapat digunakan untuk login.
- Kredensial tidak valid ditolak.
- Pengguna yang logout tidak dapat mengakses halaman terlindungi menggunakan sesi yang telah berakhir.

### FR-03 — Membuat Laporan

**Deskripsi:** Pengguna dapat membuat laporan barang hilang atau barang temuan.

**Data laporan:**
- Jenis laporan.
- Nama atau deskripsi barang.
- Kategori.
- Lokasi kejadian.
- Tanggal kejadian.
- Foto, jika disediakan.

**Persyaratan:**
- Pengguna harus login dan terverifikasi.
- Kolom wajib harus divalidasi di server.
- Foto, jika didukung, harus divalidasi berdasarkan tipe dan ukuran berkas.
- Sistem mencatat pemilik laporan dan waktu pembuatan.
- Laporan yang berhasil disimpan memiliki status awal yang sesuai dengan jenisnya.

**Kriteria penerimaan:**
- Data valid menghasilkan laporan.
- Data wajib yang tidak valid ditolak dengan pesan yang jelas.
- Pengguna tidak dapat membuat laporan atas nama pengguna lain.

### FR-04 — Mengubah dan Membatalkan Laporan

**Deskripsi:** Pemilik laporan dapat mengubah atau membatalkan laporannya.

**Persyaratan:**
- Hanya pemilik laporan yang dapat melakukan tindakan tersebut.
- Untuk laporan barang temuan, laporan tidak boleh diubah atau dibatalkan jika laporan sedang berada dalam status `in_handover` (proses serah terima sedang berlangsung dengan klaim terpilih).
- Laporan barang temuan berstatus `open` yang memiliki klaim masuk berstatus `submitted` dapat dibatalkan dengan ketentuan bahwa semua klaim yang masuk otomatis berubah menjadi status terminal `cancelled` dan pengguna pengklaim diberi tahu melalui notifikasi.
- Laporan barang hilang dapat diubah atau dibatalkan kapan saja oleh pembuatnya selama belum diselesaikan atau ditutup.
- Pembatalan laporan mengubah status laporan menjadi `cancelled` dan tidak menghapus catatan audit transaksi.

**Kriteria penerimaan:**
- Pemilik dapat mengubah/membatalkan laporan yang memenuhi syarat status.
- Pengguna lain tidak dapat mengubah laporan tersebut.
- Sistem menolak pengubahan/pembatalan laporan barang temuan saat status laporan `in_handover`.

### FR-05 — Pencarian dan Filter

**Deskripsi:** Sistem menyediakan pencarian laporan barang.

**Persyaratan:**
- Pencarian mendukung kata kunci.
- Filter mendukung jenis laporan, kategori, lokasi, dan tanggal kejadian.
- Filter dapat digunakan bersama-sama.
- Hasil pencarian hanya menampilkan laporan yang boleh diakses dan belum disembunyikan karena moderasi.
- Kondisi ketika tidak ada hasil harus ditampilkan dengan jelas.

**Kriteria penerimaan:**
- Kata kunci dan filter menghasilkan daftar yang sesuai.
- Kombinasi filter diterapkan secara konsisten.
- Hasil kosong tidak menyebabkan kesalahan aplikasi.

### FR-06 — Pengajuan Klaim

**Deskripsi:** Pengguna dapat mengajukan klaim pada laporan barang temuan.

**Persyaratan:**
- Klaim hanya dapat diajukan oleh pengguna yang memenuhi tiga syarat: (1) telah login, (2) email resmi kampus telah diverifikasi, dan (3) akun dalam status aktif.
- Pengguna tidak dapat mengklaim barang temuan miliknya sendiri.
- Laporan harus berstatus `open` dan bertipe barang temuan (`type = 'found'`).
- Satu laporan dapat memiliki beberapa klaim yang berstatus `submitted`.
- Satu laporan hanya boleh memiliki maksimal satu klaim berstatus `in_handover` pada satu waktu.
- **Batasan satu klaim aktif per pengguna:** Satu pengguna hanya boleh memiliki satu klaim berstatus aktif (`submitted` atau `in_handover`) di seluruh aplikasi pada satu waktu.
- **Pelepasan pembatasan hak klaim:** Begitu klaim pengguna mencapai status terminal (`completed`, `rejected`, `cancelled`, `expired`), klaim tersebut tidak lagi menghalangi pengguna untuk mengajukan klaim pada laporan barang temuan lain. Status terminal bersifat permanen dan tidak dapat diubah kembali menjadi aktif.
- **Nasib klaim mengantre selama dan pasca serah terima (rekomendasi DEC-025):**
  - Ketika satu klaim berada dalam status `in_handover`, klaim-klaim lain pada laporan yang sama tetap berstatus `submitted` (mengantre).
  - Selama mengantre, pengklaim tetap terikat aturan 1 klaim aktif, namun berhak membatalkan klaim miliknya sendiri secara sukarela (`cancelled`) kapan saja tanpa menunggu jika ingin membebaskan hak klaim aktifnya lebih awal.
  - Jika serah terima diselesaikan dengan sukses (`completed`/`resolved`), seluruh klaim yang masih berstatus `submitted` pada laporan tersebut otomatis diubah menjadi `cancelled` tanpa mencatat pelanggaran atau sanksi bagi para pengklaim.
  - Jika serah terima dibatalkan (baik oleh pengklaim maupun oleh penemu) dan status laporan kembali `open`, seluruh klaim lain yang berstatus `submitted` **tetap bertahan dalam status `submitted`** (tidak dibatalkan secara otomatis), sehingga penemu dapat langsung mengevaluasi dan memilih klaim mengantre berikutnya.
- **Pencegahan klaim berulang vs Hak klaim ulang:**
  - Pengguna yang klaimnya telah mencapai status terminal `rejected` (ditolak penemu) atau `cancelled` karena dibatalkan oleh pengklaim itu sendiri (baik pada tahap `submitted` maupun saat `in_handover`) **dilarang mengajukan klaim baru** pada laporan barang yang sama untuk mencegah spam.
  - Pengguna **diperbolehkan mengajukan klaim kembali** pada laporan barang temuan yang sama selama laporan tersebut masih berstatus `open` dalam kondisi:
    1. Klaim sebelumnya mencapai status terminal `expired` (akibat penemu tidak merespons hingga total 120 jam sejak klaim dibuat). Kedaluwarsa merupakan batas waktu sistem dan bukan pelanggaran pengklaim.
    2. Proses serah terima dibatalkan oleh pihak penemu (`cancelled` oleh penemu), di mana pengklaim bukan pihak yang membatalkan serah terima.
  - Jika laporan barang dibatalkan oleh penemu atau disembunyikan oleh administrator, seluruh klaim yang masih mengantre (`submitted`) otomatis diakhiri menjadi `cancelled` tanpa mencatat pelanggaran bagi pengklaim, dan hak klaim pengklaim langsung terlepas.

**Kriteria penerimaan:**
- Klaim valid tersimpan dengan status awal `submitted`.
- Pengunjung tanpa login atau pengguna yang belum verifikasi email kampus ditolak saat mencoba mengajukan klaim.
- Klaim pada laporan selain status `open` ditolak.
- Pengguna yang memiliki klaim aktif (`submitted` atau `in_handover`) ditolak saat mencoba mengajukan klaim baru.
- Klaim-klaim mengantre (`submitted`) tetap bertahan dalam status `submitted` ketika serah terima dibatalkan dan laporan kembali berstatus `open`.
- Seluruh klaim mengantre (`submitted`) otomatis beralih ke `cancelled` seketika saat serah terima berhasil diselesaikan (`completed`/`resolved`).
- Klaim ulang oleh pengguna yang sama pada laporan yang sama yang sebelumnya telah ditolak (`rejected`) atau dibatalkan sendiri oleh pengklaim (`cancelled`) ditolak oleh sistem.
- Pengajuan klaim kembali pada laporan yang sama setelah status `expired` atau setelah pembatalan serah terima oleh penemu diterima oleh sistem jika laporan masih `open`.
- Klaim bersamaan tidak menyebabkan lebih dari satu klaim terpilih menuju status `in_handover` untuk laporan yang sama.

### FR-07 — Pengelolaan Klaim, Penolakan, dan Pembatalan

**Deskripsi:** Penemu dan pengklaim dapat memproses, menolak, membatalkan, atau menyelesaikan klaim sesuai hak akses dan status.

**Persyaratan:**
- **Hak Penolakan Penemu:** Penemu berhak menolak klaim yang berstatus `submitted`. Penolakan mengubah status klaim menjadi `rejected` (terminal), melepaskan hak klaim pengklaim, dan memicu notifikasi penolakan kepada pengklaim.
- **Hak Pembatalan Pengklaim:** Pengklaim berhak membatalkan klaim miliknya sendiri yang masih berstatus `submitted`. Pembatalan mengubah status klaim menjadi `cancelled` (terminal), mencatat aktor pembatal, dan melepaskan hak klaim pengklaim.
- **Pemilihan Klaim Menuju Serah Terima:** Penemu dapat memilih satu klaim dari status `submitted` untuk melanjutkan ke proses serah terima (`in_handover`). Tindakan ini mengubah status klaim menjadi `in_handover` dan status laporan barang temuan terkait menjadi `in_handover`. Klaim lain yang masuk tetap berstatus `submitted` (mengantre) dan tidak mengunci pengklaim lain melebihi aturan normal satu klaim aktif.
- **Kebijakan Kedaluwarsa Klaim Tanpa Respons (Aturan 72 + 48 Jam = 120 Jam):**
  1. Jika klaim berada dalam status `submitted` selama **72 jam** sejak dibuat tanpa tindakan/respons dari penemu, sistem mengirim notifikasi dalam aplikasi kepada pengklaim bahwa penemu belum merespons.
  2. Setelah notifikasi tersebut, pengklaim memiliki waktu tenggang tambahan **48 jam** untuk menunggu atau membatalkan klaim secara sukarela (`cancelled`) guna membebaskan hak klaimnya.
  3. Klaim **tidak otomatis dibatalkan tepat pada jam ke-72**.
  4. Jika penemu tetap tidak merespons dan pengklaim belum membatalkan klaim, sistem secara otomatis mengubah status klaim dari `submitted` menjadi `expired` tepat setelah total **120 jam (5 hari) sejak klaim dibuat**.
  5. Batas waktu kedaluwarsa dihitung berdasarkan waktu pembuatan klaim awal (`created_at` / `submitted_at`), bukan waktu pengiriman notifikasi. Pembatalan serah terima yang sedang berjalan atau pemilihan klaim antrean berikutnya tidak mengatur ulang (*reset*) timer klaim lain yang tetap menunggu (`submitted`).
  6. Status `expired` langsung membebaskan pengklaim dari pembatasan klaim aktif sehingga dapat mengajukan klaim lain, serta boleh mengklaim kembali laporan yang sama jika masih `open`.
  7. Jika pengklaim membatalkan klaim sebelum batas 120 jam, status berubah menjadi `cancelled`. Proses otomatis kedaluwarsa tidak boleh menimpa status tersebut.
  8. Jika penemu merespons atau status klaim berubah dari `submitted` sebelum batas 120 jam, proses otomatis kedaluwarsa tidak boleh mengubah klaim menjadi `expired`.
  9. Proses otomatis kedaluwarsa harus aman dijalankan berulang kali (*idempotent*) dan tidak boleh mengubah klaim yang sudah memiliki status terminal atau status aktif lain.
- **Penanganan Pembatalan Serah Terima yang Terhenti dan Kebijakan Klaim Ulang:**
  - Jika proses serah terima (`in_handover`) tidak dapat diselesaikan atau terhenti, penemu atau pengklaim dapat membatalkan proses serah terima.
  - Pembatalan memerlukan tindakan eksplisit, dialog konfirmasi peringatan, pengisian alasan pembatalan, serta pencatatan identitas pihak yang membatalkan (`cancelled_by`).
  - Pembatalan mengubah status klaim menjadi `cancelled` (terminal), menonaktifkan form input chat, melepaskan pembatasan klaim pengklaim, dan mengembalikan status laporan barang menjadi `open` (selama tidak dibatalkan pemilik atau disembunyikan admin).
  - Klaim-klaim mengantre (`submitted`) tetap bertahan dalam status `submitted` dan dapat langsung dipilih oleh penemu untuk memulai serah terima berikutnya.
  - **Diferensiasi hak klaim ulang:**
    - Jika **pengklaim** yang membatalkan serah terima: pengklaim dilarang mengajukan klaim ulang pada laporan yang sama, namun bebas mengajukan klaim pada laporan lain.
    - Jika **penemu** yang membatalkan serah terima: pengklaim diperbolehkan mengajukan klaim kembali pada laporan yang sama selama laporan masih `open`, serta bebas mengajukan klaim pada laporan lain.
- Administrator tidak dapat mengubah status klaim atau menentukan kepemilikan barang.

**Kriteria penerimaan:**
- Penolakan oleh penemu berhasil mengubah status menjadi `rejected` dan mengaktifkan kembali hak klaim pengklaim pada barang lain.
- Pembatalan oleh pengklaim berhasil mengubah status menjadi `cancelled`.
- Sistem mengirimkan notifikasi 72 jam ketika penemu belum merespons klaim `submitted`.
- Klaim yang tidak direspons berubah menjadi `expired` tepat setelah 120 jam sejak pembuatan jika masih berstatus `submitted`.
- Pengklaim yang klaimnya `expired` dapat mengajukan klaim kembali pada laporan yang sama jika laporan masih `open`.
- Klaim yang telah dibatalkan (`cancelled`) atau telah beralih status sebelum 120 jam tidak tertimpa oleh proses kedaluwarsa.
- Pembatalan serah terima mewajibkan dialog konfirmasi, pengisian alasan, pencatatan aktor pembatal, mengakhiri klaim menjadi `cancelled`, membuka kembali laporan menjadi `open`, dan membiarkan klaim mengantre tetap `submitted`.
- Sistem membatasi hak klaim ulang jika pengklaim yang membatalkan serah terima, namun mengizinkan klaim ulang jika penemu yang membatalkan serah terima.
- Aksi di luar kewenangan aktor ditolak dengan pesan kesalahan otorisasi.

### FR-08 — Chat Privat

**Deskripsi:** Sistem menyediakan komunikasi teks antara penemu dan pengklaim yang terlibat dalam proses klaim.

**Persyaratan:**
- Hanya pihak yang berhak dapat membaca atau mengirim pesan.
- Pesan harus terhubung ke proses klaim yang sesuai.
- Pesan baru hanya dapat dikirim selama status klaim aktif (`submitted` atau `in_handover`).
- Setelah klaim mencapai status terminal (`completed`, `rejected`, `cancelled`, `expired`), chat ditutup dan menjadi hanya-baca (*read-only*).
- Pesan harus divalidasi sebelum disimpan.
- Riwayat pesan mengikuti kebijakan retensi pada PRD.
- Akses administrator ke pesan dibatasi pada kebutuhan peninjauan laporan penyalahgunaan yang sah.

**Kriteria penerimaan:**
- Pihak yang berhak dapat mengirim dan membaca pesan.
- Pengguna di luar percakapan ditolak.
- Chat pada klaim berstatus terminal menolak pesan baru.
- Pesan yang memenuhi syarat penghapusan dihapus sesuai kebijakan yang disetujui.

### FR-09 — Konfirmasi Serah Terima

**Deskripsi:** Kedua pihak harus mengonfirmasi serah terima secara independen agar proses dianggap selesai.

**Persyaratan:**
- Penemu dan pengklaim masing-masing memberikan konfirmasi pada klaim yang berstatus `in_handover`.
- Konfirmasi pertama dicatat dan mengubah indikator visual, namun belum menyelesaikan proses dan tidak menutup laporan.
- Pihak yang sama tidak dapat memberikan konfirmasi ganda pada klaim yang sama.
- Setelah kedua konfirmasi (dari penemu dan pengklaim) tercatat sah di server:
  - Status klaim terpilih berubah menjadi `completed` (terminal).
  - Status laporan barang berubah menjadi `resolved`.
  - Klaim-klaim lain yang masih menunggu (`submitted`) pada laporan tersebut otomatis diubah menjadi `cancelled` (karena barang telah resmi diserahkan).
  - Chat dikunci menjadi hanya-baca (*read-only*).
  - Waktu dan identitas kedua konfirmasi disimpan secara permanen.
- Sistem harus menggunakan transaksi database untuk menjamin konsistensi penyelesaian.

**Kriteria penerimaan:**
- Satu konfirmasi tidak menutup laporan dan tidak mengubah status klaim menjadi `completed`.
- Dua konfirmasi dari penemu dan pengklaim yang sah menyelesaikan proses klaim dan laporan.
- Pengguna di luar penemu dan pengklaim ditolak saat mencoba mengonfirmasi.
- Konfirmasi ganda oleh satu pihak dicegah di tingkat database dan server.

### FR-10 — Notifikasi Dalam Aplikasi

**Deskripsi:** Sistem menampilkan notifikasi untuk aktivitas penting.

**Persyaratan:**
- Notifikasi dibuat untuk klaim baru, pesan baru, perubahan status klaim, dan konfirmasi serah terima.
- Notifikasi hanya dapat dibaca oleh penerimanya.
- Notifikasi harus mengarah ke konteks aktivitas yang relevan apabila memungkinkan.
- Notifikasi push ketika aplikasi ditutup tidak termasuk versi awal.

**Kriteria penerimaan:**
- Aktivitas yang memenuhi aturan menghasilkan notifikasi.
- Pengguna tidak dapat membaca notifikasi milik pengguna lain.

### FR-11 — Pelaporan Penyalahgunaan

**Deskripsi:** Pengguna dapat melaporkan laporan barang atau pesan yang dianggap melanggar aturan.

**Persyaratan:**
- Pengguna harus login.
- Laporan penyalahgunaan memuat objek yang dilaporkan dan alasan.
- Sistem mencatat pelapor, waktu, dan status peninjauan.
- Sistem mencegah akses tanpa izin terhadap laporan penyalahgunaan.

**Kriteria penerimaan:**
- Laporan valid tersimpan.
- Objek laporan dapat diidentifikasi oleh administrator.
- Pengguna biasa tidak dapat mengubah keputusan moderasi.

### FR-12 — Moderasi Administrator dan Penonaktifan Akun

**Deskripsi:** Administrator meninjau laporan penyalahgunaan dan mengambil tindakan moderasi yang sesuai, termasuk penonaktifan akun berbasis peran dan penyembunyian konten.

**Persyaratan:**
- Hanya administrator yang dapat mengakses fungsi moderasi.
- Administrator dapat meninjau konten dan akun yang dilaporkan.
- Administrator dapat menyembunyikan konten (`hidden`), memberikan peringatan, atau menangguhkan akun pengguna (`suspended`) sesuai kebijakan.
- **Dampak Penonaktifan Akun Berdasarkan Peran (rekomendasi DEC-026):**
  - **Penonaktifan Akun Pengklaim:**
    - Klaim berstatus `submitted` otomatis diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`). Status laporan tetap `open`.
    - Klaim berstatus `in_handover` otomatis diubah menjadi `cancelled` oleh admin (`cancelled_by = admin_id`), dan status laporan terkait dikembalikan dari `in_handover` menjadi `open` sehingga penemu tidak dirugikan dan dapat memilih klaim mengantre lainnya.
    - Percakapan chat terkait klaim dikunci menjadi hanya-baca (*read-only*).
  - **Penonaktifan Akun Penemu:**
    - Seluruh laporan aktif milik penemu (`open` maupun `in_handover`) diubah statusnya menjadi `hidden`.
    - Seluruh klaim aktif (`submitted` maupun `in_handover`) pada seluruh laporan tersebut otomatis diakhiri menjadi `cancelled` oleh admin/sistem tanpa membebani penalti pada para pengklaim, sehingga hak klaim para pengklaim langsung terbebas seketika.
    - Chat dikunci menjadi hanya-baca, pesan ditahan jika berkaitan dengan investigasi moderasi.
  - **Kebijakan Reaktivasi Akun & Pemisahan Status Akun vs Laporan:**
    - Status akun (`users.status`) dan status laporan (`reports.status`) merupakan dua hal yang berbeda. Pengaktifan kembali akun penemu (`status = 'active'`) tidak otomatis mengubah status laporan yang `hidden` menjadi `open` atau mempublikasikannya kembali ke publik.
    - Jika akun yang ditangguhkan diaktifkan kembali (`status = 'active'`), seluruh transaksi, klaim, dan laporan masa lalu yang telah mencapai status terminal (`cancelled`, `completed`, `rejected`, `expired`, `hidden`) **bersifat permanen dan tidak dihidupkan kembali secara otomatis** (*no automatic revival*). Pengguna yang dipulihkan dapat membuat laporan baru atau mengajukan klaim baru pada laporan yang masih `open`.
    - Prosedur pembukaan kembali (*unhide*) laporan berstatus `hidden` ditunda sebagai keputusan non-blocker ke fase moderasi lanjutan.
- Alasan dan waktu tindakan moderasi dicatat lengkap di basis data.
- Administrator tidak menentukan kepemilikan barang.

**Kriteria penerimaan:**
- Pengguna biasa ditolak ketika mengakses fungsi administrator.
- Tindakan moderasi yang sah tercatat di `moderation_actions`.
- Konten yang disembunyikan (`hidden`) tidak tampil pada pencarian publik.
- Penonaktifan akun pengklaim membatalkan klaim terkait dan mengembalikan status laporan ke `open` jika sedang berada dalam `in_handover`.
- Penonaktifan akun penemu mengubah laporan aktif menjadi `hidden` dan membatalkan seluruh klaim mengantre tanpa penalti bagi para pengklaim.
- Reaktivasi akun mengaktifkan akun pengguna tanpa menghidupkan kembali klaim atau proses serah terima lama yang telah dibatalkan, serta tidak otomatis membuka atau mempublikasikan kembali laporan yang telah `hidden`.

### FR-13 — Penghapusan Pesan Otomatis

**Deskripsi:** Pesan chat dihapus sesuai kebijakan retensi.

**Persyaratan:**
- Penghitungan 30 hari dimulai setelah kedua pihak mengonfirmasi serah terima.
- Pesan yang berkaitan dengan laporan penyalahgunaan yang belum selesai ditinjau ditahan sementara.
- Status dan waktu konfirmasi serah terima tidak ikut dihapus.
- Proses penghapusan harus dapat dijalankan secara terjadwal.
- Kebijakan setelah peninjauan moderasi selesai harus ditetapkan sebelum implementasi.

**Kriteria penerimaan:**
- Pesan yang memenuhi syarat dihapus sesuai jadwal.
- Catatan transaksi tetap tersedia.
- Pesan yang sedang ditahan untuk peninjauan tidak dihapus sebelum kondisi pengecualiannya berakhir.

### FR-14 — PWA dan Offline

**Deskripsi:** Aplikasi mendukung kemampuan PWA dasar.

**Persyaratan:**
- Antarmuka responsif pada layar desktop dan seluler.
- Aplikasi memiliki manifest yang valid dan dapat dipasang pada browser yang mendukung.
- Aset statis yang aman dapat disimpan untuk penggunaan offline dasar.
- Operasi yang mengubah data memerlukan koneksi internet.
- Data privat, halaman admin, dan konten chat tidak boleh disimpan dalam cache publik yang dapat diakses pengguna lain.
- Kegagalan jaringan harus ditangani dengan pesan yang jelas.

**Kriteria penerimaan:**
- Aplikasi dapat dipasang pada lingkungan browser yang didukung.
- Aset yang telah disimpan dapat diakses sesuai kebijakan cache.
- Operasi klaim, chat, dan serah terima tidak dinyatakan berhasil tanpa konfirmasi server.

## 4. Kebutuhan Nonfungsional

### NFR-01 — Keamanan

- Autentikasi dan otorisasi dilakukan di sisi server.
- Kata sandi di-hash menggunakan mekanisme yang aman.
- Semua masukan divalidasi.
- Perlindungan CSRF digunakan untuk formulir yang relevan.
- Pengguna tidak dapat mengakses data privat hanya dengan mengubah ID pada URL.

### NFR-02 — Privasi

- Data pribadi hanya dapat diakses sesuai kebutuhan dan hak akses.
- Chat hanya dapat diakses oleh pihak yang berhak, dengan pengecualian moderasi yang dibatasi.
- Pesan dihapus sesuai kebijakan retensi.
- Kredensial tidak dicatat ke log atau repository.

### NFR-03 — Konsistensi Data

- Perubahan status klaim dan serah terima harus konsisten.
- Aturan yang melibatkan beberapa operasi basis data harus menggunakan transaksi basis data apabila diperlukan.
- Permintaan berulang tidak boleh menghasilkan penyelesaian transaksi ganda.

### NFR-04 — Kemudahan Penggunaan

- Navigasi dan status aktivitas harus jelas.
- Formulir memberikan pesan validasi yang mudah dimengerti.
- Antarmuka mendukung ukuran layar yang umum digunakan.

### NFR-05 — Kemudahan Pemeliharaan

- Struktur aplikasi mengikuti konvensi Laravel.
- Perubahan skema dikelola melalui migration.
- Logika penting dapat diuji secara otomatis.
- Konfigurasi lingkungan tidak ditulis langsung ke kode.

### NFR-06 — Keandalan

- Kegagalan jaringan ditangani secara aman.
- Status transaksi tidak boleh menyesatkan pengguna.
- Kesalahan sistem tidak menampilkan detail sensitif.

## 5. Aturan Bisnis

| ID | Aturan |
|---|---|
| BR-01 | Email harus menggunakan domain resmi universitas yang diizinkan. |
| BR-02 | Fitur inti hanya tersedia setelah email diverifikasi. |
| BR-03 | Pengguna tidak dapat mengklaim barang temuannya sendiri. |
| BR-04 | Satu barang temuan hanya memiliki satu klaim terpilih dalam status `in_handover` pada satu waktu. |
| BR-05 | Pengguna tidak dapat mengajukan klaim baru selama memiliki klaim berstatus aktif (`submitted` atau `in_handover`). Status terminal (`completed`, `rejected`, `cancelled`, `expired`) langsung melepaskan pembatasan ini. |
| BR-06 | Administrator tidak menentukan kepemilikan barang. |
| BR-07 | Serah terima selesai hanya setelah penemu dan pengklaim sama-sama mengonfirmasi di sistem. |
| BR-08 | Setelah serah terima selesai, laporan berstatus `resolved`, klaim berstatus `completed`, klaim lain yang mengantre dibatalkan, dan chat menjadi hanya-baca. |
| BR-09 | Pesan chat dihapus 30 hari setelah serah terima selesai, kecuali masih ditahan untuk peninjauan penyalahgunaan. |
| BR-10 | Catatan status dan waktu konfirmasi serah terima tetap dipertahankan setelah pesan dihapus. |
| BR-11 | Pengguna yang klaimnya ditolak (`rejected`) atau membatalkan klaimnya sendiri (`cancelled` oleh pengklaim) dilarang mengajukan klaim ulang pada laporan yang sama. Pengklaim yang klaimnya kedaluwarsa (`expired`) atau dibatalkan oleh penemu saat serah terima (`cancelled` oleh penemu) diperbolehkan mengajukan klaim kembali selama laporan berstatus `open`. |
| BR-12 | Pembatalan proses serah terima yang mandek memerlukan tindakan eksplisit, dialog konfirmasi, pencatatan alasan, dan pencatatan aktor pembatal, mengakhiri klaim menjadi `cancelled`, dan membuka kembali laporan menjadi `open`. Konsekuensi hak klaim ulang pengklaim ditentukan oleh pihak yang membatalkan serah terima. |
| BR-13 | Status klaim terminal bersifat permanen dan tidak dapat diubah kembali menjadi status aktif melalui alur biasa. |

## 6. Persyaratan Validasi Umum

- Semua perubahan data harus diperiksa di server.
- Hak akses harus diperiksa untuk setiap tindakan yang mengubah atau membaca data privat.
- ID objek dari permintaan pengguna tidak boleh dianggap sebagai bukti kepemilikan atau kewenangan.
- Formulir harus menampilkan kesalahan validasi yang relevan.
- Perubahan status harus mengikuti transisi yang diperbolehkan.
- Operasi yang berpotensi dijalankan bersamaan harus menjaga konsistensi data.

## 7. Kebutuhan Pengujian

Pengujian minimal harus mencakup:

1. Registrasi, verifikasi email, login, dan logout.
2. Akses publik pengunjung tanpa login (daftar, detail, pencarian) serta pemblokiran tindakan terproteksi.
3. Validasi pembuatan dan perubahan laporan.
4. Otorisasi pemilik laporan.
5. Pencarian dan kombinasi filter.
6. Pengajuan klaim, klaim ganda, dan klaim bersamaan.
7. Pembatasan satu klaim aktif per pengguna dan pelepasan hak klaim saat mencapai status terminal.
8. Pencegahan klaim ulang oleh pengguna yang sama setelah ditolak (`rejected`) atau dibatalkan sendiri (`cancelled`).
9. Izin klaim ulang setelah klaim kedaluwarsa (`expired`) atau setelah pembatalan serah terima oleh penemu.
10. Hak penolakan oleh penemu dan hak pembatalan oleh pengklaim.
11. Notifikasi klaim 72 jam tanpa respons penemu dan kedaluwarsa otomatis jam ke-120.
12. Pembatalan proses serah terima yang terhenti dengan input alasan pembatalan dan pencatatan aktor pembatal.
13. Privasi dan otorisasi chat (kunci hanya-baca pada status terminal).
14. Konfirmasi serah terima oleh kedua pihak dan pencegahan konfirmasi ganda pihak yang sama.
15. Pencegahan perubahan status yang tidak valid.
16. Notifikasi dan hak akses penerima.
17. Pelaporan penyalahgunaan dan hak akses administrator.
18. Penghapusan pesan otomatis dan pengecualian moderasi.
19. Perilaku PWA ketika offline.

## 8. Hal yang Belum Diputuskan

Persyaratan berikut belum boleh dianggap final:

1. Domain email resmi universitas yang diizinkan untuk registrasi akun.
2. Batas retensi pesan setelah peninjauan moderasi selesai.
3. Batas ukuran dan format foto laporan (misal 1 foto, maks 2 MB, JPEG/PNG/WebP).
4. Peramban dan perangkat minimum yang harus didukung secara resmi.

Keputusan atas hal-hal tersebut harus dicatat dalam `docs/DECISIONS_LOG.md` dan diterapkan pada dokumen terkait sebelum implementasi fitur yang terdampak.

---

**Catatan:** SRS ini merupakan draf persyaratan. Detail yang belum diputuskan harus diklarifikasi sebelum dijadikan dasar implementasi final.