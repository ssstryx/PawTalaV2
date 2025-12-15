<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- THIS WAS MISSING
use App\Models\Pet; 

class UserController extends Controller
{
    public function home()
    {
        $user = Auth::user();

        // 1. Get Total Pets Count
        $totalPets = Pet::where('user_id', $user->id)->count();

        // 2. Get Count of Pets needing vaccination
        $needsVaccine = Pet::where('user_id', $user->id)
                            ->where('vaccination_status', '!=', 'Fully Vaccinated')
                            ->count();

        // 3. Get the 3 most recently updated pets for a quick preview
        $recentPets = Pet::where('user_id', $user->id)
                         ->latest()
                         ->take(3)
                         ->get();

        return view('user.home', compact('totalPets', 'needsVaccine', 'recentPets'));
    }

    public function pets()
    {
        // This function might be redundant if you are using UserPetController for the pets page.
        // But if your routes point here, redirect or return the view.
        // For now, let's just return the view to be safe.
        $pets = Pet::where('user_id', Auth::id())->latest()->get();
        return view('user.pets.index', compact('pets')); 
    }
}