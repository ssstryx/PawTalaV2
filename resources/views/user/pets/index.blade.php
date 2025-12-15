@extends('layouts.user.app')

@section('content')
{{-- 1. PDF LIBRARY FOR CERTIFICATES --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container-fluid py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark" style="color: #4E342E;">My Pets</h2>
            <p class="text-muted mb-0">Manage your furry companions' profiles.</p>
        </div>
        <button class="btn btn-primary shadow-sm" style="background-color: #E59500; border: none;" data-bs-toggle="modal" data-bs-target="#addPetModal">
            <i class="fas fa-paw me-2"></i> Add New Pet
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- PETS GRID --}}
    <div class="row g-4">
        @forelse($pets as $pet)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; overflow: hidden; transition: transform 0.2s;">
                    <div class="position-relative" style="height: 200px; background-color: #f8f9fa;">
                         <img src="{{ $pet->photo_path ? asset('storage/' . $pet->photo_path) : asset('images/default-pet.png') }}" 
                             class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $pet->name }}">
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            @if($pet->vaccination_status == 'Fully Vaccinated')
                                <span class="badge bg-success rounded-pill shadow-sm"><i class="fas fa-check-circle me-1"></i> Vaccinated</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill shadow-sm"><i class="fas fa-exclamation-circle me-1"></i> {{ $pet->vaccination_status }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body text-center bg-white">
                        <h4 class="card-title fw-bold text-dark mb-1">{{ $pet->name }}</h4>
                        
                        @php
                            $ageDisplay = 'N/A';
                            if ($pet->birthdate) {
                                $birthDate = \Carbon\Carbon::parse($pet->birthdate);
                                $now = \Carbon\Carbon::now();
                                $years = (int) $birthDate->diffInYears($now);
                                $months = (int) $birthDate->diffInMonths($now);
                                $ageDisplay = $years > 0 ? $years . ' years' : $months . ' months';
                            }
                        @endphp
                        
                        <p class="text-muted small mb-3">{{ $pet->breed }} • {{ $ageDisplay }}</p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" 
                                    data-bs-toggle="modal" data-bs-target="#viewPetModal" data-pet="{{ json_encode($pet) }}">
                                <i class="fas fa-eye me-1"></i> Details
                            </button>

                            <button class="btn btn-sm text-white rounded-pill px-3" 
                                    style="background-color: #4E342E;"
                                    data-bs-toggle="modal" data-bs-target="#editPetModal" data-pet="{{ json_encode($pet) }}">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">You haven't added any pets yet.</h4>
                <p class="text-muted small">Click the "Add New Pet" button to get started!</p>
            </div>
        @endforelse
    </div>
</div>

{{-- INCLUDE ALL MODALS --}}
@include('user.pets.partials.add_modal')
@include('user.pets.partials.edit_modal')
@include('user.pets.partials.view_modal')
@include('user.pets.partials.certificate_modal')

<style>
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>

{{-- JAVASCRIPT LOGIC --}}
<script>
    // --- PDF DOWNLOAD FUNCTION ---
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

    // --- BREED TOGGLE LOGIC ---
    function toggleBreedInput() {
        var select = document.getElementById("breedSelect");
        var otherDiv = document.getElementById("otherBreedDiv");
        var otherInput = otherDiv.querySelector('input');
        if (select.value === "Others") {
            otherDiv.classList.remove("d-none"); otherInput.setAttribute('required', 'required');
        } else {
            otherDiv.classList.add("d-none"); otherInput.removeAttribute('required'); otherInput.value = "";
        }
    }

    function toggleEditBreedInput() {
        var select = document.getElementById("edit_breedSelect");
        var otherDiv = document.getElementById("edit_otherBreedDiv");
        var otherInput = document.getElementById('edit_other_breed');
        if (select.value === "Others") {
            otherDiv.classList.remove("d-none"); otherInput.setAttribute('required', 'required');
        } else {
            otherDiv.classList.add("d-none"); otherInput.removeAttribute('required'); otherInput.value = "";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        // --- EDIT MODAL POPULATION ---
        var editModal = document.getElementById('editPetModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var pet = JSON.parse(button.getAttribute('data-pet'));
            
            var form = document.getElementById('editPetForm');
            form.action = '/user/pets/' + pet.id; 

            document.getElementById('edit_name').value = pet.name;
            document.getElementById('edit_species').value = pet.species;
            document.getElementById('edit_sex').value = pet.sex;
            document.getElementById('edit_weight').value = pet.weight || '';
            
            if(pet.birthdate) {
                document.getElementById('edit_birthdate').value = pet.birthdate.split('T')[0].substring(0, 10);
            } else {
                document.getElementById('edit_birthdate').value = '';
            }

            var breedSelect = document.getElementById('edit_breedSelect');
            var otherBreedDiv = document.getElementById('edit_otherBreedDiv');
            var otherBreedInput = document.getElementById('edit_other_breed');
            
            var breedOptionExists = Array.from(breedSelect.options).some(option => option.value === pet.breed);

            if (breedOptionExists) {
                breedSelect.value = pet.breed;
                otherBreedDiv.classList.add('d-none');
                otherBreedInput.removeAttribute('required');
            } else {
                breedSelect.value = 'Others';
                otherBreedDiv.classList.remove('d-none');
                otherBreedInput.value = pet.breed;
                otherBreedInput.setAttribute('required', 'required');
            }

            var colorSelect = document.getElementById('edit_colorSelect');
            var colorOptionExists = Array.from(colorSelect.options).some(option => option.value === pet.color);
            if(colorOptionExists) {
                colorSelect.value = pet.color;
            } else {
                colorSelect.value = 'Other'; 
            }

            var vacDisplay = document.getElementById('edit_vaccination_display');
            if(vacDisplay) vacDisplay.value = pet.vaccination_status;
        });

        // --- VIEW MODAL & CERTIFICATE POPULATION ---
        var viewModal = document.getElementById('viewPetModal');
        viewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var pet = JSON.parse(button.getAttribute('data-pet'));
            
            document.getElementById('view_pet_name').textContent = pet.name;
            document.getElementById('view_pet_breed').textContent = pet.breed || 'Unknown Breed';
            document.getElementById('view_pet_species').textContent = pet.species;
            document.getElementById('view_pet_sex').textContent = pet.sex;
            document.getElementById('view_pet_weight').textContent = pet.weight ? pet.weight + ' kg' : 'N/A';
            document.getElementById('view_pet_birthdate').textContent = pet.birthdate ? pet.birthdate.substring(0, 10) : 'N/A';

            var imgElement = document.getElementById('view_pet_image');
            imgElement.src = pet.photo_path ? "{{ asset('storage') }}/" + pet.photo_path : "{{ asset('images/default-pet.png') }}";

            // CALCULATE AGE FOR CERTIFICATE
            var ageText = "N/A";
            if(pet.birthdate) {
                var birth = new Date(pet.birthdate);
                var today = new Date();
                var diff = today.getTime() - birth.getTime();
                var years = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
                if (years > 0) ageText = years + " years";
                else {
                    var months = Math.floor(diff / (1000 * 60 * 60 * 24 * 30.44));
                    ageText = months + " months";
                }
            }

            // VACCINATION & CERTIFICATE BUTTON LOGIC
            var badge = document.getElementById('view_pet_status_badge');
            var certBtn = document.getElementById('btn_view_certificate');
            
            badge.textContent = pet.vaccination_status;
            badge.className = 'badge rounded-pill px-3 py-2';
            
            if(pet.vaccination_status === 'Fully Vaccinated') {
                badge.classList.add('bg-success');
                certBtn.classList.remove('d-none'); // Show Certificate Button
                
                // POPULATE CERTIFICATE DATA (Admin Layout IDs)
                document.getElementById('certPetName').textContent = pet.name;
                document.getElementById('certSpecies').textContent = pet.species;
                document.getElementById('certBreed').textContent = pet.breed;
                document.getElementById('certAge').textContent = ageText;
                document.getElementById('certSex').textContent = pet.sex;
                document.getElementById('certColor').textContent = pet.color;
                
                // POPULATE OWNER DATA (Logged in User)
                document.getElementById('certOwner').textContent = "{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}";
                document.getElementById('certAddress').textContent = "{{ Auth::user()->address ?? 'N/A' }}";
                document.getElementById('certContact').textContent = "{{ Auth::user()->contact_number ?? 'N/A' }}";

            } else {
                if (pet.vaccination_status === 'Partially Vaccinated') {
                    badge.classList.add('bg-warning', 'text-dark');
                } else {
                    badge.classList.add('bg-secondary');
                }
                certBtn.classList.add('d-none'); // Hide Certificate Button
            }
        });
    });
</script>
@endsection