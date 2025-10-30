<?php

namespace Tests\Unit;

use App\Models\Battery;
use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Manufacturer;
use App\Models\SolarPanel;
use App\Support\CsvReader;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Seeders vs CSV test
 * Test if seeders and CSV files are the same
 */
class SeederTest extends TestCase
{

    use RefreshDatabase;


    /**
     * A basic unit test example.
     */
    public function test_seeders_vs_csv(): void
    {
        $this->seed(DatabaseSeeder::class);

        $batteries = CsvReader::toCollection(database_path('seed_files/batteries.csv'));
        $connectors = CsvReader::toCollection(database_path('seed_files/connectors.csv'));
        $solarPanels = CsvReader::toCollection(database_path('seed_files/solar_panels.csv'));

        $manufacturers = $batteries->merge($connectors)->merge($solarPanels)->pluck('manufacturer')->unique();


        $this->assertCount($manufacturers->count(), Manufacturer::all());

        $this->assertCount($batteries->count(), Battery::all());
        $this->assertCount($connectors->pluck('connector_type')->unique()->count(), ConnectorType::all());;
        $this->assertCount($connectors->count(), Connector::all());

        $this->assertCount($solarPanels->count(), SolarPanel::all());
    }
}
