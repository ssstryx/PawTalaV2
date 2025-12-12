<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if super admin exists to avoid duplicates
        if (!User::where('email', 'superadmin@gmail.com')->exists()) {
            User::create([
                'first_name' => 'Super',  // Changed from 'name'
                'last_name' => 'Admin',   // Added this
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'barangay' => 'Main Office', 
                'is_active' => true,
            ]);
        }
    }
}
