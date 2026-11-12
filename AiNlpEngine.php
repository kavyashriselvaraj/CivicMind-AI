<?php

namespace CivicMind\Services;

class AiNlpEngine {
    private array $categories = [
        'Electrical' => ['keywords' => ['street light', 'streetlight', 'lamp', 'power', 'wire', 'transformer', 'shock', 'fuse', 'electric', 'blackout'], 'dept_id' => 1, 'dept_name' => 'Electrical & Lighting Board'],
        'Sanitation' => ['keywords' => ['garbage', 'trash', 'waste', 'dump', 'smell', 'odor', 'drain', 'sewer', 'overflow', 'sanitation', 'cleanliness'], 'dept_id' => 2, 'dept_name' => 'Sanitation & Solid Waste Management'],
        'Roads' => ['keywords' => ['pothole', 'road', 'asphalt', 'tar', 'bridge', 'pavement', 'footpath', 'traffic sign', 'crater', 'divider'], 'dept_id' => 3, 'dept_name' => 'Roads, Bridges & Infrastructure'],
        'Water' => ['keywords' => ['water', 'pipe', 'leak', 'tap', 'seepage', 'drainage', 'supply', 'drinking water', 'contamination'], 'dept_id' => 4, 'dept_name' => 'Water Supply & Sewerage Board'],
        'Public Safety' => ['keywords' => ['dark', 'crime', 'stray dog', 'cctv', 'harassment', 'encroachment', 'hazard', 'safety', 'fire', 'accident'], 'dept_id' => 5, 'dept_name' => 'Public Safety & Emergency Support'],
    ];

    private array $highSeverityWords = ['urgent', 'emergency', 'danger', 'sparking', 'live wire', 'broken bridge', 'accident', 'flooding', 'burst', 'severe'];
    private array $mediumSeverityWords = ['blinking', 'overflow', 'smell', 'blocked', 'dark', 'damaged', 'slow'];

    public function analyzeInput(string $userText, string $location = ''): array {
        $textLower = strtolower($userText);
        $detectedCategory = 'General Civic Issue';
        $targetDeptId = 1;
        $targetDeptName = 'Municipal Administrative Board';
        $maxMatches = 0;

        foreach ($this->categories as $category => $info) {
            $matches = 0;
            foreach ($info['keywords'] as $kw) {
                if (str_contains($textLower, $kw)) {
                    $matches++;
                }
            }
            if ($matches > $maxMatches) {
                $maxMatches = $matches;
                $detectedCategory = $category;
                $targetDeptId = $info['dept_id'];
                $targetDeptName = $info['dept_name'];
            }
        }

        // Determine Severity (1 to 5)
        $severity = 3;
        foreach ($this->highSeverityWords as $word) {
            if (str_contains($textLower, $word)) {
                $severity = 5;
                break;
            }
        }
        if ($severity === 3) {
            foreach ($this->mediumSeverityWords as $word) {
                if (str_contains($textLower, $word)) {
                    $severity = 4;
                    break;
                }
            }
        }

        // Estimate SLA Resolution Time
        $slaDays = match ($severity) {
            5 => 1,
            4 => 3,
            3 => 5,
            2 => 7,
            default => 10,
        };

        // Draft AI Complaint
        $title = ucfirst($detectedCategory) . " Issue: " . mb_strimwidth($userText, 0, 60, "...");
        $draft = "OFFICIAL CIVIC REPORT DRAFT\n"
               . "----------------------------\n"
               . "Category: " . strtoupper($detectedCategory) . "\n"
               . "Assigned Department: " . $targetDeptName . "\n"
               . "Severity Level: " . $severity . "/5\n"
               . "Estimated SLA Resolution: Within " . $slaDays . " working days.\n"
               . "Problem Description: " . trim($userText) . "\n"
               . "Location Details: " . ($location ?: "As reported by citizen GPS") . "\n"
               . "Action Recommended: Immediate dispatch of field technician for site inspection and rectification.";

        return [
            'intent' => 'report_civic_issue',
            'category' => $detectedCategory,
            'severity' => $severity,
            'department_id' => $targetDeptId,
            'department_name' => $targetDeptName,
            'sla_days' => $slaDays,
            'title' => $title,
            'draft' => $draft,
            'suggested_actions' => [
                "Attach a clear photo of the issue.",
                "Ensure precise GPS coordinates are pinned on the map.",
                "Avoid touching any exposed electrical or damaged infrastructure.",
            ]
        ];
    }
}
