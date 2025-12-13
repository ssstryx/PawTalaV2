<?php

namespace App\Http\Controllers\Barangay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarangayDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
