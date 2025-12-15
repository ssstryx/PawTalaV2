<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    public function run()
    {
        $barangays = [
            'Bucayao', 'Biga', 'San Isidro', 'Calero', 'Ibaba', 
            'Ilaya', 'Poblacion', 'Santa Rita', 'Tibag'
            // ... add all barangays here
        ];

        foreach ($barangays as $name) {
            Barangay::create(['name' => $name]);
        }
    }
}
