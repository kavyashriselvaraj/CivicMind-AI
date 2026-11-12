<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use CivicMind\Services\AiNlpEngine;
use PDO;

class AiController {
    private AiNlpEngine $nlpEngine;

    public function __construct() {
        $this->nlpEngine = new AiNlpEngine();
    }

    public function index(): void {
        View::render('ai_solver/index', ['title' => 'AI Civic Problem Solver - CivicMind X']);
    }

    public function analyze(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $userPrompt = trim($input['prompt'] ?? '');
        $location = trim($input['location'] ?? '');

        if (!$userPrompt) {
            Response::error('Please enter your civic problem description.', 400);
        }

        $analysis = $this->nlpEngine->analyzeInput($userPrompt, $location);

        // Store AI Conversation Log in Database
        $user = Session::get('user');
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO ai_conversations (user_id, prompt, response, intent) VALUES (:uid, :p, :r, :i)");
        $stmt->execute([
            'uid' => $user ? $user['id'] : null,
            'p' => $userPrompt,
            'r' => json_encode($analysis, JSON_UNESCAPED_UNICODE),
            'i' => $analysis['intent'],
        ]);

        Response::success($analysis, 'AI Analysis completed successfully');
    }
}
