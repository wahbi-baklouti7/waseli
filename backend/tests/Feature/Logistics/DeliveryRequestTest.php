<?php

declare(strict_types=1);

namespace Tests\Feature\Logistics;

use App\Models\Category;
use App\Models\DeliveryRequest;
use App\Models\Region;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DeliveryRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_create_delivery_request(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $category = Category::factory()->create();
        $region = Region::factory()->create();

        $response = $this->actingAs($buyer)
            ->postJson('/api/v1/delivery-requests', [
                'category_id' => $category->id,
                'arrival_city_id' => $region->id,
                'date' => now()->addDays(2)->toDateString(),
                'description' => 'Test description',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('delivery_requests', [
            'buyer_id' => $buyer->id,
            'category_id' => $category->id,
            'description' => 'Test description',
        ]);
    }

    public function test_carrier_cannot_create_delivery_request(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $category = Category::factory()->create();
        $region = Region::factory()->create();

        $response = $this->actingAs($carrier)
            ->postJson('/api/v1/delivery-requests', [
                'category_id' => $category->id,
                'arrival_city_id' => $region->id,
                'date' => now()->addDays(2)->toDateString(),
            ]);

        $response->assertForbidden();
    }

    public function test_user_can_list_delivery_requests(): void
    {
        DeliveryRequest::factory()->count(3)->create(['status' => 'pending']);
        DeliveryRequest::factory()->create(['status' => 'completed']);

        $response = $this->getJson('/api/v1/delivery-requests');

        $response->assertOk()
            ->assertJsonCount(3, 'data'); // Only pending are listed by default service logic
    }

    public function test_user_can_filter_delivery_requests(): void
    {
        $category = Category::factory()->create();
        DeliveryRequest::factory()->create(['category_id' => $category->id]);
        DeliveryRequest::factory()->create();

        $response = $this->getJson("/api/v1/delivery-requests?category_id={$category->id}");

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_buyer_can_cancel_their_own_delivery_request(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $deliveryRequest = DeliveryRequest::factory()->create(['buyer_id' => $buyer->id]);

        $response = $this->actingAs($buyer)
            ->deleteJson("/api/v1/delivery-requests/{$deliveryRequest->id}");

        $response->assertOk();
        $this->assertEquals('cancelled', $deliveryRequest->fresh()->status);
    }

    public function test_buyer_cannot_cancel_others_delivery_request(): void
    {
        $buyer1 = User::factory()->create(['role' => UserRole::BUYER]);
        $buyer2 = User::factory()->create(['role' => UserRole::BUYER]);
        $deliveryRequest = DeliveryRequest::factory()->create(['buyer_id' => $buyer1->id]);

        $response = $this->actingAs($buyer2)
            ->deleteJson("/api/v1/delivery-requests/{$deliveryRequest->id}");

        $response->assertForbidden();
    }
}
