<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. If not logged in, let the system handle it
        if (!$user) {
            return $next($request);
        }
        // 2. If Super Admin, always allow
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // 3. CORRECT CHECK: Use 'is_active' and compare to 1
        if ($user->is_active != 1) {
            
            // Log them out
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('access.denied');
        }

        return $next($request);
    }
}
