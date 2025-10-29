<?php

namespace Database\Factories;

use App\Models\ConnectorType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectorTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConnectorType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
