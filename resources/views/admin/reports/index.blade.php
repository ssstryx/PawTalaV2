@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold" style="color: #4E342E;">Generate Reports</h1>
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
            <form action="{{ route('barangay.reports.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Species</label>
                        <select name="species" class="form-select">
                            <option value="">All Species</option>
                            <option value="Dog" {{ request('species') == 'Dog' ? 'selected' : '' }}>Dog</option>
                            <option value="Cat" {{ request('species') == 'Cat' ? 'selected' : '' }}>Cat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Vaccination Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Vaccinated" {{ request('status') == 'Vaccinated' ? 'selected' : '' }}>Fully Vaccinated</option>
                            <option value="Unvaccinated" {{ request('status') == 'Unvaccinated' ? 'selected' : '' }}>Unvaccinated / Incomplete</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted">Registration Date Range</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            <span class="input-group-text bg-light">-</span>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn text-white fw-bold" style="background-color: #E59500;">
                                Filter
                            </button>
                            @if(request()->hasAny(['species', 'status', 'start_date']))
                                <a href="{{ route('barangay.reports.index') }}" class="btn btn-light btn-sm text-muted">Clear</a>
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
            
            {{-- Print Header (Visible only when printing) --}}
            <div class="d-none d-print-block text-center mb-4">
                <h4 class="fw-bold text-uppercase mb-0">Barangay Pet Master List</h4>
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
                                <th style="width: 25%">Owner Name</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 10%">Reg. Date</th>
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
                                    <div class="small text-muted d-print-none">{{ $pet->user->address ?? '' }}</div>
                                </td>
                                <td class="text-center">
                                    @if($pet->vaccination_status == 'Fully Vaccinated')
                                        <span class="badge bg-success text-white d-print-none">Vaccinated</span>
                                        <span class="d-none d-print-inline fw-bold text-success">Vaccinated</span>
                                    @else
                                        <span class="badge bg-warning text-dark d-print-none">{{ $pet->vaccination_status }}</span>
                                        <span class="d-none d-print-inline text-danger">{{ $pet->vaccination_status }}</span>
                                    @endif
                                </td>
                                <td class="text-center small">{{ $pet->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Summary Footer --}}
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
        /* Hide everything by default */
        body * {
            visibility: hidden;
        }
        /* Show only the report container */
        .report-container, .report-container * {
            visibility: visible;
        }
        /* Position the report at the top */
        .report-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        /* Hide buttons and navigation */
        .btn, nav, header, .sidebar, .d-print-none {
            display: none !important;
        }
        /* Ensure background colors print (for badges/headers) */
        .table-light {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection