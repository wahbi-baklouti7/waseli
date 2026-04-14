<?php

declare(strict_types=1);

namespace Tests\Feature\Marketplace;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MarketplaceApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_apply_for_trip(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);

        $response = $this->actingAs($buyer)
            ->postJson("/api/v1/trips/{$trip->id}/apply");

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('trip_requests', [
            'trip_id' => $trip->id,
            'buyer_id' => $buyer->id,
        ]);
    }

    public function test_carrier_cannot_apply_for_trip(): void
    {
        $carrier1 = User::factory()->create(['role' => UserRole::CARRIER]);
        $carrier2 = User::factory()->create(['role' => UserRole::CARRIER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier1->id]);

        $response = $this->actingAs($carrier2)
            ->postJson("/api/v1/trips/{$trip->id}/apply");

        $response->assertForbidden();
    }

    public function test_carrier_can_apply_for_delivery_request(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $request = DeliveryRequest::factory()->create(['buyer_id' => $buyer->id]);

        $response = $this->actingAs($carrier)
            ->postJson("/api/v1/delivery-requests/{$request->id}/apply");

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('request_applications', [
            'request_id' => $request->id,
            'carrier_id' => $carrier->id,
        ]);
    }

    public function test_buyer_cannot_apply_for_delivery_request(): void
    {
        $buyer1 = User::factory()->create(['role' => UserRole::BUYER]);
        $buyer2 = User::factory()->create(['role' => UserRole::BUYER]);
        $request = DeliveryRequest::factory()->create(['buyer_id' => $buyer1->id]);

        $response = $this->actingAs($buyer2)
            ->postJson("/api/v1/delivery-requests/{$request->id}/apply");

        $response->assertForbidden();
    }

    public function test_carrier_can_accept_trip_application(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        $application = $trip->requests()->create(['buyer_id' => $buyer->id, 'status' => 'pending']);

        $response = $this->actingAs($carrier)
            ->patchJson("/api/v1/trip-requests/{$application->id}/status", [
                'status' => 'accepted',
            ]);

        $response->assertOk();
        $this->assertEquals('accepted', $application->fresh()->status);
    }

    public function test_buyer_can_accept_request_bid(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $request = DeliveryRequest::factory()->create(['buyer_id' => $buyer->id]);
        $bid = $request->applications()->create(['carrier_id' => $carrier->id, 'status' => 'pending']);

        $response = $this->actingAs($buyer)
            ->patchJson("/api/v1/request-applications/{$bid->id}/status", [
                'status' => 'accepted',
            ]);

        $response->assertOk();
        $this->assertEquals('accepted', $bid->fresh()->status);
    }
}
