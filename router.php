<?php

// CLI router for PHP built-in web server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // serve requested resource as static file directly
}

require_once __DIR__ . '/index.php';
