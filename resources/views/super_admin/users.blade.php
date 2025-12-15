@extends('layouts.super_admin.app')

@section('content')
<div class="container-fluid">

    {{-- HEADER: SEARCH BAR & ADD BUTTON --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Search Form --}}
        <form action="{{ url()->current() }}" method="GET" class="d-flex" style="width: 400px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, barangay..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit" style="background-color: #E59500; border-color: #E59500;">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-secondary" title="Clear Search"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Add Button --}}
        <button class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;" data-bs-toggle="modal" data-bs-target="#addNewAdminModal">
            <i class="fas fa-plus"></i> Add New Admin
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 text-center bg-white">
            <h4 class="m-0 font-weight-bold text-dark">Registered Users</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Barangay</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="fw-bold">
                                {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name[0] . '.' : '' }} {{ $user->last_name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->barangay)
                                    {{ $user->barangay->name }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role === 'super_admin')
                                    <span class="badge bg-primary rounded-pill px-3">Super Admin</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editAdminModal"
                                    data-user-id="{{ $user->id }}"
                                    data-first-name="{{ $user->first_name }}"
                                    data-middle-name="{{ $user->middle_name }}"
                                    data-last-name="{{ $user->last_name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}" 
                                    data-barangay-id="{{ $user->barangay_id }}"
                                    title="Edit User">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <form action="{{ route('super.toggle_status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    
                                    @if($user->is_active)
                                        <button type="submit" class="btn btn-sm btn-danger" title="Deactivate" onclick="return confirm('Are you sure you want to deactivate this user?');">
                                            <i class="bi bi-power"></i> Deactivate
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-success" title="Activate" onclick="return confirm('Are you sure you want to activate this user?');">
                                            <i class="bi bi-check-circle"></i> Activate
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                @if(request('search'))
                                    No users found matching "{{ request('search') }}".
                                @else
                                    No admins found.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION SUMMARY & LINKS --}}
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

{{-- KEEP YOUR MODALS BELOW HERE --}}
{{-- (I have not modified the modals or scripts section, assume they are pasted here exactly as they were in your previous file) --}}

{{-- ADD NEW ADMIN MODAL --}}
<div class="modal fade" id="addNewAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('super.create_admin') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="admin">

                    <div class="mb-3"><label class="fw-bold">First Name <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold">Middle Name</label><input type="text" name="middle_name" class="form-control"></div>
                    <div class="mb-3"><label class="fw-bold">Last Name <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" required></div>

                    <div class="mb-3">
                        <label class="fw-bold">Barangay</label>
                        <select name="barangay_id" class="form-select">
                            <option value="">Select Barangay...</option>
                            @foreach($barangays as $bg)
                                <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label class="fw-bold">Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required></div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT ADMIN MODAL --}}
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title fw-bold">Edit Admin Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body">
                <form id="editAdminForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <input type="hidden" id="edit_role" name="role">

                    <div class="mb-3"><label class="fw-bold">First Name <span class="text-danger">*</span></label><input type="text" id="edit_first_name" name="first_name" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold">Middle Name</label><input type="text" id="edit_middle_name" name="middle_name" class="form-control"></div>
                    <div class="mb-3"><label class="fw-bold">Last Name <span class="text-danger">*</span></label><input type="text" id="edit_last_name" name="last_name" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold">Email <span class="text-danger">*</span></label><input type="email" id="edit_email" name="email" class="form-control" required></div>

                    <div class="mb-3">
                        <label class="fw-bold">Barangay</label>
                        <select id="edit_barangay_id" name="barangay_id" class="form-select">
                            <option value="">Select Barangay...</option>
                            @foreach($barangays as $bg)
                                <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label class="fw-bold">New Password</label><input type="password" id="edit_password" name="password" class="form-control"></div>

                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Save Changes</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editAdminModal = document.getElementById('editAdminModal');
        editAdminModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-user-id');
            var form = document.getElementById('editAdminForm');
            
            // Set the form action correctly
            // Assuming your route is like /super-admin/users/{id}
            // You might need to adjust this URL based on your exact route definition
            form.action = "{{ url('super-admin/users') }}/" + userId; 

            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_first_name').value = button.getAttribute('data-first-name');
            document.getElementById('edit_middle_name').value = button.getAttribute('data-middle-name') || '';
            document.getElementById('edit_last_name').value = button.getAttribute('data-last-name');
            document.getElementById('edit_email').value = button.getAttribute('data-email');
            document.getElementById('edit_role').value = button.getAttribute('data-role');
            
            // Set Selected Barangay
            document.getElementById('edit_barangay_id').value = button.getAttribute('data-barangay-id');
            
            document.getElementById('edit_password').value = ''; 
        });
    });
</script>
@endsection