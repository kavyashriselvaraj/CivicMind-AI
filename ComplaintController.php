<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use CivicMind\Services\PdfReportGenerator;
use PDO;

class ComplaintController {
    public function index(): void {
        $db = Database::getInstance();
        $user = Session::get('user');

        if ($user && in_array((int)$user['role_id'], [3, 5], true)) { // Admin or Gov Officer
            $stmt = $db->query("SELECT c.*, u.full_name as reporter_name, d.name as department_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN departments d ON c.department_id = d.id ORDER BY c.id DESC");
        } elseif ($user) {
            $stmt = $db->prepare("SELECT c.*, u.full_name as reporter_name, d.name as department_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN departments d ON c.department_id = d.id WHERE c.user_id = :uid ORDER BY c.id DESC");
            $stmt->execute(['uid' => $user['id']]);
        } else {
            $stmt = $db->query("SELECT c.*, u.full_name as reporter_name, d.name as department_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN departments d ON c.department_id = d.id ORDER BY c.id DESC LIMIT 20");
        }

        $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch departments
        $deptStmt = $db->query("SELECT * FROM departments ORDER BY name ASC");
        $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('complaints/index', [
            'title' => 'Civic Complaints - CivicMind X',
            'complaints' => $complaints,
            'departments' => $departments,
        ]);
    }

    public function create(): void {
        $db = Database::getInstance();
        $deptStmt = $db->query("SELECT * FROM departments ORDER BY name ASC");
        $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('complaints/create', [
            'title' => 'File New Complaint - CivicMind X',
            'departments' => $departments,
        ]);
    }

    public function store(): void {
        $user = Session::get('user');
        $userId = $user ? $user['id'] : 1; // Default guest citizen

        $title = trim($_POST['title'] ?? '');
        $category = $_POST['category'] ?? 'General';
        $severity = (int)($_POST['severity'] ?? 3);
        $description = trim($_POST['description'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $latitude = (float)($_POST['latitude'] ?? 13.0827);
        $longitude = (float)($_POST['longitude'] ?? 80.2707);
        $deptId = (int)($_POST['department_id'] ?? 1);

        if (!$title || !$description) {
            Session::setFlash('error', 'Please provide a title and detailed description.');
            Response::redirect('/complaints/create');
        }

        $complaintNumber = 'CMP-' . date('Y') . '-' . str_pad((string)rand(100, 9999), 4, '0', STR_PAD_LEFT);
        $slaDeadline = date('Y-m-d H:i:s', strtotime("+{$severity} days"));

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO complaints (complaint_number, user_id, category, severity, title, description, address, latitude, longitude, department_id, status, sla_deadline) VALUES (:num, :uid, :cat, :sev, :title, :desc, :addr, :lat, :lng, :dept, 'Submitted', :sla)");
        $stmt->execute([
            'num' => $complaintNumber,
            'uid' => $userId,
            'cat' => $category,
            'sev' => $severity,
            'title' => $title,
            'desc' => $description,
            'addr' => $address,
            'lat' => $latitude,
            'lng' => $longitude,
            'dept' => $deptId,
            'sla' => $slaDeadline,
        ]);

        $complaintId = $db->lastInsertId();

        // Handle Photo Upload if present
        if (!empty($_FILES['photo']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                $imgStmt = $db->prepare("INSERT INTO complaint_images (complaint_id, file_path) VALUES (:cid, :path)");
                $imgStmt->execute(['cid' => $complaintId, 'path' => 'uploads/' . $fileName]);
            }
        }

        // Add to GIS Heatmap
        $hmStmt = $db->prepare("INSERT INTO heatmaps (latitude, longitude, intensity, category, complaint_id) VALUES (:lat, :lng, :int, :cat, :cid)");
        $hmStmt->execute(['lat' => $latitude, 'lng' => $longitude, 'int' => $severity / 5.0, 'cat' => $category, 'cid' => $complaintId]);

        // Audit Log
        $auditStmt = $db->prepare("INSERT INTO audit_logs (user_id, action, entity, entity_id) VALUES (:uid, 'CREATE_COMPLAINT', 'complaints', :cid)");
        $auditStmt->execute(['uid' => $userId, 'cid' => $complaintId]);

        Session::setFlash('success', "Complaint #{$complaintNumber} filed successfully!");
        Response::redirect('/complaints');
    }

    public function show(string $id): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT c.*, u.full_name as reporter_name, u.email as reporter_email, u.phone as reporter_phone, d.name as department_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN departments d ON c.department_id = d.id WHERE c.id = :id");
        $stmt->execute(['id' => $id]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$complaint) {
            View::render('404', ['title' => 'Complaint Not Found']);
            return;
        }

        // Fetch Images
        $imgStmt = $db->prepare("SELECT * FROM complaint_images WHERE complaint_id = :cid");
        $imgStmt->execute(['cid' => $id]);
        $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch History
        $histStmt = $db->prepare("SELECT h.*, u.full_name as actor_name FROM complaint_status_history h LEFT JOIN users u ON h.changed_by = u.id WHERE h.complaint_id = :cid ORDER BY h.id ASC");
        $histStmt->execute(['cid' => $id]);
        $history = $histStmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('complaints/show', [
            'title' => "Complaint #{$complaint['complaint_number']} - CivicMind X",
            'complaint' => $complaint,
            'images' => $images,
            'history' => $history,
        ]);
    }

    public function updateStatus(string $id): void {
        $user = Session::get('user');
        $newStatus = $_POST['status'] ?? 'Under Review';
        $comments = trim($_POST['comments'] ?? '');

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT status FROM complaints WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $oldStatus = $stmt->fetchColumn() ?: 'Submitted';

        // Update Status
        $upStmt = $db->prepare("UPDATE complaints SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $upStmt->execute(['status' => $newStatus, 'id' => $id]);

        // Insert Status History
        $histStmt = $db->prepare("INSERT INTO complaint_status_history (complaint_id, old_status, new_status, changed_by, comments) VALUES (:cid, :old, :new, :uid, :comm)");
        $histStmt->execute([
            'cid' => $id,
            'old' => $oldStatus,
            'new' => $newStatus,
            'uid' => $user ? $user['id'] : 1,
            'comm' => $comments ?: "Status updated to {$newStatus}",
        ]);

        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            Response::success(['id' => $id, 'status' => $newStatus], 'Status updated successfully');
        } else {
            Session::setFlash('success', "Complaint status updated to {$newStatus}");
            Response::redirect("/complaints/{$id}");
        }
    }

    public function downloadPdf(string $id): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT c.*, u.full_name as reporter_name, d.name as department_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN departments d ON c.department_id = d.id WHERE c.id = :id");
        $stmt->execute(['id' => $id]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$complaint) {
            die('Complaint not found');
        }

        $pdfGen = new PdfReportGenerator();
        $html = $pdfGen->generateComplaintPdf($complaint);

        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
}
