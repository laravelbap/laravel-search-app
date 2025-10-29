<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Seeding database...');

        $this->call(ManufacturerSeeder::class);
        $this->call(BatterySeeder::class);
        $this->call(ConnectorSeeder::class);
        $this->call(SolarPanelSeeder::class);

        $this->command->info('Database seeded successfully.');
    }
}
