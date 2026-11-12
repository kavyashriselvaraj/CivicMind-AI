<?php

namespace CivicMind\Tests\Unit;

use CivicMind\Services\AiNlpEngine;
use CivicMind\Services\ScamScoringEngine;
use CivicMind\Services\BillOcrEngine;
use CivicMind\Services\SchemeMatcherEngine;

class AiServicesTest {
    public static function runAll(): void {
        echo "=====================================================\n";
        echo "  CIVICMIND X - AUTOMATED SERVICE TEST SUITE\n";
        echo "=====================================================\n\n";

        $passed = 0;
        $failed = 0;

        // Test 1: AI NLP Engine
        echo "[TEST 1] AiNlpEngine Civic Intent & Category Classification... ";
        $nlp = new AiNlpEngine();
        $res = $nlp->analyzeInput("Streetlight is blinking continuously on Anna Nagar 2nd Avenue");
        if ($res['category'] === 'Electrical' && $res['department_id'] === 1 && $res['severity'] >= 3) {
            echo "PASSED ✓\n";
            $passed++;
        } else {
            echo "FAILED ✗\n";
            $failed++;
        }

        // Test 2: Scam Scoring Engine
        echo "[TEST 2] ScamScoringEngine Cyber Fraud Risk Evaluation... ";
        $scam = new ScamScoringEngine();
        $res2 = $scam->evaluate("URGENT: Power will be disconnected tonight at 9:30 PM due to unpaid bill. Click http://bit.ly/update-kyc to verify.");
        if ($res2['risk_score'] >= 75 && str_contains($res2['category'], 'Fraud')) {
            echo "PASSED ✓\n";
            $passed++;
        } else {
            echo "FAILED ✗\n";
            $failed++;
        }

        // Test 3: Bill OCR Engine
        echo "[TEST 3] BillOcrEngine Utility Document Parsing... ";
        $bill = new BillOcrEngine();
        $res3 = $bill->parse('sample_bill.pdf', 'Electricity');
        if (isset($res3['total_amount']) && $res3['total_amount'] > 0 && isset($res3['trend_data'])) {
            echo "PASSED ✓\n";
            $passed++;
        } else {
            echo "FAILED ✗\n";
            $failed++;
        }

        // Test 4: Scheme Matcher Engine
        echo "[TEST 4] SchemeMatcherEngine Demographic Eligibility Matching... ";
        $matcher = new SchemeMatcherEngine();
        $res4 = $matcher->findEligibleSchemes(['age' => 35, 'income' => 120000, 'occupation' => 'Farmer']);
        if (count($res4) > 0 && isset($res4[0]['match_percentage'])) {
            echo "PASSED ✓\n";
            $passed++;
        } else {
            echo "FAILED ✗\n";
            $failed++;
        }

        echo "\n-----------------------------------------------------\n";
        echo "RESULTS: Passed: {$passed} | Failed: {$failed}\n";
        echo "-----------------------------------------------------\n";
    }
}
