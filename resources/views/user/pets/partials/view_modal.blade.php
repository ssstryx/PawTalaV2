<div class="modal fade" id="viewPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-0">
                {{-- Top Image Banner --}}
                <div class="position-relative" style="height: 250px; background-color: #333;">
                    <img id="view_pet_image" src="" class="w-100 h-100 object-fit-cover" style="opacity: 0.9;">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 p-2 bg-dark rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <h3 class="text-white fw-bold mb-0" id="view_pet_name">Pet Name</h3>
                        <p class="text-white-50 mb-0 small"><span id="view_pet_breed">Breed</span></p>
                    </div>
                </div>

                {{-- Details Section --}}
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="text-center px-3 border-end">
                            <small class="text-muted text-uppercase fw-bold d-block">Species</small>
                            <span class="fw-bold text-dark" id="view_pet_species">Dog</span>
                        </div>
                        <div class="text-center px-3 border-end">
                            <small class="text-muted text-uppercase fw-bold d-block">Sex</small>
                            <span class="fw-bold text-dark" id="view_pet_sex">Male</span>
                        </div>
                        <div class="text-center px-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Weight</small>
                            <span class="fw-bold text-dark" id="view_pet_weight">5kg</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold">Vaccination Status</label>
                        <div class="mt-1 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill px-3 py-2" id="view_pet_status_badge" style="font-size: 0.9rem;">
                                Fully Vaccinated
                            </span>

                            {{-- NEW CERTIFICATE BUTTON (Hidden by default) --}}
                            <button id="btn_view_certificate" 
                                    class="btn btn-warning btn-sm text-white fw-bold shadow-sm d-none" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#certificateModal">
                                <i class="fas fa-award me-1"></i> View Certificate
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <label class="small text-muted text-uppercase fw-bold">Birthdate</label>
                        <p class="fw-bold text-dark" id="view_pet_birthdate">Jan 01, 2023</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>