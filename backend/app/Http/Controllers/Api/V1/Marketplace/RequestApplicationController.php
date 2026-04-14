<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Marketplace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketplace\RequestApplicationResource;
use App\Models\DeliveryRequest;
use App\Models\RequestApplication;
use App\Services\Marketplace\RequestApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RequestApplicationController extends Controller
{
    public function __construct(
        private readonly RequestApplicationService $applicationService
    ) {}

    /**
     * Carrier applies for a delivery request.
     */
    public function store(Request $request, DeliveryRequest $deliveryRequest): JsonResponse
    {
        if (!$request->user()->isCarrier()) {
            abort(403, 'Only carriers can apply for delivery requests.');
        }

        $application = $this->applicationService->apply($deliveryRequest, (int) $request->user()->id);

        return RequestApplicationResource::make($application)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Buyer updates status (Accept/Reject).
     */
    public function updateStatus(Request $request, RequestApplication $requestApplication): RequestApplicationResource
    {
        // Only the buyer who owns the delivery request can accept/reject
        if ($requestApplication->deliveryRequest->buyer_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->validate([
            'status' => ['required', 'string', 'in:accepted,rejected'],
        ])['status'];

        if ($status === 'accepted') {
            $this->applicationService->accept($requestApplication);
        } else {
            $this->applicationService->reject($requestApplication);
        }

        return RequestApplicationResource::make($requestApplication);
    }
}
