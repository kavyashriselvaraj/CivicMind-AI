<?php

namespace CivicMind\Services;

class BillOcrEngine {
    public function parse(string $filePath, string $billType = 'Electricity'): array {
        // Simulated high-precision OCR extraction engine
        $provider = match (strtolower($billType)) {
            'electricity', 'electric' => 'TANGEDCO / State Electricity Board',
            'water' => 'Metro Water & Sewerage Board',
            'internet', 'broadband' => 'Airtel Broadband / JioFiber',
            default => 'City Utility Provider',
        };

        // Standard parsed bill details
        $billingDate = date('Y-m-d', strtotime('-15 days'));
        $dueDate = date('Y-m-d', strtotime('+10 days'));
        $unitsConsumed = rand(220, 680);
        $ratePerUnit = 6.50;
        $totalAmount = round($unitsConsumed * $ratePerUnit, 2);

        // Historical comparison to detect anomalies (>35% spike compared to average 300 units)
        $historicalAverageUnits = 320;
        $spikePercentage = round((($unitsConsumed - $historicalAverageUnits) / $historicalAverageUnits) * 100, 1);
        $isAnomaly = $spikePercentage > 35;

        $anomalyExplanation = $isAnomaly
            ? "ANOMALY ALERT: Your consumption of {$unitsConsumed} units is {$spikePercentage}% higher than your 6-month historical average ({$historicalAverageUnits} units). Check for faulty appliances or meter leakage."
            : "NORMAL CONSUMPTION: Your utility consumption is within expected parameters.";

        $trendData = [
            ['month' => 'May', 'amount' => round(290 * $ratePerUnit, 2)],
            ['month' => 'Jun', 'amount' => round(310 * $ratePerUnit, 2)],
            ['month' => 'Jul', 'amount' => round(305 * $ratePerUnit, 2)],
            ['month' => 'Aug', 'amount' => round(330 * $ratePerUnit, 2)],
            ['month' => 'Sep', 'amount' => round(320 * $ratePerUnit, 2)],
            ['month' => 'Oct (Current)', 'amount' => $totalAmount],
        ];

        return [
            'provider_name' => $provider,
            'billing_date' => $billingDate,
            'due_date' => $dueDate,
            'total_amount' => $totalAmount,
            'units_consumed' => $unitsConsumed,
            'historical_avg_units' => $historicalAverageUnits,
            'spike_percentage' => $spikePercentage,
            'usage_anomaly' => $isAnomaly,
            'anomaly_explanation' => $anomalyExplanation,
            'trend_data' => $trendData,
            'breakdown' => [
                'base_charges' => round($totalAmount * 0.70, 2),
                'tax_and_cess' => round($totalAmount * 0.18, 2),
                'fixed_meter_rent' => round($totalAmount * 0.12, 2),
            ],
            'savings_tips' => [
                "Switch to 5-star rated inverter appliances to cut energy use by up to 25%.",
                "Ensure routine AC filter cleaning every 30 days.",
                "Opt for automated solar grid integration subsidies under PM Surya Ghar scheme.",
            ]
        ];
    }
}
