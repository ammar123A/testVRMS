<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FlRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_reservation()
    {
        $user = User::factory()->create();

        $data = [
            'purpose' => 'Business Trip',
            'attention_to' => 'Dr. Ahmad',
            'vote_ptj' => '12345',
            'dept_faculty' => 'Engineering',
            'officer_email' => 'officer@example.com',
            'vehicle_request' => 'SUV',
            'no_vehicle' => 2,
            'program' => 'Field Visit',
            'booking_type' => 'Adhoc',
            'pickup_point' => 'Main Gate',
            'pickup_state' => 'Johor',
            'destination' => 'Putrajaya',
            'destination_state' => 'WP Putrajaya',
            'start_date' => '2025-08-10',
            'start_time' => '08:00',
            'end_date' => '2025-08-11',
            'end_time' => '17:00',
            'agree' => true,
        ];

        $response = $this->actingAs($user)
                         ->post(route('reservation.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Reservation saved successfully.');

        $this->assertDatabaseHas('fl_request', [
            'user_id' => $user->id,
            'purpose' => 'Business Trip',
            'status' => 'PENDING',
        ]);
    }

    public function test_user_can_view_filtered_reservation_history()
    {
        $user = User::factory()->create();

        FlRequest::factory()->count(2)->create([
            'user_id' => $user->id,
            'status' => 'PENDING',
            'reservation_date' => '2025-08-01',
        ]);

        FlRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'APPROVED',
            'reservation_date' => '2025-08-03',
        ]);

        $response = $this->actingAs($user)->get(route('reservation.history', [
            'status' => 'PENDING',
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-02',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('reservation.history');
        $response->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() === 2 && $reservations->first()->status === 'PENDING';
        });
    }
}
