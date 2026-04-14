<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Fulfillment;

use App\Http\Controllers\Controller;
use App\Models\TripRequest;
use App\Services\FulfillmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeliveryVerificationController extends Controller
{
    public function __construct(
        private readonly FulfillmentService $fulfillmentService
    ) {}

    /**
     * Carrier marks package as picked up.
     */
    public function pickUp(Request $request, TripRequest $tripRequest): JsonResponse
    {
        // Only the carrier of the trip can mark as picked up
        if ($tripRequest->trip->carrier_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->fulfillmentService->pickUp($tripRequest);

        return response()->json([
            'message' => 'Package marked as picked up.',
            'status' => $tripRequest->status,
        ]);
    }

    /**
     * Carrier verifies delivery code to complete fulfillment.
     */
    public function verify(Request $request, TripRequest $tripRequest): JsonResponse
    {
        // Only the carrier of the trip can verify delivery
        if ($tripRequest->trip->carrier_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'delivery_code' => ['required', 'string'],
        ]);

        $this->fulfillmentService->verifyAndDeliver($tripRequest, $data['delivery_code']);

        return response()->json([
            'message' => 'Delivery verified successfully. Fulfillment complete.',
            'status' => $tripRequest->status,
        ]);
    }
}
