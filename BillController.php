<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use CivicMind\Services\BillOcrEngine;
use PDO;

class BillController {
    private BillOcrEngine $ocrEngine;

    public function __construct() {
        $this->ocrEngine = new BillOcrEngine();
    }

    public function index(): void {
        $db = Database::getInstance();
        $user = Session::get('user');
        $userId = $user ? $user['id'] : 1;

        $stmt = $db->prepare("SELECT b.*, a.provider_name, a.total_amount, a.units_consumed, a.usage_anomaly FROM bill_uploads b LEFT JOIN bill_analysis a ON b.id = a.bill_id WHERE b.user_id = :uid ORDER BY b.id DESC");
        $stmt->execute(['uid' => $userId]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('bills/index', [
            'title' => 'Bill Intelligence & OCR - CivicMind X',
            'history' => $history,
        ]);
    }

    public function upload(): void {
        $billType = $_POST['bill_type'] ?? 'Electricity';
        $user = Session::get('user');
        $userId = $user ? $user['id'] : 1;

        $filePath = 'uploads/sample_bill_' . time() . '.pdf';
        if (!empty($_FILES['bill_file']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['bill_file']['name']);
            move_uploaded_file($_FILES['bill_file']['tmp_name'], $uploadDir . $fileName);
            $filePath = 'uploads/' . $fileName;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO bill_uploads (user_id, bill_type, file_path) VALUES (:uid, :type, :path)");
        $stmt->execute(['uid' => $userId, 'type' => $billType, 'path' => $filePath]);
        $billId = $db->lastInsertId();

        // Run OCR Engine
        $parsed = $this->ocrEngine->parse($filePath, $billType);

        // Store Analysis
        $anStmt = $db->prepare("INSERT INTO bill_analysis (bill_id, provider_name, billing_date, due_date, total_amount, units_consumed, usage_anomaly, parsed_json) VALUES (:bid, :pname, :bdate, :ddate, :amt, :units, :anom, :json)");
        $anStmt->execute([
            'bid' => $billId,
            'pname' => $parsed['provider_name'],
            'bdate' => $parsed['billing_date'],
            'ddate' => $parsed['due_date'],
            'amt' => $parsed['total_amount'],
            'units' => $parsed['units_consumed'],
            'anom' => $parsed['usage_anomaly'] ? 1 : 0,
            'json' => json_encode($parsed, JSON_UNESCAPED_UNICODE),
        ]);

        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            Response::success($parsed, 'Bill analyzed successfully');
        } else {
            Session::setFlash('success', 'Bill OCR processing completed!');
            Response::redirect('/bills');
        }
    }
}
