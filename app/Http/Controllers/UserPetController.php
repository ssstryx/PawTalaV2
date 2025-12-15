<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\ActivityLog; // <--- ADDED: Import ActivityLog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserPetController extends Controller
{
    // 1. Display the User's Pets
    public function index()
    {
        // Fetch only pets belonging to the logged-in user
        $pets = Pet::where('user_id', Auth::id())->latest()->get();
        return view('user.pets.index', compact('pets'));
    }

    // 2. Store (Save) a New Pet
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'breed' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'color' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', 
        ]);

        $data = $request->all();
        
        // Force the user_id to be the logged-in user
        $data['user_id'] = Auth::id();
        
        // Default vaccination status
        $data['vaccination_status'] = 'Not Vaccinated'; 

        // --- FIX: AUTOMATICALLY SET REGISTRATION DATE TO TODAY ---
        $data['registration_date'] = now(); 

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('pet_photos', 'public');
            $data['photo_path'] = $path;
        }

        $pet = Pet::create($data); // Capture the created pet object

        // 👇 LOG ACTION
        ActivityLog::log('User Added Pet', "Owner registered a new pet: {$pet->name}");

        return redirect()->route('user.pets')->with('success', 'Pet added successfully!');
    }

    // 3. Update an Existing Pet
    public function update(Request $request, $id)
    {
        $pet = Pet::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['vaccination_status', 'photo']);

        // --- FIX: Handle "Others" Breed Logic ---
        if ($request->breed === 'Others' && $request->filled('other_breed')) {
            $data['breed'] = $request->other_breed;
        }

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            if ($pet->photo_path) {
                Storage::disk('public')->delete($pet->photo_path);
            }
            $path = $request->file('photo')->store('pet_photos', 'public');
            $data['photo_path'] = $path;
        }

        $pet->update($data);

        // 👇 LOG ACTION
        ActivityLog::log('User Updated Pet', "Owner updated pet details: {$pet->name}");

        return redirect()->route('user.pets')->with('success', 'Pet profile updated successfully!');
    }
}