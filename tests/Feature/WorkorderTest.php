<?php

namespace Tests\Feature;

use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_work_order_details_page_loads()
    {
        $response = $this->get(route('reports.work_order_details'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.work_order_details'); // optional
    }

    public function test_work_order_charted_page_loads()
    {
        $response = $this->get(route('reports.work_order_charted'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.work_order_charted'); // optional
    }

    // public function test_ajax_work_order_charted_returns_data()
    // {
    //     $postData = [
    //         'date_3' => '2025-08-01',
    //         'date_4' => '2025-08-03',
    //         'site_id' => 'UTMJB',
    //         // add any other filters expected in the controller
    //     ];

    //     $response = $this->post(route('reports.work_order_charted.ajax'), $postData);

    //     $response->assertStatus(200);
    //     // Use one of these depending on response:
    //     $response->assertJson(...);
    //     $response->assertViewIs('reports.work_order_charted'); // if returning HTML
    // }
}
