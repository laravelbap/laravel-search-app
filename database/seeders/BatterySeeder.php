<?php

namespace Database\Seeders;

use App\Models\Battery;
use App\Models\Manufacturer;
use App\Support\CsvReader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed Batteries
 * Get Batteries from CSV files and insert into database
 */
class BatterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $batteries = CsvReader::toCollection(database_path('seed_files/batteries.csv'));

        $manufacturers =Manufacturer::all();

        foreach ($batteries as $battery) {

            $manufacturer = $manufacturers->where('name',$battery['manufacturer'])->first();

            Battery::firstOrCreate([
                'name' => $battery['name'],
                'price' => $battery['price'],
                'capacity' => $battery['capacity'],
                'description' => $battery['description'],
                'manufacturer_id' => $manufacturer->id,
            ]);
        }

        $this->command->info('Batteries seeded successfully. No of batteries: ' . $batteries->count() );
    }
}
