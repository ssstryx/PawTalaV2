<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Veterinarian;
use App\Models\User;

class VeterinarianController extends Controller
{
    public function index()
    {
        // Since this route is now protected by 'super_admin' middleware,
        // we can simply fetch all records without the role check.
        $veterinarians = Veterinarian::with('user')->get();
        $users = User::where('role', 'user')->get(); 

        return view('super_admin.veterinarians.index', compact('veterinarians', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'clinic_name' => 'required|string|max:255',
            'veterinarian_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:veterinarians,license_number',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:veterinarians,email',
            'clinic_address' => 'required|string|max:500',
            'clinic_hours' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $userId = auth()->id();

        Veterinarian::create([
            'user_id' => $userId,
            'clinic_name' => $request->clinic_name,
            'veterinarian_name' => $request->veterinarian_name,
            'license_number' => $request->license_number,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'clinic_address' => $request->clinic_address,
            'clinic_hours' => $request->clinic_hours,
            'specialization' => $request->specialization,
            'status' => $request->status,
        ]);

        // FIX: Redirect to the new Super Admin route
        return redirect()->route('super.veterinarians.index')
            ->with('success', 'Veterinarian added successfully.');
    }

    public function update(Request $request, $id)
    {
        $vet = Veterinarian::findOrFail($id);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'clinic_name' => 'required',
            'veterinarian_name' => 'required',
            'license_number' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'clinic_address' => 'required',
            'clinic_hours' => 'required',
            'specialization' => 'required',
            'status' => 'required',
        ]);

        $vet->update($request->all());

        // FIX: Redirect to the new Super Admin route
        return redirect()->route('super.veterinarians.index')
            ->with('success', 'Veterinarian updated successfully');
    }

    // 1. Destroy (Archive)
    public function destroy($id)
    {
        $vet = Veterinarian::findOrFail($id);
        $vet->delete(); // Soft delete
        return redirect()->back()->with('success', 'Veterinarian archived successfully.');
    }

    // 2. View Archived Page
    public function archived()
    {
        // FIX: Simplified for Super Admin (View all archived)
        $archivedVets = Veterinarian::onlyTrashed()->with('user')->get();

        return view('super_admin.veterinarians.archived', compact('archivedVets'));
    }

    // 3. Restore
    public function restore($id)
    {
        $vet = Veterinarian::withTrashed()->findOrFail($id);
        $vet->restore(); // Bring it back
        
        // FIX: Redirect to the new Super Admin route
        return redirect()->route('super.veterinarians.index')
            ->with('success', 'Veterinarian restored successfully.');
    }
}