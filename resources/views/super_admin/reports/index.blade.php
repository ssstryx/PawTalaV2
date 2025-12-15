@extends('layouts.super_admin.app')

@section('content')
<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold" style="color: #4E342E;">System-Wide Reports</h1>
        <button onclick="window.print()" class="btn btn-primary shadow-sm" style="background-color: #4E342E; border-color: #4E342E;">
            <i class="fas fa-print fa-sm text-white-50 me-1"></i> Print Report
        </button>
    </div>

    {{-- FILTER CARD (Hidden when printing) --}}
    <div class="card shadow mb-4 d-print-none border-0">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold" style="color: #E59500;">Filter Options</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('super.reports.index') }}" method="GET">
                <div class="row g-3">
                    
                    {{-- 1. BARANGAY FILTER (Super Admin Exclusive) --}}
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Barangay</label>
                        <select name="barangay_id" class="form-select">
                            <option value="">All Barangays</option>
                            @foreach($barangays as $bg)
                                <option value="{{ $bg->id }}" {{ request('barangay_id') == $bg->id ? 'selected' : '' }}>
                                    {{ $bg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. SPECIES FILTER --}}
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">Species</label>
                        <select name="species" class="form-select">
                            <option value="All">All Species</option>
                            <option value="Dog" {{ request('species') == 'Dog' ? 'selected' : '' }}>Dog</option>
                            <option value="Cat" {{ request('species') == 'Cat' ? 'selected' : '' }}>Cat</option>
                        </select>
                    </div>

                    {{-- 3. STATUS FILTER --}}
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Vaccinated" {{ request('status') == 'Vaccinated' ? 'selected' : '' }}>Vaccinated</option>
                            <option value="Unvaccinated" {{ request('status') == 'Unvaccinated' ? 'selected' : '' }}>Unvaccinated</option>
                        </select>
                    </div>

                    {{-- 4. DATE RANGE --}}
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Registration Date</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            <span class="input-group-text bg-light border-start-0 border-end-0">-</span>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn text-white fw-bold" style="background-color: #E59500;">
                                Filter
                            </button>
                            @if(request()->anyFilled(['barangay_id', 'species', 'status', 'start_date']))
                                <a href="{{ route('super.reports.index') }}" class="btn btn-light btn-sm text-muted">Reset</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- REPORT RESULTS --}}
    <div class="card shadow-sm border-0 report-container">
        <div class="card-body p-4">
            
            {{-- Print Header --}}
            <div class="d-none d-print-block text-center mb-4">
                <h4 class="fw-bold text-uppercase mb-0">PawTala System Master List</h4>
                <p class="text-muted mb-0">Generated on {{ now()->format('F d, Y') }}</p>
                <hr>
            </div>

            @if($pets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-sm" width="100%">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">Pet Name</th>
                                <th style="width: 10%">Species</th>
                                <th style="width: 15%">Breed</th>
                                <th style="width: 20%">Owner</th>
                                <th style="width: 15%">Barangay</th> {{-- Extra Column for Super Admin --}}
                                <th style="width: 15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pets as $index => $pet)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $pet->name }}</td>
                                <td class="text-center">{{ $pet->species }}</td>
                                <td>{{ $pet->breed }}</td>
                                <td>
                                    {{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : ($pet->owner_name ?? 'N/A') }}
                                </td>
                                <td>
                                    @if($pet->user && $pet->user->barangay)
                                        <span class="badge bg-light text-dark border">{{ $pet->user->barangay->name }}</span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($pet->vaccination_status == 'Fully Vaccinated')
                                        <span class="badge bg-success d-print-none">Vaccinated</span>
                                        <span class="d-none d-print-inline fw-bold text-success">Vaccinated</span>
                                    @else
                                        <span class="badge bg-warning text-dark d-print-none">{{ $pet->vaccination_status }}</span>
                                        <span class="d-none d-print-inline text-danger">{{ $pet->vaccination_status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Footer Summary --}}
                <div class="mt-4">
                    <p class="fw-bold mb-0">Total Records: {{ $pets->count() }}</p>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                    <p>No records found matching the selected filters.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- PRINT STYLES --}}
<style>
    @media print {
        body * { visibility: hidden; }
        .report-container, .report-container * { visibility: visible; }
        .report-container { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
        .btn, nav, header, .sidebar, .d-print-none { display: none !important; }
        .table-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection