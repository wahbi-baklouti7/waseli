<?php

namespace Tests\Unit\Logistics;

use App\Models\Category;
use App\Models\Country;
use App\Models\Region;
use App\Models\Trip;
use App\Models\User;
use App\Services\Logistics\TripService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripServiceTest extends TestCase
{
    use RefreshDatabase;

    private TripService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TripService();
    }

    public function test_it_can_create_a_trip(): void
    {
        $carrier = User::factory()->create();
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);

        $data = [
            'carrier_id' => $carrier->id,
            'category_id' => $category->id,
            'departed_country_id' => $country->id,
            'arrival_city_id' => $region->id,
            'arrival_date' => now()->addDays(2)->toIso8601String(),
        ];

        $trip = $this->service->createTrip($data);

        $this->assertInstanceOf(Trip::class, $trip);
        $this->assertEquals($carrier->id, $trip->carrier_id);
        $this->assertDatabaseHas('trips', ['id' => $trip->id]);
    }

    public function test_it_can_update_a_trip(): void
    {
        $trip = Trip::factory()->create();
        $newCategory = Category::factory()->create();

        $updated = $this->service->updateTrip($trip, [
            'category_id' => $newCategory->id,
        ]);

        $this->assertEquals($newCategory->id, $updated->category_id);
        $this->assertDatabaseHas('trips', [
            'id' => $trip->id,
            'category_id' => $newCategory->id,
        ]);
    }

    public function test_it_can_delete_a_trip(): void
    {
        $trip = Trip::factory()->create();

        $result = $this->service->deleteTrip($trip);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
    }

    public function test_it_can_list_trips_with_filters(): void
    {
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();

        Trip::factory()->count(3)->create(['category_id' => $category->id]);
        Trip::factory()->count(2)->create(['category_id' => $otherCategory->id]);

        $results = $this->service->listTrips(['category_id' => (string)$category->id]);

        $this->assertEquals(3, $results->total());
    }

    public function test_it_handles_multiple_filters_simultaneously(): void
    {
        $category = Category::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create(['country_id' => $country->id]);

        // Target trip
        Trip::factory()->create([
            'category_id' => $category->id,
            'departed_country_id' => $country->id,
            'arrival_city_id' => $region->id,
        ]);

        // Noise
        Trip::factory()->create(['category_id' => $category->id]); // Same category, different location
        Trip::factory()->create(['departed_country_id' => $country->id]); // Same country, different category

        $results = $this->service->listTrips([
            'category_id' => $category->id,
            'departed_country_id' => $country->id,
            'arrival_city_id' => $region->id,
        ]);

        $this->assertEquals(1, $results->total());
    }

    public function test_it_returns_empty_pagination_for_non_existent_filters(): void
    {
        Trip::factory()->count(2)->create();

        $results = $this->service->listTrips(['category_id' => 999]);

        $this->assertEquals(0, $results->total());
        $this->assertEmpty($results->items());
    }

    public function test_it_respects_custom_pagination_limit(): void
    {
        Trip::factory()->count(10)->create();

        $results = $this->service->listTrips([], 5);

        $this->assertCount(5, $results->items());
        $this->assertEquals(10, $results->total());
    }
}
