<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Kategori barang — mengikuti kategori yang muncul di antarmuka.
     */
    public function run(): void
    {
        $categories = [
            ['Elektronik & HP', 'elektronik', 'Handphone, laptop, tablet, earphone, dan perangkat elektronik.', 'smartphone'],
            ['Tas & Ransel', 'tas', 'Tas, ransel, tas laptop, dan koper.', 'backpack'],
            ['Jam & Aksesori', 'aksesoris', 'Jam tangan, cincin, kalung, gelang, dan aksesori lainnya.', 'watch'],
            ['Dompet & Kartu', 'dompet', 'Dompet, kartu identitas, KTM, dan kartu lainnya.', 'wallet'],
            ['Pakaian & Sepatu', 'pakaian', 'Jaket, almamater, sepatu, dan pakaian lainnya.', 'shirt'],
            ['Dokumen & KTM', 'dokumen', 'Paspor, ijazah, KTM, berkas beasiswa, dan dokumen penting.', 'file-text'],
            ['Buku & Alat Tulis', 'buku', 'Buku kuliah, catatan, dan alat tulis.', 'book'],
            ['Kunci & Lainnya', 'lain', 'Kunci, gantungan, dan barang lainnya yang tidak termasuk kategori di atas.', 'key'],
        ];

        $order = 1;

        foreach ($categories as [$name, $slug, $description, $icon]) {
            ItemCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $description,
                    'icon' => $icon,
                    'is_active' => true,
                    'sort_order' => $order++,
                ]
            );
        }
    }
}