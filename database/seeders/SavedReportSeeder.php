<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavedReportSeeder extends Seeder
{
    public function run(): void
    {
        $dinda = User::where('email', 'dinda.kirana@student.mercubuana.ac.id')->firstOrFail();

        foreach (['Dompet Kulit & KTM Mahasiswa', 'Jaket Almamater UMB'] as $title) {
            $report = Report::where('title', $title)->first();

            if (! $report) {
                continue;
            }

            DB::table('saved_reports')->updateOrInsert(
                ['user_id' => $dinda->id, 'report_id' => $report->id],
                ['created_at' => now(), 'updated_at' => now()],
            );
        }
    }
}