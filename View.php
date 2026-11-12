<?php

namespace CivicMind\Core;

class View {
    public static function render(string $viewPath, array $data = [], string $layout = 'header'): void {
        extract($data);
        
        $baseDir = __DIR__ . '/../../views/';
        $viewFile = $baseDir . ltrim($viewPath, '/') . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "<h1>View Error</h1><p>View template <code>{$viewPath}</code> not found.</p>";
            exit;
        }

        // Make user and flash available globally in views
        $currentUser = Session::get('user');
        $csrfToken = Session::csrfToken();

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            require $baseDir . 'layouts/header.php';
            require $baseDir . 'layouts/nav.php';
            echo $content;
            require $baseDir . 'layouts/footer.php';
        } else {
            echo $content;
        }
    }
}
