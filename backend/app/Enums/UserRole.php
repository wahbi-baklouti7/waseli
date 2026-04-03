<?php

namespace App\Enums;

enum UserRole: string
{
    case BUYER = 'buyer';
    case CARRIER = 'carrier';

    /**
     * Get the boolean traveler value for the role.
     */
    public function isTraveler(): bool
    {
        return $this === self::CARRIER;
    }
}
