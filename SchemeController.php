<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Services\SchemeMatcherEngine;
use CivicMind\Core\Database;
use PDO;

class SchemeController {
    private SchemeMatcherEngine $matcher;

    public function __construct() {
        $this->matcher = new SchemeMatcherEngine();
    }

    public function index(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM schemes ORDER BY title ASC");
        $schemes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('schemes/index', [
            'title' => 'Government Scheme Discovery - CivicMind X',
            'schemes' => $schemes,
        ]);
    }

    public function match(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $criteria = [
            'age' => $input['age'] ?? null,
            'income' => $input['income'] ?? null,
            'occupation' => $input['occupation'] ?? '',
            'state' => $input['state'] ?? '',
        ];

        $results = $this->matcher->findEligibleSchemes($criteria);
        Response::success($results, 'Scheme eligibility matching complete');
    }
}
