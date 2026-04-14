<?php

declare(strict_types=1);

namespace App\Services\Logistics;

use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

final class TripService
{
    /**
     * @param array<string, mixed> $data
     * @return Trip
     */
    public function createTrip(array $data): Trip
    {
        return DB::transaction(function () use ($data) {
            $trip = Trip::create($data);
            return $trip->fresh(['carrier', 'category', 'departedCountry', 'arrivalCity.country']);
        });
    }

    /**
     * @param Trip $trip
     * @param array<string, mixed> $data
     * @return Trip
     */
    public function updateTrip(Trip $trip, array $data): Trip
    {
        return DB::transaction(function () use ($trip, $data) {
            $trip->update($data);
            return $trip->fresh();
        });
    }

    /**
     * @param Trip $trip
     * @return bool
     */
    public function deleteTrip(Trip $trip): bool
    {
        return DB::transaction(fn () => $trip->delete());
    }

    /**
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listTrips(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Trip::query()->with(['carrier', 'category', 'departedCountry', 'arrivalCity.country']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->forCategory((int) $filters['category_id']);
        }

        if (!empty($filters['arrival_city_id'])) {
            $query->forRegion((int) $filters['arrival_city_id']);
        }

        if (!empty($filters['departed_country_id'])) {
            $query->forCountry((int) $filters['departed_country_id']);
        }

        return $query->latest()->paginate($perPage);
    }
}
