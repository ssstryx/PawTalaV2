<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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
