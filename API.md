# CivicMind X - RESTful API Documentation

All API responses are formatted in JSON with consistent status wrappers.

---

## 1. Authentication Endpoints

### POST `/api/auth/login`
- **Request Body**:
  ```json
  {
    "email": "citizen@civicmind.org",
    "password": "password123"
  }
  ```
- **Success Response (200 OK)**:
  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "id": 1,
      "full_name": "Arun Kumar",
      "email": "citizen@civicmind.org",
      "role_id": 1,
      "role_name": "Citizen",
      "jwt_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
    }
  }
  ```

---

## 2. AI Engine Endpoints

### POST `/api/ai/analyze`
- **Request Body**:
  ```json
  {
    "prompt": "Streetlight pole #42 on Anna Nagar 2nd Avenue is dark",
    "location": "Anna Nagar"
  }
  ```
- **Success Response (200 OK)**:
  ```json
  {
    "success": true,
    "data": {
      "intent": "report_civic_issue",
      "category": "Electrical",
      "severity": 4,
      "department_id": 1,
      "department_name": "Electrical & Lighting Board",
      "sla_days": 3,
      "title": "Electrical Issue: Streetlight pole #42...",
      "draft": "OFFICIAL CIVIC REPORT DRAFT..."
    }
  }
  ```

---

## 3. Scam Detection AI

### POST `/api/scams/analyze`
- **Request Body**:
  ```json
  {
    "content": "URGENT: Electricity disconnected tonight. Call Officer Sharma 9876543210 to update KYC.",
    "input_type": "sms"
  }
  ```
- **Success Response (200 OK)**:
  ```json
  {
    "success": true,
    "data": {
      "risk_score": 95,
      "category": "High-Risk Cyber Fraud / Phishing",
      "warning_explanation": "CRITICAL THREAT: Artificial urgency...",
      "flagged_indicators": ["Financial Urgency", "Phishing Links"],
      "recommended_action": "DO NOT click links..."
    }
  }
  ```

---

## 4. Government Scheme Discovery

### POST `/api/schemes/match`
- **Request Body**:
  ```json
  {
    "age": 35,
    "income": 120000,
    "occupation": "Farmer",
    "state": "All India"
  }
  ```

---

## 5. GIS Heatmap API

### GET `/api/heatmaps/geojson?category=Electrical`
- **Returns**: GeoJSON FeatureCollection of complaint hotspots.
