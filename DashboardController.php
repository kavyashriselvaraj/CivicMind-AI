<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use CivicMind\Services\PredictiveAiEngine;
use PDO;

class DashboardController {
    public function index(): void {
        $user = Session::get('user') ?? [
            'id' => 1,
            'full_name' => 'Arun Kumar',
            'email' => 'citizen@civicmind.org',
            'role_id' => 1,
            'role_name' => 'Citizen'
        ];

        $db = Database::getInstance();
        $roleId = (int)$user['role_id'];

        if ($roleId === 5 || $roleId === 3) { // Admin or Gov Officer
            $stmt = $db->query("SELECT c.*, u.full_name as reporter_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.id DESC LIMIT 10");
            $recentComplaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stats = [
                'total_complaints' => (int)$db->query("SELECT COUNT(*) FROM complaints")->fetchColumn(),
                'in_progress' => (int)$db->query("SELECT COUNT(*) FROM complaints WHERE status = 'In Progress' OR status = 'Assigned'")->fetchColumn(),
                'resolved' => (int)$db->query("SELECT COUNT(*) FROM complaints WHERE status = 'Resolved'")->fetchColumn(),
                'scams_flagged' => (int)$db->query("SELECT COUNT(*) FROM scam_reports WHERE risk_score >= 50")->fetchColumn(),
            ];

            View::render('dashboard/admin', [
                'title' => 'Executive Management Dashboard - CivicMind X',
                'user' => $user,
                'stats' => $stats,
                'recentComplaints' => $recentComplaints,
            ]);
        } else { // Citizen or Service Provider
            $stmt = $db->prepare("SELECT * FROM complaints WHERE user_id = :uid ORDER BY id DESC");
            $stmt->execute(['uid' => $user['id']]);
            $myComplaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Active Bookings
            $bkStmt = $db->prepare("SELECT b.*, sp.business_name FROM bookings b JOIN service_providers sp ON b.provider_id = sp.id WHERE b.user_id = :uid ORDER BY b.id DESC");
            $bkStmt->execute(['uid' => $user['id']]);
            $myBookings = $bkStmt->fetchAll(PDO::FETCH_ASSOC);

            View::render('dashboard/citizen', [
                'title' => 'My Digital Operating Dashboard - CivicMind X',
                'user' => $user,
                'myComplaints' => $myComplaints,
                'myBookings' => $myBookings,
            ]);
        }
    }
}
