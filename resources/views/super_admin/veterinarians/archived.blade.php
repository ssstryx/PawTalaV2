@extends('layouts.super_admin.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Back Button --}}
        <a href="{{ route('super.veterinarians.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <h4 class="text-gray-800">Archived Veterinarians</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 text-center bg-secondary text-white">
            <h5 class="m-0 font-weight-bold">Archive List</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Veterinarian ID</th>
                            <th>Veterinarian Name</th>
                            <th>Clinic Name</th>
                            <th>Date Archived</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archivedVets as $vet)
                        <tr>
                            <td class="fw-bold">VET-{{ str_pad($vet->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $vet->veterinarian_name }}</td>
                            <td>{{ $vet->clinic_name }}</td>
                            <td>{{ $vet->deleted_at->format('M d, Y h:i A') }}</td>
                            <td class="text-center">
                                {{-- RESTORE BUTTON --}}
                                <form action="{{ route('super.veterinarians.restore', $vet->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore" onsubmit="return confirm('Restore this veterinarian?');">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No archived veterinarians found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection