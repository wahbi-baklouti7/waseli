<?php

namespace App\Http\Controllers\Api\V1\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logistics\StoreTripRequest;
use App\Http\Requests\Logistics\UpdateTripRequest;
use App\Http\Resources\Logistics\TripResource;
use App\Models\Trip;
use App\Services\Logistics\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TripController extends Controller
{
    public function __construct(
        private readonly TripService $tripService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $trips = $this->tripService->listTrips($request->all());

        return TripResource::collection($trips);
    }

    public function store(StoreTripRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['carrier_id'] = $request->user()->id;

        $trip = $this->tripService->createTrip($data);

        return TripResource::make($trip)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Trip $trip): TripResource
    {
        $trip->load(['carrier', 'category', 'departedCountry', 'arrivalCity.country']);

        return TripResource::make($trip);
    }

    public function update(UpdateTripRequest $request, Trip $trip): TripResource
    {
        if ($trip->carrier_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $trip = $this->tripService->updateTrip($trip, $request->validated());

        return TripResource::make($trip);
    }

    public function destroy(Request $request, Trip $trip): JsonResponse
    {
        if ($trip->carrier_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->tripService->deleteTrip($trip);

        return response()->json(null, 204);
    }
}
