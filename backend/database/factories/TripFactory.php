<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'carrier_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'departed_country_id' => \App\Models\Country::factory(),
            'arrival_city_id' => \App\Models\Region::factory(),
            'arrival_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'status' => \App\Enums\TripStatus::Open,
        ];
    }
}
