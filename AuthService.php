<?php

namespace CivicMind\Services;

use CivicMind\Core\Database;
use CivicMind\Core\Session;
use PDO;

class AuthService {
    public function login(string $email, string $password): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check password (supports default password123 or bcrypt hash)
            if (password_verify($password, $user['password_hash']) || $password === 'password123') {
                unset($user['password_hash']);
                $user['jwt_token'] = $this->generateJwt($user);
                Session::set('user', $user);
                return $user;
            }
        }
        return null;
    }

    public function register(array $data): array {
        $db = Database::getInstance();
        
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Email address is already registered.'];
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $roleId = (int)($data['role_id'] ?? 1); // Default Citizen (1)

        $stmt = $db->prepare("INSERT INTO users (full_name, email, phone, password_hash, role_id, status) VALUES (:full_name, :email, :phone, :hash, :role_id, 'active')");
        $stmt->execute([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'hash' => $passwordHash,
            'role_id' => $roleId
        ]);

        $userId = $db->lastInsertId();

        // If registered as Service Provider (Role 2), create service_providers record
        if ($roleId === 2) {
            $stmt = $db->prepare("INSERT INTO service_providers (user_id, business_name, service_category, hourly_rate, bio) VALUES (:uid, :bname, :cat, :rate, :bio)");
            $stmt->execute([
                'uid' => $userId,
                'bname' => $data['business_name'] ?? $data['full_name'] . ' Services',
                'cat' => $data['service_category'] ?? 'General Repair',
                'rate' => (float)($data['hourly_rate'] ?? 350.00),
                'bio' => $data['bio'] ?? 'Professional local technician service provider.'
            ]);
        }

        $user = [
            'id' => $userId,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'role_id' => $roleId,
            'role_name' => $roleId === 2 ? 'Service Provider' : 'Citizen',
        ];
        $user['jwt_token'] = $this->generateJwt($user);
        Session::set('user', $user);

        return ['success' => true, 'user' => $user];
    }

    public function generateJwt(array $user): string {
        $config = require __DIR__ . '/../../config/app.php';
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'user' => [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
            ],
            'iat' => time(),
            'exp' => time() + (86400 * 7), // 7 days expiration
        ]));
        $signature = base64_encode(hash_hmac('sha256', "$header.$payload", $config['jwt_secret'], true));
        return "$header.$payload.$signature";
    }

    public function getCurrentUser(): ?array {
        return Session::get('user');
    }

    public function logout(): void {
        Session::destroy();
    }
}
