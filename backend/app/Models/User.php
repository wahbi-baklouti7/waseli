<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;

#[Fillable([
    'first_name',
    'last_name',
    'email',
    'phone',
    'password',
    'residence_country_id',
    'region_id',
    'is_traveler',
    'is_verified',
    'trust_score',
    'status'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification(''));
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the user's role based on their is_traveler value.
     */
    public function getRoleAttribute(): \App\Enums\UserRole
    {
        return $this->is_traveler ? \App\Enums\UserRole::CARRIER : \App\Enums\UserRole::BUYER;
    }

    /**
     * Determine if the user is a buyer.
     */
    public function isBuyer(): bool
    {
        return ! $this->is_traveler;
    }

    /**
     * Determine if the user is a carrier.
     */
    public function isCarrier(): bool
    {
        return $this->is_traveler;
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'residence_country_id');
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'tunisian_city_id');
    }


    /**
     * Mark the user's email as verified and update persistence.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'is_verified' => true,
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
