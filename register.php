<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-md-6">
            <div class="glass-panel p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="text-white fw-bold mb-1">Create CivicMind Account</h3>
                    <p class="text-secondary small">Join the unified AI digital life operating system</p>
                </div>

                <form action="/register" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \CivicMind\Core\Session::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="role_id" class="form-label text-white small fw-bold">Select Account Role</label>
                        <select class="form-select form-control-glass text-white" id="role_id" name="role_id" onchange="toggleProviderFields(this.value)">
                            <option value="1" selected>Citizen - Report issues & access AI services</option>
                            <option value="2">Service Provider - Offer local repair & maintenance services</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label text-white small fw-bold">Full Name</label>
                        <input type="text" class="form-control form-control-glass text-white" id="full_name" name="full_name" placeholder="John Doe" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label text-white small fw-bold">Email Address</label>
                            <input type="email" class="form-control form-control-glass text-white" id="email" name="email" placeholder="john@example.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label text-white small fw-bold">Phone Number</label>
                            <input type="tel" class="form-control form-control-glass text-white" id="phone" name="phone" placeholder="+91 98765 43210">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-white small fw-bold">Password</label>
                        <input type="password" class="form-control form-control-glass text-white" id="password" name="password" required>
                    </div>

                    <!-- SERVICE PROVIDER SPECIFIC FIELDS -->
                    <div id="provider-fields" style="display: none;" class="glass-card p-3 mb-4">
                        <h6 class="text-info mb-3">Service Provider Details</h6>
                        <div class="mb-3">
                            <label for="business_name" class="form-label text-white small">Business / Agency Name</label>
                            <input type="text" class="form-control form-control-glass text-white" id="business_name" name="business_name" placeholder="Quick Electricals">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_category" class="form-label text-white small">Primary Category</label>
                                <select class="form-select form-control-glass text-white" id="service_category" name="service_category">
                                    <option value="Electrician">Electrician</option>
                                    <option value="Plumber">Plumber</option>
                                    <option value="Tutor">Tutor</option>
                                    <option value="Mechanic">Mechanic</option>
                                    <option value="Carpenter">Carpenter</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hourly_rate" class="form-label text-white small">Hourly Rate (₹)</label>
                                <input type="number" class="form-control form-control-glass text-white" id="hourly_rate" name="hourly_rate" value="450">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-glass-primary w-100 py-2">
                        <i class="bi bi-person-plus-fill me-2"></i> Register Account
                    </button>
                </form>

                <hr class="border-secondary my-4">
                <div class="text-center text-secondary small">
                    Already have an account? <a href="/login" class="text-primary text-decoration-none fw-bold">Login Here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleProviderFields(roleId) {
    document.getElementById('provider-fields').style.display = parseInt(roleId) === 2 ? 'block' : 'none';
}
</script>
