<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <span class="badge bg-primary text-uppercase mb-1">Ticket #<?= htmlspecialchars($complaint['complaint_number']) ?></span>
            <h2 class="text-white mb-0"><?= htmlspecialchars($complaint['title']) ?></h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/complaints/<?= $complaint['id'] ?>/pdf" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF Report
            </a>
            <a href="/complaints" class="btn btn-outline-secondary">&larr; Back to Queue</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT CONTENT COLUMN -->
        <div class="col-lg-8">
            <div class="glass-panel p-4 mb-4">
                <h5 class="text-white mb-3 border-bottom border-secondary pb-2"><i class="bi bi-info-circle-fill text-info me-2"></i> Issue Details</h5>
                <p class="text-light leading-relaxed fs-6 mb-4"><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="glass-card p-3">
                            <small class="text-secondary d-block">Location Address</small>
                            <strong class="text-white fs-6"><?= htmlspecialchars($complaint['address'] ?: 'Pin GPS Location') ?></strong>
                            <div class="text-muted small">GPS: <?= $complaint['latitude'] ?>, <?= $complaint['longitude'] ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card p-3">
                            <small class="text-secondary d-block">SLA Target Resolution</small>
                            <strong class="text-success fs-6"><?= $complaint['sla_deadline'] ? date('d M Y, h:i A', strtotime($complaint['sla_deadline'])) : 'Within 3 Days' ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AUDIT & STATUS TIMELINE -->
            <div class="glass-panel p-4">
                <h5 class="text-white mb-3 border-bottom border-secondary pb-2"><i class="bi bi-clock-history text-warning me-2"></i> Status Progress Audit History</h5>
                
                <?php if (empty($history)): ?>
                    <p class="text-secondary small">Complaint submitted. Pending municipal officer review.</p>
                <?php else: ?>
                    <div class="timeline ps-3 border-start border-primary">
                        <?php foreach ($history as $h): ?>
                            <div class="mb-3 position-relative ps-3">
                                <span class="badge bg-primary text-capitalize"><?= htmlspecialchars($h['new_status']) ?></span>
                                <small class="text-secondary ms-2"><?= date('d M Y, h:i A', strtotime($h['created_at'])) ?> by <?= htmlspecialchars($h['actor_name'] ?? 'Officer') ?></small>
                                <p class="text-light small mt-1 mb-0"><?= htmlspecialchars($h['comments']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT SIDEBAR COLUMN -->
        <div class="col-lg-4">
            <div class="glass-panel p-4 mb-4">
                <h5 class="text-white mb-3"><i class="bi bi-sliders text-primary me-2"></i> Ticket Controls</h5>
                
                <div class="mb-3">
                    <small class="text-secondary d-block">Current Status</small>
                    <span class="status-badge badge-in-progress fs-6 d-inline-block mt-1"><?= htmlspecialchars($complaint['status']) ?></span>
                </div>

                <div class="mb-3">
                    <small class="text-secondary d-block">Assigned Department</small>
                    <strong class="text-white"><?= htmlspecialchars($complaint['department_name'] ?? 'Municipal Board') ?></strong>
                </div>

                <div class="mb-4">
                    <small class="text-secondary d-block">Severity Rating</small>
                    <span class="badge bg-warning text-dark fs-6 mt-1">Level <?= $complaint['severity'] ?> / 5</span>
                </div>

                <!-- UPDATE STATUS FORM FOR OFFICERS / ADMIN -->
                <form action="/complaints/<?= $complaint['id'] ?>/status" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \CivicMind\Core\Session::csrfToken() ?>">
                    <label for="status" class="form-label text-white small fw-bold">Update Ticket Status</label>
                    <select name="status" id="status" class="form-select form-control-glass text-white mb-3">
                        <option value="Submitted">Submitted</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Assigned">Assigned</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>

                    <div class="mb-3">
                        <label for="comments" class="form-label text-white small fw-bold">Update Notes / Comments</label>
                        <input type="text" class="form-control form-control-glass text-white" id="comments" name="comments" placeholder="Dispatching field technician...">
                    </div>

                    <button type="submit" class="btn btn-glass-primary w-100">
                        <i class="bi bi-arrow-repeat me-1"></i> Update Status & Notify
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
