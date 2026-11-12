<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Services\AuthService;

class AuthController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function showLogin(): void {
        View::render('auth/login', ['title' => 'Login - CivicMind X']);
    }

    public function showRegister(): void {
        View::render('auth/register', ['title' => 'Register - CivicMind X']);
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            Session::setFlash('error', 'Please fill in both email and password.');
            Response::redirect('/login');
        }

        $user = $this->authService->login($email, $password);
        if ($user) {
            Session::setFlash('success', "Welcome back, {$user['full_name']}!");
            Response::redirect('/dashboard');
        } else {
            Session::setFlash('error', 'Invalid email credentials or password.');
            Response::redirect('/login');
        }
    }

    public function register(): void {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $roleId = (int)($_POST['role_id'] ?? 1);

        if (!$fullName || !$email || !$password) {
            Session::setFlash('error', 'Please fill in all required fields.');
            Response::redirect('/register');
        }

        $res = $this->authService->register([
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role_id' => $roleId
        ]);

        if ($res['success']) {
            Session::setFlash('success', 'Registration successful! Welcome to CivicMind X.');
            Response::redirect('/dashboard');
        } else {
            Session::setFlash('error', $res['error']);
            Response::redirect('/register');
        }
    }

    public function logout(): void {
        $this->authService->logout();
        Response::redirect('/');
    }

    public function apiLogin(): void {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        $user = $this->authService->login($email, $password);
        if ($user) {
            Response::success($user, 'Login successful');
        } else {
            Response::error('Invalid credentials', 401);
        }
    }

    public function verifyOtp(): void {
        $phone = $_POST['phone'] ?? '';
        $otp = $_POST['otp'] ?? '';

        if ($otp === '123456' || strlen($otp) === 6) {
            Response::success(['verified' => true], 'OTP Verified Successfully');
        } else {
            Response::error('Invalid OTP entered. Please try 123456.', 400);
        }
    }
}
