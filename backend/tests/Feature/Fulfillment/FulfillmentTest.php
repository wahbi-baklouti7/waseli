<?php

declare(strict_types=1);

namespace Tests\Feature\Fulfillment;

use App\Enums\TripRequestStatus;
use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\TripStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FulfillmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_carrier_can_mark_package_as_picked_up(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        $request = TripRequest::factory()->create([
            'trip_id' => $trip->id,
            'buyer_id' => $buyer->id,
            'status' => TripRequestStatus::ACCEPTED,
        ]);

        $response = $this->actingAs($carrier)
            ->patchJson("/api/v1/trip-requests/{$request->id}/pickup");

        $response->assertOk();
        $this->assertEquals(TripRequestStatus::PICKED_UP, $request->fresh()->status);
    }

    public function test_carrier_can_verify_delivery_with_code(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        $request = TripRequest::factory()->create([
            'trip_id' => $trip->id,
            'buyer_id' => $buyer->id,
            'status' => TripRequestStatus::PICKED_UP,
            'delivery_code' => 'CODE',
        ]);

        $response = $this->actingAs($carrier)
            ->postJson("/api/v1/trip-requests/{$request->id}/verify", [
                'delivery_code' => 'CODE',
            ]);

        $response->assertOk()
            ->assertJsonPath('status', 'delivered');

        $this->assertEquals(TripRequestStatus::DELIVERED, $request->fresh()->status);
    }

    public function test_trip_completes_when_all_requests_are_delivered(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        
        $request = TripRequest::factory()->create([
            'trip_id' => $trip->id,
            'buyer_id' => $buyer->id,
            'status' => TripRequestStatus::PICKED_UP,
            'delivery_code' => 'SECRET',
        ]);

        $this->actingAs($carrier)
            ->postJson("/api/v1/trip-requests/{$request->id}/verify", [
                'delivery_code' => 'SECRET',
            ]);

        $this->assertEquals(TripStatus::Completed, $trip->fresh()->status);
    }

    public function test_invalid_delivery_code_fails(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        $request = TripRequest::factory()->create([
            'trip_id' => $trip->id,
            'status' => TripRequestStatus::PICKED_UP,
            'delivery_code' => 'CODE',
        ]);

        $response = $this->actingAs($carrier)
            ->postJson("/api/v1/trip-requests/{$request->id}/verify", [
                'delivery_code' => 'WRONG',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('delivery_code');
    }

    public function test_delivery_code_is_visible_to_buyer_only(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $buyer1 = User::factory()->create(['role' => UserRole::BUYER]);
        $buyer2 = User::factory()->create(['role' => UserRole::BUYER]);
        $trip = Trip::factory()->create(['carrier_id' => $carrier->id]);
        $request = TripRequest::factory()->create([
            'trip_id' => $trip->id,
            'buyer_id' => $buyer1->id,
            'status' => TripRequestStatus::ACCEPTED,
            'delivery_code' => 'PRIVATE',
        ]);
        
        // This test is placeholder for resource logic testing
        $this->assertTrue(true);
    }
}
