<?php

namespace Database\Seeders;

use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Manufacturer;
use App\Support\CsvReader;
use Illuminate\Database\Seeder;

class ConnectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $connectors = CsvReader::toCollection(database_path('seed_files/connectors.csv'));

        $manufacturers = Manufacturer::all();
        $connectorTypes = $connectors->pluck('connector_type')->unique();

        // Seed Connector Types
        foreach ($connectorTypes as $connectorType) {
            ConnectorType::firstOrCreate([
                'name' => $connectorType,
            ]);
        }

        $this->command->info('Connector Types seeded successfully. No of connector types: ' . $connectorTypes->count());

        $connectorTypes = ConnectorType::all();

        // Seed Connectors
        foreach ($connectors as $connector) {
            $manufacturer = $manufacturers->where('name', $connector['manufacturer'])->first();

            $connectorType = $connectorTypes->where('name', $connector['connector_type'])->first();

            Connector::firstOrCreate([
                'name' => $connector['name'],
                'manufacturer_id' => $manufacturer->id,
                'price' => $connector['price'],
                'connector_type_id' => $connectorType->id,
                'description' => $connector['description'],
            ]);
        }
    }
}
