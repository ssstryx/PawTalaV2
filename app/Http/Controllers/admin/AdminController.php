<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get the logged-in admin's barangay
        $myBarangay = auth()->user()->barangay;

        // FETCH USERS: Only get users from the SAME barangay
        $users = User::where('barangay', $myBarangay)
                    ->where('role', 'user') // Assuming you have normal users too
                    ->get();

        return view('admin.app', compact('users'));
    }

    public function store(Request $request)
    {
        // WHEN ADDING A NEW USER:
        // Force the barangay field to match the Admin's barangay automatically
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user', 
            'barangay' => auth()->user()->barangay, // <--- AUTOMATIC ASSIGNMENT
        ]);

        return redirect()->back()->with('success', 'User added to ' . auth()->user()->barangay);
    }
}
