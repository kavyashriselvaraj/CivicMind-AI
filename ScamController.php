<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use CivicMind\Services\ScamScoringEngine;
use PDO;

class ScamController {
    private ScamScoringEngine $scoringEngine;

    public function __construct() {
        $this->scoringEngine = new ScamScoringEngine();
    }

    public function index(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM scam_reports ORDER BY id DESC LIMIT 10");
        $recentReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('scams/index', [
            'title' => 'Scam & Fraud Detection AI - CivicMind X',
            'recentReports' => $recentReports,
        ]);
    }

    public function analyze(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $content = trim($input['content'] ?? '');
        $inputType = $input['input_type'] ?? 'text';

        if (!$content) {
            Response::error('Please enter or paste the text/SMS/email content to analyze.', 400);
        }

        $analysis = $this->scoringEngine->evaluate($content, $inputType);

        // Store Scam Report
        $user = Session::get('user');
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO scam_reports (user_id, input_type, content, risk_score, category, warning_explanation, recommended_action) VALUES (:uid, :type, :content, :score, :cat, :warn, :rec)");
        $stmt->execute([
            'uid' => $user ? $user['id'] : null,
            'type' => $inputType,
            'content' => $content,
            'score' => $analysis['risk_score'],
            'cat' => $analysis['category'],
            'warn' => $analysis['warning_explanation'],
            'rec' => $analysis['recommended_action'],
        ]);

        Response::success($analysis, 'Scam risk analysis completed successfully');
    }
}
