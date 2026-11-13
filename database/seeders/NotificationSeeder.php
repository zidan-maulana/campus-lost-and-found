<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = fn (string $email) => User::where('email', $email)->firstOrFail();
        $report = fn (string $title) => Report::where('title', $title)->firstOrFail();

        $notifications = [
            [
                // Penemu earphone menerima klaim dari Rizky.
                'user' => $user('dinda.kirana@student.mercubuana.ac.id'),
                'type' => 'claim', 'title' => 'Pengajuan Klaim: Earphone TWS Anker Soundcore',
                'body' => 'Rizky Pratama (NIM 4153***) mengajukan klaim kepemilikan atas barang temuan Anda.',
                'target' => '/messages?claim='.$report('Earphone TWS Anker Soundcore')->claims->first()->id,
                'at' => now()->subHours(5), 'read' => false,
            ],
            [
                'user' => $user('rizky.pratama@student.mercubuana.ac.id'),
                'type' => 'system', 'title' => 'Klaim Berhasil Diajukan',
                'body' => 'Klaim Anda pada "Earphone TWS Anker Soundcore" menunggu respons penemu.',
                'target' => '/my-reports', 'at' => now()->subHours(5), 'read' => true,
            ],
            [
                // Penemu tas laptop menerima klaim dari Siti.
                'user' => $user('ahmad.fauzi@student.mercubuana.ac.id'),
                'type' => 'claim', 'title' => 'Pengajuan Klaim: Tas Laptop Lenovo ThinkPad',
                'body' => 'Siti Rahma (NIM 4152***) mengajukan klaim kepemilikan atas barang temuan Anda.',
                'target' => '/messages?claim='.$report('Tas Laptop Lenovo ThinkPad')->claims->first()->id,
                'at' => now()->subHours(30), 'read' => false,
            ],
            [
                'user' => $user('siti.rahma@student.mercubuana.ac.id'),
                'type' => 'handover', 'title' => 'Klaim Disetujui: Tas Laptop Lenovo ThinkPad',
                'body' => 'Penemu menyetujui klaim Anda dan mengajak proses serah terima. Silakan diskusikan waktu dan lokasi melalui chat.',
                'target' => '/messages?claim='.$report('Tas Laptop Lenovo ThinkPad')->claims->first()->id,
                'at' => now()->subHours(24), 'read' => false,
            ],
            [
                'user' => $user('siti.rahma@student.mercubuana.ac.id'),
                'type' => 'message', 'title' => 'Pesan Baru dari Ahmad Fauzi',
                'body' => '"Siap, ketemu di lobi Tower jam 10. Nanti saya konfirmasi saat barang diserahkan."',
                'target' => '/messages?claim='.$report('Tas Laptop Lenovo ThinkPad')->claims->first()->id,
                'at' => now()->subHours(20), 'read' => true,
            ],
            [
                'user' => $user('dinda.kirana@student.mercubuana.ac.id'),
                'type' => 'warning', 'title' => 'Klaim Ditolak: Cincin Emas Berlian',
                'body' => 'Penemu menolak pengajuan klaim Anda. Anda tidak dapat mengajukan klaim ulang pada laporan yang sama.',
                'target' => '/my-reports', 'at' => now()->subDays(3), 'read' => true,
            ],
        ];

        foreach ($notifications as $n) {
            AppNotification::updateOrCreate(
                [
                    'user_id' => $n['user']->id,
                    'title' => $n['title'],
                ],
                [
                    'type' => $n['type'],
                    'body' => $n['body'],
                    'target_url' => $n['target'],
                    'created_at' => $n['at'],
                    'updated_at' => $n['at'],
                    'read_at' => $n['read'] ? null : null,
                ]
            );
        }
    }
}