<?php

declare(strict_types=1);

namespace App\Services\Marketplace;

use App\Models\Trip;
use App\Models\TripRequest;
use Illuminate\Validation\ValidationException;

final class TripApplicationService
{
    /**
     * Apply for a trip.
     */
    public function apply(Trip $trip, int $buyerId): TripRequest
    {
        // Check if already applied
        if ($trip->requests()->where('buyer_id', $buyerId)->exists()) {
            throw ValidationException::withMessages([
                'trip_id' => ['You have already applied for this trip.'],
            ]);
        }

        return $trip->requests()->create([
            'buyer_id' => $buyerId,
            'status' => 'pending',
        ]);
    }

    /**
     * Accept an application.
     */
    public function accept(TripRequest $tripRequest): bool
    {
        return $tripRequest->update([
            'status' => \App\Enums\TripRequestStatus::ACCEPTED,
            'delivery_code' => \Illuminate\Support\Str::random(8),
        ]);
    }

    /**
     * Reject an application.
     */
    public function reject(TripRequest $tripRequest): bool
    {
        return $tripRequest->update(['status' => 'rejected']);
    }
}
