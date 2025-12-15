<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\ActivityLog;

class SuperAdminController extends Controller
{
    public function activityLogs()
    {
        // Fetch logs, latest first, 10 per page
        $activities = ActivityLog::latest()->paginate(10);

        return view('super_admin.activity_logs', compact('activities'));
    }
    public function users(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('barangay')
            ->whereIn('role', ['super_admin', 'admin'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('barangay', function($q) use ($search){
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10);

        $users->appends(['search' => $search]);

        $barangays = Barangay::all(); 

        return view('super_admin.users', compact('users', 'barangays'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'barangay_id' => 'nullable|exists:barangays,id',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'barangay_id' => $request->barangay_id,
            'is_active' => true,
        ]);

        event(new Registered($user));
        \App\Models\ActivityLog::log('Created Admin', 'Added new admin account: ' . $user->email);

        return redirect()->back()->with('success', 'New Admin created successfully.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'barangay_id' => 'nullable|exists:barangays,id',
        ]);

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->barangay_id = $request->barangay_id;

        if ($request->has('role')) {
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Admin updated successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot deactivate yourself.');
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'Status updated.');
    }
}