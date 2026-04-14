<?php

declare(strict_types=1);

namespace App\Services\Marketplace;

use App\Models\DeliveryRequest;
use App\Models\RequestApplication;
use Illuminate\Validation\ValidationException;

final class RequestApplicationService
{
    /**
     * Apply (bid) for a delivery request.
     */
    public function apply(DeliveryRequest $request, int $carrierId): RequestApplication
    {
        // Check if already applied
        if ($request->applications()->where('carrier_id', $carrierId)->exists()) {
            throw ValidationException::withMessages([
                'request_id' => ['You have already applied for this request.'],
            ]);
        }

        return $request->applications()->create([
            'carrier_id' => $carrierId,
            'status' => 'pending',
        ]);
    }

    /**
     * Accept a bid.
     */
    public function accept(RequestApplication $application): bool
    {
        return $application->update(['status' => 'accepted']);
    }

    /**
     * Reject a bid.
     */
    public function reject(RequestApplication $application): bool
    {
        return $application->update(['status' => 'rejected']);
    }
}
