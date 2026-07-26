<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            'Baclaran',
            'Banaybanay',
            'Banlic',
            'Barangay Dos',
            'Barangay Tres',
            'Barangay Uno',
            'Bigaa',
            'Butong',
            'Casile',
            'Diezmo',
            'Gulod',
            'Mamatid',
            'Marinig',
            'Niugan',
            'Pittland',
            'Pulo',
            'Sala',
            'San Isidro',
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