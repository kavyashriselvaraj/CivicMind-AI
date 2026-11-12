<?php

namespace CivicMind\Middleware;

use CivicMind\Core\Session;
use CivicMind\Core\Response;

class CsrfMiddleware {
    public function handle(): void {
        Session::start();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Session::verifyCsrfToken($token)) {
                Response::error('Invalid or missing CSRF security token.', 403);
            }
        }
    }
}
