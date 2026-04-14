<?php

declare(strict_types=1);

namespace App\Http\Resources\Marketplace;

use App\Http\Resources\Logistics\DeliveryRequestResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RequestApplicationResource extends JsonResource
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
            'delivery_request' => new DeliveryRequestResource($this->whenLoaded('deliveryRequest')),
            'carrier' => [
                'id' => $this->carrier->id,
                'name' => $this->carrier->first_name . ' ' . $this->carrier->last_name,
            ],
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
