<?php

namespace CivicMind\Middleware;

use CivicMind\Core\Session;
use CivicMind\Core\Response;

class RbacMiddleware {
    private array $allowedRoles;

    public function __construct(array $allowedRoles = [5]) { // Default Admin role_id 5
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(): void {
        Session::start();
        $user = Session::get('user');
        if (!$user || !in_array((int)$user['role_id'], $this->allowedRoles, true)) {
            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                Response::error('Forbidden. Insufficient permissions.', 403);
            } else {
                Session::setFlash('error', 'Access denied. You do not have permission.');
                Response::redirect('/dashboard');
            }
        }
    }
}
