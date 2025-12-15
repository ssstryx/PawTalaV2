<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\ActivityLog; // <--- ADDED: Import ActivityLog
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Logged-in Admin's Barangay ID
        $barangayId = Auth::user()->barangay_id;

        // 2. Start Query: Filter pets belonging to owners in this barangay
        $query = Pet::with('user')
            ->whereHas('user', function($q) use ($barangayId) {
                $q->where('barangay_id', $barangayId);
            });

        // --- Apply Filters ---

        // Filter by Species
        if ($request->filled('species') && $request->species != 'All Species') {
            $query->where('species', $request->species);
        }

        // Filter by Vaccination Status
        if ($request->filled('status') && $request->status != 'All Statuses') {
            if ($request->status == 'Vaccinated') {
                $query->where('vaccination_status', 'Fully Vaccinated');
            } elseif ($request->status == 'Unvaccinated') {
                $query->where('vaccination_status', '!=', 'Fully Vaccinated');
            }
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        $pets = $query->latest()->get();
        
        // --- LOG ACTION ---
        $filters = '';
        if ($request->filled('species') || $request->filled('status') || $request->filled('start_date')) {
            $filters = ' with filters applied.';
        } else {
            $filters = ' (Master List).';
        }

        ActivityLog::log('Admin Generated Report', 'Generated Pet Report for Barangay ID ' . $barangayId . $filters);

        return view('admin.reports.index', compact('pets'));
    }
}