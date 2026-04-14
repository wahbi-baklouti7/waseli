<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class LocationService
{
    private const CACHE_KEY = 'locations.all';

    /**
     * Retrieve all countries with their associated regions.
     * Uses Cache::rememberForever since location data is static.
     */
    public function getCountriesWithRegions(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Country::with('regions')->get();
        });
    }

    /**
     * Clear the locations cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
