<?php

namespace Database\Factories;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        return [
            'request_id' => 'REQ' . $this->faker->unique()->numberBetween(1000, 9999),
            'wr_id' => $this->faker->numberBetween(1000, 9999),
            'wo_id' => $this->faker->numberBetween(1000, 9999),
            'date_send' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['REQUESTED', 'RECOMMENDED', 'APPROVED', 'COMPLETED']),
            'charted' => $this->faker->boolean,
            'date_time_assigned' => $this->faker->dateTime(),
        ];
    }
}
