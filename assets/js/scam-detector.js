// CivicMind X - Scam & Fraud Detector Module
document.addEventListener('DOMContentLoaded', () => {
    const scanBtn = document.getElementById('scam-scan-btn');
    const inputContent = document.getElementById('scam-input-text');
    const resultsCard = document.getElementById('scam-results-card');

    if (!scanBtn || !inputContent) return;

    scanBtn.addEventListener('click', async () => {
        const text = inputContent.value.trim();
        if (!text) {
            showToast('Please enter text, SMS, or email content to scan.', 'error');
            return;
        }

        scanBtn.disabled = true;
        scanBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Scanning Threat Vectors...`;

        try {
            const inputType = document.querySelector('input[name="scam_type"]:checked')?.value || 'text';
            const res = await fetch('/api/scams/analyze', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ content: text, input_type: inputType })
            });
            const data = await res.json();

            if (data.success) {
                renderScamResults(data.data);
                showToast('Threat Assessment Completed!', 'success');
            } else {
                showToast(data.error || 'Scam scan failed.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Connection error during scam evaluation.', 'error');
        } finally {
            scanBtn.disabled = false;
            scanBtn.innerHTML = `<i class="bi bi-shield-lock-fill me-2"></i> Scan for Fraud & Cyber Risks`;
        }
    });
});

function renderScamResults(res) {
    const card = document.getElementById('scam-results-card');
    if (!card) return;

    card.style.display = 'block';
    const gaugeColor = res.risk_score >= 75 ? '#f43f5e' : (res.risk_score >= 50 ? '#f59e0b' : '#10b981');

    card.innerHTML = `
        <div class="glass-panel p-4 mt-4 animate__animated animate__zoomIn">
            <div class="row align-items-center g-4">
                <div class="col-md-4 text-center border-end border-secondary">
                    <div class="position-relative d-inline-block">
                        <div class="display-3 fw-bold" style="color: ${gaugeColor}">${res.risk_score}%</div>
                        <div class="text-secondary small fw-bold uppercase">Threat Risk Score</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <span class="badge mb-2 fs-6" style="background: ${gaugeColor}">${res.category}</span>
                    <h4 class="text-white mb-2">Security Evaluation Summary</h4>
                    <p class="text-light leading-relaxed mb-3">${res.warning_explanation}</p>
                    
                    ${res.flagged_indicators && res.flagged_indicators.length > 0 ? `
                        <div class="mb-3">
                            <strong class="text-warning small d-block mb-1">Flagged Indicators:</strong>
                            ${res.flagged_indicators.map(ind => `<span class="badge bg-dark border border-secondary text-warning me-1 mb-1">${ind}</span>`).join('')}
                        </div>
                    ` : ''}

                    <div class="p-3 glass-card bg-dark border-danger">
                        <h6 class="text-danger fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Recommended Action:</h6>
                        <pre class="text-light small mb-0 font-monospace" style="white-space: pre-wrap;">${res.recommended_action}</pre>
                    </div>
                </div>
            </div>
        </div>
    `;
    card.scrollIntoView({ behavior: 'smooth' });
}
