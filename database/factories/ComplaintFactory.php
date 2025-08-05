<?php

// namespace Database\Factories;

// use Illuminate\Database\Eloquent\Factories\Factory;

// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlComplaint>
//  */
// class ComplaintFactory extends Factory
// {
//     /**
//      * Define the model's default state.
//      *
//      * @return array<string, mixed>
//      */
//     public function definition(): array
//     {
//         return [
//             'vehicle_id' => $this->faker->unique()->numerify('VH###'),
//             'plate_number' => strtoupper($this->faker->bothify('??###')),
//             'type' => 'Van',
//             'model' => 'Toyota',
//             'chassis_no' => strtoupper($this->faker->bothify('??##')),
//             'engine_no' => strtoupper($this->faker->bothify('EN##')),
//             'colour' => 'White',
//             'road_tax_expiry' => now()->addMonths(6),
//             'puspakom_expiry' => now()->addMonths(6),
//             'permit_expiry' => now()->addMonths(6),
//             'em_id' => 'EMP001',
//         ];
//     }
// }
