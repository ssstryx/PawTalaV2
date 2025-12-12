<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewAdminVerification;
use Illuminate\Validation\Rules\Password;

class SuperAdminController extends Controller
{
    // Tab 1: Activity Logs (The new home page)
    public function activityLogs()
    {
        return view('super_admin.activity_logs');
    }

    // Tab 2: User Management (Where the form goes)
    public function userManagement()
    {
        $admins = User::where('role', 'admin')->get();
        return view('super_admin.users', compact('admins'));
    }

    // Logic to add a new Barangay Admin
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required',
                    Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                ],
            'barangay' => 'required|string',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'barangay' => $request->barangay,
            'email_verified_at' => null, // FORCE NULL so they have to verify
            'is_active' => true,
        ]);

        // Send the verification notification
        // We pass the raw password so it appears in the email
        $user->notify(new NewAdminVerification($request->password));

        return redirect()->route('super.users')->with('success', 'Admin created! A verification link has been sent to their email.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent Super Admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate yourself.');
        }

        // Flip the status (True -> False, or False -> True)
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User has been $status.");
    }
}
