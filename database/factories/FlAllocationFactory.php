<?php

namespace Database\Factories;

use App\Models\FlAllocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlAllocationFactory extends Factory
{
    protected $model = FlAllocation::class;

    public function definition(): array
    {
        return [
            'altn_year' => $this->faker->year(),
            'altn_phb' => $this->faker->randomElement(['UTM JOHOR BAHRU', 'UTM KUALA LUMPUR']),
            'altn_category' => 'BUDGET OF UNIVERSITY',
            'altn_allocation' => $this->faker->randomFloat(2, 10000, 100000),
            'altn_date' => $this->faker->date(),
        ];
    }
}
