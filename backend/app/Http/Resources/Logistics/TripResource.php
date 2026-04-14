<?php

namespace App\Http\Resources\Logistics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'carrier' => [
                'id' => $this->carrier->id,
                'name' => $this->carrier->first_name . ' ' . $this->carrier->last_name,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'departed_country' => [
                'id' => $this->departedCountry->id,
                'name' => $this->departedCountry->name,
                'code' => $this->departedCountry->code,
            ],
            'arrival_city' => [
                'id' => $this->arrivalCity->id,
                'name' => $this->arrivalCity->name,
                'country' => $this->arrivalCity->country->name ?? null,
            ],
            'arrival_date' => $this->arrival_date->toIso8601String(),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
