# Development Plan — Campus Lost & Found

**Status:** Draft v0.1  
**Proyek:** UTS Pemrograman Web Enterprise  
**Stack:** Laravel, Blade, MySQL, PWA  
**Metode:** Pengembangan bertahap dengan validasi pada setiap fase

---

## 1. Tujuan

Dokumen ini menetapkan urutan pengembangan Campus Lost & Found dari persiapan lingkungan hingga pengujian akhir.

Pengembangan dilakukan secara bertahap agar setiap bagian dapat diperiksa sebelum pekerjaan berikutnya dimulai. Antigravity bertindak sebagai coding agent yang membantu implementasi berdasarkan dokumentasi proyek dan persetujuan pengguna.

Dokumen ini merupakan rencana, bukan izin untuk langsung menjalankan semua tahap.

## 2. Prinsip Pengembangan

1. Dokumentasi proyek menjadi acuan utama implementasi.
2. Perubahan dilakukan dalam lingkup kecil dan dapat diperiksa.
3. Setiap fase harus memiliki hasil yang dapat diverifikasi.
4. Jangan membangun fitur yang belum ditetapkan dalam PRD dan SRS.
5. Jangan mengubah keputusan bisnis secara sepihak.
6. Jangan menghapus atau menimpa file penting tanpa persetujuan.
7. Jalankan pengujian yang relevan setelah perubahan.
8. Catat keputusan, kendala, dan kemajuan proyek.
9. Jangan melakukan commit atau push ke GitHub tanpa instruksi pengguna.
10. Jangan memulai implementasi sebelum pengguna menyetujui tahap pengembangan.

## 3. Gambaran Fase

| Fase | Nama | Hasil utama |
|---|---|---|
| 0 | Dokumentasi dan keputusan | Dokumen proyek siap menjadi acuan |
| 1 | Pemeriksaan lingkungan dan inisialisasi | Proyek Laravel siap dijalankan |
| 2 | Fondasi aplikasi | Konfigurasi, layout, autentikasi, dan struktur dasar |
| 3 | Database dan laporan barang | Migration serta fitur laporan |
| 4 | Proses klaim | Pengajuan dan pengelolaan klaim |
| 5 | Chat dan serah terima | Komunikasi privat dan penyelesaian transaksi |
| 6 | Aktivitas, notifikasi, dan moderasi | Pemantauan aktivitas serta pengelolaan penyalahgunaan |
| 7 | PWA | Instalasi, cache aman, dan fallback offline |
| 8 | Integrasi dan pengujian | Pemeriksaan alur end-to-end |
| 9 | Persiapan demonstrasi dan penyerahan | Aplikasi, dokumentasi, dan bukti pengujian siap |

Fase dapat disesuaikan berdasarkan kondisi repository, kemampuan lingkungan, dan waktu pengerjaan. Perubahan urutan harus dijelaskan sebelum diterapkan.

## 4. Fase 0 — Dokumentasi dan Keputusan

### Tujuan

Memastikan kebutuhan, desain, struktur database, dan batasan proyek telah dipahami.

### Aktivitas

- Meninjau `AGENTS.md`.
- Meninjau PRD, SRS, SYSTEM_DESIGN, DATABASE_DESIGN, UI_UX_SPECIFICATION, dan PWA_SPECIFICATION.
- Memeriksa konsistensi antar dokumen.
- Mengidentifikasi keputusan terbuka yang memengaruhi implementasi.
- Memperbarui `DECISIONS_LOG.md` ketika keputusan telah disetujui.

### Kriteria selesai

- Dokumen dapat diakses dari repository.
- Konflik kebutuhan telah dicatat.
- Keputusan yang menghalangi implementasi telah diklarifikasi atau disetujui sebagai batasan sementara.

### Gerbang persetujuan

Jangan melanjutkan ke implementasi hanya karena dokumen sudah tersedia. Minta persetujuan pengguna untuk memulai Fase 1.

## 5. Fase 1 — Pemeriksaan Lingkungan dan Inisialisasi

### Tujuan

Memastikan lingkungan pengembangan siap dan struktur Laravel dapat dijalankan.

### Aktivitas

- Memeriksa struktur repository dan status Git.
- Memeriksa versi PHP, Composer, Node.js, npm, dan MySQL atau layanan database yang digunakan.
- Memeriksa apakah proyek Laravel sudah tersedia.
- Mengidentifikasi dependensi yang diperlukan.
- Mengusulkan langkah inisialisasi jika proyek belum memiliki aplikasi Laravel.
- Menyiapkan konfigurasi lingkungan tanpa membocorkan kredensial.

### Kriteria selesai

- Versi perangkat lunak dan kebutuhan lingkungan terdokumentasi.
- Aplikasi Laravel dapat dijalankan pada lingkungan lokal.
- Konfigurasi database dapat diuji.
- File `.env` dan rahasia lain tidak masuk ke repository.

### Gerbang persetujuan

Jika inisialisasi memerlukan pembuatan, penggantian, atau penghapusan file, jelaskan rencana dan minta persetujuan sebelum tindakan yang berisiko.

## 6. Fase 2 — Fondasi Aplikasi

### Tujuan

Membangun fondasi yang diperlukan oleh seluruh fitur.

### Aktivitas

- Menyiapkan konfigurasi aplikasi.
- Menyiapkan layout utama Blade.
- Membuat navigasi dasar dan komponen UI yang dapat digunakan kembali.
- Menyiapkan autentikasi.
- Menyiapkan mekanisme verifikasi email sesuai keputusan proyek.
- Menyiapkan pembatasan akses berdasarkan peran dan status akun.
- Menyiapkan penanganan validasi dan pesan kesalahan.

### Kriteria selesai

- Pengguna dapat melakukan alur autentikasi yang telah disepakati.
- Akses ke halaman terbatas diperiksa di server.
- Layout dasar dapat digunakan pada desktop dan ponsel.
- Pengujian autentikasi dan otorisasi dasar lulus.

## 7. Fase 3 — Database dan Laporan Barang

### Tujuan

Mengimplementasikan struktur data dan fitur pelaporan.

### Aktivitas

- Menyusun migration dan relasi database berdasarkan desain yang disetujui (termasuk rekomendasi kolom `cancelled_by` pada `claims`).
- Menyiapkan model dan validasi data.
- Membangun pembuatan laporan barang hilang dan ditemukan (memerlukan login dan verifikasi email kampus).
- Membangun daftar, pencarian, dan filter laporan yang dapat diakses oleh pengunjung tanpa login (*guest*) maupun pengguna terdaftar.
- Membangun halaman detail laporan barang (informasi umum publik dapat diakses tanpa login).
- Membangun pengeditan dan pembatalan laporan dengan pemeriksaan kepemilikan serta status.
- Menangani unggahan foto sesuai batas ukuran dan format yang disepakati.

### Kriteria selesai

- Struktur database dapat dibuat pada lingkungan pengujian.
- Pengunjung tanpa login dapat melihat daftar, detail umum laporan, serta melakukan pencarian dan penyaringan.
- Pengguna terverifikasi dapat membuat, mengedit, dan membatalkan laporan miliknya sesuai aturan status.
- Pencarian dan filter menghasilkan data yang sesuai.
- Pengguna tidak dapat mengubah laporan milik pengguna lain.
- Aturan pembatalan dan pengeditan ditegakkan di server.
- Pengujian fitur laporan lulus.

## 8. Fase 4 — Proses Klaim

### Tujuan

Mengimplementasikan pengajuan klaim terhadap laporan barang ditemukan.

### Aktivitas

- Membuat pengajuan klaim pada laporan barang temuan (memvalidasi syarat 3 lapis: login, email resmi kampus terverifikasi, dan akun aktif).
- Menampilkan klaim kepada pihak yang berwenang (penemu dan pengklaim).
- Menerapkan aturan satu klaim yang melanjutkan proses serah terima (`in_handover`) per laporan.
- Menerapkan aturan pembatasan satu klaim aktif per pengguna dan pelepasan hak klaim seketika saat klaim mencapai status terminal (`completed`, `rejected`, `cancelled`, `expired`).
- Menerapkan fitur penolakan klaim oleh penemu dan pembatalan klaim oleh pengklaim.
- Menerapkan pencegahan klaim berulang pada laporan yang sama bagi pengguna yang pernah ditolak (`rejected`) atau membatalkan sendiri klaimnya (`cancelled` oleh pengklaim).
- Menerapkan scheduled task untuk notifikasi klaim 72 jam tanpa respons penemu serta kedaluwarsa otomatis (`expired`) setelah 120 jam (5 hari) sejak klaim dibuat (`created_at`).
- Menjamin idempotensi proses kedaluwarsa agar tidak mengubah status klaim yang telah dibatalkan atau ditindaklanjuti secara sah.
- Memastikan klaim berstatus `expired` membebaskan hak klaim aktif pengguna dan mengizinkan pengklaim mengajukan klaim kembali pada laporan yang sama jika status laporan masih `open`.
- Memastikan klaim-klaim mengantre (`submitted`) tetap bertahan dalam status `submitted` dan tidak terhapus saat klaim lain sedang dalam serah terima atau saat serah terima tersebut dibatalkan (rekomendasi DEC-025).
- Menyimpan riwayat perubahan status klaim.
- Menangani kondisi persaingan (*race condition*) ketika beberapa pengguna mengajukan klaim secara bersamaan.

### Kriteria selesai

- Pengunjung tanpa login dan pengguna tanpa verifikasi email kampus ditolak saat mengajukan klaim.
- Klaim hanya dapat diajukan pada laporan yang berstatus `open`.
- Pengguna tidak dapat mengajukan klaim untuk dirinya sendiri jika ia adalah pembuat laporan.
- Aturan pembatasan satu klaim aktif per pengguna dan pelepasan status terminal teruji secara otomatis.
- Klaim-klaim mengantre (`submitted`) teruji tetap bertahan dalam status `submitted` saat serah terima dibatalkan dan laporan kembali `open`.
- Penolakan oleh penemu dan pembatalan oleh pengklaim mengakhiri klaim ke status terminal yang benar.
- Scheduled task kedaluwarsa klaim pada jam ke-120 teruji aman, idempoten, dan tidak menimpa status klaim yang sudah terminal.
- Pencegahan klaim berulang pada laporan yang sama teruji untuk status `rejected` dan `cancelled` oleh pengklaim, serta izin klaim ulang teruji untuk status `expired`.
- Perubahan status dan hak akses terverifikasi.

## 9. Fase 5 — Chat dan Serah Terima

### Tujuan

Mendukung komunikasi privat dan penyelesaian transaksi antara penemu dan pengklaim.

### Aktivitas

- Membuat tampilan percakapan berbasis teks.
- Memastikan hanya dua pihak terkait yang dapat mengakses chat.
- Mengatur pengiriman dan penampilan pesan (chat aktif hanya pada klaim `submitted` dan `in_handover`).
- Menerapkan penutupan chat (hanya-baca) setelah klaim mencapai status terminal.
- Menyimpan konfirmasi serah terima dari masing-masing pihak dengan constraint unik pencegah konfirmasi ganda.
- Menerapkan fitur pembatalan serah terima yang terhenti dengan input alasan pembatalan dan pencatatan aktor pembatal (`cancelled_by`).
- Mengembalikan status laporan menjadi `open` setelah pembatalan serah terima (selama tidak dibatalkan pemilik/disembunyikan admin), dan membiarkan klaim mengantre tetap siap dipilih penemu.
- Menegakkan aturan klaim ulang: pengklaim dilarang klaim ulang jika ia sendiri yang membatalkan serah terima, namun diizinkan klaim kembali jika penemu yang membatalkan serah terima.
- Memperbarui status klaim menjadi `completed` dan laporan menjadi `resolved` setelah kedua pihak konfirmasi, serta mengubah otomatis seluruh klaim mengantre menjadi `cancelled` tanpa sanksi.
- Menyiapkan mekanisme penghapusan pesan otomatis sesuai kebijakan retensi yang disepakati.

### Kriteria selesai

- Pesan tidak dapat diakses oleh pihak yang tidak berwenang.
- Pesan baru tidak dapat dikirim setelah klaim mencapai status terminal.
- Transaksi hanya selesai setelah kedua pihak mengonfirmasi serah terima.
- Pembatalan serah terima yang mandek berhasil mengakhiri klaim, mencatat aktor pembatal (`cancelled_by`), dan mengembalikan laporan ke status `open`.
- Aturan izin/larangan klaim ulang pasca pembatalan serah terima teruji sesuai pihak pembatal.
- Seluruh klaim mengantre otomatis berubah menjadi `cancelled` saat serah terima berhasil selesai.
- Riwayat transaksi tetap tersedia setelah pesan dihapus.
- Mekanisme retensi pesan dan pengecualian moderasi diuji.

## 10. Fase 6 — Aktivitas, Notifikasi, dan Moderasi

### Tujuan

Mendukung pemantauan aktivitas pengguna dan penanganan penyalahgunaan.

### Aktivitas

- Membuat halaman Aktivitas Saya.
- Menampilkan laporan, klaim, dan riwayat transaksi milik pengguna.
- Membuat notifikasi dalam aplikasi.
- Membuat fitur pelaporan penyalahgunaan.
- Membuat dashboard moderasi.
- Menerapkan tindakan penonaktifan akun berbasis peran (penonaktifan pengklaim membatalkan klaimnya dan membuka laporan jika sedang serah terima; penonaktifan penemu menyembunyikan laporannya `hidden` dan membatalkan klaim mengantre tanpa sanksi pengklaim) sesuai rekomendasi DEC-026.
- Menerapkan reaktivasi akun yang tidak menghidupkan kembali data terminal secara otomatis (*immutability*).
- Mencatat tindakan administrator beserta alasannya di `moderation_actions`.
- Menerapkan pembatasan akses administrator.

### Kriteria selesai

- Pengguna hanya melihat aktivitas miliknya sendiri.
- Notifikasi ditujukan kepada penerima yang benar.
- Laporan penyalahgunaan dapat ditinjau administrator.
- Dampak penonaktifan akun pengklaim dan penemu teruji sesuai spesifikasi peran.
- Reaktivasi akun teruji tidak memicu pengaktifan kembali transaksi masa lalu.
- Tindakan moderasi tercatat dan mengikuti kewenangan yang ditetapkan.
- Administrator tidak dapat menentukan kepemilikan barang dalam klaim.

## 11. Fase 7 — PWA

### Tujuan

Menambahkan kemampuan instalasi dan pengalaman offline yang aman.

### Aktivitas

- Membuat web app manifest.
- Menyiapkan ikon aplikasi.
- Menambahkan service worker.
- Menentukan strategi cache berdasarkan PWA_SPECIFICATION.
- Membuat fallback offline.
- Menangani pembaruan service worker.
- Memastikan halaman dan transaksi sensitif tidak menggunakan cache yang tidak aman.

### Kriteria selesai

- Manifest dan service worker dapat diakses serta berfungsi.
- Aset statis yang dipilih dapat dimuat dari cache.
- Fallback offline berfungsi.
- Tindakan transaksi tidak dilaporkan berhasil ketika server tidak dapat dijangkau.
- Pengujian cache, logout, dan pemulihan koneksi lulus.

## 12. Fase 8 — Integrasi dan Pengujian

### Tujuan

Memastikan seluruh fitur utama dapat bekerja bersama.

### Aktivitas

- Menjalankan seluruh pengujian otomatis yang tersedia.
- Menguji alur utama dari awal hingga akhir.
- Menguji hak akses pengguna dan administrator.
- Menguji validasi formulir dan penanganan kegagalan.
- Menguji kondisi koneksi terputus.
- Menguji tampilan pada ukuran layar berbeda.
- Memeriksa log dan memperbaiki masalah yang ditemukan.
- Memastikan dokumentasi sesuai dengan perilaku aplikasi.

### Skenario pengujian utama

1. Pengguna mendaftar dan menyelesaikan verifikasi.
2. Pengguna membuat laporan barang hilang atau ditemukan.
3. Pengguna lain mencari laporan dan membuka detailnya.
4. Pengguna mengajukan klaim terhadap barang ditemukan.
5. Kedua pihak berkomunikasi melalui chat.
6. Kedua pihak mengonfirmasi serah terima.
7. Sistem menutup proses terkait dan mempertahankan metadata transaksi.
8. Pesan dihapus sesuai kebijakan retensi.
9. Pengguna melaporkan konten yang melanggar aturan.
10. Administrator meninjau dan mencatat tindakan moderasi.
11. Pengguna mencoba mengakses data yang bukan haknya.
12. Aplikasi diuji dalam kondisi offline dan setelah koneksi pulih.
13. Administrator menonaktifkan akun pengklaim atau penemu; sistem menguji pembatalan klaim aktif, pengembalian laporan serah terima ke status `open`, penyembunyian laporan penemu ke `hidden`, dan pelepasan hak klaim pengguna yang tidak bersalah tanpa sanksi (DEC-026).
14. Administrator mengaktifkan kembali akun (`status = 'active'`); sistem memverifikasi bahwa klaim, serah terima, dan laporan masa lalu tetap berada pada status terminal (*immutable*) tanpa ada pemulihan data otomatis.

### Kriteria selesai

- Skenario kritis telah diuji.
- Tidak ada masalah kritis yang belum ditangani.
- Hasil pengujian dan keterbatasan dicatat.
- Masalah yang belum selesai disampaikan secara transparan.

## 13. Fase 9 — Persiapan Demonstrasi dan Penyerahan

### Tujuan

Mempersiapkan proyek untuk demonstrasi UTS dan pemeriksaan akademik.

### Aktivitas

- Menyiapkan instruksi instalasi dan menjalankan aplikasi.
- Menyiapkan konfigurasi lingkungan tanpa memasukkan rahasia.
- Menyiapkan data demo yang tidak menggunakan data pribadi nyata.
- Menyusun skenario demonstrasi fitur utama.
- Memastikan README menjelaskan kebutuhan dan langkah menjalankan aplikasi.
- Mencatat fitur yang selesai, fitur yang belum selesai, dan keterbatasan.
- Menyiapkan bukti pengujian jika diperlukan.

### Kriteria selesai

- Aplikasi dapat dijalankan mengikuti instruksi yang terdokumentasi.
- Skenario demonstrasi dapat diikuti.
- Dokumentasi sesuai dengan kondisi implementasi aktual.
- Keterbatasan proyek diketahui oleh tim.

## 14. Pembagian Pekerjaan Tim

Pembagian tugas akhir harus disesuaikan dengan jumlah anggota, kemampuan, dan kesepakatan tim.

Sebagai pendekatan awal, pekerjaan dapat dikelompokkan menjadi:

- **Area A — Fondasi dan autentikasi:** konfigurasi Laravel, autentikasi, layout, dan otorisasi.
- **Area B — Laporan dan pencarian:** database laporan, formulir, daftar, filter, dan detail.
- **Area C — Klaim dan transaksi:** pengajuan klaim, chat, konfirmasi serah terima, dan riwayat.
- **Area D — Integrasi dan kualitas:** notifikasi, moderasi, PWA, pengujian, dan dokumentasi.

Pembagian tersebut bukan penetapan pemilik tugas. Tim harus menyepakati penanggung jawab, menghindari perubahan file yang bertabrakan, dan mengintegrasikan pekerjaan melalui Git secara terkontrol.

## 15. Manajemen Risiko

| Risiko | Penanganan |
|---|---|
| Kebutuhan berubah di tengah implementasi | Perbarui dokumen dan catat keputusan |
| Ruang lingkup terlalu besar | Prioritaskan alur utama sesuai waktu UTS |
| Aturan klaim tidak jelas | Klarifikasi sebelum implementasi klaim |
| Akses chat tidak aman | Uji otorisasi pada setiap permintaan |
| Serah terima tidak konsisten | Gunakan transaksi database dan pengujian |
| Cache menampilkan data sensitif | Gunakan strategi cache yang aman dan uji pergantian akun |
| Dependensi atau lingkungan bermasalah | Catat versi dan uji instalasi secara bertahap |
| Perubahan agent merusak file lain | Periksa diff, jalankan tes, dan gunakan Git secara terkontrol |
| Waktu pengerjaan terbatas | Fokus pada alur utama sebelum penyempurnaan non-esensial |

## 16. Pelaporan Kemajuan

Setelah menyelesaikan satu unit pekerjaan, coding agent harus melaporkan:

1. Fase dan unit pekerjaan yang dikerjakan.
2. File yang dibuat atau diubah.
3. Perilaku yang berhasil diimplementasikan.
4. Perintah pengujian yang dijalankan.
5. Hasil pengujian aktual.
6. Masalah atau keterbatasan yang masih ada.
7. Keputusan yang membutuhkan persetujuan pengguna.
8. Langkah berikutnya yang disarankan.

Jangan menyatakan pengujian berhasil jika belum dijalankan. Jangan menyatakan fitur selesai jika baru berupa tampilan tanpa logika yang diperlukan.

## 17. Aturan Perubahan Lingkup

Jika coding agent menemukan kebutuhan tambahan atau konflik antar dokumen:

1. Jelaskan masalah dan dampaknya.
2. Tunjukkan dokumen atau aturan yang terdampak.
3. Ajukan perubahan yang disarankan.
4. Tunggu persetujuan jika perubahan memengaruhi kebutuhan, keamanan, data, atau alur bisnis.
5. Perbarui dokumen terkait setelah keputusan disepakati.

## 18. Status Proyek Saat Dokumen Dibuat

Proyek sedang berada pada tahap penyusunan dokumentasi.

Penyelesaian dokumen tidak berarti aplikasi Laravel telah dibuat, database telah diinisialisasi, atau fitur telah diimplementasikan.

Fase implementasi baru dimulai setelah pengguna meninjau dokumen dan memberikan persetujuan secara eksplisit.

**Prinsip utama:** bangun aplikasi secara bertahap, uji setiap bagian, dan pertahankan konsistensi antara dokumentasi, kode, serta perilaku aktual aplikasi.