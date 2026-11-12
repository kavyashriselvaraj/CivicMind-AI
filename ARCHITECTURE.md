# CivicMind X - System Architecture Documentation

CivicMind X is designed around a lightweight, modular, Object-Oriented PHP 8.3 MVC framework. It decouples core business logic into domain services and provides clean RESTful API endpoints alongside server-rendered glassmorphic frontend views.

---

## 1. High-Level Architecture Diagram

```mermaid
graph TD
    User([Citizen / User]) -->|HTTPS / REST API| Router[Front Controller & Router]
    Provider([Service Provider]) -->|HTTPS / REST API| Router
    Officer([Municipal Officer]) -->|HTTPS / REST API| Router
    Admin([System Admin]) -->|HTTPS / REST API| Router

    subgraph Core Framework Layer
        Router --> Middleware[Middleware Pipeline (Auth, RBAC, CSRF)]
        Middleware --> Controllers[MVC Controllers Layer]
    end

    subgraph AI Engine & Business Services
        Controllers --> AiNLP[AI NLP Engine]
        Controllers --> ScamEngine[Scam Risk Scoring Engine]
        Controllers --> BillOCR[Bill OCR & Anomaly Engine]
        Controllers --> SchemeEngine[Scheme Eligibility Matcher]
        Controllers --> PredictiveAI[Predictive Civic Intelligence]
        Controllers --> TransEngine[Multilingual Engine]
        Controllers --> PdfEngine[PDF Report Generator]
    end

    subgraph Data & Storage Layer
        Controllers --> Models[Data Models PDO]
        Models --> MySQL[(MySQL 8 Database / SQLite Fallback)]
        Controllers --> Filesystem[(File Storage - Uploads & PDF)]
    end
```

---

## 2. Component Diagram

```mermaid
classDiagram
    class Router {
        +get(path, handler)
        +post(path, handler)
        +dispatch(method, uri)
    }

    class Database {
        +getInstance(): PDO
        +getActiveDriver(): string
    }

    class AiNlpEngine {
        +analyzeInput(userText, location): array
    }

    class ScamScoringEngine {
        +evaluate(content, type): array
    }

    class BillOcrEngine {
        +parse(filePath, billType): array
    }

    class SchemeMatcherEngine {
        +findEligibleSchemes(criteria): array
    }

    class PredictiveAiEngine {
        +generateForecasts(): array
    }

    Router --> Database
    AiNlpEngine --> Database
    SchemeMatcherEngine --> Database
    PredictiveAiEngine --> Database
```

---

## 3. Entity-Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ COMPLAINTS : "reports"
    USERS ||--o{ BOOKINGS : "places"
    USERS ||--o{ SCAM_REPORTS : "submits"
    USERS ||--o{ BILL_UPLOADS : "uploads"
    DEPARTMENTS ||--o{ COMPLAINTS : "handles"
    COMPLAINTS ||--o{ COMPLAINT_IMAGES : "contains"
    COMPLAINTS ||--o{ COMPLAINT_STATUS_HISTORY : "tracks"
    SERVICE_PROVIDERS ||--o{ BOOKINGS : "receives"
    BOOKINGS ||--o{ REVIEWS : "generates"
    SCHEMES ||--o{ ELIGIBILITY_RULES : "defines"
    BILL_UPLOADS ||--|| BILL_ANALYSIS : "analyzes"

    USERS {
        int id PK
        string full_name
        string email
        string password_hash
        int role_id FK
        string status
    }

    COMPLAINTS {
        int id PK
        string complaint_number
        int user_id FK
        string category
        int severity
        string title
        text description
        string status
        int department_id FK
    }

    SERVICE_PROVIDERS {
        int id PK
        int user_id FK
        string business_name
        string service_category
        decimal hourly_rate
        decimal rating
    }

    SCHEMES {
        int id PK
        string title
        string category
        int min_age
        int max_age
        decimal max_income
    }
```

---

## 4. Sequence Diagram: AI Complaint Auto-Drafting Workflow

```mermaid
sequenceDiagram
    autonumber
    actor Citizen
    participant View as Glassmorphic View UI
    participant Controller as AiController
    participant Engine as AiNlpEngine
    participant Database as MySQL DB

    Citizen->>View: Enters "Street light #42 dark for 3 days"
    View->>Controller: POST /api/ai/analyze {prompt}
    Controller->>Engine: analyzeInput(prompt)
    Engine->>Engine: Run NLP keyword match & severity calculation
    Engine-->>Controller: Returns category, severity, department, draft
    Controller->>Database: Log conversation into ai_conversations
    Controller-->>View: JSON Response with analysis & draft
    View-->>Citizen: Renders formatted draft with "Submit Complaint" button
```

---

## 5. Deployment Architecture Diagram

```mermaid
graph LR
    ClientBrowser[Web Browser / Mobile App] -->|HTTPS Port 443/8000| Nginx[Nginx Reverse Proxy / Port 80]
    Nginx -->|FastCGI| PHPApp[PHP 8.3 Apache Container]
    PHPApp -->|PDO TCP 3306| MySQLDB[(MySQL 8 Database Container)]
```
