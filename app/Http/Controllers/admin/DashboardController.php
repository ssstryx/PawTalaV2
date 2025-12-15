<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;  
use App\Models\User; 
use Illuminate\Support\Facades\Auth; // <--- Import Auth

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get Logged-in Admin's Barangay ID
        $barangayId = Auth::user()->barangay_id;

        // 2. Filter Total Pets (Only count pets owned by users in this barangay)
        $totalPets = Pet::whereHas('user', function($q) use ($barangayId) {
            $q->where('barangay_id', $barangayId);
        })->count();

        // 3. Filter Total Owners (Only count users in this barangay)
        $totalOwners = User::where('role', 'user')
                           ->where('barangay_id', $barangayId)
                           ->count();

        // 4. Filter Vaccinated Pets (In this barangay)
        $fullyVaccinated = Pet::where('vaccination_status', 'Fully Vaccinated')
            ->whereHas('user', function($q) use ($barangayId) {
                $q->where('barangay_id', $barangayId);
            })->count();

        // 5. Filter Unvaccinated Pets (In this barangay)
        $incompleteVaccination = Pet::where('vaccination_status', '!=', 'Fully Vaccinated')
            ->whereHas('user', function($q) use ($barangayId) {
                $q->where('barangay_id', $barangayId);
            })->count();

        // 6. Vaccination Percentage
        $vaccinationRate = $totalPets > 0 ? round(($fullyVaccinated / $totalPets) * 100) : 0;

        // 7. Recent Registrations (Only from this barangay)
        $recentPets = Pet::with('user')
            ->whereHas('user', function($q) use ($barangayId) {
                $q->where('barangay_id', $barangayId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPets', 
            'totalOwners', 
            'fullyVaccinated', 
            'incompleteVaccination',
            'vaccinationRate',
            'recentPets'
        ));
    }
}