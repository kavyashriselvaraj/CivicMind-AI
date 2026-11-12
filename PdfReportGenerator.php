<?php

namespace CivicMind\Services;

class PdfReportGenerator {
    public function generateComplaintPdf(array $complaint): string {
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>CivicMind X - Complaint Report #{$complaint['complaint_number']}</title>
            <style>
                body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #1e293b; padding: 40px; }
                .header { border-bottom: 2px solid #3b82f6; padding-bottom: 15px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
                .logo { font-size: 24px; font-weight: bold; color: #2563eb; }
                .badge { background: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: bold; }
                .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
                .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
                .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; }
                .val { font-size: 16px; color: #0f172a; margin-top: 4px; font-weight: 600; }
                .footer { font-size: 12px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 40px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='logo'>CivicMind X</div>
                <div class='badge'>Status: " . htmlspecialchars($complaint['status'] ?? 'Submitted') . "</div>
            </div>
            <h2>Official Civic Complaint Summary</h2>
            <p>Generated on " . date('d M Y, h:i A') . "</p>
            
            <div class='grid'>
                <div class='card'><div class='label'>Complaint Number</div><div class='val'>" . htmlspecialchars($complaint['complaint_number'] ?? 'N/A') . "</div></div>
                <div class='card'><div class='label'>Category</div><div class='val'>" . htmlspecialchars($complaint['category'] ?? 'General') . "</div></div>
                <div class='card'><div class='label'>Severity Level</div><div class='val'>" . htmlspecialchars($complaint['severity'] ?? '3') . " / 5</div></div>
                <div class='card'><div class='label'>Department</div><div class='val'>" . htmlspecialchars($complaint['department_name'] ?? 'Municipal Board') . "</div></div>
            </div>

            <div class='card' style='margin-bottom: 20px;'>
                <div class='label'>Issue Title</div>
                <div class='val'>" . htmlspecialchars($complaint['title'] ?? 'Civic Report') . "</div>
            </div>

            <div class='card'>
                <div class='label'>Full Description</div>
                <p style='margin-top: 8px; line-height: 1.6;'>" . nl2br(htmlspecialchars($complaint['description'] ?? '')) . "</p>
            </div>

            <div class='footer'>
                CivicMind X AI Digital Life Operating System &bull; Certified Municipal Document &bull; Verification Hash: " . md5(json_encode($complaint)) . "
            </div>
        </body>
        </html>
        ";

        return $html;
    }
}
