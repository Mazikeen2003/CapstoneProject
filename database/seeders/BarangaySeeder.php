<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

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
            Barangay::query()->updateOrCreate(
                ['barangay_name' => $name],
                ['boundary_geojson' => null]
            );
        }
    }
}