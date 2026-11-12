-- CivicMind X - Complete Production Database Schema
-- Compatible with MySQL 8.0+ / MariaDB 10.5+

CREATE DATABASE IF NOT EXISTS `civicmind_x` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `civicmind_x`;

-- 1. ROLES TABLE
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. PERMISSIONS TABLE
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `guard_name` VARCHAR(50) DEFAULT 'web',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. ROLE PERMISSIONS PIVOT TABLE
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. USERS TABLE
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL DEFAULT 1,
    `status` ENUM('active', 'pending', 'suspended') DEFAULT 'active',
    `preferred_language` VARCHAR(10) DEFAULT 'en',
    `avatar` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. DEPARTMENTS TABLE
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `contact_email` VARCHAR(150) NULL,
    `head_name` VARCHAR(100) NULL,
    `phone` VARCHAR(20) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. COMPLAINTS TABLE
CREATE TABLE IF NOT EXISTS `complaints` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `complaint_number` VARCHAR(30) NOT NULL UNIQUE,
    `user_id` INT NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `severity` INT DEFAULT 3,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NOT NULL,
    `address` TEXT NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    `department_id` INT NULL,
    `status` ENUM('Submitted', 'Under Review', 'Assigned', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Submitted',
    `assigned_to` INT NULL,
    `ai_generated` TINYINT(1) DEFAULT 0,
    `sla_deadline` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. COMPLAINT IMAGES TABLE
CREATE TABLE IF NOT EXISTS `complaint_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `complaint_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_type` VARCHAR(50) DEFAULT 'image/jpeg',
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`complaint_id`) REFERENCES `complaints`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. COMPLAINT STATUS HISTORY TABLE
CREATE TABLE IF NOT EXISTS `complaint_status_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `complaint_id` INT NOT NULL,
    `old_status` VARCHAR(50) NOT NULL,
    `new_status` VARCHAR(50) NOT NULL,
    `changed_by` INT NOT NULL,
    `comments` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`complaint_id`) REFERENCES `complaints`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. SCHEMES TABLE
CREATE TABLE IF NOT EXISTS `schemes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `state` VARCHAR(100) DEFAULT 'All India',
    `min_age` INT DEFAULT 0,
    `max_age` INT DEFAULT 120,
    `min_income` DECIMAL(12,2) DEFAULT 0.00,
    `max_income` DECIMAL(12,2) DEFAULT 99999999.00,
    `target_occupation` VARCHAR(100) DEFAULT 'All',
    `gender` ENUM('All', 'Male', 'Female', 'Transgender') DEFAULT 'All',
    `benefits` TEXT NOT NULL,
    `application_url` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. ELIGIBILITY RULES TABLE
CREATE TABLE IF NOT EXISTS `eligibility_rules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `scheme_id` INT NOT NULL,
    `rule_key` VARCHAR(100) NOT NULL,
    `operator` VARCHAR(10) NOT NULL,
    `rule_value` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`scheme_id`) REFERENCES `schemes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. DOCUMENTS TABLE
CREATE TABLE IF NOT EXISTS `documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `required_docs` TEXT NOT NULL,
    `procedure_steps` TEXT NOT NULL,
    `processing_time_days` INT DEFAULT 14,
    `fees` VARCHAR(100) DEFAULT 'Free / Nominal',
    `common_mistakes` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. BILL UPLOADS TABLE
CREATE TABLE IF NOT EXISTS `bill_uploads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `bill_type` VARCHAR(50) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `upload_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. BILL ANALYSIS TABLE
CREATE TABLE IF NOT EXISTS `bill_analysis` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `bill_id` INT NOT NULL,
    `provider_name` VARCHAR(100) NOT NULL,
    `billing_date` DATE NULL,
    `due_date` DATE NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `units_consumed` DECIMAL(10, 2) NULL,
    `usage_anomaly` TINYINT(1) DEFAULT 0,
    `parsed_json` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`bill_id`) REFERENCES `bill_uploads`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. SCAM REPORTS TABLE
CREATE TABLE IF NOT EXISTS `scam_reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `input_type` ENUM('text', 'sms', 'email', 'image') NOT NULL,
    `content` TEXT NOT NULL,
    `risk_score` INT NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `warning_explanation` TEXT NOT NULL,
    `recommended_action` TEXT NOT NULL,
    `analyzed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. SERVICE PROVIDERS TABLE
CREATE TABLE IF NOT EXISTS `service_providers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `business_name` VARCHAR(150) NOT NULL,
    `service_category` VARCHAR(100) NOT NULL,
    `hourly_rate` DECIMAL(10, 2) DEFAULT 0.00,
    `experience_years` INT DEFAULT 1,
    `is_verified` TINYINT(1) DEFAULT 0,
    `rating` DECIMAL(3, 2) DEFAULT 5.00,
    `total_reviews` INT DEFAULT 0,
    `bio` TEXT NULL,
    `city` VARCHAR(100) DEFAULT 'Chennai',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. BOOKINGS TABLE
CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `provider_id` INT NOT NULL,
    `service_description` TEXT NOT NULL,
    `scheduled_date` DATETIME NOT NULL,
    `status` ENUM('Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
    `total_price` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`provider_id`) REFERENCES `service_providers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. REVIEWS TABLE
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `provider_id` INT NOT NULL,
    `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`provider_id`) REFERENCES `service_providers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 18. NOTIFICATIONS TABLE
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `type` VARCHAR(50) DEFAULT 'info',
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 19. HEATMAPS TABLE
CREATE TABLE IF NOT EXISTS `heatmaps` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `latitude` DECIMAL(10, 8) NOT NULL,
    `longitude` DECIMAL(11, 8) NOT NULL,
    `intensity` DECIMAL(4, 2) DEFAULT 1.0,
    `category` VARCHAR(50) NOT NULL,
    `complaint_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`complaint_id`) REFERENCES `complaints`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 20. ANALYTICS TABLE
CREATE TABLE IF NOT EXISTS `analytics` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `metric_name` VARCHAR(100) NOT NULL,
    `metric_value` DECIMAL(12, 2) NOT NULL,
    `recorded_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 21. AUDIT LOGS TABLE
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity` VARCHAR(100) NOT NULL,
    `entity_id` INT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 22. AI CONVERSATIONS TABLE
CREATE TABLE IF NOT EXISTS `ai_conversations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `prompt` TEXT NOT NULL,
    `response` TEXT NOT NULL,
    `intent` VARCHAR(100) NULL,
    `sentiment` VARCHAR(50) DEFAULT 'neutral',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
