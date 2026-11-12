<?php

namespace CivicMind\Core;

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middleware = []): void {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable|array $handler, array $middleware = []): void {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, callable|array $handler, array $middleware = []): void {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, callable|array $handler, array $middleware): void {
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $regex = "#^" . $regex . "$#";
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'regex' => $regex,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): void {
        $parsedUrl = parse_url($uri, PHP_URL_PATH);
        $cleanUri = rtrim($parsedUrl, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['regex'], $cleanUri, $matches)) {
                
                // Execute Middleware
                foreach ($route['middleware'] as $mw) {
                    if (is_callable($mw)) {
                        call_user_func($mw);
                    } elseif (class_exists($mw)) {
                        (new $mw())->handle();
                    }
                }

                // Extract URL parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Call Handler
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    call_user_func_array($handler, $params);
                    return;
                }

                if (is_array($handler) && count($handler) === 2) {
                    [$controllerClass, $methodName] = $handler;
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $methodName)) {
                            call_user_func_array([$controller, $methodName], $params);
                            return;
                        }
                    }
                }
            }
        }

        // 404 Handler
        http_response_code(404);
        if (str_starts_with($cleanUri, '/api/')) {
            Response::error('Endpoint not found', 404);
        } else {
            View::render('404', ['title' => 'Page Not Found']);
        }
    }
}
