<?php
use CivicMind\Services\TranslationEngine;
$currentLang = $_SESSION['lang'] ?? 'en';
?>

<div class="container py-5">
    <!-- HERO SECTION -->
    <div class="row align-items-center justify-content-center min-vh-75 text-center my-4">
        <div class="col-lg-10">
            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary px-3 py-2 rounded-pill mb-3 animate__animated animate__fadeInDown">
                <i class="bi bi-sparkles me-1"></i> Next-Gen AI Digital Life Operating System
            </span>
            
            <h1 class="display-3 fw-extrabold text-gradient-primary mb-3 animate__animated animate__fadeIn">
                CivicMind X
            </h1>
            
            <p class="lead text-light mb-5 fs-4 col-md-10 mx-auto animate__animated animate__fadeIn animate__delay-1s">
                Solve everyday civic problems, detect scams, discover government schemes, parse utility bills, and track complaints through one unified AI interface.
            </p>

            <!-- INSTANT AI PROBLEM SOLVER HERO BOX -->
            <div class="glass-panel p-4 text-start shadow-lg mb-5 animate__animated animate__fadeInUp animate__delay-1s">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label for="ai-prompt-input" class="form-label text-white fw-bold mb-0">
                        <i class="bi bi-robot text-primary me-2"></i> How can CivicMind AI assist you today?
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" id="voice-input-btn" title="Speak in English/Tamil/Hindi">
                        <i class="bi bi-mic-fill me-1"></i> Voice Input
                    </button>
                </div>

                <div class="prompt-box-wrapper mb-3">
                    <textarea class="prompt-textarea" id="ai-prompt-input" rows="3" placeholder='<?= htmlspecialchars(TranslationEngine::translate('ai_prompt_placeholder', $currentLang)) ?>'></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div class="d-flex flex-wrap gap-2 text-secondary small">
                        <span class="fw-semibold text-light align-self-center">Try asking:</span>
                        <button type="button" class="badge bg-dark border border-secondary text-light cursor-pointer py-2 px-2" onclick="document.getElementById('ai-prompt-input').value='My street light pole #42 is not working for 3 days';">"Streetlight dark"</button>
                        <button type="button" class="badge bg-dark border border-secondary text-light cursor-pointer py-2 px-2" onclick="document.getElementById('ai-prompt-input').value='Received SMS asking to update electricity e-KYC or power will be cut tonight';">"Suspicious SMS alert"</button>
                        <button type="button" class="badge bg-dark border border-secondary text-light cursor-pointer py-2 px-2" onclick="document.getElementById('ai-prompt-input').value='What documents are required for Tatkaal Passport renewal?';">"Passport procedure"</button>
                    </div>
                    <button class="btn btn-glass-primary px-4 py-2" type="button" id="ai-analyze-submit-btn">
                        <i class="bi bi-cpu-fill me-2"></i> Analyze & Draft
                    </button>
                </div>
            </div>

            <!-- AI ANALYSIS RESULTS OUTPUT CONTAINER -->
            <div id="ai-results-container" style="display: none;"></div>
        </div>
    </div>

    <!-- LIVE METRICS COUNTER BAR -->
    <div class="row g-4 my-5">
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-4">
                <i class="bi bi-people-fill text-primary fs-1 mb-2"></i>
                <div class="display-6 fw-bold text-white">1,420+</div>
                <div class="text-secondary small fw-bold">Active Citizens</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-4">
                <i class="bi bi-check-circle-fill text-success fs-1 mb-2"></i>
                <div class="display-6 fw-bold text-white">92.4%</div>
                <div class="text-secondary small fw-bold">Resolution Rate</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-4">
                <i class="bi bi-shield-check text-danger fs-1 mb-2"></i>
                <div class="display-6 fw-bold text-white">189</div>
                <div class="text-secondary small fw-bold">Scams Intercepted</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="glass-card text-center py-4">
                <i class="bi bi-award-fill text-warning fs-1 mb-2"></i>
                <div class="display-6 fw-bold text-white">₹14.2M</div>
                <div class="text-secondary small fw-bold">Scheme Benefits Unlocked</div>
            </div>
        </div>
    </div>

    <!-- 12 CORE MODULE CARDS GRID -->
    <div class="my-5">
        <h2 class="text-center text-white mb-2">12 Production Modules Engine</h2>
        <p class="text-center text-secondary mb-5 col-md-6 mx-auto">Complete ecosystem connecting citizens, municipal departments, and service professionals.</p>

        <div class="row g-4">
            <!-- Module 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-primary bg-opacity-25 text-primary">
                            <i class="bi bi-shield-lock-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">1. Authentication & RBAC</h5>
                            <small class="text-secondary">JWT + OTP + Argon2id</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Role-based access control for Citizens, Service Providers, Municipal Officers, Moderators, and System Admins.</p>
                    <a href="/login" class="btn btn-sm btn-outline-primary rounded-pill">Manage Profile &rarr;</a>
                </div>
            </div>

            <!-- Module 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-info bg-opacity-25 text-info">
                            <i class="bi bi-cpu-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">2. AI Problem Solver</h5>
                            <small class="text-secondary">NLP & Intent Classification</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Converts plain language problems into structured civic complaints with auto-assigned departments and SLA deadlines.</p>
                    <a href="/ai-solver" class="btn btn-sm btn-outline-info rounded-pill">Launch AI Solver &rarr;</a>
                </div>
            </div>

            <!-- Module 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-primary bg-opacity-25 text-primary">
                            <i class="bi bi-file-earmark-check-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">3. Civic Complaint Pipeline</h5>
                            <small class="text-secondary">GPS Tagging & Photo Upload</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">End-to-end complaint lifecycle (Submitted &rarr; Under Review &rarr; Assigned &rarr; In Progress &rarr; Resolved).</p>
                    <a href="/complaints" class="btn btn-sm btn-outline-primary rounded-pill">Track Complaints &rarr;</a>
                </div>
            </div>

            <!-- Module 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-warning bg-opacity-25 text-warning">
                            <i class="bi bi-bank2 fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">4. Scheme Discovery AI</h5>
                            <small class="text-secondary">Demographic Filter Engine</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Matches age, occupation, and income parameters against national & state welfare schemes instantly.</p>
                    <a href="/schemes" class="btn btn-sm btn-outline-warning rounded-pill">Discover Schemes &rarr;</a>
                </div>
            </div>

            <!-- Module 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-success bg-opacity-25 text-success">
                            <i class="bi bi-journal-check fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">5. Document Assistant</h5>
                            <small class="text-secondary">Passport, DL & Aadhaar Procedures</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Step-by-step guides, required documents checklist, fee breakdown, and common rejection pitfalls.</p>
                    <a href="/documents" class="btn btn-sm btn-outline-success rounded-pill">View Document Guides &rarr;</a>
                </div>
            </div>

            <!-- Module 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-danger bg-opacity-25 text-danger">
                            <i class="bi bi-shield-exclamation fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">6. Scam Detection AI</h5>
                            <small class="text-secondary">Multi-Vector Threat Scanner</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Analyzes suspicious SMS, phishing emails, WhatsApp texts, and URLs to generate threat risk scores (0-100%).</p>
                    <a href="/scams" class="btn btn-sm btn-outline-danger rounded-pill">Scan Threat &rarr;</a>
                </div>
            </div>

            <!-- Module 7 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-info bg-opacity-25 text-info">
                            <i class="bi bi-receipt-cutoff fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">7. Bill Intelligence & OCR</h5>
                            <small class="text-secondary">Usage Anomaly Detection</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Parses electricity/water bills, flags unexpected consumption spikes (>35%), and plots cost trends.</p>
                    <a href="/bills" class="btn btn-sm btn-outline-info rounded-pill">Analyze Utility Bills &rarr;</a>
                </div>
            </div>

            <!-- Module 8 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-primary bg-opacity-25 text-primary">
                            <i class="bi bi-tools fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">8. Trusted Service Providers</h5>
                            <small class="text-secondary">Verified Local Network</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Book rated electricians, plumbers, mechanics, carpenters, and tutors with verified background checks.</p>
                    <a href="/providers" class="btn btn-sm btn-outline-primary rounded-pill">Find Professionals &rarr;</a>
                </div>
            </div>

            <!-- Module 9 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-indigo bg-opacity-25 text-indigo">
                            <i class="bi bi-map-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">9. GIS Community Heatmap</h5>
                            <small class="text-secondary">Spatial Marker Clustering</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Interactive Leaflet.js map displaying live civic complaints, garbage hotspots, road damage, and water leaks.</p>
                    <a href="/heatmaps" class="btn btn-sm btn-outline-indigo rounded-pill" style="color: #6366f1; border-color: #6366f1;">Open GIS Map &rarr;</a>
                </div>
            </div>

            <!-- Module 10 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-warning bg-opacity-25 text-warning">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">10. Predictive Intelligence</h5>
                            <small class="text-secondary">Issue Hotspot Forecasting</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">AI models predict future high-risk civic zones and recommend optimal municipal resource allocation.</p>
                    <a href="/analytics" class="btn btn-sm btn-outline-warning rounded-pill">View Forecasts &rarr;</a>
                </div>
            </div>

            <!-- Module 11 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-success bg-opacity-25 text-success">
                            <i class="bi bi-translate fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">11. Multilingual & Voice</h5>
                            <small class="text-secondary">English, தமிழ் & हिन्दी</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Full translation dictionary matrix and Web Speech API hands-free voice input and read-aloud voice synthesis.</p>
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill" onclick="speakText('CivicMind X supports voice speech in English, Tamil, and Hindi.')">Test Speech Output &rarr;</button>
                </div>
            </div>

            <!-- Module 12 -->
            <div class="col-lg-4 col-md-6">
                <div class="glass-card h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle bg-danger bg-opacity-25 text-danger">
                            <i class="bi bi-speedometer2 fs-3"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-0">12. Executive Analytics</h5>
                            <small class="text-secondary">Metrics Dashboard & PDF Export</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3">Administrative metrics, Chart.js breakdown, resolution velocity gauges, and official PDF report generation.</p>
                    <a href="/analytics" class="btn btn-sm btn-outline-danger rounded-pill">Executive Dashboard &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
