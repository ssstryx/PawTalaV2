@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold" style="color: #4E342E;">Barangay Pet Overview</h1>
        {{-- Print Report Button (Optional Placeholder) --}}
        <button class="btn btn-sm btn-secondary shadow-sm" onclick="window.print()">
            <i class="fas fa-print fa-sm text-white-50"></i> Generate Report
        </button>
    </div>

    {{-- 1. STATS CARDS ROW --}}
    <div class="row g-4 mb-4">
        
        {{-- Total Pets --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 border-start border-4 border-primary" style="border-color: #4E342E !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4E342E;">Total Registered Pets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300" style="color: #d1d1d1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Owners --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 border-start border-4" style="border-color: #E59500 !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #E59500;">Pet Owners</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOwners }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300" style="color: #d1d1d1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fully Vaccinated --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 border-start border-4 border-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Fully Vaccinated</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $fullyVaccinated }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300" style="color: #d1d1d1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Vaccination --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Needs Vaccination</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $incompleteVaccination }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-syringe fa-2x text-gray-300" style="color: #d1d1d1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. CONTENT ROW --}}
    <div class="row">

        {{-- Left Column: Recent Registrations --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #4E342E;">Newest Registrations</h6>
                    <a href="{{ route('barangay.pets.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pet Name</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPets as $pet)
                                <tr>
                                    <td class="fw-bold">
                                        <i class="fas {{ $pet->species == 'Dog' ? 'fa-dog' : 'fa-cat' }} me-2 text-secondary"></i>
                                        {{ $pet->name }}
                                    </td>
                                    <td>{{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : 'N/A' }}</td>
                                    <td>
                                        @if($pet->vaccination_status == 'Fully Vaccinated')
                                            <span class="badge bg-success rounded-pill" style="font-size: 0.75rem;">Vaccinated</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.75rem;">Pending</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $pet->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No registrations yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Vaccination Overview --}}
        <div class="col-lg-4 mb-4">
            {{-- Progress Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold" style="color: #4E342E;">Vaccination Coverage</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="display-4 fw-bold {{ $vaccinationRate > 80 ? 'text-success' : 'text-warning' }}">
                        {{ $vaccinationRate }}%
                    </h2>
                    <p class="text-muted mb-3">of registered pets are fully vaccinated.</p>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar {{ $vaccinationRate > 80 ? 'bg-success' : 'bg-warning' }}" 
                             role="progressbar" 
                             style="width: {{ $vaccinationRate }}%">
                        </div>
                    </div>
                    
                    <hr>
                    <p class="small text-muted text-start mb-1"><i class="fas fa-check-circle text-success me-1"></i> Target: 80% Coverage</p>
                    <p class="small text-muted text-start"><i class="fas fa-info-circle text-info me-1"></i> Pending: {{ $incompleteVaccination }} pets</p>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm border-0">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold" style="color: #4E342E;">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('barangay.pets.index') }}" class="btn text-white fw-bold" style="background-color: #E59500;">
                            <i class="fas fa-plus-circle me-1"></i> Register New Pet
                        </a>
                        <a href="{{ route('barangay.users.index') }}" class="btn btn-outline-dark fw-bold">
                            <i class="fas fa-user-plus me-1"></i> Add New Owner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection