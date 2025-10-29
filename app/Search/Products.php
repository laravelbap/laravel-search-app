<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Models\Battery;
use App\Models\Connector;
use App\Models\SolarPanel;

/**
 * Products
 * Aggregate class for all products
 */
class Products extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        SolarPanel::class,
        Battery::class,
        Connector::class
    ];

    protected $relations = [
        SolarPanel::class => ['manufacturer'],
        Battery::class => ['manufacturer'],
        Connector::class => ['manufacturer', 'connectorType'],
    ];

    public function toSearchableArray(): array
    {
        $m = $this->model;

        return match (get_class($m)) {
            SolarPanel::class => [
                'object_id' => 'SolarPanel:' . $m->getKey(),
                'type' => 'SolarPanel',
                'model_id' => (string)$m->id,
                'name' => $m->name,
                'description' => $m->description,
                'price' => $m->price,
                'power_output' => $m->power_output,
                'manufacturer_name' => $m->manufacturer->name,
                'manufacturer_id' => $m->manufacturer->id,
                'created_at' => $m->created_at,
                'updated_at' => $m->updated_at,
            ],
            Battery::class => [
                'object_id' => 'Battery:' . $m->getKey(),
                'type' => 'Battery',
                'model_id' => (string)$m->id,
                'name' => $m->name,
                'description' => $m->description,
                'price' => $m->price,
                'capacity' => $m->capacity,
                'manufacturer_name' => $m->manufacturer->name,
                'manufacturer_id' => $m->manufacturer->id,
                'created_at' => $m->created_at,
                'updated_at' => $m->updated_at,
            ],
            Connector::class => [
                'object_id' => 'Connector:' . $m->getKey(),
                'type' => 'Connector',
                'model_id' => (string)$m->id,
                'name' => $m->name,
                'description' => $m->description,
                'price' => $m->price,
                'manufacturer_name' => $m->manufacturer->name,
                'manufacturer_id' => $m->manufacturer->id,
                'connector_type_name' => $m->connectorType->name,
                'connector_type_id' => $m->connectorType->id,
                'created_at' => $m->created_at,
                'updated_at' => $m->updated_at,
            ],
            default => []
        };
    }
}
