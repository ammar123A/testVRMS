<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlDriver>
 */
class FlDriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'ic_number' => $this->faker->numerify('##########'), // e.g., 900101123456
            'license_number' => 'D' . $this->faker->numerify('########'), // e.g., D12345678
            'phone' => $this->faker->phoneNumber,
            'em_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}
