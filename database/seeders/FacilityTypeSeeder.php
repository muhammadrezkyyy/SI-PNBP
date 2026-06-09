<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Gedung',
            'Kelas',
            'Ruang Meeting',
        ];

        foreach ($types as $type) {
            \App\Models\FacilityType::firstOrCreate(['name' => $type]);
        }
    }
}
