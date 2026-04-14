<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logistics\StoreDeliveryRequest;
use App\Http\Resources\Logistics\DeliveryRequestResource;
use App\Models\DeliveryRequest;
use App\Services\Logistics\DeliveryRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class DeliveryRequestController extends Controller
{
    public function __construct(
        private readonly DeliveryRequestService $deliveryRequestService
    ) {}

    /**
     * Display a listing of delivery requests.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $deliveryRequests = $this->deliveryRequestService->list($request->all());

        return DeliveryRequestResource::collection($deliveryRequests);
    }

    /**
     * Store a newly created delivery request.
     */
    public function store(StoreDeliveryRequest $request): JsonResponse
    {
        $deliveryRequest = $this->deliveryRequestService->create(
            $request->validated(),
            (int) $request->user()->id
        );

        return DeliveryRequestResource::make($deliveryRequest)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified delivery request.
     */
    public function show(DeliveryRequest $deliveryRequest): DeliveryRequestResource
    {
        $deliveryRequest->load(['buyer', 'arrivalCity.country', 'category']);

        return DeliveryRequestResource::make($deliveryRequest);
    }

    /**
     * Cancel/Delete the specified delivery request.
     */
    public function destroy(Request $request, DeliveryRequest $deliveryRequest): JsonResponse
    {
        if ($deliveryRequest->buyer_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->deliveryRequestService->cancel($deliveryRequest);

        return response()->json(['message' => 'Delivery request cancelled successfully.']);
    }
}
