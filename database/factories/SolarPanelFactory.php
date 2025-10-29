<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use App\Models\SolarPanel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolarPanelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SolarPanel::class;

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
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'power_output' => $this->faker->numberBetween(100, 500),
            'description' => $this->faker->sentence(),
        ];
    }
}
