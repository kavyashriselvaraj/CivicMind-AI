<div class="container py-4">
    <div class="row justify-content-center text-center mb-4">
        <div class="col-lg-8">
            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning px-3 py-1 rounded-pill mb-2">
                <i class="bi bi-award-fill me-1"></i> Module 4: Welfare Scheme Engine
            </span>
            <h2 class="text-white display-5 fw-bold mb-2">Government Scheme Discovery</h2>
            <p class="text-secondary fs-5">Enter your demographic profile to automatically discover eligible Central and State welfare schemes, subsidy grants, and application procedures.</p>
        </div>
    </div>

    <!-- DEMOGRAPHIC FILTER BOX -->
    <div class="glass-panel p-4 mb-5">
        <h5 class="text-white mb-3"><i class="bi bi-sliders text-warning me-2"></i> Demographic Filter Criteria</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label for="filter-age" class="form-label text-white small fw-bold">Age (Years)</label>
                <input type="number" class="form-control form-control-glass text-white" id="filter-age" value="35" placeholder="35">
            </div>
            <div class="col-md-3">
                <label for="filter-income" class="form-label text-white small fw-bold">Annual Family Income (₹)</label>
                <input type="number" class="form-control form-control-glass text-white" id="filter-income" value="150000" placeholder="150000">
            </div>
            <div class="col-md-3">
                <label for="filter-occupation" class="form-label text-white small fw-bold">Target Occupation</label>
                <select class="form-select form-control-glass text-white" id="filter-occupation">
                    <option value="All">All Occupations</option>
                    <option value="Farmer">Farmer / Agriculture</option>
                    <option value="Low Income Household">Low Income Household</option>
                    <option value="Self-Employed">Self-Employed / Vendor</option>
                    <option value="Student">Student</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-state" class="form-label text-white small fw-bold">State / Region</label>
                <select class="form-select form-control-glass text-white" id="filter-state">
                    <option value="All India">All India / Central</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Karnataka">Karnataka</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-glass-primary w-100 mt-4 py-2" id="scheme-match-btn">
            <i class="bi bi-search me-2"></i> Match Eligible Welfare Schemes
        </button>
    </div>

    <!-- SCHEMES RESULTS LIST -->
    <div class="row g-4" id="schemes-grid">
        <?php foreach ($schemes as $sch): ?>
            <div class="col-md-6">
                <div class="glass-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark font-monospace"><?= htmlspecialchars($sch['category']) ?></span>
                            <span class="badge bg-dark border border-secondary text-secondary"><?= htmlspecialchars($sch['state']) ?></span>
                        </div>
                        <h4 class="text-white mb-2"><?= htmlspecialchars($sch['title']) ?></h4>
                        <p class="text-secondary small mb-3"><?= htmlspecialchars($sch['description']) ?></p>
                        
                        <div class="p-3 glass-card bg-dark bg-opacity-50 mb-3">
                            <strong class="text-success small d-block mb-1"><i class="bi bi-gift-fill me-1"></i> Scheme Benefits:</strong>
                            <div class="text-light small"><?= htmlspecialchars($sch['benefits']) ?></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top border-secondary pt-3 mt-2">
                        <span class="text-muted small">Age Limit: <?= $sch['min_age'] ?> - <?= $sch['max_age'] ?> yrs</span>
                        <?php if ($sch['application_url']): ?>
                            <a href="<?= htmlspecialchars($sch['application_url']) ?>" target="_blank" class="btn btn-sm btn-outline-warning">Official Portal &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.getElementById('scheme-match-btn').addEventListener('click', async () => {
    const age = document.getElementById('filter-age').value;
    const income = document.getElementById('filter-income').value;
    const occupation = document.getElementById('filter-occupation').value;
    const state = document.getElementById('filter-state').value;

    try {
        const res = await fetch('/api/schemes/match', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ age, income, occupation, state })
        });
        const data = await res.json();
        if (data.success) {
            renderMatchedSchemes(data.data);
            showToast('Eligible Schemes Matched!', 'success');
        }
    } catch (e) {
        showToast('Error matching schemes.', 'error');
    }
});

function renderMatchedSchemes(list) {
    const container = document.getElementById('schemes-grid');
    container.innerHTML = list.map(item => `
        <div class="col-md-6 animate__animated animate__fadeIn">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-${item.is_eligible ? 'success' : 'secondary'}">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-${item.is_eligible ? 'success' : 'secondary'} fs-6">${item.match_percentage}% Match Score</span>
                        <span class="badge bg-warning text-dark">${item.scheme.category}</span>
                    </div>
                    <h4 class="text-white mb-2">${item.scheme.title}</h4>
                    <p class="text-secondary small mb-2">${item.scheme.description}</p>
                    <div class="text-info small mb-3"><strong>Status:</strong> ${item.remarks}</div>
                    
                    <div class="p-3 glass-card bg-dark bg-opacity-50 mb-3">
                        <strong class="text-warning small d-block mb-1">Required Checklist:</strong>
                        <ul class="mb-0 text-light small ps-3">
                            ${item.required_documents.map(doc => `<li>${doc}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top border-secondary pt-3 mt-2">
                    <span class="text-muted small">Max Income: ₹${Number(item.scheme.max_income).toLocaleString()}</span>
                    <a href="${item.scheme.application_url}" target="_blank" class="btn btn-sm btn-outline-warning">Apply Direct &rarr;</a>
                </div>
            </div>
        </div>
    `).join('');
}
</script>
