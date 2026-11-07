<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun demo untuk pengembangan & pengujian lokal.
     * Password default: password (jangan dipakai di produksi).
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin FindUMB', 'email' => 'admin@mercubuana.ac.id',
                'nim' => null, 'role' => UserRole::Admin,
            ],
            [
                'name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@student.mercubuana.ac.id',
                'nim' => '415201234', 'role' => UserRole::User,
            ],
            [
                'name' => 'Dinda Kirana', 'email' => 'dinda.kirana@student.mercubuana.ac.id',
                'nim' => '415202345', 'role' => UserRole::User,
            ],
            [
                'name' => 'Rizky Pratama', 'email' => 'rizky.pratama@student.mercubuana.ac.id',
                'nim' => '415203456', 'role' => UserRole::User,
            ],
            [
                'name' => 'Siti Rahma', 'email' => 'siti.rahma@student.mercubuana.ac.id',
                'nim' => '415204567', 'role' => UserRole::User,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nim' => $data['nim'],
                    'password' => Hash::make('password'),
                    'role' => $data['role'],
                    'status' => UserStatus::Active,
                    'email_verified_at' => now(),
                    'last_seen_at' => now(),
                ]
            );
        }
    }
}