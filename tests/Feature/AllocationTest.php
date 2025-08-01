<?php

namespace Tests\Feature;

use App\Models\FlAllocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_allocation_list()
    {
        FlAllocation::factory()->count(3)->create();

        $response = $this->get(route('allocation.index'));

        $response->assertStatus(200);
        $response->assertViewIs('fleet.allocation.index');
        $response->assertViewHas('allocations');
    }

    public function test_create_form_renders_successfully()
    {
        $response = $this->get(route('allocation.create'));

        $response->assertStatus(200);
        $response->assertViewIs('fleet.allocation.create');
        $response->assertViewHasAll(['phbs', 'categories']);
    }

    public function test_store_creates_allocation_record()
    {
        $data = [
            'altn_year' => '2025',
            'altn_phb' => 'UTM JOHOR BAHRU',
            'altn_category' => 'BUDGET OF UNIVERSITY',
            'altn_allocation' => 20000.00,
            'altn_date' => '2025-08-01',
        ];

        $response = $this->post(route('allocation.store'), $data);

        $response->assertRedirect(route('allocation.index'));
        $response->assertSessionHas('success', 'Allocation created successfully.');

        $this->assertDatabaseHas('fl_allocation', $data);
    }

    public function test_history_filters_allocations_by_year_and_phb()
    {
        FlAllocation::factory()->create([
            'altn_year' => 2025,
            'altn_phb' => 'UTM JOHOR BAHRU',
            'altn_date' => '2025-06-01',
        ]);

        FlAllocation::factory()->create([
            'altn_year' => 2024,
            'altn_phb' => 'UTM KUALA LUMPUR',
            'altn_date' => '2024-06-01',
        ]);

        $response = $this->get(route('allocation.history', [
            'year' => 2025,
            'phb' => 'UTM JOHOR BAHRU',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('fleet.allocation.history');
        $response->assertViewHas('allocations');
    }
}
