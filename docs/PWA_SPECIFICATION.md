# PWA Specification — Campus Lost & Found

**Status:** Draft v0.1  
**Platform:** Laravel + Blade + MySQL dengan dukungan Progressive Web App (PWA)

---

## 1. Tujuan

Dokumen ini menetapkan persyaratan teknis dan perilaku Progressive Web App (PWA) untuk Campus Lost & Found.

PWA bertujuan membuat aplikasi dapat dipasang pada perangkat yang mendukung, memiliki pengalaman penggunaan yang menyerupai aplikasi, serta tetap menampilkan halaman dasar ketika koneksi internet terganggu.

PWA tidak menggantikan backend Laravel. Data dan tindakan transaksi tetap diproses serta divalidasi oleh server.

## 2. Tujuan dan Batasan

### 2.1 Tujuan

- Mendukung instalasi aplikasi pada browser dan perangkat yang kompatibel.
- Menyediakan tampilan responsif untuk desktop dan perangkat seluler.
- Mempercepat akses terhadap aset statis melalui cache.
- Menampilkan halaman offline yang informatif ketika jaringan tidak tersedia.
- Mencegah pengguna mengira tindakan transaksi berhasil ketika server tidak dapat dijangkau.

### 2.2 Di Luar Ruang Lingkup

Untuk versi awal, PWA tidak diwajibkan mendukung:

- Pengajuan klaim secara offline.
- Pengiriman pesan chat secara offline.
- Konfirmasi serah terima secara offline.
- Sinkronisasi otomatis transaksi yang tertunda.
- Push notification ketika aplikasi tertutup.
- Penyimpanan seluruh data laporan untuk akses offline.
- Fitur voice call, video call, atau komunikasi eksternal.

## 3. Persyaratan PWA

Implementasi harus memenuhi persyaratan berikut:

- **PWA-01:** Aplikasi menyediakan web app manifest yang valid.
- **PWA-02:** Aplikasi menyediakan service worker untuk mengatur cache dan pengalaman offline.
- **PWA-03:** Aplikasi menyediakan ikon aplikasi dalam ukuran dan format yang sesuai.
- **PWA-04:** Halaman utama menggunakan desain responsif.
- **PWA-05:** Aset statis yang aman untuk di-cache dapat diakses kembali setelah pernah dimuat.
- **PWA-06:** Aplikasi menampilkan halaman atau pesan offline ketika halaman jaringan tidak dapat dimuat.
- **PWA-07:** Tindakan yang membutuhkan server harus gagal secara jelas ketika koneksi tidak tersedia.
- **PWA-08:** Data pribadi dan halaman transaksi tidak boleh disimpan dalam cache bersama yang berpotensi menampilkan data pengguna lain.
- **PWA-09:** Pembaruan service worker harus ditangani agar pengguna tidak terus menggunakan aset versi lama tanpa penjelasan.
- **PWA-10:** Fitur PWA harus diuji pada browser yang menjadi target pengembangan.

## 4. Web App Manifest

Aplikasi harus menyediakan manifest, umumnya melalui file `manifest.webmanifest`.

Informasi yang perlu dikonfigurasi:

| Properti | Tujuan |
|---|---|
| `name` | Nama lengkap aplikasi |
| `short_name` | Nama singkat pada layar perangkat |
| `start_url` | Halaman awal saat aplikasi dibuka |
| `display` | Mode tampilan aplikasi |
| `theme_color` | Warna tema browser |
| `background_color` | Warna latar saat aplikasi dimuat |
| `icons` | Daftar ikon aplikasi |

Ketentuan:

- Nama aplikasi menggunakan **Campus Lost & Found**.
- `start_url` harus mengarah ke rute aplikasi yang valid.
- Ikon harus berupa aset yang sah dan disediakan dalam ukuran yang diperlukan.
- Warna final mengikuti keputusan desain UI/UX.
- Jangan menggunakan logo resmi kampus tanpa aset yang sesuai dan izin yang diperlukan.
- Manifest harus ditautkan dari halaman HTML yang relevan.

## 5. Service Worker

Service worker digunakan untuk mengatur cache aset dan menyediakan fallback ketika jaringan tidak tersedia.

Tanggung jawab utamanya:

1. Melakukan cache terhadap aset statis yang dipilih.
2. Menyediakan fallback offline untuk navigasi yang gagal karena jaringan.
3. Memperbarui cache ketika versi aplikasi berubah.
4. Menghindari cache terhadap respons yang mengandung data pribadi atau transaksi sensitif.
5. Menjaga agar kegagalan jaringan tidak dianggap sebagai keberhasilan transaksi.

Service worker bukan pengganti autentikasi, otorisasi, atau validasi server.

## 6. Strategi Cache

Strategi cache harus dipilih berdasarkan jenis sumber daya.

| Jenis sumber daya | Strategi awal | Ketentuan |
|---|---|---|
| CSS, JavaScript, ikon, dan aset statis berversi | Cache-first atau strategi setara | Cache harus dapat diperbarui saat versi berubah |
| Halaman fallback offline | Precache | Harus tersedia setelah instalasi atau pemuatan awal yang berhasil |
| Halaman berisi data akun | Network-only atau strategi aman yang setara | Jangan menyajikan data akun lama dari cache bersama |
| Halaman chat dan klaim | Network-only | Informasi transaksi harus berasal dari server |
| Permintaan POST atau tindakan perubahan data | Network-only | Jangan mengulang otomatis tindakan transaksi |
| Hasil pencarian dan daftar laporan | Strategi jaringan sebagai pilihan awal | Cache data dinamis hanya jika aturan privasi dan konsistensi sudah disepakati |

Ketentuan tambahan:

- Jangan menggunakan cache publik untuk respons yang bersifat pribadi.
- Perhatikan header HTTP seperti `Cache-Control` dan `Vary` ketika mengatur cache.
- Jangan menyimpan isi chat atau token autentikasi di Cache Storage.
- Data sensitif yang tersimpan di browser harus diminimalkan.
- Strategi cache harus ditinjau kembali saat implementasi menyesuaikan arsitektur autentikasi Laravel.

## 7. Pengalaman Offline

Ketika koneksi internet tidak tersedia:

- Aplikasi menampilkan halaman fallback atau pesan offline yang jelas.
- Pengguna diberi tahu bahwa data dinamis mungkin tidak tersedia atau belum diperbarui.
- Pengguna dapat mencoba memuat ulang ketika koneksi kembali.
- Formulir atau tindakan transaksi tidak boleh dilaporkan berhasil jika server belum memberikan konfirmasi.

Pesan yang disarankan:

> Anda sedang offline. Beberapa informasi dan fitur belum dapat diakses. Hubungkan kembali perangkat ke internet untuk melanjutkan.

Halaman fallback tidak boleh menampilkan informasi pribadi dari sesi pengguna sebelumnya.

## 8. Tindakan yang Memerlukan Internet

Tindakan berikut harus memerlukan koneksi aktif ke server:

- Login dan registrasi.
- Verifikasi email.
- Membuat, mengubah, atau membatalkan laporan.
- Mengajukan klaim.
- Mengirim pesan chat.
- Mengonfirmasi serah terima.
- Melaporkan penyalahgunaan.
- Menjalankan tindakan moderasi.

Jika koneksi terputus saat tindakan dilakukan:

1. Tampilkan bahwa permintaan belum dapat dipastikan berhasil.
2. Jangan menganggap transaksi berhasil hanya berdasarkan interaksi pengguna.
3. Ketika memungkinkan, periksa kembali status terbaru dari server.
4. Hindari pengulangan otomatis terhadap permintaan yang dapat menghasilkan duplikasi atau efek ganda.

Untuk permintaan yang hasilnya tidak pasti akibat koneksi terputus, antarmuka harus membantu pengguna memeriksa status terkini sebelum mencoba lagi.

## 9. Instalasi Aplikasi

Aplikasi harus mendukung pengalaman instalasi pada browser dan perangkat yang kompatibel.

Persyaratan:

- Manifest tersedia dan valid.
- Ikon aplikasi tersedia.
- Aplikasi disajikan melalui HTTPS pada lingkungan deployment yang sesuai.
- Pengguna dapat membuka aplikasi melalui ikon yang terpasang.
- Jika browser mendukung prompt instalasi, antarmuka dapat memberikan petunjuk instalasi.
- Jika browser tidak mendukung prompt instalasi, pengguna tetap dapat mengikuti cara instalasi manual yang disediakan browser.

Jangan menampilkan tombol instalasi palsu jika browser tidak mendukung tindakan tersebut.

## 10. Pembaruan Aplikasi

Ketika versi baru tersedia:

- Cache lama yang tidak diperlukan harus dibersihkan secara aman.
- Cache baru harus memiliki penanda versi yang berbeda.
- Pembaruan service worker tidak boleh menghapus data transaksi di server.
- Jika pembaruan memerlukan pemuatan ulang halaman, berikan pengalaman yang jelas dan tidak mengganggu proses transaksi.
- Jangan menonaktifkan service worker secara tiba-tiba tanpa alasan teknis yang jelas.

Strategi final untuk aktivasi service worker dan pemuatan ulang ditentukan saat implementasi.

## 11. Keamanan dan Privasi

- Semua komunikasi autentikasi dan transaksi harus menggunakan HTTPS pada lingkungan produksi.
- Service worker hanya boleh meng-cache sumber daya yang telah ditinjau.
- Respons pribadi tidak boleh dimasukkan ke cache publik.
- Token autentikasi dan isi chat tidak boleh disimpan di Cache Storage.
- Server tetap menjadi sumber kebenaran untuk status laporan, klaim, dan serah terima.
- Logout harus mempertimbangkan risiko data pengguna yang tertinggal di browser.
- Cache aplikasi tidak boleh digunakan untuk melewati pemeriksaan autentikasi atau otorisasi.

## 12. Pengujian PWA

Pengujian minimum:

1. Memastikan manifest dapat diakses dan memiliki properti wajib.
2. Memastikan ikon aplikasi tersedia dan dapat dimuat.
3. Memastikan service worker berhasil didaftarkan.
4. Memastikan aset statis yang dipilih dapat dimuat dari cache.
5. Memastikan halaman fallback muncul ketika navigasi gagal karena offline.
6. Memastikan halaman chat dan klaim tidak menampilkan data pribadi dari cache yang tidak sesuai.
7. Memastikan tindakan transaksi tidak dilaporkan berhasil ketika server tidak dapat dijangkau.
8. Memastikan aplikasi dapat diperbarui setelah versi aset berubah.
9. Memastikan layout tetap dapat digunakan pada layar kecil dan desktop.
10. Memastikan perilaku dasar tetap aman setelah logout dan login menggunakan akun berbeda.

Hasil pengujian harus mencatat browser, perangkat atau ukuran layar, kondisi jaringan, hasil, dan masalah yang ditemukan.

## 13. Keputusan yang Belum Final

Hal-hal berikut harus dikonfirmasi sebelum implementasi:

1. Browser dan perangkat target minimum.
2. Warna tema dan ikon final.
3. Apakah daftar laporan publik boleh disimpan secara terbatas untuk akses offline.
4. Strategi final untuk cache aset dan navigasi.
5. Mekanisme pemberitahuan pembaruan service worker.
6. Apakah aplikasi menggunakan library PWA atau implementasi service worker sendiri.

Jangan mengambil keputusan yang berdampak pada privasi atau konsistensi transaksi tanpa meninjau dokumen proyek terkait.

## 14. Pedoman Implementasi

Sebelum menambahkan fitur PWA:

1. Baca dokumen proyek yang relevan, terutama `SYSTEM_DESIGN.md` dan `UI_UX_SPECIFICATION.md`.
2. Periksa dukungan PWA yang mungkin sudah tersedia di proyek.
3. Usulkan file dan perubahan yang diperlukan sebelum implementasi.
4. Pastikan strategi cache tidak bertentangan dengan autentikasi dan privasi.
5. Implementasikan secara bertahap dan uji kondisi online, offline, serta pemulihan koneksi.
6. Laporkan file yang berubah, pengujian yang dijalankan, hasil, dan keterbatasan.

**Prinsip utama:** PWA meningkatkan pengalaman akses aplikasi, tetapi tidak boleh mengorbankan keamanan data atau konsistensi transaksi.