<?php

declare(strict_types=1);

namespace App\Http\Resources\Marketplace;

use App\Http\Resources\Logistics\TripResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TripRequestResource extends JsonResource
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
            'trip' => new TripResource($this->whenLoaded('trip')),
            'buyer' => [
                'id' => $this->buyer->id,
                'name' => $this->buyer->first_name . ' ' . $this->buyer->last_name,
            ],
            'status' => $this->status,
            'delivery_code' => $this->when($request->user()->id === $this->buyer_id, $this->delivery_code),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
