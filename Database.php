<?php

namespace CivicMind\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    private static string $activeDriver = 'mysql';

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            
            try {
                // Try MySQL First
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                self::$activeDriver = 'mysql';
            } catch (PDOException $e) {
                // Fallback to SQLite if MySQL fails or is not running
                try {
                    $sqlitePath = $config['sqlite_fallback_path'];
                    $dbDir = dirname($sqlitePath);
                    if (!is_dir($dbDir)) {
                        mkdir($dbDir, 0777, true);
                    }
                    self::$instance = new PDO("sqlite:" . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    self::$activeDriver = 'sqlite';
                    self::bootstrapSqlite(self::$instance);
                } catch (PDOException $ex) {
                    die("Database Connection Error: " . $ex->getMessage());
                }
            }
        }
        return self::$instance;
    }

    public static function getActiveDriver(): string {
        return self::$activeDriver;
    }

    private static function bootstrapSqlite(PDO $pdo): void {
        // Create tables if using SQLite fallback
        $queries = [
            "CREATE TABLE IF NOT EXISTS roles (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT UNIQUE, description TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS permissions (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT UNIQUE, guard_name TEXT DEFAULT 'web', created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS role_permissions (role_id INTEGER, permission_id INTEGER, PRIMARY KEY (role_id, permission_id));",
            "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, full_name TEXT, email TEXT UNIQUE, phone TEXT, password_hash TEXT, role_id INTEGER DEFAULT 1, status TEXT DEFAULT 'active', preferred_language TEXT DEFAULT 'en', avatar TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS departments (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, code TEXT UNIQUE, contact_email TEXT, head_name TEXT, phone TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS complaints (id INTEGER PRIMARY KEY AUTOINCREMENT, complaint_number TEXT UNIQUE, user_id INTEGER, category TEXT, severity INTEGER DEFAULT 3, title TEXT, description TEXT, address TEXT, latitude REAL, longitude REAL, department_id INTEGER, status TEXT DEFAULT 'Submitted', assigned_to INTEGER, ai_generated INTEGER DEFAULT 0, sla_deadline DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS complaint_images (id INTEGER PRIMARY KEY AUTOINCREMENT, complaint_id INTEGER, file_path TEXT, file_type TEXT, uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS complaint_status_history (id INTEGER PRIMARY KEY AUTOINCREMENT, complaint_id INTEGER, old_status TEXT, new_status TEXT, changed_by INTEGER, comments TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS schemes (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, category TEXT, description TEXT, state TEXT DEFAULT 'All India', min_age INTEGER DEFAULT 0, max_age INTEGER DEFAULT 120, min_income REAL DEFAULT 0.0, max_income REAL DEFAULT 99999999.0, target_occupation TEXT DEFAULT 'All', gender TEXT DEFAULT 'All', benefits TEXT, application_url TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS eligibility_rules (id INTEGER PRIMARY KEY AUTOINCREMENT, scheme_id INTEGER, rule_key TEXT, operator TEXT, rule_value TEXT);",
            "CREATE TABLE IF NOT EXISTS documents (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, category TEXT, required_docs TEXT, procedure_steps TEXT, processing_time_days INTEGER DEFAULT 14, fees TEXT, common_mistakes TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS bill_uploads (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, bill_type TEXT, file_path TEXT, upload_date DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS bill_analysis (id INTEGER PRIMARY KEY AUTOINCREMENT, bill_id INTEGER, provider_name TEXT, billing_date DATE, due_date DATE, total_amount REAL, units_consumed REAL, usage_anomaly INTEGER DEFAULT 0, parsed_json TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS scam_reports (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, input_type TEXT, content TEXT, risk_score INTEGER, category TEXT, warning_explanation TEXT, recommended_action TEXT, analyzed_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS service_providers (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, business_name TEXT, service_category TEXT, hourly_rate REAL DEFAULT 0.0, experience_years INTEGER DEFAULT 1, is_verified INTEGER DEFAULT 0, rating REAL DEFAULT 5.0, total_reviews INTEGER DEFAULT 0, bio TEXT, city TEXT DEFAULT 'Chennai', created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS bookings (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, provider_id INTEGER, service_description TEXT, scheduled_date DATETIME, status TEXT DEFAULT 'Pending', total_price REAL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS reviews (id INTEGER PRIMARY KEY AUTOINCREMENT, booking_id INTEGER, user_id INTEGER, provider_id INTEGER, rating INTEGER, comment TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS notifications (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, title TEXT, message TEXT, type TEXT DEFAULT 'info', is_read INTEGER DEFAULT 0, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS heatmaps (id INTEGER PRIMARY KEY AUTOINCREMENT, latitude REAL, longitude REAL, intensity REAL DEFAULT 1.0, category TEXT, complaint_id INTEGER, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS analytics (id INTEGER PRIMARY KEY AUTOINCREMENT, metric_name TEXT, metric_value REAL, recorded_date DATE, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS audit_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, action TEXT, entity TEXT, entity_id INTEGER, ip_address TEXT, user_agent TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            "CREATE TABLE IF NOT EXISTS ai_conversations (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, prompt TEXT, response TEXT, intent TEXT, sentiment TEXT DEFAULT 'neutral', created_at DATETIME DEFAULT CURRENT_TIMESTAMP);"
        ];

        foreach ($queries as $q) {
            $pdo->exec($q);
        }

        // Check if seed data exists
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM roles");
        $row = $stmt->fetch();
        if ((int)$row['cnt'] === 0) {
            self::seedSqlite($pdo);
        }
    }

    private static function seedSqlite(PDO $pdo): void {
        $pdo->exec("INSERT INTO roles (id, name, description) VALUES (1, 'Citizen', 'Citizen role'), (2, 'Service Provider', 'Provider role'), (3, 'Government Officer', 'Gov Officer role'), (4, 'Moderator', 'Moderator role'), (5, 'Administrator', 'Admin role');");
        
        $pdo->exec("INSERT INTO departments (id, name, code, contact_email, head_name, phone) VALUES 
            (1, 'Electrical & Lighting Board', 'DEPT_ELEC', 'elec@civicmind.gov', 'Rajesh Sharma', '+91 44 2888 1001'),
            (2, 'Sanitation & Solid Waste Management', 'DEPT_SANI', 'clean@civicmind.gov', 'Priya Sundaram', '+91 44 2888 1002'),
            (3, 'Roads, Bridges & Infrastructure', 'DEPT_ROAD', 'infra@civicmind.gov', 'Vikram Malhotra', '+91 44 2888 1003'),
            (4, 'Water Supply & Sewerage Board', 'DEPT_WATR', 'water@civicmind.gov', 'Ananya Roy', '+91 44 2888 1004'),
            (5, 'Public Safety & Emergency Support', 'DEPT_SAFE', 'safety@civicmind.gov', 'Karan Verma', '+91 44 2888 1005');");

        $hash = password_hash('password123', PASSWORD_BCRYPT);
        $pdo->exec("INSERT INTO users (id, full_name, email, phone, password_hash, role_id, status) VALUES 
            (1, 'Arun Kumar', 'citizen@civicmind.org', '+91 98765 43210', '{$hash}', 1, 'active'),
            (2, 'Suresh Electricals', 'provider@civicmind.org', '+91 98765 43211', '{$hash}', 2, 'active'),
            (3, 'Officer Rajesh', 'officer@civicmind.gov', '+91 98765 43212', '{$hash}', 3, 'active'),
            (4, 'Sarah Moderator', 'moderator@civicmind.org', '+91 98765 43213', '{$hash}', 4, 'active'),
            (5, 'Super Admin', 'admin@civicmind.org', '+91 98765 43214', '{$hash}', 5, 'active');");

        $pdo->exec("INSERT INTO service_providers (id, user_id, business_name, service_category, hourly_rate, experience_years, is_verified, rating, total_reviews, bio, city) VALUES
            (1, 2, 'Suresh Quick Electrical Services', 'Electrician', 450.00, 8, 1, 4.90, 128, 'Certified licensed electrician specializing in home wiring and fuse box repairs.', 'Chennai');");

        $pdo->exec("INSERT INTO complaints (id, complaint_number, user_id, category, severity, title, description, address, latitude, longitude, department_id, status, assigned_to, ai_generated) VALUES
            (1, 'CMP-2026-001', 1, 'Electrical', 4, 'Streetlight Non-Functional near Anna Nagar', 'Main streetlight dark for 3 days.', '12th Main Road, Anna Nagar, Chennai', 13.08500000, 80.21000000, 1, 'In Progress', 3, 1),
            (2, 'CMP-2026-002', 1, 'Roads', 5, 'Dangerous Deep Pothole on Highway', 'Severe road damage causing traffic slowdowns.', 'GST Road, Guindy, Chennai', 13.00670000, 80.20200000, 3, 'Assigned', 3, 0);");

        $pdo->exec("INSERT INTO schemes (id, title, category, description, state, min_age, max_age, min_income, max_income, target_occupation, gender, benefits, application_url) VALUES
            (1, 'PM-Kisan Samman Nidhi', 'Agriculture', 'Income support scheme providing ₹6,000 per year to farmers.', 'All India', 18, 75, 0.0, 300000.0, 'Farmer', 'All', 'Direct benefit transfer of ₹6,000 per annum.', 'https://pmkisan.gov.in'),
            (2, 'Makkalai Thedi Maruthuvam', 'Healthcare', 'Doorstep healthcare service delivering essential medicines.', 'Tamil Nadu', 0, 100, 0.0, 500000.0, 'All', 'All', 'Free doorstep medicine delivery.', 'https://tnhealth.tn.gov.in');");

        $pdo->exec("INSERT INTO documents (id, title, category, required_docs, procedure_steps, processing_time_days, fees, common_mistakes) VALUES
            (1, 'Passport Application Guide', 'Identity & Travel', '1. Proof of Address\n2. Date of Birth proof', '1. Register online\n2. Book appointment\n3. Visit PSK', 14, '₹1,500', '1. Name mismatch in documents'),
            (2, 'Driving License Renewal', 'Transport', '1. Original DL\n2. Form 1A', '1. Visit Parivahan\n2. Pay fee', 7, '₹400', '1. Expired grace period');");

        $pdo->exec("INSERT INTO heatmaps (id, latitude, longitude, intensity, category, complaint_id) VALUES
            (1, 13.08500000, 80.21000000, 0.9, 'Electrical', 1),
            (2, 13.00670000, 80.20200000, 1.0, 'Roads', 2);");
    }
}
