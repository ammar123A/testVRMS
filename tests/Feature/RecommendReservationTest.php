<?php

namespace Tests\Feature;

use App\Models\FlRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_filtered_requests()
    {
        $user = User::factory()->create();

        FlRequest::factory()->create([
            'request_id' => 'REQ1001',
            'user_id'=>$user->id,
            'booking_type' => 'SEND',
            'created_at' => '2025-08-01',
            'start_date' => '2025-08-05',
        ]);

        $response = $this->get('/allocation/recommend');

        $response->assertStatus(200);
        $response->assertViewIs('fleet.recommend.index');
        $response->assertViewHas('requests');
    }

    public function test_history_returns_filtered_requests()
    {
        FlRequest::factory()->create([
            'request_id' => 'REQ2002',
            'booking_type' => 'FETCH',
            'status' => 'RECOMMENDED',
            'created_at' => '2025-08-01',
            'start_date' => '2025-08-03',
        ]);

        $response = $this->get('/recommendations/history');

        $response->assertStatus(200);
        $response->assertViewIs('fleet.recommend.history');
        $response->assertViewHas('requests');
    }
}
