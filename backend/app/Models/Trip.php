<?php

namespace App\Models;

use App\Enums\TripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

final class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        'category_id',
        'departed_country_id',
        'arrival_city_id',
        'arrival_date',
        'status',
    ];

    protected $casts = [
        'arrival_date' => 'datetime',
        'status' => TripStatus::class,
    ];

    protected $attributes = [
        'status' => 'open',
    ];

    // Relationships
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'carrier_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function departedCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'departed_country_id');
    }

    public function arrivalCity(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'arrival_city_id');
    }

    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TripRequest::class);
    }

    // Scopes
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', TripStatus::Open);
    }

    public function scopeForRegion(Builder $query, int $regionId): Builder
    {
        return $query->where('arrival_city_id', $regionId);
    }

    public function scopeForCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeForCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('departed_country_id', $countryId);
    }
}
