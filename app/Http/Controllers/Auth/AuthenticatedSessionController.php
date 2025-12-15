<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException; 
use App\Models\User;
use App\Models\ActivityLog; // <--- 1. Import the ActivityLog Model

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Check if user exists and credentials are correct
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Check for specific error reasons (Verification or Deactivation)
            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (is_null($user->email_verified_at)) {
                    // Unverified Email
                    throw ValidationException::withMessages([
                        'email' => ['Please verify your email address to activate your account.'],
                    ]);
                }
                
                if (! $user->is_active) {
                    // Deactivated by Admin
                    throw ValidationException::withMessages([
                        'email' => ['Your account is currently inactive. Please contact your administrator.'],
                    ]);
                }
            }
            
            // Default "Invalid Credentials" message
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }
        
        // Success (already verified and active)
        $request->session()->regenerate();

        // <--- 2. Log the Login Action
        ActivityLog::log('User Login', 'User logged into the system.');

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // <--- 3. Log the Logout Action (Must be before actual logout to capture User ID)
        ActivityLog::log('User Logout', 'User logged out.');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}