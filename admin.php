<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="text-white mb-1"><i class="bi bi-speedometer2 text-danger me-2"></i> Municipal Executive Control Dashboard</h2>
            <p class="text-secondary small mb-0">System Administrator Portal &bull; Welcome, <strong><?= htmlspecialchars($user['full_name']) ?></strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="/analytics" class="btn btn-outline-info"><i class="bi bi-graph-up me-1"></i> Full Analytics</a>
            <a href="/heatmaps" class="btn btn-glass-primary"><i class="bi bi-map me-1"></i> GIS Map</a>
        </div>
    </div>

    <!-- METRICS CARDS ROW -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-3">
                <div class="text-secondary small fw-bold">TOTAL LOGGED COMPLAINTS</div>
                <div class="display-6 fw-bold text-white mt-1"><?= $stats['total_complaints'] ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-3">
                <div class="text-secondary small fw-bold">IN PROGRESS / SLA ACTIVE</div>
                <div class="display-6 fw-bold text-warning mt-1"><?= $stats['in_progress'] ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-3">
                <div class="text-secondary small fw-bold">RESOLVED COMPLAINTS</div>
                <div class="display-6 fw-bold text-success mt-1"><?= $stats['resolved'] ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-3">
                <div class="text-secondary small fw-bold">HIGH-RISK SCAMS BLOCKED</div>
                <div class="display-6 fw-bold text-danger mt-1"><?= $stats['scams_flagged'] ?></div>
            </div>
        </div>
    </div>

    <!-- MAIN COMPLAINT MANAGEMENT TABLE -->
    <div class="glass-panel p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-white mb-0"><i class="bi bi-table text-primary me-2"></i> City-Wide Complaint Queue & Status Workflow</h5>
            <a href="/complaints" class="btn btn-sm btn-outline-light rounded-pill">View All Queue &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle bg-transparent mb-0">
                <thead>
                    <tr class="text-secondary small">
                        <th>Ticket #</th>
                        <th>Reporter</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Severity</th>
                        <th>Status Workflow</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentComplaints as $cmp): ?>
                        <tr>
                            <td class="fw-bold text-info"><?= htmlspecialchars($cmp['complaint_number']) ?></td>
                            <td class="text-white"><?= htmlspecialchars($cmp['reporter_name'] ?? 'Citizen') ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($cmp['category']) ?></span></td>
                            <td class="text-light"><?= htmlspecialchars($cmp['title']) ?></td>
                            <td>
                                <span class="badge bg-dark border border-warning text-warning">Level <?= $cmp['severity'] ?></span>
                            </td>
                            <td>
                                <form action="/complaints/<?= $cmp['id'] ?>/status" method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= \CivicMind\Core\Session::csrfToken() ?>">
                                    <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary" style="width: auto;" onchange="this.form.submit()">
                                        <option value="Submitted" <?= $cmp['status'] === 'Submitted' ? 'selected' : '' ?>>Submitted</option>
                                        <option value="Under Review" <?= $cmp['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                                        <option value="Assigned" <?= $cmp['status'] === 'Assigned' ? 'selected' : '' ?>>Assigned</option>
                                        <option value="In Progress" <?= $cmp['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Resolved" <?= $cmp['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                        <option value="Closed" <?= $cmp['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="/complaints/<?= $cmp['id'] ?>/pdf" target="_blank" class="btn btn-sm btn-outline-danger" title="Download PDF Report">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
