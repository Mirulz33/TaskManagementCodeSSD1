<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Allow all users to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for login.
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Handle authentication attempt.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // Check if user exceeded login attempts
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        // Get user by email
        $user = User::where('email', $credentials['email'])->first();

        // Admin-level block check
        if ($user && $user->is_blocked) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been blocked by the administrator.',
            ]);
        }

        // Failed login attempt
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {

            // Lock for 15 minutes (900 seconds)
            RateLimiter::hit($this->throttleKey(), 900);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Successful login → reset limiter
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Check rate limit before authentication.
     */
    public function ensureIsNotRateLimited(): void
    {
        // Allow only 5 attempts
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Fire lockout event
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Generate unique throttle key (email + IP).
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
