<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Barangay;

class SuperAdminReportController extends Controller
{
    public function index(Request $request)
    {
        // Start building the query
        $query = Pet::with(['user.barangay']); // Eager load user and their barangay

        // 1. Filter by Barangay
        if ($request->filled('barangay_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('barangay_id', $request->barangay_id);
            });
        }

        // 2. Filter by Species
        if ($request->filled('species') && $request->species != 'All') {
            $query->where('species', $request->species);
        }

        // 3. Filter by Vaccination Status
        if ($request->filled('status')) {
            if ($request->status == 'Vaccinated') {
                $query->where('vaccination_status', 'Fully Vaccinated');
            } elseif ($request->status == 'Unvaccinated') {
                $query->where('vaccination_status', '!=', 'Fully Vaccinated');
            }
        }

        // 4. Filter by Registration Date
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Get results (No pagination for reports, usually we want the full list for printing)
        $pets = $query->latest()->get();

        // Get all Barangays for the filter dropdown
        $barangays = Barangay::all();

        return view('super_admin.reports.index', compact('pets', 'barangays'));
    }
}