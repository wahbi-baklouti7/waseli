<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\TripRequest;
use App\Models\User;
use App\Enums\TripRequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripRequest>
 */
class TripRequestFactory extends Factory
{
    protected $model = TripRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trip_id' => Trip::factory(),
            'buyer_id' => User::factory(),
            'status' => TripRequestStatus::PENDING,
            'delivery_code' => null,
        ];
    }
}
