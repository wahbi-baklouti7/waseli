<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CategoryService
{
    private const CACHE_KEY = 'categories.all';

    /**
     * Retrieve all item categories.
     * Uses Cache::rememberForever since categories are static logic profiles.
     */
    public function getAllCategories(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Category::all();
        });
    }

    /**
     * Clear the categories cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
