<?php

namespace Tests\Feature\Logistics;

use App\Models\Category;
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TripTest extends TestCase
{
    use RefreshDatabase;

    public function test_carrier_can_create_trip(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);

        $response = $this->actingAs($carrier)
            ->postJson('/api/v1/trips', [
                'category_id' => $category->id,
                'departed_country_id' => $country->id,
                'arrival_city_id' => $region->id,
                'arrival_date' => now()->addDays(5)->toIso8601String(),
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.carrier.id', $carrier->id);

        $this->assertDatabaseHas('trips', [
            'carrier_id' => $carrier->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_buyer_cannot_create_trip(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::BUYER]);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);

        $response = $this->actingAs($buyer)
            ->postJson('/api/v1/trips', [
                'category_id' => $category->id,
                'departed_country_id' => $country->id,
                'arrival_city_id' => $region->id,
                'arrival_date' => now()->addDays(5)->toIso8601String(),
            ]);

        $response->assertForbidden();
    }

    public function test_anyone_can_list_trips(): void
    {
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);
        
        Trip::factory()->count(3)->create([
            'category_id' => $category->id,
            'departed_country_id' => $country->id,
            'arrival_city_id' => $region->id,
        ]);

        $response = $this->getJson('/api/v1/trips');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_cannot_create_trip_with_past_date(): void
    {
        $carrier = User::factory()->create(['role' => UserRole::CARRIER]);
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);

        $response = $this->actingAs($carrier)
            ->postJson('/api/v1/trips', [
                'category_id' => $category->id,
                'departed_country_id' => $country->id,
                'arrival_city_id' => $region->id,
                'arrival_date' => now()->subDays(1)->toIso8601String(), // Past date
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['arrival_date']);
    }

    public function test_cannot_update_someone_elses_trip(): void
    {
        $carrierA = User::factory()->create(['role' => UserRole::CARRIER]);
        $carrierB = User::factory()->create(['role' => UserRole::CARRIER]);
        $tripOfA = Trip::factory()->create(['carrier_id' => $carrierA->id]);

        $response = $this->actingAs($carrierB)
            ->putJson("/api/v1/trips/{$tripOfA->id}", [
                'status' => 'completed',
            ]);

        $response->assertForbidden();
    }
}
