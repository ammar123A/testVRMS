<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FlLeave;
use App\Models\FlDriver;

/**
 * @extends Factory<\App\Models\FlLeave>
 */
class FlLeaveFactory extends Factory
{
    protected $model = FlLeave::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-10 days', 'now');
        $endDate = (clone $startDate)->modify('+' . rand(1, 5) . ' days');

        return [
            'em_id' => FlDriver::factory(),
            'name' => $this->faker->name,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'reason' => $this->faker->randomElement(['Medical', 'Annual Leave', 'Emergency', 'Family']),
        ];
    }
}
