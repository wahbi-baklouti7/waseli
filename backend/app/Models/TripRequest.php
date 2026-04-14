<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TripRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['trip_id', 'buyer_id', 'status', 'delivery_code'])]
final class TripRequest extends Model
{
    /** @use HasFactory<\Database\Factories\TripRequestFactory> */
    use HasFactory;

    protected $casts = [
        'status' => TripRequestStatus::class,
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Relationships
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Helpers
     */
    public function isPending(): bool
    {
        return $this->status === TripRequestStatus::PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === TripRequestStatus::ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === TripRequestStatus::REJECTED;
    }
}
