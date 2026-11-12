<?php

namespace CivicMind\Services;

use CivicMind\Core\Database;
use PDO;

class SchemeMatcherEngine {
    public function findEligibleSchemes(array $criteria): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM schemes ORDER BY id DESC");
        $allSchemes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $age = isset($criteria['age']) ? (int)$criteria['age'] : null;
        $income = isset($criteria['income']) ? (float)$criteria['income'] : null;
        $occupation = $criteria['occupation'] ?? '';
        $state = $criteria['state'] ?? '';

        $matched = [];
        foreach ($allSchemes as $scheme) {
            $score = 100;
            $reasons = [];

            if ($age !== null) {
                if ($age < (int)$scheme['min_age'] || $age > (int)$scheme['max_age']) {
                    $score -= 40;
                    $reasons[] = "Age {$age} outside allowed range ({$scheme['min_age']}-{$scheme['max_age']})";
                }
            }

            if ($income !== null) {
                if ($income > (float)$scheme['max_income']) {
                    $score -= 40;
                    $reasons[] = "Income ₹{$income} exceeds maximum limit of ₹{$scheme['max_income']}";
                }
            }

            if ($occupation && $scheme['target_occupation'] !== 'All') {
                if (!str_contains(strtolower($scheme['target_occupation']), strtolower($occupation))) {
                    $score -= 20;
                    $reasons[] = "Targeted primarily at: {$scheme['target_occupation']}";
                }
            }

            $isEligible = $score >= 60;
            $matched[] = [
                'scheme' => $scheme,
                'match_percentage' => max(0, $score),
                'is_eligible' => $isEligible,
                'eligibility_status' => $isEligible ? 'Highly Eligible' : 'Potentially Ineligible',
                'remarks' => $reasons ? implode(', ', $reasons) : 'All criteria fully satisfied.',
                'required_documents' => [
                    'Aadhaar Card / Photo Identity',
                    'Income Certificate issued by Revenue Department',
                    'Bank Account Passbook with IFSC Code',
                    'Proof of Residence / Nativity Certificate'
                ]
            ];
        }

        usort($matched, fn($a, $b) => $b['match_percentage'] <=> $a['match_percentage']);

        return $matched;
    }
}
