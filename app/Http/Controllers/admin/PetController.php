<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\ActivityLog; // <--- ADDED: Import ActivityLog
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $adminBarangayId = Auth::user()->barangay_id;

        $pets = Pet::with('user')
            ->whereHas('user', function($query) use ($adminBarangayId) {
                $query->where('barangay_id', $adminBarangayId);
            })
            ->when($search, function ($query, $search) {
               // ... existing search logic
               $query->where('name', 'like', "%{$search}%")
                     ->orWhere('owner_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        $pets->appends(['search' => $search]);

        $users = User::where('role', 'user')
                     ->where('barangay_id', $adminBarangayId)
                     ->get();

        $veterinarians = \App\Models\Veterinarian::all(); 

        return view('admin.pets.index', compact('pets', 'users', 'veterinarians'));
    }

    public function create()
    {
        return view('admin.pets.create');
    }

    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'breed' => 'nullable|string',
            'other_breed' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'color' => 'nullable|string',
            'vaccination_status' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'veterinarian_id' => 'nullable|exists:veterinarians,id',
        ]);

        $owner = User::find($request->user_id);

        // Handle Breed
        $finalBreed = $request->breed;
        if ($request->breed === 'Others') {
            $finalBreed = $request->other_breed;
        }

        // Handle Photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pet_photos', 'public');
        }

        // 2. Save the Pet
        $pet = Pet::create([
            'user_id' => $request->user_id, 
            'owner_name' => $owner->first_name . ' ' . $owner->last_name,
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'breed' => $finalBreed,
            'birthdate' => $request->birthdate,
            'weight' => $request->weight,
            'color' => $request->color,
            'vaccination_status' => $request->vaccination_status,
            'photo_path' => $photoPath,
            'registration_date' => now(),
            'veterinarian_id' => $request->veterinarian_id,
        ]);

        // 👇 LOG ACTION
        ActivityLog::log('Admin Registered Pet', "Registered new pet '{$pet->name}' for owner: {$owner->first_name} {$owner->last_name}");

        return redirect()->route('barangay.pets.index')
            ->with('success', 'Pet registered successfully!');
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $currentUser = auth()->user();

        if ($currentUser->role === 'super_admin') {
            $users = User::where('role', 'user')->get();
        } else {
            $users = User::where('role', 'user')
                ->where('barangay_id', $currentUser->barangay_id)
                ->get();
        }

        return view('admin.pets.edit', compact('pet', 'users'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'sex' => 'required|string',
            'breed' => 'nullable|string',
            'other_breed' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'color' => 'nullable|string',
            'vaccination_status' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'veterinarian_id' => 'nullable|exists:veterinarians,id',
        ]);

        $owner = User::find($request->user_id);

        // Handle Breed Logic
        $finalBreed = $request->breed;
        if ($request->breed === 'Others') {
            $finalBreed = $request->other_breed;
        }

        // Prepare Data
        $data = [
            'user_id' => $request->user_id,
            'owner_name' => $owner->first_name . ' ' . $owner->last_name,
            'name' => $request->name,
            'species' => $request->species,
            'sex' => $request->sex,
            'breed' => $finalBreed,
            'birthdate' => $request->birthdate,
            'weight' => $request->weight,
            'color' => $request->color,
            'vaccination_status' => $request->vaccination_status,
            'veterinarian_id' => $request->veterinarian_id,
        ];

        // Handle Photo Update
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('pet_photos', 'public');
        }

        $pet->update($data);

        // 👇 LOG ACTION
        ActivityLog::log('Admin Updated Pet', "Updated details for pet '{$pet->name}'");

        return redirect()->route('barangay.pets.index') 
            ->with('success', 'Pet updated successfully!');
    }

    // 1. ARCHIVE (Soft Delete)
    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);
        $petName = $pet->name; // Capture name before deleting
        $pet->delete();

        // 👇 LOG ACTION
        ActivityLog::log('Admin Archived Pet', "Archived pet record: {$petName}");

        return redirect()->back()->with('success', 'Pet archived successfully.');
    }

    // 2. VIEW ARCHIVED PAGE
    public function archived()
    {
        $currentUser = auth()->user();

        if ($currentUser->role === 'super_admin') {
             $archivedPets = Pet::onlyTrashed()->with('user')->get();
        } else {
             $archivedPets = Pet::onlyTrashed()->with('user')
                ->whereHas('user', function ($query) use ($currentUser) {
                    $query->where('barangay_id', $currentUser->barangay_id);
                })->get();
        }
        
        foreach($archivedPets as $pet){
            $pet->formatted_id = 'PT-' . str_pad($pet->id, 3, '0', STR_PAD_LEFT);
        }

        return view('admin.pets.archived', compact('archivedPets'));
    }

    // 3. RESTORE
    public function restore($id)
    {
        $pet = Pet::withTrashed()->findOrFail($id);
        $pet->restore();

        // 👇 LOG ACTION
        ActivityLog::log('Admin Restored Pet', "Restored pet record: {$pet->name}");

        return redirect()->route('barangay.pets.index')->with('success', 'Pet restored successfully.');
    }
}