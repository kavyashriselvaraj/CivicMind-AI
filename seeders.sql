-- CivicMind X - Production Data Seeders
USE `civicmind_x`;

-- 1. SEED ROLES
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Citizen', 'Standard citizen account for civic reporting and services'),
(2, 'Service Provider', 'Verified vendor offering local maintenance services'),
(3, 'Government Officer', 'Municipal department officer assigned to handle civic complaints'),
(4, 'Moderator', 'Content & report auditor'),
(5, 'Administrator', 'Full system access and analytics dashboard controller');

-- 2. SEED DEPARTMENTS
INSERT INTO `departments` (`id`, `name`, `code`, `contact_email`, `head_name`, `phone`) VALUES
(1, 'Electrical & Lighting Board', 'DEPT_ELEC', 'elec@civicmind.gov', 'Rajesh Sharma', '+91 44 2888 1001'),
(2, 'Sanitation & Solid Waste Management', 'DEPT_SANI', 'clean@civicmind.gov', 'Priya Sundaram', '+91 44 2888 1002'),
(3, 'Roads, Bridges & Infrastructure', 'DEPT_ROAD', 'infra@civicmind.gov', 'Vikram Malhotra', '+91 44 2888 1003'),
(4, 'Water Supply & Sewerage Board', 'DEPT_WATR', 'water@civicmind.gov', 'Ananya Roy', '+91 44 2888 1004'),
(5, 'Public Safety & Emergency Support', 'DEPT_SAFE', 'safety@civicmind.gov', 'Karan Verma', '+91 44 2888 1005');

-- 3. SEED USERS (Passwords hashed with Bcrypt default 'password123')
-- Hash: $2y$10$wN1G9E38C4vVf.hR.E1l6.s5pYdZf9eW7o1gXq/uXhK2oN8Z.y2mS (or runtime hash fallback)
INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `role_id`, `status`) VALUES
(1, 'Arun Kumar', 'citizen@civicmind.org', '+91 98765 43210', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe115C.xV3d2uPqE8Yh9Y6N7N.y2mS123', 1, 'active'),
(2, 'Suresh Electricals', 'provider@civicmind.org', '+91 98765 43211', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe115C.xV3d2uPqE8Yh9Y6N7N.y2mS123', 2, 'active'),
(3, 'Officer Rajesh', 'officer@civicmind.gov', '+91 98765 43212', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe115C.xV3d2uPqE8Yh9Y6N7N.y2mS123', 3, 'active'),
(4, 'Sarah Moderator', 'moderator@civicmind.org', '+91 98765 43213', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe115C.xV3d2uPqE8Yh9Y6N7N.y2mS123', 4, 'active'),
(5, 'Super Admin', 'admin@civicmind.org', '+91 98765 43214', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe115C.xV3d2uPqE8Yh9Y6N7N.y2mS123', 5, 'active');

-- 4. SEED SERVICE PROVIDERS
INSERT INTO `service_providers` (`id`, `user_id`, `business_name`, `service_category`, `hourly_rate`, `experience_years`, `is_verified`, `rating`, `total_reviews`, `bio`, `city`) VALUES
(1, 2, 'Suresh Quick Electrical Services', 'Electrician', 450.00, 8, 1, 4.90, 128, 'Certified licensed electrician specializing in home wiring, fuse box repairs, and solar panel setup.', 'Chennai'),
(2, 5, 'Velu Plumbing & Water Solutions', 'Plumber', 380.00, 12, 1, 4.85, 94, 'Expert in pipe leak repairs, sanitary fittings, overhead tank cleaning, and drainage unclogging.', 'Chennai');

-- 5. SEED COMPLAINTS
INSERT INTO `complaints` (`id`, `complaint_number`, `user_id`, `category`, `severity`, `title`, `description`, `address`, `latitude`, `longitude`, `department_id`, `status`, `assigned_to`, `ai_generated`) VALUES
(1, 'CMP-2026-001', 1, 'Electrical', 4, 'Streetlight Non-Functional near Anna Nagar 2nd Avenue', 'The main street light pole #42 has been dark for 3 days creating safety hazards for pedestrians at night.', '12th Main Road, Anna Nagar, Chennai', 13.08500000, 80.21000000, 1, 'In Progress', 3, 1),
(2, 'CMP-2026-002', 1, 'Roads', 5, 'Dangerous Deep Pothole on Highway Junction', 'Severe road damage causing traffic slowdowns and vehicle rim damages during peak hours.', 'GST Road, Guindy, Chennai', 13.00670000, 80.20200000, 3, 'Assigned', 3, 0),
(3, 'CMP-2026-003', 1, 'Sanitation', 3, 'Garbage Accumulation near Public Park', 'Waste container overflowing with unsegregated domestic trash for 48 hours.', 'T. Nagar, Chennai', 13.04180000, 80.23410000, 2, 'Under Review', NULL, 1);

-- 6. SEED SCHEMES
INSERT INTO `schemes` (`id`, `title`, `category`, `description`, `state`, `min_age`, `max_age`, `min_income`, `max_income`, `target_occupation`, `gender`, `benefits`, `application_url`) VALUES
(1, 'PM-Kisan Samman Nidhi', 'Agriculture', 'Financial support scheme providing income support of ₹6,000 per year to all landholding farmers families across the country.', 'All India', 18, 75, 0.00, 300000.00, 'Farmer', 'All', 'Direct benefit transfer of ₹6,000 per annum in three equal installments into bank accounts.', 'https://pmkisan.gov.in'),
(2, 'Makkalai Thedi Maruthuvam', 'Healthcare', 'Doorstep healthcare service delivering essential medicines and screening for chronic non-communicable diseases.', 'Tamil Nadu', 0, 100, 0.00, 500000.00, 'All', 'All', 'Free doorstep medicine delivery, blood pressure and diabetes screening by healthcare volunteers.', 'https://tnhealth.tn.gov.in'),
(3, 'Pradhan Mantri Awas Yojana (PMAY)', 'Housing', 'Credit linked subsidy scheme for affordable housing construction and home loan interest subsidy for EWS and LIG families.', 'All India', 21, 65, 0.00, 600000.00, 'Low Income Household', 'All', 'Up to ₹2.67 Lakhs interest subsidy on housing loans for first-time home buyers.', 'https://pmaymis.gov.in');

-- 7. SEED DOCUMENTS
INSERT INTO `documents` (`id`, `title`, `category`, `required_docs`, `procedure_steps`, `processing_time_days`, `fees`, `common_mistakes`) VALUES
(1, 'Passport Application Guide (Tatkaal & Normal)', 'Identity & Travel', '1. Proof of Present Address (Aadhaar / Utility Bill)\n2. Proof of Date of Birth (Birth Certificate / PAN)\n3. Photo ID Proof', 'Step 1: Register on Official Passport Seva Portal.\nStep 2: Fill online application form.\nStep 3: Pay fee online and schedule appointment at PSK/POPSK.\nStep 4: Visit PSK with original documents for biometric verification.\nStep 5: Police verification & dispatch by speed post.', 14, '₹1,500 (Normal) / ₹3,500 (Tatkaal)', '1. Mismatch between name in Aadhaar and Birth certificate.\n2. Submitting outdated utility bills as address proof.\n3. Missing original documents during PSK appointment visit.'),
(2, 'Smart Card / Driving License Renewal', 'Transport', '1. Existing Driving License original\n2. Form 1A Medical Certificate (for age 40+)\n3. Passport size photo & Signature scan\n4. Aadhaar card for online KYC', 'Step 1: Visit Parivahan Sewa portal.\nStep 2: Enter DL number and date of birth.\nStep 3: Upload medical form & Aadhaar e-KYC.\nStep 4: Pay renewal fees online.\nStep 5: Download temporary approval or receive card by post.', 7, '₹400', '1. Delaying renewal beyond 1 year grace period incurring retest penalty.\n2. Incomplete doctor seal on Form 1A medical fitness form.');

-- 8. SEED SCAM REPORTS
INSERT INTO `scam_reports` (`id`, `user_id`, `input_type`, `content`, `risk_score`, `category`, `warning_explanation`, `recommended_action`) VALUES
(1, 1, 'sms', 'URGENT: Your electricity connection will be disconnected tonight at 9:30 PM due to unpaid bill of ₹840. Call Officer Sharma immediately at 9876543210 to update e-KYC.', 95, 'Financial Impersonation Fraud', 'High urgency tactic impersonating state electricity board. Standard utility providers never request e-KYC updates via personal mobile numbers or instant messaging apps.', 'Do not call the number. Do not pay any amount online via shared APK links. Report the number to CyberCrime 1930.');

-- 9. SEED HEATMAPS
INSERT INTO `heatmaps` (`id`, `latitude`, `longitude`, `intensity`, `category`, `complaint_id`) VALUES
(1, 13.08500000, 80.21000000, 0.9, 'Electrical', 1),
(2, 13.00670000, 80.20200000, 1.0, 'Roads', 2),
(3, 13.04180000, 80.23410000, 0.6, 'Sanitation', 3),
(4, 13.06000000, 80.24000000, 0.8, 'Water', NULL),
(5, 13.02000000, 80.21500000, 0.7, 'Electrical', NULL);

-- 10. SEED ANALYTICS
INSERT INTO `analytics` (`id`, `metric_name`, `metric_value`, `recorded_date`) VALUES
(1, 'active_users', 1420.00, CURRENT_DATE),
(2, 'complaints_logged', 384.00, CURRENT_DATE),
(3, 'resolution_rate_percent', 92.40, CURRENT_DATE),
(4, 'scams_detected', 189.00, CURRENT_DATE),
(5, 'scheme_queries', 2150.00, CURRENT_DATE);
