@extends('layouts.user.app')

@section('content')
<div class="container-fluid py-2">

    {{-- 1. WELCOME BANNER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" 
                 style="background: linear-gradient(135deg, #4E342E 0%, #6D4C41 100%); border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->first_name }}! 👋</h2>
                        <p class="mb-0 text-white-50">Here's what's happening with your furry family today.</p>
                    </div>
                    <div class="d-none d-md-block opacity-25">
                        <i class="fas fa-paw fa-4x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. STATS CARDS --}}
    <div class="row g-4 mb-4">
        {{-- Total Pets Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 d-flex justify-content-center align-items-center" 
                             style="background-color: #FFF3E0; width: 60px; height: 60px;">
                            <i class="fas fa-dog fa-2x" style="color: #E59500;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold mb-0 small">Total Pets</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ $totalPets }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vaccination Status Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 d-flex justify-content-center align-items-center" 
                             style="background-color: #E8F5E9; width: 60px; height: 60px;">
                            <i class="fas fa-syringe fa-2x" style="color: #2E7D32;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold mb-0 small">Needs Vaccination</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ $needsVaccine }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Action Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center" 
                 style="background-color: #E59500; border-radius: 15px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center">
                    <h5 class="fw-bold text-white mb-3">Have a new companion?</h5>
                    <a href="{{ route('user.pets') }}" class="btn btn-light fw-bold w-100 rounded-pill" style="color: #E59500;">
                        <i class="fas fa-plus me-2"></i> Register New Pet
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. RECENT PETS & INFO --}}
    <div class="row g-4">
        {{-- Your Pets List --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">🐶 Your Pets at a Glance</h5>
                </div>
                <div class="card-body">
                    @if($recentPets->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <tbody>
                                    @foreach($recentPets as $pet)
                                    <tr>
                                        <td width="60">
                                            <img src="{{ $pet->photo_path ? asset('storage/' . $pet->photo_path) : asset('images/default-pet.png') }}" 
                                                 class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $pet->name }}</h6>
                                            <small class="text-muted">{{ $pet->breed }}</small>
                                        </td>
                                        <td>
                                            @if($pet->vaccination_status == 'Fully Vaccinated')
                                                <span class="badge bg-success rounded-pill px-3">Vaccinated</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3">{{ $pet->vaccination_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('user.pets') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted">You haven't added any pets yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tips Section --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">💡 Pet Care Tip</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded bg-light mb-3 border-start border-4 border-warning">
                        <p class="mb-0 text-dark fst-italic small">
                            "Regular vaccinations are crucial to protect your pet from common infectious diseases. Check your pet's status regularly!"
                        </p>
                    </div>
                    {{-- Placeholder image for visual appeal --}}
                    <div class="bg-light rounded d-flex justify-content-center align-items-center" style="height: 150px;">
                        <i class="fas fa-heart fa-4x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection