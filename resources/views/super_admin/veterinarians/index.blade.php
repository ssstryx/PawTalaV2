@extends('layouts.super_admin.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-end align-items-center mb-4">
        {{-- VIEW ARCHIVED BUTTON --}}
        <a href="{{ route('super.veterinarians.archived') }}" class="btn btn-secondary shadow-sm me-2">
            <i class="bi bi-archive-fill me-1"></i> View Archived
        </a>

        <button class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;" data-bs-toggle="modal" data-bs-target="#addVetModal">
            <i class="fas fa-plus"></i> Add New Veterinarian
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        
        <div class="card-header py-3 text-center bg-white">
            <h4 class="m-0 font-weight-bold text-dark">Registered Veterinarians</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Veterinarian ID</th>
                            <th>Veterinarian Name</th>
                            <th>Clinic Name</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            {{-- Fixed width to match Pets table style --}}
                            <th class="text-center" style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($veterinarians as $vet)
                        <tr>
                            <td class="fw-bold">VET-{{ str_pad($vet->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="fw-bold">{{ $vet->veterinarian_name }}</div>
                            </td>
                            <td>{{ $vet->clinic_name }}</td>
                            <td>{{ $vet->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($vet->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- View Button --}}
                                <button class="btn btn-sm btn-info text-white me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewVetModal"
                                    data-vet-name="{{ $vet->veterinarian_name }}"
                                    data-clinic="{{ $vet->clinic_name }}"
                                    data-license="{{ $vet->license_number }}"
                                    data-contact="{{ $vet->contact_number }}"
                                    data-email="{{ $vet->email }}"
                                    data-address="{{ $vet->clinic_address }}"
                                    data-hours="{{ $vet->clinic_hours }}"
                                    data-spec="{{ $vet->specialization }}"
                                    data-status="{{ ucfirst($vet->status) }}">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>

                                {{-- Edit Button --}}
                                <button class="btn btn-sm btn-warning mx-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editVetModal"
                                    data-id="{{ $vet->id }}"
                                    data-vet-name="{{ $vet->veterinarian_name }}"
                                    data-clinic="{{ $vet->clinic_name }}"
                                    data-license="{{ $vet->license_number }}"
                                    data-contact="{{ $vet->contact_number }}"
                                    data-email="{{ $vet->email }}"
                                    data-address="{{ $vet->clinic_address }}"
                                    data-hours="{{ $vet->clinic_hours }}"
                                    data-spec="{{ $vet->specialization }}"
                                    data-status="{{ $vet->status }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                {{-- Archive Button --}}
                                <form action="{{ route('super.veterinarians.destroy', $vet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to archive this veterinarian?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-secondary" title="Archive">
                                        <i class="bi bi-archive-fill"></i> Archive
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No veterinarians found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION --}}

{{-- ADD MODAL --}}
<div class="modal fade" id="addVetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Veterinarian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('super.veterinarians.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Name</label>
                            <input type="text" name="clinic_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Veterinarian Name</label>
                            <input type="text" name="veterinarian_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">License No.</label>
                            <input type="text" name="license_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Specialization</label>
                            <input type="text" name="specialization" class="form-control" required placeholder="e.g. Surgery, Dermatology">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Clinic Address</label>
                        <input type="text" name="clinic_address" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Hours</label>
                            <input type="text" name="clinic_hours" class="form-control" required placeholder="e.g. Mon-Sat 8am-5pm">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Save Veterinarian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editVetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Veterinarian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVetForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Name</label>
                            <input type="text" id="edit_clinic_name" name="clinic_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Veterinarian Name</label>
                            <input type="text" id="edit_veterinarian_name" name="veterinarian_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">License No.</label>
                            <input type="text" id="edit_license_number" name="license_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Specialization</label>
                            <input type="text" id="edit_specialization" name="specialization" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Contact Number</label>
                            <input type="text" id="edit_contact_number" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Email Address</label>
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Clinic Address</label>
                        <input type="text" id="edit_clinic_address" name="clinic_address" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Hours</label>
                            <input type="text" id="edit_clinic_hours" name="clinic_hours" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Status</label>
                            <select id="edit_status" name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Update Veterinarian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewVetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            {{-- Header --}}
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: #E59500;">Veterinarian Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-2">
                
                {{-- Center Section --}}
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                         style="width: 70px; height: 70px; background-color: #fff;">
                        <i class="bi bi-hospital" style="color: #E59500; font-size: 2rem;"></i>
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-2" id="view_vet_name"></h4>
                    
                    <span class="badge rounded-pill px-3 py-2 text-white" 
                          style="background-color: #6c757d; font-weight: normal; font-size: 0.85rem;" 
                          id="view_specialization">
                    </span>
                </div>

                {{-- Details List --}}
                <div class="px-2">
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Clinic Name</div>
                        <div class="col-7 text-end fw-bold text-dark" id="view_clinic"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">License No.</div>
                        <div class="col-7 text-end fw-bold text-dark" id="view_license"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Contact</div>
                        <div class="col-7 text-end fw-bold text-dark" id="view_contact"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Email</div>
                        <div class="col-7 text-end fw-bold text-dark text-break" id="view_email" style="font-size: 0.9rem;"></div>
                    </div>
                    <div class="py-2 border-bottom mt-2">
                        <div class="text-muted small mb-1">Address</div>
                        <div class="fw-bold text-dark" id="view_address"></div>
                    </div>
                    <div class="py-2 mt-1">
                        <div class="text-muted small mb-1">Hours</div>
                        <div class="fw-bold text-dark" id="view_hours"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Handle EDIT Modal Population
        var editVetModal = document.getElementById('editVetModal');
        editVetModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var form = document.getElementById('editVetForm');
            
            // Set Form Action
            form.action = '/admin/veterinarians/' + id;

            // Fill Fields
            document.getElementById('edit_clinic_name').value = button.getAttribute('data-clinic');
            document.getElementById('edit_veterinarian_name').value = button.getAttribute('data-vet-name');
            document.getElementById('edit_license_number').value = button.getAttribute('data-license');
            document.getElementById('edit_contact_number').value = button.getAttribute('data-contact');
            document.getElementById('edit_email').value = button.getAttribute('data-email');
            document.getElementById('edit_clinic_address').value = button.getAttribute('data-address');
            document.getElementById('edit_clinic_hours').value = button.getAttribute('data-hours');
            document.getElementById('edit_specialization').value = button.getAttribute('data-spec');
            document.getElementById('edit_status').value = button.getAttribute('data-status');
        });

        // 2. Handle VIEW Modal Population
        var viewVetModal = document.getElementById('viewVetModal');
        viewVetModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            document.getElementById('view_vet_name').textContent = button.getAttribute('data-vet-name');
            document.getElementById('view_specialization').textContent = button.getAttribute('data-spec');
            document.getElementById('view_clinic').textContent = button.getAttribute('data-clinic');
            document.getElementById('view_license').textContent = button.getAttribute('data-license');
            document.getElementById('view_contact').textContent = button.getAttribute('data-contact');
            document.getElementById('view_email').textContent = button.getAttribute('data-email');
            document.getElementById('view_address').textContent = button.getAttribute('data-address');
            document.getElementById('view_hours').textContent = button.getAttribute('data-hours');
        });
    });
</script>

@endsection