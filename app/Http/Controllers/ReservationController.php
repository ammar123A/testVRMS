<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FlRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_loads_correctly()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reservation.index'));

        $response->assertStatus(200);
        $response->assertViewIs('supervisor.vehicle.vehicle');
    }

    /** @test */
    public function user_can_submit_reservation_form()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('reservation.store'), [
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
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Reservation saved successfully.');

        $this->assertDatabaseHas('fl_requests', [
            'user_id' => $user->id,
            'purpose' => 'Business Trip',
            'status' => 'PENDING',
        ]);
    }

    /** @test */
    public function reservation_history_filters_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create reservations with varying data
        FlRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'PENDING',
            'reservation_date' => '2025-08-01',
        ]);

        FlRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'APPROVED',
            'reservation_date' => '2025-08-02',
        ]);

        $response = $this->get(route('reservation.history', [
            'status' => 'PENDING',
            'start_date' => '2025-08-01',
            'end_date' => '2025-08-10',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() === 1 && $reservations->first()->status === 'PENDING';
        });
    }
}
