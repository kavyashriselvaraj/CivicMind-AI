// CivicMind X - AI Solver Interactive Module

document.addEventListener('DOMContentLoaded', () => {
    const analyzeBtn = document.getElementById('ai-analyze-submit-btn');
    const promptInput = document.getElementById('ai-prompt-input');
    const resultsContainer = document.getElementById('ai-results-container');

    if (!analyzeBtn || !promptInput) return;

    analyzeBtn.addEventListener('click', async () => {
        const text = promptInput.value.trim();
        if (!text) {
            showToast('Please describe your civic issue first.', 'error');
            return;
        }

        analyzeBtn.disabled = true;
        analyzeBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Analyzing with Civic AI...`;

        try {
            const res = await fetch('/api/ai/analyze', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ prompt: text, location: document.getElementById('ai-location-input')?.value || '' })
            });
            const data = await res.json();

            if (data.success) {
                renderAiResults(data.data);
                showToast('AI Analysis Complete!', 'success');
            } else {
                showToast(data.error || 'Failed to analyze request.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while connecting to AI Engine.', 'error');
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = `<i class="bi bi-cpu-fill me-2"></i> Analyze & Draft Complaint`;
        }
    });
});

function renderAiResults(analysis) {
    const container = document.getElementById('ai-results-container');
    if (!container) return;

    container.style.display = 'block';
    container.innerHTML = `
        <div class="glass-panel p-4 mt-4 animate__animated animate__fadeInUp">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-3">
                <div>
                    <span class="badge bg-primary fs-6 mb-1">${analysis.category}</span>
                    <h4 class="text-white mb-0">${analysis.title}</h4>
                </div>
                <div class="text-end">
                    <div class="text-secondary small">Severity Score</div>
                    <div class="fs-4 fw-bold text-warning">${analysis.severity} / 5</div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 glass-card">
                        <div class="text-secondary small">Responsible Department</div>
                        <div class="fw-bold text-info fs-6 mt-1">${analysis.department_name}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 glass-card">
                        <div class="text-secondary small">Estimated SLA Resolution</div>
                        <div class="fw-bold text-success fs-6 mt-1">Within ${analysis.sla_days} Working Days</div>
                    </div>
                </div>
            </div>

            <h5 class="text-white mb-2"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i> AI-Generated Official Complaint Draft</h5>
            <pre class="p-3 glass-card text-light font-monospace small mb-4" style="white-space: pre-wrap;">${analysis.draft}</pre>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-outline-info rounded-pill btn-sm" onclick="speakText('${analysis.title}. Department: ${analysis.department_name}')">
                    <i class="bi bi-volume-up-fill me-1"></i> Read Aloud
                </button>
                <form action="/complaints" method="POST" class="d-inline">
                    <input type="hidden" name="title" value="${analysis.title}">
                    <input type="hidden" name="category" value="${analysis.category}">
                    <input type="hidden" name="severity" value="${analysis.severity}">
                    <input type="hidden" name="department_id" value="${analysis.department_id}">
                    <input type="hidden" name="description" value="${analysis.draft}">
                    <button type="submit" class="btn btn-glass-primary">
                        <i class="bi bi-send-check-fill me-2"></i> Submit Official Complaint
                    </button>
                </form>
            </div>
        </div>
    `;
    container.scrollIntoView({ behavior: 'smooth' });
}
