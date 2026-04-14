<?php

namespace App\Enums;

enum TripRequestStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case PICKED_UP = 'picked_up';
    case DELIVERED = 'delivered';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted (Waiting for Pickup)',
            self::PICKED_UP => 'Picked Up',
            self::DELIVERED => 'Delivered',
            self::REJECTED => 'Rejected',
        };
    }
}
