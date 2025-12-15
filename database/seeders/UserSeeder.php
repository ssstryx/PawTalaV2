<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Create SUPER ADMIN
        User::create([
            'first_name' => 'Super',
            'middle_name' => null, // Explicitly set null since they don't have one
            'last_name' => 'Admin',
            'email' => 'pawtala.portal@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
            'contact_number' => '0900-000-0000', // Dummy data to prevent error
            'address' => 'Main Office, Calapan City', // Dummy data
            'barangay' => null,
        ]);

        // 2. Create ADMIN (Jepsie)
        User::create([
            'first_name' => 'Jepsie Joy',
            'middle_name' => 'Nobleza',
            'last_name' => 'Beron',
            'email' => 'jepsiejoyberon@gmail.com',
            'password' => Hash::make('Jepsie_123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'contact_number' => '0912-000-0000', 
            'address' => 'Bucayao, Calapan City', 
            'barangay' => 'Bucayao', 
        ]);

        // 3. Create USER (Keyth)
        User::create([
            'owner_id' => '1001', 
            'first_name' => 'Keyth Lyn Joy',
            'middle_name' => 'Casilla',
            'last_name' => 'Vertucio',
            'email' => 'ultimatefujoshi31@gmail.com',
            'password' => Hash::make('Keyth_123'),
            'role' => 'user',
            'email_verified_at' => now(),
            'contact_number' => '0912-345-6789',
            'address' => 'Brgy. Calero, Calapan City',
            'barangay' => 'Calero',
        ]);
    }
}