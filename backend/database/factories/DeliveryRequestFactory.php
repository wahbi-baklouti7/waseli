<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\DeliveryRequest;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryRequest>
 */
class DeliveryRequestFactory extends Factory
{
    protected $model = DeliveryRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'buyer_id' => User::factory(),
            'arrival_city_id' => Region::factory(),
            'category_id' => Category::factory(),
            'date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'description' => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
