<?php
// api/index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/..' . $uri;

// 1. If requesting a specific .php file directly (e.g. /admin_login.php)
if (str_ends_with($uri, '.php')) {
    if (file_exists($filePath)) {
        require $filePath;
        exit;
    }
}

// 2. Serve static assets (CSS, JS, Images) if requested through the router
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $mime = mime_content_type($filePath);
    if (str_ends_with($filePath, '.css')) $mime = 'text/css';
    if (str_ends_with($filePath, '.js')) $mime = 'application/javascript';
    
    header("Content-Type: $mime");
    readfile($filePath);
    exit;
}

// 3. Fallback for root / or missing routes
if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/../admin/index.html')) {
        require __DIR__ . '/../admin/index.html';
        exit;
    }
}

http_response_code(404);
echo json_encode(["error" => "Endpoint not found: " . $uri]);