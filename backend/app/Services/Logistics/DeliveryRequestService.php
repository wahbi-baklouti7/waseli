<?php

declare(strict_types=1);

namespace App\Services\Logistics;

use App\Models\DeliveryRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class DeliveryRequestService
{
    /**
     * List delivery requests with filters and pagination.
     */
    public function list(array $filters): LengthAwarePaginator
    {
        return DeliveryRequest::query()
            ->with(['buyer', 'arrivalCity.country', 'category'])
            ->pending()
            ->filter($filters)
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new delivery request.
     */
    public function create(array $data, int $buyerId): DeliveryRequest
    {
        return DB::transaction(function () use ($data, $buyerId) {
            return DeliveryRequest::create([
                'buyer_id' => $buyerId,
                'category_id' => $data['category_id'],
                'arrival_city_id' => $data['arrival_city_id'],
                'date' => $data['date'],
                'description' => $data['description'] ?? null,
                'status' => 'pending',
            ]);
        });
    }

    /**
     * Cancel a delivery request.
     */
    public function cancel(DeliveryRequest $deliveryRequest): bool
    {
        return $deliveryRequest->update(['status' => 'cancelled']);
    }

    /**
     * Delete a delivery request.
     */
    public function delete(DeliveryRequest $deliveryRequest): bool
    {
        return $deliveryRequest->delete();
    }
}
