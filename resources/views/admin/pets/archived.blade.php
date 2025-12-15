@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Back Button --}}
        <a href="{{ route('barangay.pets.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <h4 class="text-gray-800">Archived Pets</h4>
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
                <table class="table table-bordered align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Pet ID</th>
                            <th>Pet Name</th>
                            <th>Species</th>
                            <th>Breed</th>
                            <th>Owner</th>
                            <th>Date Archived</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archivedPets as $pet)
                        <tr>
                            <td class="fw-bold">{{ $pet->formatted_id }}</td>
                            <td class="fw-bold">{{ $pet->name }}</td>
                            <td>{{ $pet->species }}</td>
                            <td>{{ $pet->breed }}</td>
                            <td>
                                {{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : ($pet->owner_name ?? 'N/A') }}
                            </td>
                            <td>{{ $pet->deleted_at->format('M d, Y h:i A') }}</td>
                            <td class="text-center">
                                {{-- RESTORE BUTTON --}}
                                <form action="{{ route('barangay.pets.restore', $pet->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore" onsubmit="return confirm('Restore this pet record?');">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No archived pets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection