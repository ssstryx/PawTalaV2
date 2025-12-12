<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CustomVerificationController;
use App\Http\Controllers\admin\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Main Dashboard Redirect Logic
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'super_admin') {
        // Redirect Super Admin to Activity Logs by default
        return redirect()->route('super.activity_logs');
    } elseif ($user->role === 'admin') {
        return view('admin.dashboard'); 
    } else {
        return view('dashboard'); 
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// SUPER ADMIN ROUTES
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // 1. Activity Logs
    Route::get('/super-admin/activity-logs', [SuperAdminController::class, 'activityLogs'])->name('super.activity_logs');
    
    // 2. User Management
    Route::get('/super-admin/users', [SuperAdminController::class, 'userManagement'])->name('super.users');
    Route::post('/super-admin/create-admin', [SuperAdminController::class, 'storeAdmin'])->name('super.create_admin');
    Route::patch('/super-admin/users/{id}/toggle', [SuperAdminController::class, 'toggleStatus'])->name('super.toggle_status');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/verify-account/{id}/{hash}', [CustomVerificationController::class, 'verify'])
    ->middleware(['signed']) // Ensures the link hasn't been tampered with
    ->name('custom.verification.verify');

Route::get('/access-denied', function () {
    return view('auth.access-denied');
})->name('access.denied');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('barangay.')->group(function () {
    
    // 1. Dashboard (Replaces Activity Logs)
    Route::get('/dashboard', [BarangayDashboardController::class, 'index'])->name('dashboard');

    // 2. User Management (Scoped to their Barangay)
    Route::resource('users', BarangayUserController::class);

    // 3. Pet Registration & Records
    Route::resource('pets', PetController::class);
    
    // 4. Vaccination & Certificates (You can add these later)
    Route::post('/pets/{pet}/vaccine', [PetController::class, 'addVaccine'])->name('pets.vaccine');
    Route::post('/pets/{pet}/certificate', [PetController::class, 'issueCertificate'])->name('pets.certificate');
});

require __DIR__.'/auth.php';