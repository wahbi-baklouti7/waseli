<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TripRequestStatus;
use App\Models\TripRequest;
use Illuminate\Validation\ValidationException;

final class FulfillmentService
{
    /**
     * Mark package as picked up.
     */
    public function pickUp(TripRequest $tripRequest): bool
    {
        if ($tripRequest->status !== TripRequestStatus::ACCEPTED) {
            throw ValidationException::withMessages([
                'status' => ['Package can only be picked up if the request was accepted.'],
            ]);
        }

        return $tripRequest->update(['status' => TripRequestStatus::PICKED_UP]);
    }

    /**
     * Verify delivery code and mark as delivered.
     */
    public function verifyAndDeliver(TripRequest $tripRequest, string $code): bool
    {
        if ($tripRequest->status !== TripRequestStatus::PICKED_UP) {
            throw ValidationException::withMessages([
                'status' => ['Package can only be marked as delivered if it was picked up.'],
            ]);
        }

        if ($tripRequest->delivery_code !== $code) {
            throw ValidationException::withMessages([
                'delivery_code' => ['Invalid delivery verification code.'],
            ]);
        }

        $updated = $tripRequest->update(['status' => TripRequestStatus::DELIVERED]);

        $this->checkAndCompleteTrip($tripRequest->trip);

        return $updated;
    }

    /**
     * Complete the trip if all requests are delivered.
     */
    private function checkAndCompleteTrip(\App\Models\Trip $trip): void
    {
        $allDelivered = $trip->requests()
            ->whereIn('status', [TripRequestStatus::ACCEPTED, TripRequestStatus::PICKED_UP, TripRequestStatus::PENDING])
            ->doesntExist();

        if ($allDelivered && $trip->requests()->exists()) {
            $trip->update(['status' => \App\Enums\TripStatus::Completed]);
        }
    }
}
