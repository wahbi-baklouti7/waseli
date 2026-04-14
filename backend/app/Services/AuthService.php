<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Enums\UserRole;

final class AuthService
{
    /**
     * Handle user registration.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'resident_country_id' => $data['resident_country_id'] ?? null,
            'region_id' => $data['region_id'] ?? null,
            'role' => $data['role'] ?? UserRole::BUYER->value,
            'status' => 'active',
        ]);

        $this->generateOTP($user);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle user login.
     *
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        // Support both email and phone login
        $user = User::where('email', $data['email'])
            ->orWhere('phone', $data['email']) // Reuse the email field for phone if it's sent as a single identifier
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => [__('auth.failed')],
            ]);
        }

        if ($user->email_verified_at === null) {
            throw ValidationException::withMessages([
                'login' => [__('auth.inactive')],
            ]);
        }

        $user->tokens()->delete(); // Clear existing tokens for single device log in if needed
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle user logout.
     */
    public function logout(User $user): bool
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        return (bool) $token->delete();
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(array $data): string
    {
        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $status;
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(array $data): string
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            $user->tokens()->delete();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return $status;
    }

    /**
     * Handle email verification request.
     */
    public function verifyEmail(int $id, string $hash): void
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
             throw ValidationException::withMessages([
                'email' => ['Invalid verification link.'],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();
    }

    /**
     * Handle resend verification email request.
     */
    public function resendVerificationEmail(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Email already verified.'],
            ]);
        }

        $this->generateOTP($user);
    }

    /**
     * Verify email with OTP code.
     */
    public function verifyOtp(User $user, string $code): bool
    {
        if ($user->hasVerifiedEmail()) {
            return true;
        }

        $verification = EmailVerification::where('user_id', $user->id)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $verification) {
            throw ValidationException::withMessages([
                'code' => [__('auth.otp_not_found')],
            ]);
        }

        if (! hash_equals($verification->code, $code)) {
            $verification->increment('attempts');

            if ($verification->attempts >= 5) {
                $verification->delete();
                throw ValidationException::withMessages([
                    'code' => [__('auth.otp_attempts_exceeded')],
                ]);
            }

            throw ValidationException::withMessages([
                'code' => [__('auth.otp_invalid', ['count' => 5 - $verification->attempts])],
            ]);
        }

        $user->markEmailAsVerified();
        
        // Delete all codes for this user after success
        EmailVerification::where('user_id', $user->id)->delete();

        return true;
    }

    /**
     * Generate a 6-digit OTP and send notification.
     */
    private function generateOTP(User $user): void
    {
        // Delete old codes
        EmailVerification::where('user_id', $user->id)->delete();

        $code = (string) random_int(100000, 999999);

        EmailVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        $user->notify(new \App\Notifications\VerifyEmailNotification($code));
    }
}
