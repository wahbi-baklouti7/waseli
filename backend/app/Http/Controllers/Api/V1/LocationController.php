<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CountryResource;
use App\Services\LocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LocationService $locationService
    ) {}

    /**
     * List all countries with their associated regions.
     */
    public function index(): JsonResponse
    {
        $countries = $this->locationService->getCountriesWithRegions();

        return $this->ok(
            'Locations retrieved successfully',
            CountryResource::collection($countries)
        );
    }
}
