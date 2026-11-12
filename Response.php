<?php

namespace CivicMind\Core;

class Response {
    public static function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }

    public static function error(string $message, int $statusCode = 400): void {
        self::json(['success' => false, 'error' => $message], $statusCode);
    }

    public static function success(mixed $data = [], string $message = 'Success'): void {
        self::json(['success' => true, 'message' => $message, 'data' => $data]);
    }
}
