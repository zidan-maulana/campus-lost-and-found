<?php

namespace Database\Seeders;

use App\Enums\AbuseReportStatus;
use App\Models\AbuseReport;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Aduan civitas contoh agar tab "Aduan & Moderasi" di dashboard admin
 * punya isi saat pengujian lokal. Idempoten terhadap kombinasi
 * (reporter, target, reason).
 */
class AbuseReportSeeder extends Seeder
{
    public function run(): void
    {
        $user = fn (string $email) => User::where('email', $email)->first();

        $reporter = $user('dinda.kirana@student.mercubuana.ac.id')
            ?? User::where('role', 'user')->first();

        if (! $reporter) {
            return;
        }

        // Ambil dua laporan berbeda sebagai target aduan.
        $reports = Report::query()->orderBy('id')->limit(2)->get();

        $rows = [];

        if ($reports->get(0)) {
            $rows[] = [
                'target_type' => 'report',
                'target_id' => (string) $reports[0]->id,
                'reason' => 'fake_report',
                'details' => 'Foto barang diambil dari mesin pencari dan tidak ada barang aslinya di pos keamanan.',
            ];
        }

        if ($reports->get(1)) {
            $rows[] = [
                'target_type' => 'report',
                'target_id' => (string) $reports[1]->id,
                'reason' => 'other',
                'details' => 'Deskripsi laporan memuat tautan mencurigakan yang mengarah ke situs luar kampus.',
            ];
        }

        $other = $user('rizky.pratama@student.mercubuana.ac.id');

        if ($other) {
            $rows[] = [
                'target_type' => 'user',
                'target_id' => (string) $other->id,
                'reason' => 'fraud_claim',
                'details' => 'Mengajukan klaim pada beberapa laporan temuan berbeda dalam rentang waktu singkat.',
            ];
        }

        foreach ($rows as $row) {
            AbuseReport::firstOrCreate(
                [
                    'reporter_id' => $reporter->id,
                    'target_type' => $row['target_type'],
                    'target_id' => $row['target_id'],
                    'reason' => $row['reason'],
                ],
                [
                    'details' => $row['details'],
                    'status' => AbuseReportStatus::Pending,
                ]
            );
        }
    }
}
