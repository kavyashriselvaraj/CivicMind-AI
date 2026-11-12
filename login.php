<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-md-5">
            <div class="glass-panel p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="rounded-circle p-3 d-inline-flex mb-2" style="background: linear-gradient(135deg, #2563eb, #6366f1);">
                        <i class="bi bi-person-fill text-white fs-2"></i>
                    </div>
                    <h3 class="text-white fw-bold mb-1">Welcome Back</h3>
                    <p class="text-secondary small">Access your CivicMind X Digital Life Operating System</p>
                </div>

                <!-- LOGIN TABS: PASSWORD / OTP -->
                <ul class="nav nav-pills nav-justified mb-4 glass-card p-1">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill py-2 text-white" id="pwd-tab" data-bs-toggle="pill" data-bs-target="#pwd-form">Password Login</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill py-2 text-white" id="otp-tab" data-bs-toggle="pill" data-bs-target="#otp-form">OTP Login</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- PASSWORD FORM -->
                    <div class="tab-pane fade show active" id="pwd-form">
                        <form action="/login" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= \CivicMind\Core\Session::csrfToken() ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label text-white small fw-bold">Email Address</label>
                                <input type="email" class="form-control form-control-glass text-white" id="email" name="email" value="citizen@civicmind.org" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label text-white small fw-bold">Password</label>
                                <input type="password" class="form-control form-control-glass text-white" id="password" name="password" value="password123" required>
                                <div class="form-text text-secondary small">Default Demo Passwords: <code>password123</code></div>
                            </div>
                            <button type="submit" class="btn btn-glass-primary w-100 py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login to CivicMind
                            </button>
                        </form>
                    </div>

                    <!-- OTP FORM -->
                    <div class="tab-pane fade" id="otp-form">
                        <div class="mb-3">
                            <label for="otp-phone" class="form-label text-white small fw-bold">Mobile Phone</label>
                            <input type="tel" class="form-control form-control-glass text-white" id="otp-phone" value="+91 98765 43210">
                        </div>
                        <div class="mb-4">
                            <label for="otp-code" class="form-label text-white small fw-bold">6-Digit OTP Code</label>
                            <input type="text" class="form-control form-control-glass text-white" id="otp-code" placeholder="123456">
                            <div class="form-text text-secondary small">Demo OTP: <code>123456</code></div>
                        </div>
                        <button type="button" class="btn btn-glass-primary w-100 py-2" onclick="verifyOtpCode()">
                            <i class="bi bi-shield-check me-2"></i> Verify OTP & Login
                        </button>
                    </div>
                </div>

                <hr class="border-secondary my-4">
                <div class="text-center text-secondary small">
                    Don't have an account? <a href="/register" class="text-primary text-decoration-none fw-bold">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function verifyOtpCode() {
    const phone = document.getElementById('otp-phone').value;
    const otp = document.getElementById('otp-code').value;
    try {
        const res = await fetch('/api/auth/verify-otp', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ phone, otp })
        });
        const data = await res.json();
        if (data.success) {
            showToast('OTP Verification Successful!', 'success');
            window.location.href = '/dashboard';
        } else {
            showToast(data.error || 'Invalid OTP', 'error');
        }
    } catch (e) {
        showToast('OTP Verification Error', 'error');
    }
}
</script>
