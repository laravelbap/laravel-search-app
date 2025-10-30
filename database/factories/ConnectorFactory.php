<?php

namespace Database\Factories;

use App\Models\Connector;
use App\Models\ConnectorType;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Connector::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'manufacturer_id' => Manufacturer::factory(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'connector_type_id' => ConnectorType::factory(),
            'description' => $this->faker->sentence(),
        ];
    }
}
