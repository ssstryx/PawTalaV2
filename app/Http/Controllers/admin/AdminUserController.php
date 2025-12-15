<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog; // <--- ADDED: Import ActivityLog
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // <--- ADDED: Needed for validation
use Illuminate\Auth\Events\Registered;    // <--- ADDED: Needed for event

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $adminBarangayId = Auth::user()->barangay_id; 

        $users = User::where('role', 'user')
            ->where('barangay_id', $adminBarangayId) 
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        $users->appends(['search' => $search]);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        
        // FIX: Assign the created user to the $user variable
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'barangay_id' => Auth::user()->barangay_id, 
            'role' => 'user',
        ]);

        // Log the action
        ActivityLog::log('Admin Added User', "Created new user account: {$user->first_name} {$user->last_name}");
        
        event(new Registered($user));

        return redirect()->route('barangay.users.index')
            ->with('success', 'User created successfully. Verification email has been sent.');
    }

    public function show($id)
    {
        $user = User::with('barangay')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('barangay')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
        ]);

        // Log the action
        ActivityLog::log('Admin Updated User', "Updated details for user: {$user->first_name} {$user->last_name}");

        return redirect()->route('barangay.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate yourself.');
        }

        // Optional: Check verification (You can comment this out if you want to allow archiving unverified users)
        if (is_null($user->email_verified_at)) {
            return back()->with('error', 'User must verify their email before manually changing status.');
        }
        
        $user->is_active = !$user->is_active;
        $user->save();

        // FIX: Add Activity Log here
        $logStatus = $user->is_active ? 'User Activated' : 'User Deactivated';
        ActivityLog::log($logStatus, "Barangay Admin changed status for: {$user->email}");

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User has been $status.");
    }
}