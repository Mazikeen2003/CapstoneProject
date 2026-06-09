<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangays = [
            'Banay-banay',
            'Banadero',
            'Bigaa',
            'Butong',
            'Casile',
            'Diezmo',
            'Gulod',
            'Halang',
            'Laguerta',
            'Lawa',
            'Lecheria',
            'Leismer',
            'Mamatid',
            'Marinig',
            'Niugan',
            'Pittland',
            'Pulo',
            'Sala',
        ];

        foreach ($barangays as $name) {
            DB::table('barangays')->insert([
                'barangay_name' => $name,
                'boundary_geojson' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

