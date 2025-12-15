<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\super_admin\VeterinarianController;
use App\Http\Controllers\super_admin\SuperAdminController;
use App\Http\Controllers\super_admin\SuperAdminReportController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserPetController;
use App\Http\Controllers\Auth\CustomVerificationController;

// Admin Controllers
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\PetController;
use App\Http\Controllers\admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Main Dashboard Redirect Logic
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'super_admin') {
        return redirect()->route('super.activity_logs');
    } elseif ($user->role === 'admin') {
        return redirect()->route('barangay.dashboard');
    } elseif ($user->role === 'user') {
        return redirect()->route('user.home');
    } else {
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// --- USER ROUTES ---
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function() {
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::get('/pets', [UserPetController::class, 'index'])->name('pets');
    Route::post('/pets/store', [UserPetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{id}', [UserPetController::class, 'update'])->name('pets.update');
});

// --- SUPER ADMIN ROUTES ---
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    
    // 1. Activity Logs
    Route::get('/super-admin/activity-logs', [SuperAdminController::class, 'activityLogs'])->name('super.activity_logs');
    
    // 2. User Management (FIX: Changed 'userManagement' to 'users')
    Route::get('/super-admin/users', [SuperAdminController::class, 'users'])->name('super.users');
    
    // 3. Reports (FIX: Added the correct name 'super.reports.index')
    Route::get('/super-admin/reports', [SuperAdminReportController::class, 'index'])->name('super.reports.index');

    // 4. Other Actions
    Route::post('/super-admin/create-admin', [SuperAdminController::class, 'storeAdmin'])->name('super.create_admin');
    Route::patch('/super-admin/users/{id}/toggle', [SuperAdminController::class, 'toggleStatus'])->name('super.toggle_status');
    Route::patch('/super-admin/users/{user}', [SuperAdminController::class, 'updateAdmin'])->name('super.update_admin');

    // 5. Veterinarians
    Route::prefix('super-admin')->name('super.')->group(function() {
        Route::get('/veterinarians/archived', [VeterinarianController::class, 'archived'])->name('veterinarians.archived');
        Route::patch('/veterinarians/{id}/restore', [VeterinarianController::class, 'restore'])->name('veterinarians.restore');
        Route::resource('veterinarians', VeterinarianController::class);
    });
});
    // === VETERINARIAN MANAGEMENT ===
    Route::prefix('super-admin')->name('super.')->group(function() {
        Route::get('/veterinarians/archived', [VeterinarianController::class, 'archived'])->name('veterinarians.archived');
        Route::patch('/veterinarians/{id}/restore', [VeterinarianController::class, 'restore'])->name('veterinarians.restore');
        Route::resource('veterinarians', VeterinarianController::class);
    });

// --- PROFILE ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- VERIFICATION & DENIED ---
Route::get('/verify-account/{id}/{hash}', [CustomVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('custom.verification.verify');

Route::get('/access-denied', function () {
    return view('auth.access-denied');
})->name('access.denied');

// --- BARANGAY ADMIN ROUTES ---
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('barangay.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle_status');
    Route::resource('users', AdminUserController::class)->except(['update']); 
    
    Route::get('/pets/archived', [PetController::class, 'archived'])->name('pets.archived');
    Route::patch('/pets/{id}/restore', [PetController::class, 'restore'])->name('pets.restore');
    Route::resource('pets', PetController::class);
    Route::post('/pets/{pet}/vaccine', [PetController::class, 'addVaccine'])->name('pets.vaccine');
    Route::post('/pets/{pet}/certificate', [PetController::class, 'issueCertificate'])->name('pets.certificate');
    
    // Barangay Admin Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';