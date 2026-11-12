<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-panel p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                    <h3 class="text-white mb-0"><i class="bi bi-pencil-square text-primary me-2"></i> Submit Official Civic Complaint</h3>
                    <a href="/complaints" class="btn btn-sm btn-outline-secondary">&larr; Back to Queue</a>
                </div>

                <form action="/complaints" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \CivicMind\Core\Session::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label text-white small fw-bold">Issue Title / Short Summary</label>
                        <input type="text" class="form-control form-control-glass text-white" id="title" name="title" placeholder="e.g. Deep Pothole on Main Road causing traffic bottleneck" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label text-white small fw-bold">Category</label>
                            <select class="form-select form-control-glass text-white" id="category" name="category" required>
                                <option value="Electrical">Electrical & Streetlights</option>
                                <option value="Roads">Roads & Infrastructure</option>
                                <option value="Sanitation">Sanitation & Garbage</option>
                                <option value="Water">Water Supply & Sewerage</option>
                                <option value="Public Safety">Public Safety & Crime</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="department_id" class="form-label text-white small fw-bold">Target Department</label>
                            <select class="form-select form-control-glass text-white" id="department_id" name="department_id">
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="severity" class="form-label text-white small fw-bold">Severity Level (1 = Minor, 5 = Critical Hazard)</label>
                        <input type="range" class="form-range" id="severity" name="severity" min="1" max="5" value="3" oninput="document.getElementById('sev-val').innerText = this.value">
                        <div class="text-warning small text-end">Selected Severity Level: <strong id="sev-val">3</strong> / 5</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label text-white small fw-bold">Detailed Problem Description</label>
                        <textarea class="form-control form-control-glass text-white" id="description" name="description" rows="4" placeholder="Provide details such as duration of issue, exact landmark, potential safety hazard..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label text-white small fw-bold">Street Address / Landmark</label>
                            <input type="text" class="form-control form-control-glass text-white" id="address" name="address" placeholder="12th Main Road, Anna Nagar">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="latitude" class="form-label text-white small fw-bold">Latitude (GPS)</label>
                            <input type="number" step="any" class="form-control form-control-glass text-white" id="latitude" name="latitude" value="13.0850">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="longitude" class="form-label text-white small fw-bold">Longitude (GPS)</label>
                            <input type="number" step="any" class="form-control form-control-glass text-white" id="longitude" name="longitude" value="80.2100">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="form-label text-white small fw-bold">Attach Evidence Photo (Optional)</label>
                        <input type="file" class="form-control form-control-glass text-white" id="photo" name="photo" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-glass-primary w-100 py-2 fs-5">
                        <i class="bi bi-send-fill me-2"></i> Submit Official Complaint
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
