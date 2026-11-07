<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Meniru data contoh yang sebelumnya hardcoded di antarmuka,
     * agar katalog tidak kosong dan alur fitur dapat dicoba.
     * Idempoten terhadap judul laporan.
     */
    public function run(): void
    {
        $user = fn (string $email) => User::where('email', $email)->firstOrFail();
        $category = fn (string $slug) => \App\Models\ItemCategory::where('slug', $slug)->firstOrFail();

        $reports = [
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'elektronik',
                'type' => ReportType::Lost, 'status' => ReportStatus::Open,
                'title' => 'Samsung SM-A135F', 'brand' => 'Samsung Galaxy A13',
                'description' => 'Smartphone Samsung warna hitam, terpasang casing transparan dengan stiker logo Informatika di bagian belakang. Layar dalam keadaan terkunci.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Gedung Tower Lt. 7 R. 702',
                'incident_date' => '2026-06-20', 'incident_time' => '14.30 WIB (Siang)',
                'storage_note' => 'Disimpan Sendiri', 'image' => 'images/samples/real/samsung-a13.jpg',
            ],
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'dokumen',
                'type' => ReportType::Lost, 'status' => ReportStatus::Open,
                'title' => 'Paspor & Berkas Beasiswa', 'brand' => 'Map Biru Transparan',
                'description' => 'Map transparan biru berisi paspor, slip beasiswa, transkrip nilai, dan surat rekomendasi dosen. Di dalamnya juga ada kartu nama.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Ruang BAAK Gedung Tower Lt. 2',
                'incident_date' => '2026-01-10', 'incident_time' => '11.00 WIB (Pagi)',
                'storage_note' => 'Disimpan Sendiri', 'image' => 'images/samples/real/paspor-dokumen.jpg',
            ],
            [
                'owner' => 'rizky.pratama@student.mercubuana.ac.id', 'category' => 'lain',
                'type' => ReportType::Found, 'status' => ReportStatus::Open,
                'title' => 'Tasbih & Gelang Manik Merah', 'brand' => 'Tasbih Kayu Kuning',
                'description' => 'Tasbih kayu kuning dengan gelang manik-manik merah menyatu. Ditemukan di area musala kampus.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Musala Gedung D Lantai 3',
                'incident_date' => '2026-05-02', 'incident_time' => '12.15 WIB (Siang)',
                'storage_note' => 'Dititipkan di Pengurus Musala', 'image' => 'images/samples/real/tasbih-manik.jpg',
            ],
            [
                'owner' => 'rizky.pratama@student.mercubuana.ac.id', 'category' => 'lain',
                'type' => ReportType::Found, 'status' => ReportStatus::Open,
                'title' => 'Kunci Motor Honda & Remote', 'brand' => 'Gantungan Kuning',
                'description' => 'Kunci motor Honda dengan remote alarm dan gantungan karet kuning bertuliskan nama. Ditemukan di area parkir.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Parkir Barat',
                'incident_date' => '2026-05-20', 'incident_time' => '16.00 WIB (Sore)',
                'storage_note' => 'Dititipkan di Pos Keamanan', 'image' => 'images/samples/real/kunci-motor.jpg',
            ],
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'aksesoris',
                'type' => ReportType::Lost, 'status' => ReportStatus::Open,
                'title' => 'Cincin Emas Berlian', 'brand' => 'Perhiasan Emas Kuning',
                'description' => 'Cincin emas sepasang model pertunangan dengan ukiran inisial halus di lingkar dalam. Terjatuh di sekitar parkiran.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Parkir Barat Kampus Meruya',
                'incident_date' => '2026-01-15', 'incident_time' => 'Siang saat menuju kendaraan',
                'storage_note' => 'Disimpan Sendiri', 'image' => 'images/samples/real/cincin-emas.jpg',
            ],
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'elektronik',
                'type' => ReportType::Lost, 'status' => ReportStatus::Open,
                'title' => 'Tablet Samsung Tab S8', 'brand' => 'Samsung Galaxy Tab S8',
                'description' => 'Tablet warna graphite abu-abu tua dengan book cover magnetic hitam dan S-Pen menempel di punggung tablet.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Gedung E Lt. 3 Ruang Diskusi',
                'incident_date' => '2026-01-14', 'incident_time' => 'Sore (± 16.00 WIB)',
                'storage_note' => 'Disimpan Sendiri', 'image' => 'images/samples/real/tablet-tab-s8.jpg',
            ],
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'tas',
                'type' => ReportType::Lost, 'status' => ReportStatus::InHandover,
                'title' => 'Tas Laptop Lenovo ThinkPad', 'brand' => 'ThinkPad Essential Backpack',
                'description' => 'Ransel laptop hitam bergaris merah khas ThinkPad, di dalamnya ada charger laptop 65W dan buku catatan kuliah.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Lab Komputer Gedung Tower Lt. 4',
                'incident_date' => '2026-01-18', 'incident_time' => '13.00 WIB usai praktikum',
                'storage_note' => 'Dititipkan di Lab Komputer Tower', 'image' => 'images/samples/real/tas-laptop.jpg',
            ],
            [
                'owner' => 'ahmad.fauzi@student.mercubuana.ac.id', 'category' => 'dompet',
                'type' => ReportType::Lost, 'status' => ReportStatus::Open,
                'title' => 'Dompet Kulit & KTM Mahasiswa', 'brand' => 'Dompet Kulit Cokelat',
                'description' => 'Dompet kulit warna cokelat tua berisi KTM UMB, e-KTP, kartu debit bank, dan uang tunai.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Kantin Gedung D Lantai 1',
                'incident_date' => '2026-02-08', 'incident_time' => 'Istirahat makan siang',
                'storage_note' => 'Dititipkan di Pengelola Kantin', 'image' => 'images/samples/real/dompet-ktm.jpg',
            ],
            [
                'owner' => 'siti.rahma@student.mercubuana.ac.id', 'category' => 'pakaian',
                'type' => ReportType::Found, 'status' => ReportStatus::Open,
                'title' => 'Jaket Almamater UMB', 'brand' => 'Jas Almamater Resmi UMB',
                'description' => 'Jaket almamater biru navy ukuran L dengan badge bordir logo Universitas Mercu Buana di dada kiri. Tertinggal di sandaran kursi.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Perpustakaan Pusat Lantai 2, Meja Baca Mandiri',
                'incident_date' => '2026-01-22', 'incident_time' => '17.00 WIB (Sore)',
                'storage_note' => 'Dititipkan di Staf Perpustakaan', 'image' => 'images/samples/real/jaket-almamater.jpg',
            ],
            [
                'owner' => 'dinda.kirana@student.mercubuana.ac.id', 'category' => 'elektronik',
                'type' => ReportType::Found, 'status' => ReportStatus::Open,
                'title' => 'Earphone TWS Anker Soundcore', 'brand' => 'Anker Soundcore R50i',
                'description' => 'Charging case TWS warna hitam matte beserta sepasang earphone di dalamnya. Kondisi baterai masih menyala.',
                'campus_location' => 'Kampus Meruya', 'location' => 'Gazebo Taman Depan Gedung Tower',
                'incident_date' => '2026-02-02', 'incident_time' => '15.45 WIB (Sore)',
                'storage_note' => 'Dititipkan di Pos Keamanan Kampus', 'image' => 'images/samples/real/earphone-tws.jpg',
            ],
        ];

        foreach ($reports as $data) {
            Report::updateOrCreate(
                ['title' => $data['title'], 'type' => $data['type']->value],
                [
                    'user_id' => $user($data['owner'])->id,
                    'category_id' => $category($data['category'])->id,
                    'type' => $data['type'],
                    'status' => $data['status'],
                    'title' => $data['title'],
                    'brand' => $data['brand'],
                    'description' => $data['description'],
                    'campus_location' => $data['campus_location'],
                    'location' => $data['location'],
                    'incident_date' => $data['incident_date'],
                    'incident_time' => $data['incident_time'],
                    'storage_note' => $data['storage_note'],
                    'image_path' => $data['image'],
                ]
            );
        }
    }
}