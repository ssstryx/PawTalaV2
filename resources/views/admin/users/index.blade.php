@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">User Management</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Owner ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                                                                                            <tbody>
                                                                                            @foreach ($users as $user)
                                                                                                <tr>
                                                                                                    <td>{{ $user->userProfile->owner_id }}</td>
                                                                                                    <td>{{ $user->userProfile->first_name }} {{ $user->userProfile->last_name }}</td>
                                                                                                    <td>{{ $user->email }}</td>
                                                                                                    <td>{{ $user->userProfile->address }}</td>
                                                                                                    <td>
                                                                                                        @if ($user->is_active)
                                                                                                            <span class="badge bg-success">Active</span>
                                                                                                        @else
                                                                                                            <span class="badge bg-danger">Inactive</span>
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <a href="#" class="btn btn-sm btn-warning edit-user-btn" data-bs-id="{{ $user->id }}"
                                                                                                            data-bs-first_name="{{ $user->userProfile->first_name }}"
                                                                                                            data-bs-last_name="{{ $user->userProfile->last_name }}"
                                                                                                            data-bs-middle_name="{{ $user->userProfile->middle_name ?? '' }}"
                                                                                                            data-bs-contact_number="{{ $user->userProfile->contact_number }}"
                                                                                                            data-bs-email="{{ $user->email }}"
                                                                                                            data-bs-address="{{ $user->userProfile->address }}">Edit</a>
                                                                                                        <form action="{{ route('barangay.users.toggle_status', $user->id) }}" method="POST" class="d-inline">
                                                                                                            @csrf
                                                                                                            @method('PATCH')
                                                                                                            @if ($user->is_active)
                                                                                                                <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                                                                                            @else
                                                                                                                <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                                                                                            @endif
                                                                                                        </form>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @empty
                                                                                                <tr>
                                                                                                    <td colspan="6" class="text-center">No users found.</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        </tbody>                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('barangay.users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('addUserModal'), {
            keyboard: false
        });
        myModal.show();
    </script>
@endif

@endsection

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="edit_middle_name" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editUserModalElement = document.getElementById('editUserModal');
            var editUserModal = new bootstrap.Modal(editUserModalElement); // Initialize modal instance

            editUserModalElement.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal

                // Extract info from data-bs-* attributes
                var userId = button.getAttribute('data-bs-id');
                var firstName = button.getAttribute('data-bs-first_name');
                var lastName = button.getAttribute('data-bs-last_name');
                var middleName = button.getAttribute('data-bs-middle_name');
                var contactNumber = button.getAttribute('data-bs-contact_number');
                var email = button.getAttribute('data-bs-email');
                var address = button.getAttribute('data-bs-address');

                // Update the modal's content.
                var modalForm = editUserModalElement.querySelector('#editUserForm');
                modalForm.action = `/admin/users/${userId}`;
                modalForm.querySelector('#edit_first_name').value = firstName;
                modalForm.querySelector('#edit_last_name').value = lastName;
                modalForm.querySelector('#edit_middle_name').value = middleName;
                modalForm.querySelector('#edit_contact_number').value = contactNumber;
                modalForm.querySelector('#edit_email').value = email;
                modalForm.querySelector('#edit_address').value = address;
            });

            // Add click listener to all edit buttons
            document.querySelectorAll('.edit-user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    editUserModal.show(this); // Pass the clicked button as relatedTarget
                });
            });
        });
    </script>
    
    @endsection
