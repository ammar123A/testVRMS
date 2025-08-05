<?php

namespace Tests\Feature;

use App\Models\FlComplaint;
use App\Models\FlVehicle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_complaint_form_displays()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/complaint/create');
        $response->assertStatus(200);
        $response->assertViewIs('complaint.form');
        $response->assertViewHas('vehicles');
    }

    // public function test_vehicle_details_returns_json()
    // {
    //     $vehicle = FlVehicle::factory()->create();

    //     $response = $this->getJson("/complaint/vehicle/{$vehicle->vehicle_id}");
    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         'vehicle_id' => $vehicle->vehicle_id,
    //         'plate_number' => $vehicle->plate_number,
    //     ]);
    // }

    public function test_complaint_can_be_submitted()
    {
        $user = User::factory()->create();
        $vehicle = FlVehicle::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/complaint/store', [
            'vehicle_id' => $vehicle->vehicle_id,
            'odometer' => 123456,
            'fuel' => 80,
            'complaint' => 'Test engine noise issue',
        ]);

        $response->assertRedirect(route('complaint.history'));
        $this->assertDatabaseHas('complaints', [
            'vehicle_id' => $vehicle->vehicle_id,
            'plate_number' => $vehicle->plate_number,
            'complaint' => 'Test engine noise issue',
        ]);
    }

    public function test_complaint_history_page_loads()
    {
        $user = User::factory()->create(['em_id' => 'EMP123']);
        FlVehicle::factory()->create(['em_id' => 'EMP123']);
        $this->actingAs($user);

        $response = $this->get('/complaint/history');
        $response->assertStatus(200);
        $response->assertViewIs('complaint.history');
        $response->assertViewHas('vehicles');
    }

    // public function test_history_search_filters_data()
    // {
    //     $complaint = FlComplaint::factory()->create([
    //         // 'status' => 'PENDING',
    //         'plate_number' => 'ABC1234',
    //     ]);

    //     $response = $this->get('/complaints/history');

    //     $response->assertStatus(200);
    //     $response->assertViewIs('complaint.history');
    //     $response->assertViewHas('complaints');
    // }

    public function test_verify_history_page_loads()
    {
        $complaint = FlComplaint::factory()->create([
            // 'status' => 'APPROVED',
            'plate_number' => 'XYZ987',
        ]);

        $response = $this->get('/complaints/history');

        $response->assertStatus(200);
        $response->assertViewIs('complaint.history');
        $response->assertViewHas('vehicles');
        $response->assertViewHas('complaints');
    }

    public function test_verify_wr_history_page_loads()
    {
        $response = $this->get('/complaints/verify-wr-history');

        $response->assertStatus(200);
        $response->assertViewIs('maintenance.verify-wr.history');
        $response->assertViewHasAll(['vehicleTypes', 'statuses', 'departments', 'technicians']);
    }
}
