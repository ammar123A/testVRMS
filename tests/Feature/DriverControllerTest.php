<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FlDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DriverControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_drivers_ordered_by_updated_at_desc()
    {
        $older = FlDriver::factory()->create(['updated_at' => now()->subDay(), 'name' => 'Old']);
        $newer = FlDriver::factory()->create(['updated_at' => now(), 'name' => 'New']);

        $response = $this->get(route('driver.index'));

        $response->assertStatus(200);
        // Ensure "New" appears before "Old"
        $response->assertSeeInOrder(['New', 'Old']);
    }

    public function test_it_filters_by_em_id_with_partial_match()
    {
        FlDriver::factory()->create(['em_id' => 'EM12345', 'name' => 'Alice']);
        FlDriver::factory()->create(['em_id' => 'EM99999', 'name' => 'Bob']);

        $response = $this->get(route('driver.index', ['em_id' => '123']));

        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');
    }

    public function test_it_filters_by_name_with_partial_match()
    {
        FlDriver::factory()->create(['name' => 'Zulkifli Ahmad', 'em_id' => 'EM00001']);
        FlDriver::factory()->create(['name' => 'Nur Aina', 'em_id' => 'EM00002']);

        $response = $this->get(route('driver.index', ['name' => 'Zul']));

        $response->assertStatus(200);
        $response->assertSee('Zulkifli Ahmad');
        $response->assertDontSee('Nur Aina');
    }

    public function test_it_paginates_results()
    {
        FlDriver::factory()->count(15)->create();

        $response = $this->get(route('driver.index'));
        $response->assertStatus(200);

        // Laravel paginator adds the "Next" page link; presence indicates pagination rendered.
        $response->assertSee('Next');
    }

    public function test_store_requires_em_id()
    {
        $payload = [
            // 'em_id' missing on purpose
            'name'           => 'Has No EM ID',
            'phone'          => '0123456789',
            'license_number' => 'L1234567',
            'ic_number'      => '900101-01-1234',
        ];

        $response = $this->post(route('driver.store'), $payload);

        $response->assertSessionHasErrors(['em_id']);
        $this->assertDatabaseCount('fl_drivers', 0);
    }

    public function test_store_creates_driver_and_redirects_with_flash_message()
    {
        $payload = [
            'em_id'          => 'EM10001',
            'name'           => 'Valid Driver',
            'phone'          => '019-5555555',
            'license_number' => 'L7654321',
            'ic_number'      => '890202-02-5678',
        ];

        $response = $this->post(route('driver.store'), $payload);

        $response->assertRedirect(route('driver.index'));
        $response->assertSessionHas('success', 'Driver registered successfully.');

        $this->assertDatabaseHas('fl_drivers', [
            'em_id' => 'EM10001',
            'name'  => 'Valid Driver',
        ]);
    }

    public function test_store_respects_max_length_constraints()
    {
        $payload = [
            'em_id'          => str_repeat('E', 21), // > max:20
            'name'           => str_repeat('N', 256), // > max:255
            'phone'          => str_repeat('1', 21),  // > max:20
            'license_number' => str_repeat('L', 21),  // > max:20
            'ic_number'      => str_repeat('9', 21),  // > max:20
        ];

        $response = $this->post(route('driver.store'), $payload);
        $response->assertSessionHasErrors([
            'em_id', 'name', 'phone', 'license_number', 'ic_number'
        ]);
        $this->assertDatabaseCount('fl_drivers', 0);
    }
}
