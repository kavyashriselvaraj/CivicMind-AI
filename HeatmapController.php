<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Database;
use PDO;

class HeatmapController {
    public function index(): void {
        View::render('heatmaps/index', ['title' => 'Community GIS Heatmap - CivicMind X']);
    }

    public function getGeoJson(): void {
        $category = $_GET['category'] ?? '';
        $db = Database::getInstance();

        if ($category) {
            $stmt = $db->prepare("SELECT h.*, c.title, c.severity, c.status FROM heatmaps h LEFT JOIN complaints c ON h.complaint_id = c.id WHERE h.category = :cat");
            $stmt->execute(['cat' => $category]);
        } else {
            $stmt = $db->query("SELECT h.*, c.title, c.severity, c.status FROM heatmaps h LEFT JOIN complaints c ON h.complaint_id = c.id");
        }

        $points = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $features = [];
        foreach ($points as $p) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$p['longitude'], (float)$p['latitude']]
                ],
                'properties' => [
                    'id' => $p['id'],
                    'intensity' => (float)$p['intensity'],
                    'category' => $p['category'],
                    'title' => $p['title'] ?? 'Community Hotspot',
                    'status' => $p['status'] ?? 'Active',
                    'severity' => (int)($p['severity'] ?? 3),
                ]
            ];
        }

        Response::json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
