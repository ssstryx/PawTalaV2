<div class="modal fade" id="addPetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #E59500;">
                <h5 class="modal-title fw-bold"><i class="fas fa-paw me-2"></i>Register New Pet</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('user.pets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Hidden Owner ID --}}
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Brownie">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Species <span class="text-danger">*</span></label>
                            <select class="form-select" name="species" required>
                                <option value="" selected disabled>Select Species</option>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        {{-- BREED DROPDOWN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Breed <span class="text-danger">*</span></label>
                            <select class="form-select" id="breedSelect" name="breed" onchange="toggleBreedInput()" required>
                                <option value="" selected disabled>Select Breed</option>
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
                            
                            {{-- HIDDEN INPUT FOR "OTHERS" --}}
                            <div class="mt-2 d-none" id="otherBreedDiv">
                                <input type="text" class="form-control" name="other_breed" placeholder="Please specify the breed">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Sex <span class="text-danger">*</span></label>
                            <select class="form-select" name="sex" required>
                                <option value="" selected disabled>Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Weight (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" placeholder="e.g. 5.5">
                        </div>
                    </div>

                    {{-- COLOR DROPDOWN --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Color / Markings</label>
                        <select class="form-select" name="color">
                            <option value="" selected disabled>Select Color</option>
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

                    <div class="mb-4 p-3 bg-light rounded border border-dashed text-center">
                        <label class="form-label fw-bold mb-2">Upload Pet Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <small class="text-muted d-block mt-1">Accepted formats: JPG, PNG. Max size: 2MB.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2" style="background-color: #E59500; border: none;">
                            Save Pet Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>