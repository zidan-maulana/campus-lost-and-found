# Instruksi Agen — Campus Lost & Found

## 1. Gambaran Umum Proyek

Campus Lost & Found adalah aplikasi web yang membantu sivitas akademika Universitas Mercu Buana melaporkan, mencari, mengklaim, dan mengembalikan barang hilang atau barang temuan di lingkungan universitas.

Teknologi yang digunakan:
- Laravel sebagai kerangka kerja aplikasi dan backend.
- Blade sebagai sistem tampilan.
- MySQL sebagai basis data.
- Progressive Web App (PWA) untuk mendukung pemasangan aplikasi dan kemampuan offline dasar.

Proyek ini merupakan tugas perkuliahan. Pengembangan harus mengutamakan ketepatan fungsi, keamanan, kemudahan pemeliharaan, dan kesesuaian dengan ruang lingkup yang telah disepakati.

## 2. Dokumentasi sebagai Acuan Utama

Sebelum mengerjakan tugas implementasi, baca dokumentasi yang relevan di dalam folder `docs/`.

Urutan acuan dokumentasi:
1. `docs/PRD.md` — tujuan produk, ruang lingkup, dan aturan bisnis.
2. `docs/SRS.md` — kebutuhan fungsional dan nonfungsional secara terperinci.
3. `docs/SYSTEM_DESIGN.md` — rancangan arsitektur aplikasi.
4. `docs/DATABASE_DESIGN.md` — struktur basis data dan hubungan antartabel.
5. `docs/UI_UX_SPECIFICATION.md` — rancangan halaman dan interaksi pengguna.
6. `docs/PWA_SPECIFICATION.md` — persyaratan PWA dan kemampuan offline.
7. `docs/DEVELOPMENT_PLAN.md` — tahapan pengembangan dan kriteria penyelesaian.
8. `docs/DECISIONS_LOG.md` — keputusan yang telah disepakati beserta alasannya.

Jika terdapat pertentangan antar dokumen, jangan memilih salah satu secara sepihak. Jelaskan pertentangan tersebut dan minta klarifikasi sebelum mengimplementasikan bagian yang terdampak.

Jangan mengarang kebutuhan atau mengubah keputusan yang belum disetujui.

## 3. Pengendalian Ruang Lingkup

- Implementasikan hanya fitur yang termasuk dalam persyaratan yang telah disetujui.
- Jangan menambahkan fitur, pustaka, layanan, atau kompleksitas arsitektur yang tidak diperlukan.
- Jangan mengubah aturan bisnis yang telah disepakati tanpa persetujuan pengguna.
- Jangan mulai mengembangkan fitur aplikasi sebelum tahap persiapan dan dokumentasi yang relevan selesai diperiksa.
- Jika persyaratan belum jelas, jelaskan bagian yang ambigu dan ajukan usulan penyelesaian sederhana sebelum melanjutkan.

## 4. Alur Kerja Pengembangan

Untuk setiap tugas implementasi:

1. Periksa dokumentasi dan kode yang relevan.
2. Jelaskan rencana perubahan secara singkat.
3. Sebutkan berkas yang akan dibuat atau diubah.
4. Implementasikan hanya pekerjaan yang disetujui.
5. Jalankan pengujian atau validasi yang relevan.
6. Laporkan berkas yang berubah, perintah yang dijalankan, hasil pemeriksaan, dan masalah yang tersisa.
7. Perbarui dokumentasi jika terdapat perubahan keputusan yang telah disetujui atau detail implementasi yang perlu dicatat.

Jangan menyatakan pengujian berhasil jika pengujian tersebut belum benar-benar dijalankan dan hasilnya belum diperiksa.

## 5. Standar Pengembangan Kode

- Ikuti konvensi pengembangan Laravel.
- Gunakan Blade untuk antarmuka berbasis server sesuai teknologi yang disepakati.
- Gunakan migration untuk perubahan struktur basis data.
- Gunakan relasi Eloquent dan validasi sesuai kebutuhan.
- Jaga agar tanggung jawab controller tetap terfokus.
- Pisahkan logika bisnis yang kompleks ke kelas yang sesuai apabila memang diperlukan.
- Gunakan penamaan yang jelas dan format kode yang konsisten.
- Hindari duplikasi logika dan abstraksi yang tidak diperlukan.
- Buat pengujian otomatis untuk aturan bisnis penting dan bagian yang berkaitan dengan keamanan.

## 6. Keamanan dan Privasi

- Jangan pernah memasukkan kata sandi asli, kunci API, token akses, atau kredensial produksi ke dalam repository.
- Jangan memasukkan berkas `.env` ke version control. Gunakan `.env.example` dengan nilai contoh yang aman.
- Validasi setiap masukan dan periksa hak akses di sisi server.
- Terapkan pemeriksaan kepemilikan data dan hak akses berdasarkan peran.
- Lindungi percakapan privat, klaim barang, laporan barang, dan fungsi moderasi dari akses tanpa izin.
- Gunakan perlindungan CSRF dan mekanisme keamanan bawaan Laravel.
- Jangan menampilkan informasi pribadi dalam log atau pesan kesalahan yang dapat diakses pengguna.
- Ikuti kebijakan penyimpanan dan penghapusan pesan serta kebijakan moderasi yang telah disetujui.

## 7. Keamanan Repository dan Git

- Periksa status Git sebelum mengubah berkas.
- Jangan menimpa perubahan pengguna atau menghapus berkas tanpa izin.
- Jangan menjalankan operasi Git yang berpotensi menghapus atau merusak perubahan tanpa persetujuan eksplisit.
- Gunakan pesan commit yang singkat dan deskriptif apabila diminta melakukan commit.
- Jangan memasukkan `.env`, kredensial, berkas basis data lokal, atau dependensi hasil instalasi ke dalam commit.
- Jangan melakukan push ke GitHub kecuali diminta secara eksplisit.

## 8. Batasan Proyek

Proyek ini adalah Campus Lost & Found. Jangan mencampurkan dataset, model pembelajaran mesin, atau fitur dari proyek lain yang tidak berkaitan.

Jangan mengimplementasikan hal-hal berikut pada versi awal kecuali persyaratannya telah diubah dan disetujui:
- Notifikasi push.
- Integrasi layanan pesan eksternal.
- Panggilan suara atau video.
- Lampiran gambar atau berkas di dalam chat.

## 9. Tahap Proyek Saat Ini

Repository dan struktur dokumentasi telah dibuat, tetapi isi dokumentasi masih dalam proses penyusunan.

Pada tahap ini:
- Jangan membuat kerangka aplikasi Laravel secara otomatis.
- Jangan menghasilkan kode aplikasi.
- Jangan membuat migration basis data.
- Jangan memasang dependensi.

Tunggu hingga pengguna secara eksplisit menyetujui perpindahan ke tahap pengembangan berikutnya.

## 10. Format Laporan Penyelesaian Tugas

Setelah menyelesaikan setiap tugas, berikan laporan yang mencakup:

- **Status:** selesai, selesai sebagian, atau terhambat.
- **Perubahan:** berkas yang dibuat atau diubah.
- **Validasi:** pemeriksaan atau pengujian yang benar-benar dilakukan.
- **Masalah:** kesalahan, persyaratan yang belum jelas, atau risiko yang ditemukan.
- **Langkah berikutnya:** tindakan terkecil yang relevan untuk melanjutkan proyek.