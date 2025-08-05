<?php

namespace Database\Factories;

use App\Models\FlRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlRequestFactory extends Factory
{
    protected $model = FlRequest::class;

    public function definition()
    {
        return [
            'request_id' => 'REQ' . $this->faker->unique()->numerify('####'),
            'user_id' => User::factory()->state([
                'user_type' => $this->faker->randomElement(['staff', 'student']),
            ]),
            'purpose' => $this->faker->sentence,
            'attention_to' => $this->faker->name,
            'vote_ptj' => $this->faker->numerify('#####'),
            'dept_faculty' => $this->faker->company,
            'officer_email' => $this->faker->safeEmail,
            'vehicle_request' => $this->faker->randomElement(['SUV', 'Bus', 'Van']),
            'no_vehicle' => $this->faker->numberBetween(1, 3),
            'program' => $this->faker->word,
            'booking_type' => $this->faker->randomElement(['Adhoc', 'Scheduled']),
            'pickup_point' => $this->faker->address,
            'pickup_state' => $this->faker->state,
            'destination' => $this->faker->city,
            'destination_state' => $this->faker->state,
            'start_date' => $this->faker->date,
            'start_time' => $this->faker->time('H:i'),
            'end_date' => $this->faker->date,
            'end_time' => $this->faker->time('H:i'),
            'agree' => true,
            'status' => $this->faker->randomElement(['PENDING', 'APPROVED', 'REJECTED']),
            'reservation_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
