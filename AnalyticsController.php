<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Database;
use CivicMind\Services\PredictiveAiEngine;
use PDO;

class AnalyticsController {
    private PredictiveAiEngine $predictiveEngine;

    public function __construct() {
        $this->predictiveEngine = new PredictiveAiEngine();
    }

    public function index(): void {
        $db = Database::getInstance();

        // Metrics Summary
        $usersCount = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $complaintsCount = (int)$db->query("SELECT COUNT(*) FROM complaints")->fetchColumn();
        $resolvedCount = (int)$db->query("SELECT COUNT(*) FROM complaints WHERE status = 'Resolved' OR status = 'Closed'")->fetchColumn();
        $scamsCount = (int)$db->query("SELECT COUNT(*) FROM scam_reports")->fetchColumn();

        $resolutionRate = $complaintsCount > 0 ? round(($resolvedCount / $complaintsCount) * 100, 1) : 100;

        // Fetch Predictive AI forecasts
        $forecasts = $this->predictiveEngine->generateForecasts();

        View::render('analytics/index', [
            'title' => 'Analytics & Predictive Intelligence - CivicMind X',
            'metrics' => [
                'total_users' => $usersCount,
                'total_complaints' => $complaintsCount,
                'resolved_complaints' => $resolvedCount,
                'resolution_rate' => $resolutionRate,
                'scams_detected' => $scamsCount,
            ],
            'forecasts' => $forecasts,
        ]);
    }

    public function getChartData(): void {
        $db = Database::getInstance();

        $byCategory = $db->query("SELECT category, COUNT(*) as count FROM complaints GROUP BY category")->fetchAll(PDO::FETCH_ASSOC);
        $byStatus = $db->query("SELECT status, COUNT(*) as count FROM complaints GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);

        Response::success([
            'categories' => $byCategory,
            'statuses' => $byStatus,
        ]);
    }
}
