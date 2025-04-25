<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AppRecord;
use Illuminate\Support\Facades\Hash;

class ResellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create reseller users
        $resellers = [
            [
                'name' => 'Reseller One',
                'email' => 'reseller1@tutorai.com',
                'password' => Hash::make('password123'),
                'role' => 'reseller',
                'credit' => 50,
            ],
            [
                'name' => 'Reseller Two',
                'email' => 'reseller2@tutorai.com',
                'password' => Hash::make('password123'),
                'role' => 'reseller',
                'credit' => 75,
            ],
            [
                'name' => 'Reseller Three',
                'email' => 'reseller3@tutorai.com',
                'password' => Hash::make('password123'),
                'role' => 'reseller',
                'credit' => 100,
            ],
        ];

        foreach ($resellers as $reseller) {
            User::create($reseller);
        }

        // Create sample app records
        $appRecords = [
            [
                'app_id' => 'APP001',
                'os' => 'android',
                'status' => 'disable',
            ],
            [
                'app_id' => 'APP002',
                'os' => 'ios',
                'status' => 'disable',
            ],
            [
                'app_id' => 'APP003',
                'os' => 'android',
                'status' => 'disable',
            ],
            [
                'app_id' => 'APP004',
                'os' => 'ios',
                'status' => 'disable',
            ],
            [
                'app_id' => 'APP005',
                'os' => 'android',
                'status' => 'disable',
            ],
        ];

        foreach ($appRecords as $appRecord) {
            AppRecord::create($appRecord);
        }
    }
}
