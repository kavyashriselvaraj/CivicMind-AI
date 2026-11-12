<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Database;
use PDO;

class DocumentController {
    public function index(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM documents ORDER BY title ASC");
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('documents/index', [
            'title' => 'Document Assistant & Civic Procedures - CivicMind X',
            'documents' => $documents,
        ]);
    }
}
