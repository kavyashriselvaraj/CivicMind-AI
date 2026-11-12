<?php

namespace CivicMind\Services;

class ScamScoringEngine {
    private array $riskPatterns = [
        'financial_urgency' => ['keywords' => ['disconnect', 'suspended', 'penalty', 'urgent', 'immediately', 'block', 'expire', 'tonight', 'due today', 'unpaid', 'power'], 'weight' => 25],
        'phishing_links' => ['keywords' => ['.apk', 'bit.ly', 'tinyurl', 'http://', 'click here', 'download link', 'verify-now', 'update-kyc'], 'weight' => 35],
        'impersonation' => ['keywords' => ['officer', 'manager', 'bank', 'electricity board', 'customs', 'police', 'lottery', 'income tax', 'support team', 'electricity', 'bill'], 'weight' => 25],
        'credential_harvesting' => ['keywords' => ['otp', 'password', 'pin', 'cvv', 'card number', 'aadhaar copy', 'pan details', 'kyc'], 'weight' => 30],
        'fake_rewards' => ['keywords' => ['congratulations', 'won', 'prize', 'cashback', 'reward points', 'free gift', 'claim now'], 'weight' => 25],
    ];

    public function evaluate(string $content, string $type = 'text'): array {
        $lowerContent = strtolower($content);
        $totalRiskScore = 0;
        $flaggedPatterns = [];

        foreach ($this->riskPatterns as $patternName => $data) {
            foreach ($data['keywords'] as $kw) {
                if (str_contains($lowerContent, $kw)) {
                    $totalRiskScore += $data['weight'];
                    $flaggedPatterns[] = ucwords(str_replace('_', ' ', $patternName)) . " (matched: '{$kw}')";
                    break;
                }
            }
        }

        // Cap risk score at 100%
        $riskScore = min(100, max(5, $totalRiskScore));

        $category = match (true) {
            $riskScore >= 75 => 'High-Risk Cyber Fraud / Phishing',
            $riskScore >= 50 => 'Suspicious Impersonation / Scam',
            $riskScore >= 25 => 'Unverified Commercial / Spam',
            default => 'Low Risk / Likely Legitimate',
        };

        $explanation = match (true) {
            $riskScore >= 75 => "CRITICAL THREAT: This message exhibits classic cyber-fraud indicators such as artificial urgency, unverified links, or requests for sensitive information.",
            $riskScore >= 50 => "MODERATE RISK: The message contains suspicious patterns often used by scammers to trick users into revealing personal data.",
            $riskScore >= 25 => "LOW-MODERATE RISK: Some promotional or urgent language detected. Exercise caution before clicking external links.",
            default => "SAFE / LOW RISK: No immediate scam or phishing indicators were found in the submitted text.",
        };

        $recommendedActions = match (true) {
            $riskScore >= 50 => [
                "DO NOT click any embedded links or download .APK files.",
                "DO NOT share OTPs, UPI PINs, or bank account credentials.",
                "Block and report the sender immediately.",
                "If financial loss occurred, call CyberCrime Helpline 1930 immediately.",
            ],
            default => [
                "Verify the sender's identity through official customer care channels.",
                "Always check official websites directly rather than using links in SMS.",
            ],
        };

        return [
            'risk_score' => $riskScore,
            'category' => $category,
            'warning_explanation' => $explanation,
            'flagged_indicators' => array_unique($flaggedPatterns),
            'recommended_action' => implode("\n", $recommendedActions),
        ];
    }
}
