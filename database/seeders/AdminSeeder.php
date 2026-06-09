<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun Admin utama
        User::updateOrCreate(
            ['email' => 'admin@pnbp.go.id'],
            [
                'name'         => 'Administrator Utama',
                'phone_number' => '6281111222333',
                'password'     => Hash::make('password'),
                'role'         => UserRole::ADMIN,
            ]
        );

        // Buat akun Pelanggan dummy untuk testing
        User::updateOrCreate(
            ['email' => 'pelanggan@example.com'],
            [
                'name'         => 'Budi Pelanggan',
                'phone_number' => '6289999888777',
                'password'     => Hash::make('password'),
                'role'         => UserRole::CUSTOMER,
            ]
        );

        // Tambahkan beberapa Gedung dummy
        $buildings = [
            [
                'name'        => 'Gedung Serbaguna A',
                'description' => 'Kapasitas 500 orang, full AC, sound system',
                'daily_rate'  => 5000000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Aula Utama B',
                'description' => 'Kapasitas 200 orang, proyektor',
                'daily_rate'  => 2500000,
                'is_active'   => true,
            ],
            [
                'name'        => 'Ruang Rapat VIP',
                'description' => 'Kapasitas 30 orang, meja bundar',
                'daily_rate'  => 1000000,
                'is_active'   => true,
            ],
        ];

        foreach ($buildings as $b) {
            Building::updateOrCreate(['name' => $b['name']], $b);
        }
    }
}
