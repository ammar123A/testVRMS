<?php

namespace Database\Factories;

use App\Models\FlComplaint;
use App\Models\FlVehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlComplaintFactory extends Factory
{
    protected $model = FlComplaint::class;

    public function definition(): array
    {
        return [
            'vehicle_id' => FlVehicle::factory(), // create related vehicle
            'plate_number' => $this->faker->regexify('[A-Z]{3}[0-9]{3,4}'),
            'type' => $this->faker->randomElement(['Van', 'Bus', 'SUV']),
            'model' => $this->faker->word,
            'chassis_no' => $this->faker->bothify('??###'),
            'engine_no' => $this->faker->bothify('??##??'),
            'colour' => $this->faker->safeColorName,
            'odometer' => $this->faker->numberBetween(10000, 99999),
            'fuel' => $this->faker->numberBetween(0, 100),
            'road_tax_expiry' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'puspakom_expiry' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'permit_expiry' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'complaint' => $this->faker->sentence,
            'em_id' => 'EMP' . $this->faker->unique()->numerify('###'),
        ];
    }
}
