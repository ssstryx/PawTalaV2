<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        Log::info('Login attempt initiated for email: ' . $this->string('email'));

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            Log::warning('Authentication failed for email: ' . $this->string('email'));
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        
        Log::info('Authentication successful for user: ' . Auth::user()->id);
        $user = Auth::user();

        // After successful authentication, check if the user is an admin or user and if their email is verified.
        Log::info('Checking email verification for user ID: ' . $user->id . ' with role: ' . $user->role);
        if (($user->role === 'admin' || $user->role === 'user') && !$user->hasVerifiedEmail()) {
            // Log the user out.
            Auth::logout();
            Log::warning('User ID: ' . $user->id . ' logged out due to unverified email.');
            // Throw a validation exception with a custom message.
            throw ValidationException::withMessages([
                'email' => 'You must verify your email address before you can log in.',
            ]);
        }
        Log::info('Email verification passed for user ID: ' . $user->id);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

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
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
