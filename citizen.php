<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="text-white mb-1"><i class="bi bi-speedometer text-primary me-2"></i> Citizen Digital Command Dashboard</h2>
            <p class="text-secondary small mb-0">Welcome back, <strong><?= htmlspecialchars($user['full_name']) ?></strong></p>
        </div>
        <a href="/complaints/create" class="btn btn-glass-primary">
            <i class="bi bi-plus-circle-fill me-2"></i> File New Civic Complaint
        </a>
    </div>

    <div class="row g-4 mb-4">
        <!-- MY COMPLAINTS TAB -->
        <div class="col-lg-8">
            <div class="glass-panel p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                    <h5 class="text-white mb-0"><i class="bi bi-card-checklist text-primary me-2"></i> My Reported Complaints</h5>
                    <span class="badge bg-primary"><?= count($myComplaints) ?> Total</span>
                </div>

                <?php if (empty($myComplaints)): ?>
                    <div class="text-center text-secondary py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                        No active complaints reported yet.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle bg-transparent mb-0">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>Complaint ID</th>
                                    <th>Category</th>
                                    <th>Issue Title</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($myComplaints as $cmp): ?>
                                    <tr>
                                        <td class="fw-bold text-info"><?= htmlspecialchars($cmp['complaint_number']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($cmp['category']) ?></span></td>
                                        <td class="text-white"><?= htmlspecialchars($cmp['title']) ?></td>
                                        <td>
                                            <span class="badge bg-dark border border-warning text-warning">Level <?= $cmp['severity'] ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                                $statusClass = match($cmp['status']) {
                                                    'Submitted' => 'badge-submitted',
                                                    'Assigned' => 'badge-assigned',
                                                    'In Progress' => 'badge-in-progress',
                                                    'Resolved' => 'badge-resolved',
                                                    default => 'badge-closed',
                                                };
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($cmp['status']) ?></span>
                                        </td>
                                        <td>
                                            <a href="/complaints/<?= $cmp['id'] ?>" class="btn btn-sm btn-outline-light rounded-pill">View &rarr;</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- QUICK ACCESS WIDGETS -->
        <div class="col-lg-4">
            <div class="glass-panel p-4 mb-4">
                <h5 class="text-white mb-3"><i class="bi bi-journal-bookmark-fill text-warning me-2"></i> My Service Bookings</h5>
                <?php if (empty($myBookings)): ?>
                    <p class="text-secondary small">No active service provider bookings.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush bg-transparent">
                        <?php foreach ($myBookings as $bk): ?>
                            <li class="list-group-item bg-transparent text-light border-secondary px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-white d-block"><?= htmlspecialchars($bk['business_name']) ?></strong>
                                        <small class="text-secondary"><?= date('d M Y, h:i A', strtotime($bk['scheduled_date'])) ?></small>
                                    </div>
                                    <span class="badge bg-success">₹<?= $bk['total_price'] ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="/providers" class="btn btn-outline-info rounded-pill w-100 mt-3 btn-sm">Book Local Provider &rarr;</a>
            </div>

            <div class="glass-panel p-4">
                <h5 class="text-white mb-3"><i class="bi bi-shield-check text-danger me-2"></i> Instant Tools</h5>
                <div class="d-grid gap-2">
                    <a href="/scams" class="btn btn-outline-danger btn-sm text-start"><i class="bi bi-shield-exclamation me-2"></i> Scan Suspicious SMS / Text</a>
                    <a href="/bills" class="btn btn-outline-success btn-sm text-start"><i class="bi bi-receipt me-2"></i> Upload Utility Bill for OCR</a>
                    <a href="/schemes" class="btn btn-outline-warning btn-sm text-start"><i class="bi bi-award me-2"></i> Check Gov Scheme Eligibility</a>
                </div>
            </div>
        </div>
    </div>
</div>
