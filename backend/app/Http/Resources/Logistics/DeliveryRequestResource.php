<?php

declare(strict_types=1);

namespace App\Http\Resources\Logistics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeliveryRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buyer' => [
                'id' => $this->buyer->id,
                'name' => $this->buyer->first_name . ' ' . $this->buyer->last_name,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'arrival_city' => [
                'id' => $this->arrivalCity->id,
                'name' => $this->arrivalCity->name,
                'country' => $this->arrivalCity->country->name ?? null,
            ],
            'date' => $this->date->toIso8601String(),
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
