<div class="modal fade" id="certificateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
                <button onclick="downloadCertificate()" class="btn btn-sm btn-outline-danger fw-bold">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                </button>
                {{-- Go back to View Details Modal --}}
                <button type="button" class="btn-close" data-bs-target="#viewPetModal" data-bs-toggle="modal" aria-label="Back"></button>
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