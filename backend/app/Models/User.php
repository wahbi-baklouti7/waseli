<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    'role', // Added role
    'password',
    'resident_country_id', // Renamed
    'region_id',         // Renamed
    'is_verified',
    'is_whatsapp_verified', // Added
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
            'is_verified' => 'boolean',
            'is_whatsapp_verified' => 'boolean',
            'role' => UserRole::class, // Enum cast
        ];
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification(''));
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Helper to check roles
     */
    public function isBuyer(): bool
    {
        return $this->role === UserRole::BUYER;
    }

    public function isCarrier(): bool
    {
        
        return $this->role === UserRole::CARRIER;
    }

    /**
     * Relationships
     */
    public function residentCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'resident_country_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
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
