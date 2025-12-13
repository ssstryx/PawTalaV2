@extends('layouts.super_admin.app')

@section('content')
<style>
    .btn-fixed-width {
        min-width: 95px; /* Adjust this value as needed to fit the longer text */
    }
</style>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">User Management</h3>
            <p class="text-secondary mb-0">Manage access for Barangay Admins.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 fw-medium" data-bs-toggle="modal" data-bs-target="#addNewAdminModal">
            <i class="bi bi-plus-lg me-1"></i> Add New Admin
        </button>
    </div>

    <!-- Add New Admin Modal -->
    <div class="modal fade" id="addNewAdminModal" tabindex="-1" aria-labelledby="addNewAdminModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewAdminModalLabel">Add New Barangay Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('super.create_admin') }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
              </div>
              <div class="mb-3">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name">
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3">
                <label for="barangay" class="form-label">Barangay</label>
                <input type="text" class="form-control" id="barangay" name="barangay" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create Admin</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h6 class="mb-0 fw-bold text-secondary">Existing Admins Directory</h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Name / Email</th>
                            <th class="text-secondary text-uppercase small fw-bold">Barangay</th>
                            <th class="text-secondary text-uppercase small fw-bold">Role</th>
                            <th class="text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="text-center text-secondary text-uppercase small fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">{{ $user->barangay ?? 'N/A' }}</span>
                            </td>

                            <td>
                                @if($user->role === 'super_admin')
                                    <span class="badge bg-info text-dark">Super Admin</span>
                                @else
                                    <span class="badge bg-secondary">Admin</span>
                                @endif
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary me-2">Edit</button>
                                    <form action="{{ route('super.toggle_status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'danger' : 'success' }} btn-fixed-width">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No admins found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection