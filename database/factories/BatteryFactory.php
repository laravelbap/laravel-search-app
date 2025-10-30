<?php

namespace Database\Factories;

use App\Models\Battery;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatteryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Battery::class;

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
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'capacity' => $this->faker->randomFloat(2, 1, 100),
            'description' => $this->faker->sentence(),
        ];
    }
}
