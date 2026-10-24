# UI/UX Specification — Campus Lost & Found

**Status:** Draft v0.1  
**Platform:** Web responsif dengan dukungan Progressive Web App (PWA)  
**Teknologi antarmuka:** Laravel Blade, HTML, CSS, dan JavaScript

---

## 1. Tujuan

Dokumen ini menetapkan panduan desain antarmuka dan pengalaman pengguna untuk Campus Lost & Found di Universitas Mercu Buana.

Antarmuka harus membantu pengguna melaporkan barang hilang atau ditemukan, mencari laporan, mengajukan klaim, berkomunikasi dengan pihak terkait, serta menyelesaikan proses serah terima dengan jelas dan aman.

Dokumen ini menjadi acuan desain bagi implementasi frontend menggunakan Laravel Blade.

## 2. Prinsip Desain

### 2.1 Sederhana dan Mudah Dipahami

- Gunakan bahasa Indonesia yang jelas dan konsisten.
- Gunakan label tombol yang menjelaskan tindakan, seperti **Buat Laporan**, **Ajukan Klaim**, dan **Konfirmasi Serah Terima**.
- Hindari elemen visual yang tidak membantu pengguna menyelesaikan tugasnya.

### 2.2 Konsisten

- Gunakan gaya tombol, formulir, kartu laporan, label status, dan notifikasi yang konsisten.
- Letakkan navigasi utama pada posisi yang sama di halaman-halaman utama.
- Gunakan istilah status yang sama dengan aturan bisnis aplikasi.

### 2.3 Responsif

- Antarmuka harus dapat digunakan pada desktop, tablet, dan ponsel.
- Pada layar kecil, susunan konten berubah menjadi satu kolom jika diperlukan.
- Formulir dan tombol harus tetap nyaman digunakan pada perangkat layar sentuh.

### 2.4 Jelas tentang Status dan Tindakan

- Tampilkan status laporan dan klaim secara jelas.
- Bedakan tindakan utama, tindakan sekunder, dan tindakan berisiko seperti membatalkan laporan.
- Berikan umpan balik ketika pengguna mengirim formulir atau melakukan tindakan penting.
- Jangan menganggap tindakan berhasil sebelum server mengonfirmasi keberhasilannya.

### 2.5 Aksesibilitas

- Gunakan kontras teks dan latar yang memadai.
- Setiap input memiliki label yang jelas.
- Jangan mengandalkan warna saja untuk menyampaikan status.
- Pastikan elemen interaktif dapat digunakan melalui keyboard.
- Gunakan teks alternatif untuk gambar yang bermakna.

## 3. Arah Visual

### 3.1 Karakter Antarmuka

Arah desain yang disarankan:

- Modern, bersih, dan sesuai untuk lingkungan kampus.
- Mengutamakan keterbacaan dan kemudahan pencarian.
- Menggunakan ruang kosong yang cukup agar halaman tidak terasa padat.
- Mengutamakan informasi barang, status, dan tindakan yang relevan.

### 3.2 Warna dan Tipografi

Palet warna final belum ditetapkan. Sebelum implementasi visual, pilih palet yang konsisten dan pastikan kontras teks memenuhi kebutuhan aksesibilitas.

Panduan awal:

- Gunakan satu warna utama untuk identitas dan tindakan utama.
- Gunakan warna netral untuk latar dan permukaan kartu.
- Gunakan warna status secara konsisten, disertai label teks.
- Gunakan maksimal beberapa tingkat ukuran teks agar hierarki informasi jelas.
- Prioritaskan font yang mudah dibaca dan tersedia dengan baik di perangkat pengguna.

Jangan mengarang identitas visual resmi Universitas Mercu Buana atau menggunakan logo kampus tanpa aset yang sesuai.

### 3.3 Komponen Visual

Gunakan komponen yang dapat digunakan kembali untuk:

- Tombol utama dan sekunder.
- Input, textarea, dropdown, dan kontrol tanggal.
- Kartu laporan barang.
- Label status.
- Pesan validasi.
- Dialog konfirmasi.
- Notifikasi dalam aplikasi.
- Tampilan kosong dan pesan kesalahan.
- Indikator pemuatan.

## 4. Navigasi Utama

Enam halaman utama aplikasi:

1. **Beranda / Daftar Barang** — melihat, mencari, dan memfilter laporan.
2. **Formulir Laporan** — membuat laporan barang hilang atau ditemukan.
3. **Detail Barang** — melihat informasi laporan dan tindakan yang tersedia.
4. **Chat dan Proses Klaim** — berkomunikasi dan mengelola proses klaim.
5. **Aktivitas Saya** — melihat laporan, klaim, dan aktivitas pengguna.
6. **Dashboard Admin** — memeriksa laporan penyalahgunaan dan melakukan moderasi.

Halaman login, registrasi, dan verifikasi email merupakan halaman pendukung di luar enam halaman utama tersebut.

Navigasi harus menyesuaikan status autentikasi dan peran akun. Tautan admin hanya ditampilkan kepada administrator yang berwenang, tetapi otorisasi tetap wajib diperiksa di server.

## 5. Spesifikasi Halaman

### 5.1 Beranda / Daftar Barang

**Tujuan:** Membantu pengguna menemukan laporan barang yang relevan.

Komponen utama:

- Header dan navigasi.
- Judul serta penjelasan singkat aplikasi.
- Kolom pencarian berdasarkan kata kunci.
- Filter jenis laporan: hilang atau ditemukan.
- Filter kategori, lokasi, dan tanggal.
- Tombol untuk membuat laporan.
- Daftar laporan dalam bentuk kartu.
- Navigasi halaman jika jumlah hasil melebihi satu halaman.
- Tampilan hasil kosong ketika tidak ada laporan yang cocok.

Informasi pada kartu laporan:

- Foto barang jika tersedia.
- Judul laporan.
- Jenis laporan.
- Kategori.
- Lokasi.
- Tanggal kejadian jika tersedia.
- Status laporan.

Perilaku:

- Pengguna dapat mengubah kata kunci dan filter.
- Pengguna dapat membuka detail laporan.
- Jika hasil pencarian kosong, tampilkan pesan yang informatif dan sarankan pengguna mengubah filter.
- Jangan tampilkan tombol klaim pada laporan yang tidak memenuhi syarat untuk diklaim.

### 5.2 Formulir Laporan

**Tujuan:** Memungkinkan pengguna membuat laporan barang hilang atau ditemukan.

Field yang direncanakan:

- Jenis laporan.
- Judul barang.
- Kategori.
- Deskripsi.
- Lokasi kejadian.
- Tanggal kejadian, jika diketahui.
- Foto barang, jika tersedia dan sesuai aturan unggahan.

Komponen:

- Label pada setiap input.
- Petunjuk singkat untuk input yang membutuhkan penjelasan.
- Validasi yang mudah dipahami.
- Tombol **Simpan Laporan**.
- Tombol kembali atau batal.

Perilaku:

- Tampilkan kesalahan validasi di dekat input terkait.
- Pertahankan data yang valid ketika pengiriman gagal, jika aman dilakukan.
- Berikan pesan keberhasilan setelah server menyimpan laporan.
- Konfirmasi sebelum pengguna meninggalkan formulir jika ada perubahan yang belum disimpan, apabila mekanismenya diterapkan.
- Pengguna harus login dan memenuhi persyaratan akun sebelum membuat laporan.

### 5.3 Detail Barang

**Tujuan:** Menampilkan informasi lengkap dan tindakan yang sesuai dengan jenis serta status laporan.

Informasi:

- Foto barang jika tersedia (1 foto).
- Judul dan deskripsi.
- Jenis laporan (Barang Hilang atau Barang Temuan).
- Kategori.
- Lokasi.
- Tanggal kejadian.
- Informasi umum pembuat laporan yang memang diperlukan.
- Status laporan (Tersedia, Dalam Proses Serah Terima, Selesai, Dibatalkan).

Tindakan yang mungkin tersedia:

- **Ajukan Klaim** untuk laporan barang ditemukan:
  - **Akses Pengunjung Tanpa Login:** Jika pengunjung belum login membuka detail laporan barang temuan, tombol **"Ajukan Klaim"** tetap terlihat namun mengarahkan pengguna ke halaman Login/Registrasi disertai pesan banner informatif (*"Silakan login dengan akun kampus terverifikasi untuk mengajukan klaim barang ini."*).
  - **Pengguna Belum Verifikasi Email Kampus:** Dinonaktifkan disertai pesan penjelasan (*"Verifikasi email kampus Anda terlebih dahulu untuk dapat mengajukan klaim."*).
  - **Status Laporan:** Hanya aktif jika status laporan adalah `open` dan pengguna yang login bukan pembuat laporan.
  - **Klaim Aktif Berjalan:** Dinonaktifkan disertai pesan penjelasan jika pengguna sedang memiliki klaim aktif lain di sistem (*"Anda memiliki klaim aktif yang sedang diproses. Selesaikan atau batalkan klaim tersebut terlebih dahulu."*).
  - **Pencegahan Klaim Ulang:** Dinonaktifkan disertai pesan penjelasan jika pengguna sebelumnya pernah ditolak (`rejected`) oleh penemu atau membatalkan klaimnya sendiri (`cancelled` oleh pengklaim) pada laporan yang sama (*"Anda tidak dapat mengajukan klaim ulang pada laporan ini."*).
  - **Izin Klaim Ulang:** Tetap diizinkan dan aktif jika klaim sebelumnya pada laporan ini berstatus kedaluwarsa (`expired`), ATAU dibatalkan oleh penemu saat proses serah terima (`cancelled` oleh penemu), selama laporan masih `open` dan pengguna tidak memiliki klaim aktif lain.
- **Edit Laporan** untuk pemilik laporan jika diizinkan (hanya jika laporan berstatus `open` atau laporan barang hilang yang belum selesai).
- **Batalkan Laporan** untuk pemilik laporan (hanya jika laporan barang temuan tidak sedang dalam status `in_handover`).
- **Laporkan Konten** untuk melaporkan dugaan penyalahgunaan kepada administrator.

Perilaku:

- Tindakan menyesuaikan status laporan, identitas pengguna, status verifikasi email kampus, dan aturan bisnis.
- Pengguna tidak dapat mengedit atau membatalkan laporan milik orang lain.
- Jika ada proses klaim aktif yang menghalangi perubahan, jelaskan alasannya.
- Ajukan konfirmasi sebelum pembatalan laporan.
- Jangan menampilkan informasi pribadi atau riwayat chat pihak lain.

### 5.4 Chat dan Proses Klaim

**Tujuan:** Memfasilitasi komunikasi antara penemu dan pengklaim untuk menyepakati kepemilikan dan serah terima.

Komponen:

- Ringkasan laporan barang (judul, foto kecil, jenis, lokasi).
- Label status klaim saat ini (`Menunggu Tanggapan`, `Proses Serah Terima`, `Selesai`, `Ditolak`, `Dibatalkan`, `Kedaluwarsa`).
- Identitas umum pihak yang terlibat.
- Riwayat pesan dalam urutan waktu.
- Input pesan teks dan tombol kirim (hanya aktif selama status klaim `submitted` atau `in_handover`).
- Panel tindakan alur klaim:
  - **Pada status `submitted` (Menunggu Tanggapan):**
    - **Banner Status Klaim Mengantre:** Jika laporan sedang berada dalam proses serah terima (`in_handover`) dengan pengklaim lain, pengklaim mengantre melihat banner informatif: *"Barang temuan ini sedang dalam proses serah terima dengan pengklaim lain. Klaim Anda saat ini berada dalam antrean. Jika proses serah terima tersebut dibatalkan, penemu dapat memproses klaim Anda. Anda dapat menunggu atau memilih 'Batalkan Klaim' jika ingin mengajukan klaim pada barang lain."*
    - **Banner Penangguhan Akun Pihak Terkait:** Jika akun pihak lawan transaksi ditangguhkan (`suspended`) oleh administrator, ruang percakapan menampilkan banner peringatan sistem: *"Akun pihak terkait telah dinonaktifkan oleh administrator karena pelanggaran. Seluruh interaksi, chat, dan proses serah terima pada klaim ini telah dihentikan secara permanen."*
    - Penemu melihat tombol: **"Pilih untuk Serah Terima"** dan **"Tolak Klaim"** (disertai dialog konfirmasi).
    - Pengklaim melihat tombol: **"Batalkan Klaim"** (disertai dialog konfirmasi yang mengingatkan bahwa pembatalan sukarela membuat pengklaim tidak dapat mengklaim barang ini kembali).
    - **Banner Peringatan 72 Jam:** Jika penemu belum merespons selama 72 jam sejak klaim dibuat, pengklaim melihat banner informasi: *"Penemu belum merespons klaim Anda selama 72 jam. Anda memiliki waktu tambahan 48 jam untuk menunggu atau membatalkan klaim ini sekarang agar dapat mengajukan klaim pada barang lain. Jika tetap tidak ada respons, sistem akan mengakhiri klaim ini secara otomatis pada jam ke-120 (5 hari) sejak klaim diajukan."* disertai tombol cepat **"Batalkan Klaim Sekarang"**.
  - **Pada status `in_handover` (Proses Serah Terima):**
    - Indikator status konfirmasi serah terima visual:
      - Penemu: [Sudah Konfirmasi / Menunggu Konfirmasi]
      - Pengklaim: [Sudah Konfirmasi / Menunggu Konfirmasi]
    - Tombol utama: **"Konfirmasi Serah Terima"** (dinonaktifkan jika pengguna yang bersangkutan sudah menekan konfirmasi, disertai teks penjelasan).
    - Tombol alternatif: **"Batalkan Proses Serah Terima"** untuk kedua pihak jika serah terima gagal/terhenti, memicu dialog modal wajib isi alasan pembatalan dan menampilkan peringatan konsekuensi:
      - Jika diklik oleh **Pengklaim**: *"Perhatian: Jika Anda membatalkan proses serah terima, Anda tidak akan diperbolehkan mengajukan klaim ulang pada barang temuan ini."*
      - Jika diklik oleh **Penemu**: *"Perhatian: Jika Anda membatalkan proses serah terima, laporan akan dibuka kembali untuk klaim lain atau klaim baru dari pengklaim."*
  - **Pada status Terminal (`completed`, `rejected`, `cancelled`, `expired`):**
    - Form pesan dan tombol kirim dinonaktifkan (hanya-baca).
    - Menampilkan kartu ringkasan hasil akhir transaksi dan alasan pembatalan/penolakan jika tersedia.

Perilaku:

- Hanya penemu dan pengklaim yang terkait dapat membuka percakapan tersebut.
- Tampilkan pesan dengan pembedaan visual antara pesan pengguna sendiri dan pihak lain.
- Tampilkan waktu pengiriman pesan secara wajar.
- Tampilkan status kosong ketika belum ada pesan.
- Jika pengiriman pesan gagal, beri tahu pengguna dan jangan mengklaim pesan sudah terkirim.
- Setelah kedua pihak mengonfirmasi serah terima, nonaktifkan input dan tombol kirim seketika.
- Sebelum konfirmasi serah terima, tampilkan penjelasan hukum/aturan bahwa tindakan tersebut menyatakan serah terima fisik telah benar-benar dilakukan.

### 5.5 Aktivitas Saya

**Tujuan:** Menjadi tempat pengguna mengelola laporan dan memantau proses klaimnya.

Bagian yang direncanakan:

- Laporan yang dibuat pengguna.
- Klaim yang diajukan pengguna.
- Klaim yang sedang diproses.
- Riwayat transaksi yang telah selesai.
- Notifikasi dalam aplikasi.

Informasi setiap item:

- Judul barang atau laporan.
- Jenis aktivitas.
- Status terkini.
- Tanggal aktivitas atau pembaruan.
- Tautan menuju detail yang relevan.

Perilaku:

- Pengguna hanya melihat aktivitas miliknya sendiri.
- Status yang ditampilkan harus mengikuti data server.
- Aktivitas kosong harus disertai pesan dan tindakan lanjutan yang relevan.
- Notifikasi yang belum dibaca harus dapat dibedakan tanpa hanya mengandalkan warna.

### 5.6 Dashboard Admin

**Tujuan:** Memungkinkan administrator meninjau laporan penyalahgunaan dan melakukan moderasi.

Komponen:

- Ringkasan jumlah laporan penyalahgunaan berdasarkan status.
- Daftar laporan penyalahgunaan.
- Detail konten yang dilaporkan sesuai kewenangan.
- Informasi pelapor dan objek yang dilaporkan sesuai kebutuhan moderasi.
- Pilihan tindakan moderasi.
- Input alasan tindakan.
- Riwayat tindakan moderasi.

Tindakan yang direncanakan:

- Meninjau laporan.
- Menyembunyikan konten yang melanggar aturan (`hidden`).
- Memberikan peringatan.
- Menangguhkan akun (`suspended`) untuk pelanggaran serius atau berulang.
- Mencatat alasan dan hasil tindakan.

Perilaku:

- Hanya administrator yang berwenang dapat mengakses dashboard dan menjalankan tindakan.
- **Peringatan Konfirmasi Penonaktifan Akun Berbasis Peran (rekomendasi DEC-026):**
  - Modal penonaktifan akun menampilkan ringkasan dampak secara transparan sebelum dieksekusi:
    - Jika pengguna adalah **Pengklaim**: *"Penonaktifan akun pengklaim ini akan membatalkan seluruh klaim aktifnya. Jika pengklaim sedang berada dalam proses serah terima, status laporan terkait akan dibuka kembali (open) agar pihak penemu tidak dirugikan."*
    - Jika pengguna adalah **Penemu**: *"Penonaktifan akun penemu ini akan menyembunyikan (hidden) seluruh laporan barang miliknya dan membatalkan seluruh klaim aktif yang sedang menunggu atau dalam serah terima tanpa penalti bagi para pengklaim."*
- Setiap tindakan harus memiliki alasan wajib dan dicatat di `moderation_actions`.
- Administrator tidak boleh menentukan siapa pemilik barang dalam proses klaim.
- Jika tidak ada laporan penyalahgunaan, tampilkan keadaan kosong yang jelas.

## 6. Halaman Pendukung

### 6.1 Login

Komponen:

- Input email.
- Input kata sandi.
- Tombol masuk.
- Tautan registrasi.
- Pesan kesalahan autentikasi yang tidak membocorkan informasi sensitif.

### 6.2 Registrasi

Komponen:

- Nama.
- Email.
- Kata sandi dan konfirmasi kata sandi.
- Persetujuan terhadap ketentuan penggunaan jika ditetapkan.
- Tombol registrasi.

Persyaratan domain email kampus harus mengikuti keputusan resmi yang belum ditetapkan.

### 6.3 Verifikasi Email

Tampilkan instruksi verifikasi, status email, dan opsi untuk meminta pengiriman ulang jika fitur tersebut disediakan.

Jangan izinkan tindakan yang mensyaratkan akun terverifikasi sebelum verifikasi selesai.

## 7. Status dan Umpan Balik Antarmuka

Antarmuka menyediakan pola yang konsisten untuk:

- **Loading:** proses sedang berjalan (spinner/skeleton loader).
- **Success:** tindakan berhasil dikonfirmasi server (toast/alert hijau).
- **Validation error:** data tidak memenuhi ketentuan (teks merah di bawah input).
- **General error:** terjadi kegagalan sistem (alert merah informatif).
- **Empty state:** belum ada data atau hasil pencarian (ilustrasi/ikon dan teks saran aksi).
- **Permission denied:** pengguna tidak memiliki hak untuk melakukan tindakan (halaman 403 atau modal peringatan).
- **Offline:** koneksi tidak tersedia dan tindakan membutuhkan internet (banner peringatan kuning/merah di atas layar).

### 7.1 Pemetaan Label dan Indikator Status Resmi

Untuk menjaga konsistensi, antarmuka menggunakan pemetaan status berikut:

#### Status Laporan Barang (`reports.status`)
| Kode Database | Label Tampilan UI | Warna / Indikator | Keterangan untuk Pengguna |
|---|---|---|---|
| `'open'` | **Tersedia** | Hijau / Biru | Barang temuan siap menerima klaim; barang hilang aktif dicari. |
| `'in_handover'` | **Proses Serah Terima** | Kuning / Oranye | Salah satu klaim sedang diproses untuk serah terima. |
| `'resolved'` | **Selesai** | Abu-abu Netral | Serah terima telah berhasil dikonfirmasi oleh kedua belah pihak. |
| `'cancelled'` | **Dibatalkan** | Merah Muted | Laporan telah dibatalkan oleh pembuatnya. |
| `'hidden'` | **Disembunyikan** | Merah Gelap | Laporan disembunyikan oleh administrator karena pelanggaran moderasi. |

#### Status Pengajuan Klaim (`claims.status`)
| Kode Database | Label Tampilan UI | Warna / Indikator | Keterangan untuk Pengguna |
|---|---|---|---|
| `'submitted'` | **Menunggu Tanggapan** | Biru | Klaim telah dikirim, menunggu respons/persetujuan penemu. |
| `'in_handover'` | **Proses Serah Terima** | Kuning / Oranye | Klaim terpilih untuk serah terima fisik barang. |
| `'completed'` | **Serah Terima Selesai** | Hijau Sukses | Kedua pihak telah mengonfirmasi; transaksi berakhir sukses. |
| `'rejected'` | **Klaim Ditolak** | Merah | Penemu menolak klaim; pengklaim bebas mengklaim barang lain. |
| `'cancelled'` | **Klaim Dibatalkan** | Abu-abu | Pengklaim membatalkan klaim atau proses serah terima dibatalkan. |
| `'expired'` | **Kedaluwarsa** | Abu-abu Gelap | Penemu tidak merespons hingga batas waktu terlampaui. |

## 8. Formulir dan Dialog Konfirmasi

- Setiap input harus memiliki label yang jelas dan penanda wajib isi.
- Pesan validasi harus menjelaskan cara memperbaiki kesalahan di bawah input terkait.
- Tombol submit dinonaktifkan (*disabled*) saat proses pengiriman berlangsung untuk mencegah klik ganda.
- **Dialog Konfirmasi Serah Terima:**
  - Menampilkan ringkasan barang, nama pihak lawan, dan teks peringatan: *"Apakah Anda yakin barang ini telah diserahterimakan secara fisik? Tindakan ini tidak dapat dibatalkan setelah kedua pihak mengonfirmasi."*
- **Dialog Modal Pembatalan Serah Terima:**
  - Menampilkan peringatan: *"Membatalkan proses serah terima akan mengakhiri klaim ini dan membuka kembali laporan barang untuk klaim lain."*
  - Wajib menyediakan textarea input **Alasan Pembatalan** sebelum tombol konfirmasi pembatalan dapat ditekan.
- **Dialog Penolakan Klaim:**
  - Penemu wajib mengonfirmasi penolakan klaim pengklaim melalui dialog konfirmasi dengan opsi catatan singkat.

## 9. Responsivitas dan Perangkat

### Desktop

- Gunakan ruang layar secara efisien.
- Daftar laporan dapat menggunakan beberapa kolom.
- Navigasi utama harus mudah ditemukan.

### Tablet

- Kurangi jumlah kolom jika diperlukan.
- Pastikan filter dan formulir tetap mudah diakses.

### Ponsel

- Utamakan satu kolom.
- Tombol utama harus mudah disentuh.
- Formulir chat harus tetap terbaca saat keyboard virtual terbuka.
- Hindari tabel lebar yang memerlukan pengguliran horizontal jika dapat disajikan sebagai kartu.

## 10. PWA dan Kondisi Offline

- Antarmuka harus mendukung pemasangan sebagai PWA sesuai spesifikasi PWA.
- Berikan indikator ketika aplikasi tidak memiliki koneksi.
- Halaman atau aset yang telah di-cache dapat ditampilkan sesuai strategi cache yang disepakati.
- Jangan menampilkan data cache seolah-olah merupakan status transaksi terkini.
- Membuat laporan, mengajukan klaim, mengirim pesan, dan mengonfirmasi serah terima membutuhkan koneksi internet.
- Jangan mengantrekan tindakan transaksi sensitif untuk dikirim otomatis tanpa desain sinkronisasi dan validasi yang disetujui.

Detail cache, halaman offline, dan mekanisme pembaruan aset ditetapkan dalam `PWA_SPECIFICATION.md`.

## 11. Aksesibilitas dan Keamanan UI

- Gunakan elemen HTML semantik.
- Pastikan fokus keyboard terlihat.
- Gunakan label yang dapat dibaca pembaca layar.
- Jangan menaruh kata sandi, token, atau informasi sensitif pada URL.
- Jangan mengandalkan tombol tersembunyi sebagai pengganti pemeriksaan otorisasi server.
- Batasi informasi yang ditampilkan pada halaman chat dan aktivitas sesuai hak akses.
- Pastikan teks pengguna yang ditampilkan tidak diperlakukan sebagai HTML tepercaya.

## 12. Batasan Desain

- Gunakan Laravel Blade sebagai teknologi utama untuk halaman.
- Hindari penambahan framework frontend besar tanpa persetujuan.
- Hindari animasi atau efek yang mengganggu penggunaan.
- Jangan menambahkan fitur di luar PRD dan SRS tanpa persetujuan.
- Gunakan komponen Blade yang dapat digunakan kembali jika memang membantu konsistensi.
- Data dan status yang ditampilkan harus berasal dari backend yang berwenang.

## 13. Hal yang Belum Diputuskan

1. Palet warna dan identitas visual final (kode hex/CSS tokens).
2. Logo dan aset resmi yang boleh digunakan.
3. Batas ukuran dan format foto laporan (misal 1 foto, maks 2 MB, format JPEG/PNG/WebP).
4. Detail strategi cache dan tampilan offline untuk data laporan publik.
5. Apakah desain akan menggunakan navigasi khusus untuk ponsel atau satu pola navigasi responsif.

Keputusan tersebut harus diselaraskan dengan PRD, SRS, SYSTEM_DESIGN, dan PWA_SPECIFICATION sebelum implementasi yang terkait.

## 14. Pedoman Implementasi

Sebelum membangun antarmuka:

1. Baca dokumen proyek yang menjadi acuan.
2. Periksa apakah komponen yang dibutuhkan sudah tersedia.
3. Buat halaman dan komponen secara bertahap.
4. Uji tampilan pada desktop dan ponsel.
5. Uji keadaan loading, kosong, gagal, dan tidak memiliki izin.
6. Pastikan setiap tindakan UI terhubung dengan validasi dan otorisasi backend.
7. Laporkan halaman yang telah selesai, hasil pengujian, dan hal yang masih belum terselesaikan.

**Prinsip utama:** antarmuka harus membuat proses pelaporan dan klaim terasa jelas, tetapi tidak boleh menjanjikan keberhasilan transaksi sebelum backend mengonfirmasinya.