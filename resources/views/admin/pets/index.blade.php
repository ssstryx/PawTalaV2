@extends('layouts.admin.app')

@section('content')
{{-- Include html2pdf library for Certificate downloading --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container-fluid">
    
    {{-- HEADER: SEARCH BAR & ACTION BUTTONS --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Search Form --}}
        <form action="{{ url()->current() }}" method="GET" class="d-flex" style="width: 400px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Pet, Breed, or Owner..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit" style="background-color: #E59500; border-color: #E59500;">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-secondary" title="Clear Search"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>

        {{-- Buttons --}}
        <div>
            <a href="{{ route('barangay.pets.archived') }}" class="btn btn-secondary shadow-sm me-2">
                <i class="bi bi-archive-fill me-1"></i> View Archived
            </a>
            <button class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;" data-bs-toggle="modal" data-bs-target="#addPetModal">
                <i class="fas fa-plus"></i> Add New Registration
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 text-center bg-white">
            <h4 class="m-0 font-weight-bold text-dark">Registered Pets</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Pet ID</th>
                            <th>Pet Name</th>
                            <th>Species</th>
                            <th>Breed</th>
                            <th>Owner</th>
                            <th>Registration Date</th>
                            <th class="text-center" style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pets as $pet)
                        <tr>
                            <td class="fw-bold">{{ $pet->formatted_id ?? 'PT-' . str_pad($pet->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="fw-bold">{{ $pet->name }}</td>
                            <td>{{ $pet->species }}</td>
                            <td>{{ $pet->breed }}</td>
                            <td>
                                {{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : ($pet->owner_name ?? 'N/A') }}
                            </td>
                            <td>{{ $pet->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                
                                {{-- VIEW BUTTON --}}
                                <button class="btn btn-sm text-white me-1" 
                                    style="background-color: #E59500; border-color: #E59500;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewPetModal"
                                    
                                    data-name="{{ $pet->name }}"
                                    data-id="{{ $pet->formatted_id ?? 'PT-' . str_pad($pet->id, 3, '0', STR_PAD_LEFT) }}"
                                    
                                    data-owner="{{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : $pet->owner_name }}" 
                                    data-owner-email="{{ $pet->user->email ?? 'Not Recorded' }}"
                                    data-owner-contact="{{ $pet->user->contact_number ?? 'Not Recorded' }}"
                                    data-owner-address="{{ $pet->user->address ?? 'Not Recorded' }}"

                                    data-image="{{ $pet->photo_path ? asset('storage/' . $pet->photo_path) : asset('images/default-pet.png') }}"
                                    data-species="{{ $pet->species }}"
                                    data-sex="{{ $pet->sex }}"
                                    data-birthdate="{{ $pet->birthdate ? \Carbon\Carbon::parse($pet->birthdate)->format('F j, Y') : '' }}"
                                    data-weight="{{ $pet->weight }}"
                                    data-breed="{{ $pet->breed }}"
                                    data-color="{{ $pet->color }}"
                                    data-vaccination="{{ $pet->vaccination_status }}"

                                    {{-- Vet Info for View Modal --}}
                                    data-vet-name="{{ $pet->veterinarian ? $pet->veterinarian->veterinarian_name : 'Not Assigned' }}" 
                                    data-vet-specialization="{{ $pet->veterinarian->specialization ?? 'N/A' }}"
                                    data-vet-clinic="{{ $pet->veterinarian->clinic_name ?? 'N/A' }}"
                                    data-vet-license="{{ $pet->veterinarian->license_number ?? 'N/A' }}"
                                    data-vet-contact="{{ $pet->veterinarian->contact_number ?? 'N/A' }}"
                                    data-vet-email="{{ $pet->veterinarian->email ?? 'N/A' }}"
                                    data-vet-address="{{ $pet->veterinarian->clinic_address ?? 'N/A' }}"
                                    data-vet-hours="{{ $pet->veterinarian->clinic_hours ?? 'N/A' }}"
                                    
                                    title="View Profile">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>

                                {{-- EDIT BUTTON --}}
                                <button class="btn btn-sm btn-warning mx-1" 
                                    title="Edit"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editPetModal"
                                    
                                    {{-- Data Attributes for Edit Modal --}}
                                    data-id="{{ $pet->id }}"
                                    data-user-id="{{ $pet->user_id }}"
                                    data-veterinarian-id="{{ $pet->veterinarian_id }}" 
                                    data-owner-name="{{ $pet->user ? $pet->user->first_name . ' ' . $pet->user->last_name : 'N/A' }}"
                                    data-name="{{ $pet->name }}"
                                    data-species="{{ $pet->species }}"
                                    data-sex="{{ $pet->sex }}"
                                    data-breed="{{ $pet->breed }}"
                                    data-birthdate="{{ $pet->birthdate ? \Carbon\Carbon::parse($pet->birthdate)->format('Y-m-d') : '' }}"
                                    data-weight="{{ $pet->weight }}"
                                    data-color="{{ $pet->color }}"
                                    data-vaccination="{{ $pet->vaccination_status }}"
                                    data-photo="{{ $pet->photo_path ? asset('storage/' . $pet->photo_path) : '' }}"
                                >
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                {{-- ARCHIVE BUTTON --}}
                                <form action="{{ route('barangay.pets.destroy', $pet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to archive this pet record?');">
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-paw fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">
                                        @if(request('search'))
                                            No registered pets found matching "{{ request('search') }}".
                                        @else
                                            No registered pets found.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION & SUMMARY SECTION --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    @if($pets->count() > 0)
                        Showing {{ $pets->firstItem() }} to {{ $pets->lastItem() }} of {{ $pets->total() }} entries
                    @else
                        Showing 0 entries
                    @endif
                </div>
                
                <div>
                    @if($pets instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $pets->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- KEEP ALL YOUR MODALS BELOW HERE EXACTLY AS THEY WERE --}}
{{-- (I have not touched the modals or scripts below, they remain fully functional) --}}

{{-- ADD PET MODAL --}}
<div class="modal fade" id="addPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Register New Pet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('barangay.pets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Owner Full Name <span class="text-danger">*</span></label>
                            <select class="form-select" name="user_id" required>
                                <option value="" selected disabled>Select Owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Brownie">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Species <span class="text-danger">*</span></label>
                            <select class="form-select" name="species" required>
                                <option value="" selected disabled>Select Species</option>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sex <span class="text-danger">*</span></label>
                            <select class="form-select" name="sex" required>
                                <option value="" selected disabled>Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Breed <span class="text-danger">*</span></label>
                        <select class="form-select" id="breedSelect" name="breed" onchange="toggleBreedInput()">
                            <option value="" selected disabled>Select Breed</option>
                            <optgroup label="Dogs">
                                <option value="Aspin">Aspin (Asong Pinoy)</option>
                                <option value="Shih Tzu">Shih Tzu</option>
                                <option value="Golden Retriever">Golden Retriever</option>
                                <option value="German Shepherd">German Shepherd</option>
                            </optgroup>
                            <optgroup label="Cats">
                                <option value="Puspin">Puspin (Pusang Pinoy)</option>
                                <option value="Persian">Persian</option>
                                <option value="Siamese">Siamese</option>
                            </optgroup>
                            <option value="Others">Others (Specify)</option>
                        </select>
                        <div class="mt-2 d-none" id="otherBreedDiv">
                            <input type="text" class="form-control" name="other_breed" placeholder="Please specify the breed">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Weight (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" placeholder="e.g. 5.2">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Color / Markings</label>
                            <select class="form-select" name="color">
                                <option value="" selected disabled>Select Color</option>
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="Brown">Brown</option>
                                <option value="Golden/Cream">Golden / Cream</option>
                                <option value="Gray/Blue">Gray / Blue</option>
                                <option value="Tricolor">Tricolor</option>
                                <option value="Spotted">Spotted</option>
                                <option value="Striped/Brindle">Striped / Brindle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vaccination Status</label>
                            <select class="form-select" name="vaccination_status">
                                <option value="Not Vaccinated">Not Vaccinated</option>
                                <option value="Partially Vaccinated">Partially Vaccinated</option>
                                <option value="Fully Vaccinated">Fully Vaccinated</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </div>
                    </div>

                    {{-- VETERINARIAN DROPDOWN (ADD MODAL) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Veterinarian (Optional)</label>
                        <select class="form-select" name="veterinarian_id">
                            <option value="" selected>Select Veterinarian</option>
                            @if(isset($veterinarians))
                                @foreach($veterinarians as $vet)
                                    <option value="{{ $vet->id }}">
                                        {{ $vet->veterinarian_name }} ({{ $vet->clinic_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Save Registration</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT PET MODAL --}}
<div class="modal fade" id="editPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Pet Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPetForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    <input type="hidden" name="user_id" id="edit_user_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Owner (Read Only)</label>
                            <input type="text" class="form-control" id="edit_owner_name" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Species <span class="text-danger">*</span></label>
                            <select class="form-select" name="species" id="edit_species" required>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sex <span class="text-danger">*</span></label>
                            <select class="form-select" name="sex" id="edit_sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Breed</label>
                        <select class="form-select" id="edit_breedSelect" name="breed" onchange="toggleEditBreedInput()">
                            <optgroup label="Dogs">
                                <option value="Aspin (Asong Pinoy)">Aspin (Asong Pinoy)</option>
                                <option value="Shih Tzu">Shih Tzu</option>
                                <option value="Golden Retriever">Golden Retriever</option>
                                <option value="German Shepherd">German Shepherd</option>
                            </optgroup>
                            <optgroup label="Cats">
                                <option value="Puspin (Pusang Pinoy)">Puspin (Pusang Pinoy)</option>
                                <option value="Persian">Persian</option>
                                <option value="Siamese">Siamese</option>
                            </optgroup>
                            <option value="Others">Others (Specify)</option>
                        </select>
                        <div class="mt-2 d-none" id="edit_otherBreedDiv">
                            <input type="text" class="form-control" name="other_breed" id="edit_other_breed" placeholder="Specify breed">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" id="edit_birthdate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Weight (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" id="edit_weight">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Color</label>
                            <select class="form-select" name="color" id="edit_color">
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="Brown">Brown</option>
                                <option value="Golden/Cream">Golden/Cream</option>
                                <option value="Gray/Blue">Gray/Blue</option>
                                <option value="Tricolor">Tricolor</option>
                                <option value="Spotted">Spotted</option>
                                <option value="Striped/Brindle">Striped/Brindle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vaccination Status</label>
                            <select class="form-select" name="vaccination_status" id="edit_vaccination_status">
                                <option value="Not Vaccinated">Not Vaccinated</option>
                                <option value="Partially Vaccinated">Partially Vaccinated</option>
                                <option value="Fully Vaccinated">Fully Vaccinated</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </div>
                    </div>

                    {{-- VETERINARIAN DROPDOWN (EDIT MODAL) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Veterinarian</label>
                        <select class="form-select" name="veterinarian_id" id="edit_veterinarian_id">
                            <option value="">Select Veterinarian</option>
                            @if(isset($veterinarians))
                                @foreach($veterinarians as $vet)
                                    <option value="{{ $vet->id }}">
                                        {{ $vet->veterinarian_name }} ({{ $vet->clinic_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Update Photo (Optional)</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <div class="mt-2" id="current_photo_preview_div" style="display:none;">
                            <small class="text-muted">Current Photo:</small><br>
                            <img id="current_photo_preview" src="" width="100" class="rounded border mt-1">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- VIEW PET PROFILE MODAL --}}
<div class="modal fade" id="viewPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" style="color: #E59500;">
                    <i class="bi bi-person-vcard me-2"></i>
                    <span id="viewPetTitle">Pet</span>'s Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    
                    {{-- Left Column: Image & Basic Info --}}
                    <div class="col-md-4 text-center">
                        <div class="mb-3 position-relative">
                            <img id="viewPetImage" src="" alt="Pet Photo" 
                                 class="img-fluid rounded-3 border shadow-sm" 
                                 style="width: 100%; height: 250px; object-fit: cover;">
                        </div>

                        <div class="card bg-light border-0">
                            <div class="card-body text-start">
                                <h4 id="viewPetName" class="fw-bold mb-1 text-dark"></h4>
                                <p class="text-muted mb-2 small"><span id="viewPetSpecies"></span> • <span id="viewPetSex"></span></p>
                                
                                <div class="badge w-100 py-2 text-white" style="background-color: #E59500;">
                                    Age: <span id="viewPetAge">N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Detailed Info & Buttons --}}
                    <div class="col-md-8">
                        
                        <h6 class="fw-bold text-uppercase text-secondary mb-3 small">Pet Details</h6>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Pet ID</label>
                                <p class="fw-bold text-dark" id="viewPetID"></p>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Weight</label>
                                <p class="fw-bold text-dark"><span id="viewPetWeight"></span> kg</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Birthdate</label>
                                <p class="fw-bold text-dark" id="viewPetBirthdate"></p>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Breed</label>
                                <p class="fw-bold text-dark" id="viewPetBreed"></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Color</label>
                                <p class="fw-bold text-dark" id="viewPetColor"></p>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Vaccination Status</label>
                                <div>
                                    <span id="viewPetVaccination" class="badge bg-success rounded-pill"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-grid gap-2">
                            <button class="btn text-white text-start px-3 fw-bold" 
                                    id="btnOwnerInfo" 
                                    data-bs-target="#ownerInfoModal" 
                                    data-bs-toggle="modal"
                                    style="background-color: #E59500; border-color: #E59500;">
                                <i class="bi bi-person me-2"></i> Owner Information
                            </button>
                            
                            <button class="btn btn-outline-success text-start px-3 fw-bold"
                                    id="btnVetInfo"
                                    data-bs-target="#vetInfoModal" 
                                    data-bs-toggle="modal">
                                <i class="bi bi-hospital me-2"></i> Veterinary Information
                            </button>
                            
                            <button class="btn btn-outline-info text-start px-3 fw-bold"
                                    id="btnMedicalHistory"
                                    data-bs-target="#medicalHistoryModal" 
                                    data-bs-toggle="modal">
                                <i class="bi bi-file-medical me-2"></i> View Medical History
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MEDICAL HISTORY MODAL --}}
<div class="modal fade" id="medicalHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header border-0 pb-0 justify-content-center position-relative">
                <h5 class="modal-title fw-bold text-center w-100" style="color: #E59500; font-size: 1.5rem;">
                    Pet Medical History
                </h5>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-target="#viewPetModal" data-bs-toggle="modal" aria-label="Back"></button>
            </div>

            <div class="modal-body p-4">
                
                {{-- Toolbar --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted mb-0">Medical Records for: <span class="fw-bold text-dark" id="medHistPetName">Unknown</span></h6>
                    
                    <div>
                        {{-- Rabies Cert Button --}}
                        <button id="btnViewRabiesCert" 
                                class="btn btn-sm btn-success text-white me-2" 
                                title="View Rabies Vaccination Certificate"
                                data-bs-toggle="modal" 
                                data-bs-target="#certificateModal"
                                style="display: none;">
                            <i class="bi bi-patch-check-fill me-1"></i> View Certificate
                        </button>

                        {{-- Add Record Button --}}
                        <button class="btn btn-sm btn-primary" 
                                style="background-color: #E59500; border-color: #E59500;" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addMedicalRecordModal">
                            <i class="bi bi-plus-lg me-1"></i> Add Record
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Date</th>
                                <th>Clinic Name</th>
                                <th>Veterinarian</th>
                                <th>Diagnosis / Purpose</th>
                                <th>Treatment / Medication</th>
                                <th>Remarks</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Sample Record --}}
                            <tr>
                                <td class="text-center">2024-03-15</td>
                                <td>PawCare Clinic</td>
                                <td>Dr. A. Ramos</td>
                                <td>Regular Checkup</td>
                                <td>Deworming</td>
                                <td>Healthy, gained 0.5kg</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editMedicalRecordModal"
                                            
                                            data-date="2024-03-15"
                                            data-clinic="PawCare Clinic"
                                            data-vet-id="1" {{-- Use ID here for the dropdown --}}
                                            data-diagnosis="Regular Checkup"
                                            data-treatment="Deworming"
                                            data-remarks="Healthy, gained 0.5kg">
                                            <i class="bi bi-pencil-square"></i> Update
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                    <button class="btn btn-sm btn-outline-secondary disabled">
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    
                    <span class="text-muted small fw-bold">Page 1 of 1</span>
                    
                    <button class="btn btn-sm btn-outline-secondary disabled">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- CERTIFICATE OF VACCINATION MODAL --}}
<div class="modal fade" id="certificateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
                <button onclick="downloadCertificate()" class="btn btn-sm btn-outline-danger fw-bold">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                </button>
                <button type="button" class="btn-close" data-bs-target="#medicalHistoryModal" data-bs-toggle="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light text-center p-4">
                <div id="certificateContent" 
                     style="background-color: white; border: 4px double #000; margin: 0 auto; width: 950px; max-width: 100%; padding: 25px; color: black; font-family: 'Times New Roman', serif; text-align: left;">
                    
                    <div class="text-center mb-3">
                        <h6 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px; font-size: 1.1rem;">National Rabies Prevention and Control Program</h6>
                        <p class="mb-0" style="font-size: 0.9rem;">Republic of the Philippines</p>
                        <p class="mb-0" style="font-size: 0.9rem;">Province of Oriental Mindoro</p>
                        <p class="mb-0" style="font-size: 0.9rem; text-decoration: underline;">Municipality/City Calapan City</p>
                        
                        <h4 class="fw-bold mt-3 text-uppercase" style="font-size: 1.4rem;">Certificate of Rabies Vaccination</h4>
                    </div>

                    <table class="table table-bordered border-dark mb-0" style="border-color: #000 !important; background-color: transparent;">
                        <tbody>
                            <tr>
                                <td colspan="2" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Name of Pet/Pangalan ng Alaga:</strong>
                                    <div class="fw-bold ps-3" id="certPetName" style="font-size: 1.2rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Species/Uri:</strong>
                                    <div class="fw-bold ps-3" id="certSpecies" style="font-size: 1rem;"></div>
                                </td>
                                <td width="50%" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Breed/Lahi:</strong>
                                    <div class="fw-bold ps-3" id="certBreed" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Age/Gulang:</strong>
                                    <div class="fw-bold ps-3" id="certAge" style="font-size: 1rem;"></div>
                                </td>
                                <td class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Sex/Kasarian:</strong>
                                    <div class="fw-bold ps-3" id="certSex" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Markings/Kulay:</strong>
                                    <div class="fw-bold ps-3" id="certColor" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Name of Owner/Pangalan ng may-ari:</strong>
                                    <div class="fw-bold ps-3" id="certOwner" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Address/Tirahan:</strong>
                                    <div class="fw-bold ps-3" id="certAddress" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 ps-2">
                                    <strong style="font-size: 0.9rem;">Telephone No.:</strong>
                                    <div class="fw-bold ps-3" id="certContact" style="font-size: 1rem;"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ADD MEDICAL RECORD MODAL --}}
<div class="modal fade" id="addMedicalRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Medical Record</h5>
                <button type="button" class="btn-close" data-bs-target="#medicalHistoryModal" data-bs-toggle="modal" aria-label="Back"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Name</label>
                            <input type="text" name="clinic_name" class="form-control" required>
                        </div>
                    </div>

                    {{-- UPDATED: VETERINARIAN DROPDOWN (ADD) --}}
                    <div class="mb-3">
                        <label class="fw-bold">Veterinarian</label>
                        <select class="form-select" name="veterinarian_id" required>
                            <option value="" selected>Select Veterinarian</option>
                            @if(isset($veterinarians))
                                @foreach($veterinarians as $vet)
                                    <option value="{{ $vet->id }}">
                                        {{ $vet->veterinarian_name }} ({{ $vet->clinic_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Diagnosis / Purpose</label>
                        <input type="text" name="diagnosis" class="form-control" required placeholder="e.g. Vaccination, Checkup, Injury">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Treatment / Medication</label>
                        <textarea name="treatment" class="form-control" rows="2" placeholder="e.g. Anti-Rabies Shot, Antibiotics"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Next appointment in 3 months"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#medicalHistoryModal" data-bs-toggle="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Save Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT MEDICAL RECORD MODAL --}}
<div class="modal fade" id="editMedicalRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Update Medical Record</h5>
                <button type="button" class="btn-close" data-bs-target="#medicalHistoryModal" data-bs-toggle="modal" aria-label="Back"></button>
            </div>
            <div class="modal-body">
                <form id="editMedicalForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Date</label>
                            <input type="date" id="edit_med_date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Clinic Name</label>
                            <input type="text" id="edit_med_clinic" name="clinic_name" class="form-control" required>
                        </div>
                    </div>

                    {{-- UPDATED: VETERINARIAN DROPDOWN (EDIT) --}}
                    <div class="mb-3">
                        <label class="fw-bold">Veterinarian</label>
                        <select class="form-select" id="edit_med_vet" name="veterinarian_id" required>
                            <option value="">Select Veterinarian</option>
                            @if(isset($veterinarians))
                                @foreach($veterinarians as $vet)
                                    <option value="{{ $vet->id }}">
                                        {{ $vet->veterinarian_name }} ({{ $vet->clinic_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Diagnosis / Purpose</label>
                        <input type="text" id="edit_med_diagnosis" name="diagnosis" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Treatment / Medication</label>
                        <textarea id="edit_med_treatment" name="treatment" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Remarks</label>
                        <textarea id="edit_med_remarks" name="remarks" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#medicalHistoryModal" data-bs-toggle="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #E59500; border-color: #E59500;">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- OWNER INFO MODAL --}}
<div class="modal fade" id="ownerInfoModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 position-relative justify-content-center">
                <h5 class="modal-title fw-bold text-center w-100" style="color: #E59500; font-size: 1.5rem;">Owner Information</h5>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-target="#viewPetModal" data-bs-toggle="modal" aria-label="Back"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
                    <div class="card-body p-4">
                        <div class="mb-3 border-bottom pb-2">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Full Name</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #E59500;"><i class="bi bi-person-fill"></i></div>
                                <h5 class="mb-0 fw-bold text-dark" id="ownerName">Juan Dela Cruz</h5>
                            </div>
                        </div>
                        <div class="mb-3 border-bottom pb-2">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Contact Number</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #E59500;"><i class="bi bi-telephone-fill"></i></div>
                                <p class="mb-0 text-dark fw-medium" id="ownerContact">Not Recorded</p>
                            </div>
                        </div>
                        <div class="mb-3 border-bottom pb-2">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Email Address</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #E59500;"><i class="bi bi-envelope-fill"></i></div>
                                <p class="mb-0 text-dark fw-medium" id="ownerEmail">Not Recorded</p>
                            </div>
                        </div>
                        <div>
                            <label class="small text-muted text-uppercase fw-bold mb-1">Address</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm" style="color: #E59500;"><i class="bi bi-geo-alt-fill"></i></div>
                                <p class="mb-0 text-dark fw-medium" id="ownerAddress">Not Recorded</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- VETERINARIAN DETAILS MODAL --}}
<div class="modal fade" id="vetInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: #E59500;">Veterinarian Details</h5>
                <button type="button" class="btn-close" data-bs-target="#viewPetModal" data-bs-toggle="modal" aria-label="Back"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                         style="width: 70px; height: 70px; background-color: #fff;">
                        <i class="bi bi-hospital" style="color: #E59500; font-size: 2rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2" id="vetName"></h4>
                    <span class="badge rounded-pill px-3 py-2 text-white" 
                          style="background-color: #6c757d; font-weight: normal; font-size: 0.85rem;" 
                          id="vetSpecialization">
                    </span>
                </div>
                <div class="px-2">
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Clinic Name</div>
                        <div class="col-7 text-end fw-bold text-dark" id="vetClinic"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">License No.</div>
                        <div class="col-7 text-end fw-bold text-dark" id="vetLicense"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Contact</div>
                        <div class="col-7 text-end fw-bold text-dark" id="vetContact"></div>
                    </div>
                    <div class="row border-bottom py-2 align-items-center">
                        <div class="col-5 text-muted small">Email</div>
                        <div class="col-7 text-end fw-bold text-dark text-break" id="vetEmail" style="font-size: 0.9rem;"></div>
                    </div>
                    <div class="py-2 border-bottom mt-2">
                        <div class="text-muted small mb-1">Address</div>
                        <div class="fw-bold text-dark" id="vetAddress"></div>
                    </div>
                    <div class="py-2 mt-1">
                        <div class="text-muted small mb-1">Hours</div>
                        <div class="fw-bold text-dark" id="vetHours"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // PDF DOWNLOAD FUNCTION
    function downloadCertificate() {
        const element = document.getElementById('certificateContent');
        var opt = {
            margin: 0.2, // Small margin
            filename: 'Rabies_Certificate.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
    }

    function toggleBreedInput() {
        var select = document.getElementById("breedSelect");
        var otherDiv = document.getElementById("otherBreedDiv");
        if (select.value === "Others") {
            otherDiv.classList.remove("d-none"); 
            otherDiv.querySelector('input').setAttribute('required', 'required'); 
        } else {
            otherDiv.classList.add("d-none"); 
            otherDiv.querySelector('input').removeAttribute('required'); 
            otherDiv.querySelector('input').value = ""; 
        }
    }

    function toggleEditBreedInput() {
        var select = document.getElementById("edit_breedSelect");
        var otherDiv = document.getElementById("edit_otherBreedDiv");
        if (select.value === "Others") {
            otherDiv.classList.remove("d-none");
        } else {
            otherDiv.classList.add("d-none");
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. EDIT PET MODAL LOGIC ---
        var editPetModal = document.getElementById('editPetModal');
        editPetModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 

            // Get Data from Button
            var id = button.getAttribute('data-id');
            var userId = button.getAttribute('data-user-id');
            var veterinarianId = button.getAttribute('data-veterinarian-id'); // Vet ID
            var ownerName = button.getAttribute('data-owner-name');
            var name = button.getAttribute('data-name');
            var species = button.getAttribute('data-species');
            var sex = button.getAttribute('data-sex');
            var breed = button.getAttribute('data-breed');
            var birthdate = button.getAttribute('data-birthdate');
            var weight = button.getAttribute('data-weight');
            var color = button.getAttribute('data-color');
            var vaccination = button.getAttribute('data-vaccination');
            var photoUrl = button.getAttribute('data-photo');

            // Update Form Action
            var form = document.getElementById('editPetForm');
            var actionUrl = "{{ route('barangay.pets.update', '0') }}"; 
            form.action = actionUrl.replace('/0', '/' + id);

            // Fill Fields
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_owner_name').value = ownerName;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_species').value = species;
            document.getElementById('edit_sex').value = sex;
            document.getElementById('edit_birthdate').value = birthdate;
            document.getElementById('edit_weight').value = weight;
            document.getElementById('edit_color').value = color;
            document.getElementById('edit_vaccination_status').value = vaccination;
            document.getElementById('edit_veterinarian_id').value = veterinarianId; // Set Vet Dropdown

            // Handle Breed
            var breedSelect = document.getElementById('edit_breedSelect');
            var otherBreedDiv = document.getElementById('edit_otherBreedDiv');
            var otherBreedInput = document.getElementById('edit_other_breed');
            
            // Logic to check if breed exists in options
            var optionExists = [...breedSelect.options].some(o => o.value === breed);
            if (optionExists) {
                breedSelect.value = breed;
                otherBreedDiv.classList.add('d-none');
                otherBreedInput.value = '';
            } else {
                breedSelect.value = 'Others';
                otherBreedDiv.classList.remove('d-none');
                otherBreedInput.value = breed; 
            }

            // Handle Photo Preview
            var photoDiv = document.getElementById('current_photo_preview_div');
            var photoImg = document.getElementById('current_photo_preview');
            if (photoUrl) {
                photoImg.src = photoUrl;
                photoDiv.style.display = 'block';
            } else {
                photoDiv.style.display = 'none';
            }
        });

        // 2. VIEW PET MODAL LOGIC
        var viewPetModal = document.getElementById('viewPetModal');
        viewPetModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            if (!button.hasAttribute('data-name')) {
                return;
            }

            var name = button.getAttribute('data-name');
            var id = button.getAttribute('data-id');
            var owner = button.getAttribute('data-owner');
            var image = button.getAttribute('data-image');
            var species = button.getAttribute('data-species');
            var sex = button.getAttribute('data-sex');
            var birthdate = button.getAttribute('data-birthdate');
            var weight = button.getAttribute('data-weight');
            var breed = button.getAttribute('data-breed');
            var color = button.getAttribute('data-color');
            var vaccination = button.getAttribute('data-vaccination');

            var ownerEmail = button.getAttribute('data-owner-email');
            var ownerContact = button.getAttribute('data-owner-contact');
            var ownerAddress = button.getAttribute('data-owner-address');

            var vetName = button.getAttribute('data-vet-name');
            var vetSpecialization = button.getAttribute('data-vet-specialization');
            var vetClinic = button.getAttribute('data-vet-clinic');
            var vetLicense = button.getAttribute('data-vet-license');
            var vetContact = button.getAttribute('data-vet-contact');
            var vetEmail = button.getAttribute('data-vet-email');
            var vetAddress = button.getAttribute('data-vet-address');
            var vetHours = button.getAttribute('data-vet-hours');

            var ageText = 'Unknown';
            if (birthdate) {
                var birthDateObj = new Date(birthdate);
                var today = new Date();
                var ageYears = today.getFullYear() - birthDateObj.getFullYear();
                var m = today.getMonth() - birthDateObj.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDateObj.getDate())) { ageYears--; }
                if (ageYears === 0) {
                     var ageMonths = (today.getFullYear() - birthDateObj.getFullYear()) * 12 + (today.getMonth() - birthDateObj.getMonth());
                     ageText = ageMonths + " months";
                } else {
                     ageText = ageYears + " years";
                }
            }

            // Fill Pet Profile
            document.getElementById('viewPetTitle').textContent = name;
            document.getElementById('viewPetImage').src = image;
            document.getElementById('viewPetName').textContent = name;
            document.getElementById('viewPetSpecies').textContent = species;
            document.getElementById('viewPetSex').textContent = sex;
            document.getElementById('viewPetAge').textContent = ageText;
            document.getElementById('viewPetID').textContent = id;
            document.getElementById('viewPetWeight').textContent = weight || '-';
            document.getElementById('viewPetBirthdate').textContent = birthdate || '-';
            document.getElementById('viewPetBreed').textContent = breed || 'Unknown';
            document.getElementById('viewPetColor').textContent = color || '-';
            document.getElementById('viewPetVaccination').textContent = vaccination;
            
            // Update Medical History Title Placeholder
            document.getElementById('medHistPetName').textContent = name;
            
            // SAVE vaccination status to the medical history modal itself
            document.getElementById('medicalHistoryModal').setAttribute('data-saved-vaccination', vaccination);

            // Fill Certificate Fields (NEW)
            document.getElementById('certPetName').textContent = name;
            document.getElementById('certSpecies').textContent = species;
            document.getElementById('certBreed').textContent = breed;
            document.getElementById('certAge').textContent = ageText;
            document.getElementById('certSex').textContent = sex;
            document.getElementById('certColor').textContent = color;
            document.getElementById('certOwner').textContent = owner;
            document.getElementById('certAddress').textContent = ownerAddress;
            document.getElementById('certContact').textContent = ownerContact;

            // Pass Data to Sub-Buttons
            var ownerBtn = document.getElementById('btnOwnerInfo');
            ownerBtn.setAttribute('data-owner-name', owner);
            ownerBtn.setAttribute('data-owner-email', ownerEmail); 
            ownerBtn.setAttribute('data-owner-contact', ownerContact); 
            ownerBtn.setAttribute('data-owner-address', ownerAddress); 

            var vetBtn = document.getElementById('btnVetInfo');
            vetBtn.setAttribute('data-vet-name', vetName);
            vetBtn.setAttribute('data-vet-specialization', vetSpecialization);
            vetBtn.setAttribute('data-vet-clinic', vetClinic);
            vetBtn.setAttribute('data-vet-license', vetLicense);
            vetBtn.setAttribute('data-vet-contact', vetContact);
            vetBtn.setAttribute('data-vet-email', vetEmail);
            vetBtn.setAttribute('data-vet-address', vetAddress);
            vetBtn.setAttribute('data-vet-hours', vetHours);
        });

        // 3. MEDICAL HISTORY MODAL LOGIC (CHECK VACCINATION STATUS)
        var medicalHistoryModal = document.getElementById('medicalHistoryModal');
        medicalHistoryModal.addEventListener('show.bs.modal', function (event) {
            // Get saved status (Saved when viewPetModal opens)
            var status = medicalHistoryModal.getAttribute('data-saved-vaccination');
            var certBtn = document.getElementById('btnViewRabiesCert');

            if (status === 'Fully Vaccinated') {
                certBtn.style.display = 'inline-block';
            } else {
                certBtn.style.display = 'none';
            }
        });

        // 4. OWNER INFO MODAL LOGIC
        var ownerInfoModal = document.getElementById('ownerInfoModal');
        ownerInfoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (button.hasAttribute('data-owner-name')) {
                document.getElementById('ownerName').textContent = button.getAttribute('data-owner-name') || 'Unknown Owner';
                document.getElementById('ownerEmail').textContent = button.getAttribute('data-owner-email') || 'Not Recorded';
                document.getElementById('ownerContact').textContent = button.getAttribute('data-owner-contact') || 'Not Recorded';
                document.getElementById('ownerAddress').textContent = button.getAttribute('data-owner-address') || 'Not Recorded';
            }
        });

        // 5. VETERINARY INFO MODAL LOGIC
        var vetInfoModal = document.getElementById('vetInfoModal');
        vetInfoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (button.hasAttribute('data-vet-name')) {
                document.getElementById('vetName').textContent = button.getAttribute('data-vet-name') || 'Not Assigned';
                document.getElementById('vetSpecialization').textContent = button.getAttribute('data-vet-specialization') || 'General Practice';
                document.getElementById('vetClinic').textContent = button.getAttribute('data-vet-clinic') || 'N/A';
                document.getElementById('vetLicense').textContent = button.getAttribute('data-vet-license') || 'N/A';
                document.getElementById('vetContact').textContent = button.getAttribute('data-vet-contact') || 'N/A';
                document.getElementById('vetEmail').textContent = button.getAttribute('data-vet-email') || 'N/A';
                document.getElementById('vetAddress').textContent = button.getAttribute('data-vet-address') || 'N/A';
                document.getElementById('vetHours').textContent = button.getAttribute('data-vet-hours') || 'N/A';
            }
        });

        // 6. EDIT MEDICAL RECORD LOGIC (UPDATED)
        var editMedicalRecordModal = document.getElementById('editMedicalRecordModal');
        editMedicalRecordModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (button.hasAttribute('data-date')) {
                document.getElementById('edit_med_date').value = button.getAttribute('data-date');
                document.getElementById('edit_med_clinic').value = button.getAttribute('data-clinic');
                
                // UPDATED: Set the dropdown value using the ID
                document.getElementById('edit_med_vet').value = button.getAttribute('data-vet-id');
                
                document.getElementById('edit_med_diagnosis').value = button.getAttribute('data-diagnosis');
                document.getElementById('edit_med_treatment').value = button.getAttribute('data-treatment');
                document.getElementById('edit_med_remarks').value = button.getAttribute('data-remarks');
            }
        });
    });
</script>
@endsection