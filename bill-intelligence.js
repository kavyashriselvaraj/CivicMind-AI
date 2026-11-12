// CivicMind X - Bill Intelligence & OCR Chart Module
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('bill-upload-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Extracting OCR & Utility Data...`;

        try {
            const res = await fetch('/bills/upload', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                renderBillAnalysis(data.data);
                showToast('Bill OCR Processing Completed!', 'success');
            } else {
                showToast(data.error || 'Failed to process bill.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Error uploading bill.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Run OCR & Analyze Expense`;
        }
    });
});

function renderBillAnalysis(analysis) {
    const container = document.getElementById('bill-results-container');
    if (!container) return;

    container.style.display = 'block';
    container.innerHTML = `
        <div class="glass-panel p-4 mt-4 animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-info mb-1">${analysis.provider_name}</span>
                    <h3 class="text-white mb-0">₹${analysis.total_amount.toLocaleString()}</h3>
                    <div class="text-secondary small">Total Billing Amount (Due: ${analysis.due_date})</div>
                </div>
                <div>
                    ${analysis.usage_anomaly 
                        ? `<span class="badge bg-danger p-2 fs-6"><i class="bi bi-exclamation-octagon-fill me-1"></i> Spike Anomaly (+${analysis.spike_percentage}%)</span>` 
                        : `<span class="badge bg-success p-2 fs-6"><i class="bi bi-check-circle-fill me-1"></i> Normal Usage</span>`}
                </div>
            </div>

            <div class="alert ${analysis.usage_anomaly ? 'alert-danger' : 'alert-success'} bg-opacity-25 border-0 text-white mb-4">
                <strong><i class="bi bi-info-circle-fill me-2"></i> Anomaly Diagnostic:</strong> ${analysis.anomaly_explanation}
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="glass-card p-3 h-100">
                        <h6 class="text-white mb-3">6-Month Consumption Trend Graph</h6>
                        <canvas id="billTrendChart" height="200"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card p-3 h-100">
                        <h6 class="text-white mb-3">AI Cost Optimization Tips</h6>
                        <ul class="list-group list-group-flush bg-transparent">
                            ${analysis.savings_tips.map(tip => `
                                <li class="list-group-item bg-transparent text-light border-secondary">
                                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>${tip}
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.scrollIntoView({ behavior: 'smooth' });

    // Render Chart.js
    if (typeof Chart !== 'undefined' && analysis.trend_data) {
        const ctx = document.getElementById('billTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: analysis.trend_data.map(d => d.month),
                datasets: [{
                    label: 'Bill Amount (₹)',
                    data: analysis.trend_data.map(d => d.amount),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    }
}
