<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'buyer_id',
    'arrival_city_id',
    'category_id',
    'date',
    'description',
    'status'
])]
final class DeliveryRequest extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryRequestFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relationships
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function arrivalCity(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'arrival_city_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestApplication::class, 'request_id');
    }

    /**
     * Scopes
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['category_id'] ?? null, fn($q, $id) => $q->where('category_id', $id))
            ->when($filters['arrival_city_id'] ?? null, fn($q, $id) => $q->where('arrival_city_id', $id))
            ->when($filters['date'] ?? null, fn($q, $date) => $q->where('date', $date));
    }
}
