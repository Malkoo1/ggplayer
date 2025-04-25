<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'credit' => 100,
        ]);

        // Create additional admin users if needed
        // User::create([
        //     'name' => 'Super Admin',
        //     'email' => 'superadmin@tutorai.com',
        //     'password' => Hash::make('password123'),
        //     'role' => 'admin',
        //     'credit' => 100,
        // ]);
    }
}
