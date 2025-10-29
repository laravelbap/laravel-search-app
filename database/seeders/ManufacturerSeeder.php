<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Support\CsvReader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed Manufacturers
 * Get Manufacturers from CSV files and insert into database
 */
class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        // Get all manufacturers from csv files
        $batteries = CsvReader::toCollection(database_path('seed_files/batteries.csv'));
        $connectors = CsvReader::toCollection(database_path('seed_files/connectors.csv'));
        $solarPanels = CsvReader::toCollection(database_path('seed_files/solar_panels.csv'));

        // Merge all manufacturers into one collection
        $manufacturers = $batteries->merge($connectors)->merge($solarPanels)->pluck('manufacturer')->unique();

        // Insert or First
        foreach ($manufacturers as $manufacturer) {
            Manufacturer::firstOrCreate(['name' => $manufacturer]);
        }

        $this->command->info('Manufacturers seeded successfully. No of manufacturers: ' . $manufacturers->count() );

    }
}
