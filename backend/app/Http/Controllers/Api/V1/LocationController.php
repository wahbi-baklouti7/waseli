<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Region;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    use ApiResponse;

    /**
     * List all countries.
     */
    public function countries(): JsonResponse
    {
        return $this->ok('Countries retrieved', [
            'countries' => Country::select('id', 'name', 'code')->get(),
        ]);
    }

    /**
     * List all Tunisian regions.
     */
    public function regions(): JsonResponse
    {
        return $this->ok('Regions retrieved', [
            'regions' => Region::select('id', 'name')->get(),
        ]);
    }
}
