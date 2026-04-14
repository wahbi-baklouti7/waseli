<?php

declare(strict_types=1);

namespace Tests\Unit\Logistics;

use App\Models\Category;
use App\Models\DeliveryRequest;
use App\Models\Region;
use App\Models\User;
use App\Services\Logistics\DeliveryRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DeliveryRequestServiceTest extends TestCase
{
    use RefreshDatabase;

    private DeliveryRequestService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DeliveryRequestService();
    }

    public function test_it_can_create_a_delivery_request(): void
    {
        $buyer = User::factory()->create();
        $category = Category::factory()->create();
        $region = Region::factory()->create();

        $data = [
            'category_id' => $category->id,
            'arrival_city_id' => $region->id,
            'date' => now()->addDays(3)->toDateString(),
            'description' => 'Need this delivered fast',
        ];

        $deliveryRequest = $this->service->create($data, (int) $buyer->id);

        $this->assertInstanceOf(DeliveryRequest::class, $deliveryRequest);
        $this->assertEquals($buyer->id, $deliveryRequest->buyer_id);
        $this->assertEquals('pending', $deliveryRequest->status);
        $this->assertDatabaseHas('delivery_requests', ['id' => $deliveryRequest->id]);
    }

    public function test_it_can_list_delivery_requests_with_filtering(): void
    {
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();

        DeliveryRequest::factory()->count(3)->create([
            'category_id' => $category->id,
            'status' => 'pending'
        ]);
        DeliveryRequest::factory()->count(2)->create([
            'category_id' => $otherCategory->id,
            'status' => 'pending'
        ]);
        
        // Completed should be excluded by default pending scope in service
        DeliveryRequest::factory()->create([
            'category_id' => $category->id,
            'status' => 'completed'
        ]);

        $results = $this->service->list(['category_id' => $category->id]);

        $this->assertEquals(3, $results->total());
    }

    public function test_it_can_handle_multiple_filters(): void
    {
        $category = Category::factory()->create();
        $region = Region::factory()->create();
        $date = now()->addDays(5)->toDateString();

        // Target
        DeliveryRequest::factory()->create([
            'category_id' => $category->id,
            'arrival_city_id' => $region->id,
            'date' => $date,
            'status' => 'pending'
        ]);

        // Noise
        DeliveryRequest::factory()->create(['category_id' => $category->id]);
        DeliveryRequest::factory()->create(['arrival_city_id' => $region->id]);

        $results = $this->service->list([
            'category_id' => $category->id,
            'arrival_city_id' => $region->id,
            'date' => $date
        ]);

        $this->assertEquals(1, $results->total());
    }

    public function test_it_can_cancel_a_delivery_request(): void
    {
        $deliveryRequest = DeliveryRequest::factory()->create(['status' => 'pending']);

        $result = $this->service->cancel($deliveryRequest);

        $this->assertTrue($result);
        $this->assertEquals('cancelled', $deliveryRequest->fresh()->status);
    }

    public function test_it_can_delete_a_delivery_request(): void
    {
        $deliveryRequest = DeliveryRequest::factory()->create();

        $result = $this->service->delete($deliveryRequest);

        $this->assertTrue($result);
        $this->assertSoftDeleted('delivery_requests', ['id' => $deliveryRequest->id]);
    }
}
