<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return $this->ok('Registration successful. Please verify your email.', [
            'user' => UserResource::make($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    /**
     * Handle user login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return $this->ok('Login successful', [
            'user' => UserResource::make($result['user']),
            'token' => $result['token'],
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->ok('Logged out successfully');
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->ok('User profile retrieved', [
            'user' => UserResource::make($request->user()),
        ]);
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->forgotPassword($request->validated());

        return $this->ok(__($status));
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request->validated());

        return $this->ok(__($status));
    }

    /**
     * Handle email verification request.
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $this->authService->verifyEmail((int) $request->route('id'), (string) $request->route('hash'));

        return $this->ok('Email verified successfully');
    }

    /**
     * Verify email with OTP code.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'email' => ['required_without:user', 'nullable', 'string'],
        ]);

        $user = $request->user();
        
        if (! $user) {
            $user = User::where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => [__('auth.failed')],
                ]);
            }
        }

        $this->authService->verifyOtp($user, $request->code);

        return $this->ok('E-mail vérifié avec succès');
    }

    /**
     * Handle resend verification email request.
     */
    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required_without:user', 'nullable', 'string'],
        ]);

        $user = $request->user();
        
        if (! $user) {
            $user = User::where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => [__('auth.failed')],
                ]);
            }
        }

        $this->authService->resendVerificationEmail($user);

        return $this->ok('Un nouveau code de vérification a été envoyé');
    }
}
