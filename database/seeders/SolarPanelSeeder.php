<?php

namespace Database\Seeders;


use App\Models\Manufacturer;
use App\Models\SolarPanel;
use App\Support\CsvReader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed Solar Panels
 * Get Solar Panels from CSV files and insert into database
 */
class SolarPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $solarPanels = CsvReader::toCollection(database_path('seed_files/solar_panels.csv'));

        $manufacturers =Manufacturer::all();

        foreach ($solarPanels as $solarPanel) {

            $manufacturer = $manufacturers->where('name',$solarPanel['manufacturer'])->first();

            SolarPanel::firstOrCreate([
                'name' => $solarPanel['name'],
                'price' => $solarPanel['price'],
                'power_output' => $solarPanel['power_output'],
                'description' => $solarPanel['description'],
                'manufacturer_id' => $manufacturer->id
            ]);

        }

        $this->command->info('Solar Panels seeded successfully. No of solar panels: ' . $solarPanels->count() );
    }
}
