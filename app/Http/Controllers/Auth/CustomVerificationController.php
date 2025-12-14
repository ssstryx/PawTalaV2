<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class CustomVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // 1. Check if the signature is valid (Security Check)
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        // 2. Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified. Please login.');
        }

        // 3. Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // 4. Redirect to Login Page
        return redirect()->route('login', ['status' => 'email-verified']);
    }
}