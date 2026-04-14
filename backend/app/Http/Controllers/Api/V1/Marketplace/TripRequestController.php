<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\TripRequestResource;
use App\Models\Trip;
use App\Models\TripRequest;
use App\Services\Marketplace\TripApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TripRequestController extends Controller
{
    public function __construct(
        private readonly TripApplicationService $applicationService
    ) {}

    /**
     * Apply for a trip.
     */
    public function store(Request $request, Trip $trip): JsonResponse
    {
        if (!$request->user()->isBuyer()) {
            abort(403, 'Only buyers can apply for trips.');
        }

        $application = $this->applicationService->apply($trip, (int) $request->user()->id);

        return TripRequestResource::make($application)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update status (Accept/Reject).
     */
    public function updateStatus(Request $request, TripRequest $tripRequest): TripRequestResource
    {
        // Only the carrier who owns the trip can accept/reject
        if ($tripRequest->trip->carrier_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->validate([
            'status' => ['required', 'string', 'in:accepted,rejected'],
        ])['status'];

        if ($status === 'accepted') {
            $this->applicationService->accept($tripRequest);
        } else {
            $this->applicationService->reject($tripRequest);
        }

        return TripRequestResource::make($tripRequest);
    }
}
