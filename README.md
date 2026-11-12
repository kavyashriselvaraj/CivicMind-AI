# CivicMind X – AI Digital Life Operating System

> **CivicMind X** is a unified AI-powered platform designed to empower citizens, service providers, municipal officers, content moderators, and system administrators through intelligent automation, fraud detection, scheme matching, utility bill intelligence, and GIS community heatmaps.

---

## Key Features & 12 Production Modules

1. **Module 1: Authentication & RBAC System**:
   - Multi-role support (Citizen, Service Provider, Gov Officer, Moderator, Administrator)
   - JWT token auth + Session management + 6-digit OTP verification + Argon2id/Bcrypt hashing

2. **Module 2: AI Problem Solver & Assistant**:
   - NLP engine analyzing plain language issues (e.g. *"My streetlight is not working"*)
   - Intent recognition, category mapping, severity calculation (1-5), authority routing, and auto-drafting

3. **Module 3: Civic Complaint Management**:
   - Photo upload dropzone + GPS tagging + SLA deadline tracking
   - Status pipeline (`Submitted` -> `Under Review` -> `Assigned` -> `In Progress` -> `Resolved` -> `Closed`)

4. **Module 4: Government Scheme Discovery**:
   - Demographic filter engine (Age, Income Range, Occupation, State, Gender)
   - Instant eligibility matching, benefit summaries, and required document checklists

5. **Module 5: Document Assistant**:
   - Step-by-step procedures for Passport, Driving License renewal, Aadhaar updates, Ration Cards
   - Processing timelines, fee structures, and rejection pitfalls to avoid

6. **Module 6: Scam & Fraud Detection AI**:
   - Threat scanner for SMS text, phishing emails, WhatsApp forwards, and URLs
   - Risk scoring algorithm (0-100%), scam categorization, and immediate safety action guidance

7. **Module 7: Utility Bill Intelligence & OCR**:
   - Parsers for electricity, water, and internet bills
   - Consumption anomaly detection (>35% spike warnings) + Chart.js cost trend graphs

8. **Module 8: Trusted Local Service Provider Network**:
   - Verified local marketplace for Electricians, Plumbers, Tutors, Mechanics, and Carpenters
   - Rating stars, background check badges, direct appointment booking modal

9. **Module 9: Community GIS Heatmap**:
   - Leaflet.js geospatial heatmap visualizer for garbage, road potholes, water leaks, and dark streetlights
   - Category filtering, intensity radius, and spatial marker clustering

10. **Module 10: Predictive Civic Intelligence**:
    - AI data models predicting future municipal issue hotspots and high-risk zones
    - Recommended resource allocation guide (asphalt tonnage, sanitation sweepers, electrical hours)

11. **Module 11: Multilingual & Accessibility Engine**:
    - Multi-language dictionary matrix supporting English, Tamil (தமிழ்), and Hindi (हिन्दी)
    - Web Speech API integration for hands-free voice input and speech synthesis read-aloud output

12. **Module 12: Real-time Analytics & Executive Dashboard**:
    - Administrative metrics widgets, Chart.js ticket distribution breakdown, SLA velocity gauges
    - Automated PDF report export engine

---

## Technology Stack

- **Backend Architecture**: PHP 8.3 OOP MVC, Custom Lightweight Router, REST API Controller Stack, Middleware Pipeline (Auth, RBAC, CSRF)
- **Database Engine**: MySQL 8.0+ / MariaDB 10.5+ (with seamless auto-bootstrapping SQLite fallback for standalone local execution)
- **Frontend Stack**: HTML5, CSS3, Bootstrap 5, HSL Glassmorphic Design System, JavaScript ES6, Three.js 3D Canvas Background, GSAP, Leaflet.js, Chart.js, Web Speech API

---

## Quick Start Guide

### Running Locally with PHP Built-in Web Server

```bash
# 1. Clone or navigate to the project directory
cd "c:\Users\Admin\OneDrive\Desktop\civic ai"

# 2. Start local dev server
php -S localhost:8000 -t public public/router.php
```
Open **`http://localhost:8000`** in your browser.

---

### Running Automated Test Suite

```bash
php tests/run_tests.php
```

---

## System Architecture & Documentation

- [System Architecture & Diagrams](docs/ARCHITECTURE.md)
- [API Documentation](docs/API.md)
- [Production Deployment Guide](docs/DEPLOYMENT.md)
