<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ItemCategorySeeder::class,
            UserSeeder::class,
            ReportSeeder::class,
            ClaimSeeder::class,
            NotificationSeeder::class,
            SavedReportSeeder::class,
        ]);
    }
}