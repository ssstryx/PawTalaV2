<div class="modal fade" id="editPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #4E342E;">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Pet Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Form Action is set via JS --}}
                <form id="editPetForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="alert alert-warning small mb-3">
                        <i class="fas fa-info-circle me-1"></i> Note: To update vaccination status, please visit the Barangay Clinic.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Pet Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Vaccination Status</label>
                            <input type="text" class="form-control bg-light" id="edit_vaccination_display" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Species</label>
                            <select class="form-select" name="species" id="edit_species" required>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Sex</label>
                            <select class="form-select" name="sex" id="edit_sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        {{-- BREED DROPDOWN (EDIT) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Breed</label>
                            <select class="form-select" id="edit_breedSelect" name="breed" onchange="toggleEditBreedInput()" required>
                                <optgroup label="Dogs">
                                    <option value="Aspin (Asong Pinoy)">Aspin</option>
                                    <option value="Shih Tzu">Shih Tzu</option>
                                    <option value="Golden Retriever">Golden Retriever</option>
                                    <option value="German Shepherd">German Shepherd</option>
                                </optgroup>
                                <optgroup label="Cats">
                                    <option value="Puspin (Pusang Pinoy)">Puspin</option>
                                    <option value="Persian">Persian</option>
                                    <option value="Siamese">Siamese</option>
                                </optgroup>
                                <option value="Others">Others (Specify)</option>
                            </select>
                            
                            {{-- HIDDEN INPUT FOR "OTHERS" --}}
                            <div class="mt-2 d-none" id="edit_otherBreedDiv">
                                <input type="text" class="form-control" name="other_breed" id="edit_other_breed" placeholder="Specify breed">
                            </div>
                        </div>

                        {{-- COLOR DROPDOWN (EDIT) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Color / Markings</label>
                            <select class="form-select" name="color" id="edit_colorSelect">
                                <option value="Black">Black</option>
                                <option value="White">White</option>
                                <option value="Brown">Brown</option>
                                <option value="Golden / Cream">Golden / Cream</option>
                                <option value="Gray / Blue">Gray / Blue</option>
                                <option value="Tricolor">Tricolor</option>
                                <option value="Spotted">Spotted</option>
                                <option value="Striped / Brindle">Striped / Brindle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" id="edit_birthdate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Weight (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" id="edit_weight">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Update Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #4E342E; border: none;">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>