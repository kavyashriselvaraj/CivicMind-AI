<?php
use CivicMind\Core\Session;
use CivicMind\Services\TranslationEngine;

$currentUser = Session::get('user');
$currentLang = Session::get('lang', 'en');
?>
<nav class="navbar navbar-expand-lg navbar-glass">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2563eb, #6366f1); width: 38px; height: 38px;">
                <i class="bi bi-cpu-fill text-white fs-5"></i>
            </div>
            <span class="brand-text fs-4 text-white">CivicMind <span class="text-primary">X</span></span>
        </a>

        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-2">
                <li class="nav-item">
                    <a class="nav-link text-light" href="/ai-solver">
                        <i class="bi bi-robot text-info"></i> AI Solver
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/complaints">
                        <i class="bi bi-card-checklist text-primary"></i> Complaints
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/schemes">
                        <i class="bi bi-award-fill text-warning"></i> Schemes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/scams">
                        <i class="bi bi-shield-exclamation text-danger"></i> Scam Radar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/bills">
                        <i class="bi bi-receipt-cutoff text-success"></i> Bills & OCR
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/providers">
                        <i class="bi bi-tools text-info"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/heatmaps">
                        <i class="bi bi-map-fill text-info"></i> GIS Map
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/analytics">
                        <i class="bi bi-graph-up-arrow text-primary"></i> Analytics
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 ms-lg-2">
                <!-- Theme Toggle Button -->
                <button type="button" class="btn btn-outline-secondary rounded-circle p-2 d-flex align-items-center justify-content-center" id="theme-toggle-btn" style="width: 36px; height: 36px;" title="Toggle Light/Dark Theme">
                    <i class="bi bi-sun-fill text-warning" id="theme-icon"></i>
                </button>

                <?php if ($currentUser): ?>
                    <a href="/dashboard" class="btn btn-outline-primary rounded-pill px-3 py-1 text-white d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span><?= htmlspecialchars($currentUser['full_name']) ?></span>
                        <span class="badge bg-primary text-capitalize"><?= htmlspecialchars($currentUser['role_name'] ?? 'User') ?></span>
                    </a>
                    <a href="/logout" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-light rounded-pill px-3 py-1">Login</a>
                    <a href="/register" class="btn btn-glass-primary px-3 py-1 text-nowrap">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Notifications Display -->
<?php if ($flashSuccess = Session::getFlash('success')): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show glass-panel text-white border-success mb-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($flashSuccess) ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<?php if ($flashError = Session::getFlash('error')): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show glass-panel text-white border-danger mb-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($flashError) ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>
