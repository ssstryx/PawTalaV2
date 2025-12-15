@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            
            {{-- HEADER: SEARCH BAR & ADD BUTTON --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                {{-- Search Form --}}
                <form action="{{ url()->current() }}" method="GET" class="d-flex" style="width: 400px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or ID..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" style="background-color: #E59500; border-color: #E59500;">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="btn btn-secondary" title="Clear Search"><i class="fas fa-times"></i></a>
                        @endif
                    </div>
                </form>

                {{-- Add Button --}}
                <button class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i> Add New User
                </button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow mb-4">
                
                <div class="card-header py-3 text-center bg-white">
                    <h4 class="m-0 font-weight-bold text-dark">Registered Users</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-light">
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
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->owner_id ?? $user->id }}</td>
                                        
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        
                                        <td>{{ $user->email }}</td>
                                        
                                        <td>{{ $user->address }}</td>
                                        
                                        <td>
                                            @if ($user->is_active ?? true) 
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#editUserModal"
                                               data-bs-id="{{ $user->id }}"
                                               data-bs-first_name="{{ $user->first_name }}"
                                               data-bs-middle_name="{{ $user->middle_name }}" 
                                               data-bs-last_name="{{ $user->last_name }}"
                                               data-bs-contact_number="{{ $user->contact_number }}"
                                               data-bs-email="{{ $user->email }}"
                                               data-bs-address="{{ $user->address }}">
                                               Edit
                                            </button>

                                            <form action="{{ route('barangay.users.toggle_status', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if ($user->is_active ?? true)
                                                    <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <p class="mb-0">
                                                @if(request('search'))
                                                    No users found matching "{{ request('search') }}".
                                                @else
                                                    No registered users found.
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- NEW PAGINATION SUMMARY & LINKS --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="small text-muted">
                            @if($users->count() > 0)
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                            @else
                                Showing 0 entries
                            @endif
                        </div>
                        
                        <div>
                            @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $users->links('pagination::bootstrap-5') }}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('barangay.users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Middle Name <span class="text-secondary">(Optional)</span></label>
                        <input type="text" class="form-control" name="middle_name">
                    </div>

                    <div class="mb-3">
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label>Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required>
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

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Middle Name <span class="text-secondary">(Optional)</span></label>
                        <input type="text" class="form-control" id="edit_middle_name" name="middle_name">
                    </div>

                    <div class="mb-3">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Open Add Modal if there are errors from validation
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            myModal.show();
        @endif

        // Handle Edit Modal Data Population
        var editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', function (event) {
            // 1. Get the button that was clicked
            var button = event.relatedTarget;
            
            // 2. Find the form inside the modal
            var form = editUserModal.querySelector('#editUserForm');
            
            // 3. Get the User ID from the button
            var userId = button.getAttribute('data-bs-id');

            // Set the Action URL for the form
            var baseUrl = "{{ url('admin/users') }}"; 
            form.action = baseUrl + '/' + userId;

            // 4. Fill the input fields with data from the button
            form.querySelector('#edit_first_name').value = button.getAttribute('data-bs-first_name');
            form.querySelector('#edit_middle_name').value = button.getAttribute('data-bs-middle_name');
            form.querySelector('#edit_last_name').value = button.getAttribute('data-bs-last_name');
            form.querySelector('#edit_contact_number').value = button.getAttribute('data-bs-contact_number');
            form.querySelector('#edit_email').value = button.getAttribute('data-bs-email');
            form.querySelector('#edit_address').value = button.getAttribute('data-bs-address');
        });
    });
</script>
@endsection