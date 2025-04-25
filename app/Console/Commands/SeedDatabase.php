<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with admin and reseller users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding database with admin and reseller users...');

        // Run the admin seeder
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\AdminSeeder']);

        // Run the reseller seeder
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\ResellerSeeder']);

        $this->info('Database seeding completed successfully!');
    }
}
