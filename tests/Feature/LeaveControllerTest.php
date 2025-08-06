<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FlLeave;
use App\Models\FlDriver;

class LeaveControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_leave_index_with_filters()
    {
        $driver = FlDriver::factory()->create(['name' => 'Ali bin Ahmad']);
        FlLeave::factory()->create([
            'em_id' => $driver->em_id,
            'start_date' => now()->subDays(2),
            'end_date' => now(),
            'reason' => 'Medical',
        ]);

        $response = $this->get(route('leave.index', [
            'em_id' => $driver->em_id,
            'name' => 'Ali',
            'date_1' => now()->subDays(3)->toDateString(),
            'date_2' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('leaves');
        $response->assertViewHas('drivers');
    }

    public function test_it_stores_a_new_leave_record()
    {
        $driver = FlDriver::factory()->create([
            'em_id' => 'EMP123',
            'name' => 'Siti Aminah'
        ]);

        $postData = [
            'em_id' => $driver->em_id,
            'name' => $driver->name,
            'start_date' => '2025-08-10',
            'end_date' => '2025-08-12',
            'reason' => 'Annual Leave',
        ];

        $response = $this->post(route('leave.store'), $postData);

        $response->assertRedirect(route('leave.index'));
        $this->assertDatabaseHas('fl_leave', [
            'em_id' => 'EMP123',
            'reason' => 'Annual Leave',
        ]);
    }

    public function test_it_fails_validation_when_required_fields_are_missing()
    {
        $response = $this->post(route('leave.store'), []);

        $response->assertSessionHasErrors(['em_id', 'start_date', 'end_date', 'reason']);
    }
}
