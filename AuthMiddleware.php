<?php

namespace CivicMind\Middleware;

use CivicMind\Core\Session;
use CivicMind\Core\Response;

class AuthMiddleware {
    public function handle(): void {
        Session::start();
        $user = Session::get('user');

        if (!$user) {
            // Check Authorization Header for JWT
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7);
                $decoded = self::verifyJwt($token);
                if ($decoded) {
                    Session::set('user', $decoded);
                    return;
                }
            }

            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                Response::error('Unauthorized access. Please login.', 401);
            } else {
                Session::setFlash('error', 'Please login to access this page.');
                Response::redirect('/login');
            }
        }
    }

    public static function verifyJwt(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        [$header64, $payload64, $sig64] = $parts;
        $config = require __DIR__ . '/../../config/app.php';
        $signature = hash_hmac('sha256', "$header64.$payload64", $config['jwt_secret'], true);
        $expectedSig = base64_encode($signature);
        if (hash_equals($sig64, $expectedSig)) {
            $payload = json_decode(base64_decode($payload64), true);
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return null;
            }
            return $payload['user'] ?? null;
        }
        return null;
    }
}
