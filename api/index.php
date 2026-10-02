<?php
// api/index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($uri, '/');
$filePath = __DIR__ . '/../' . $path;

// 1. If hitting a .php endpoint (e.g. /admin_signup.php or /admin_login.php)
if (str_ends_with($uri, '.php')) {
    if (file_exists($filePath)) {
        require $filePath;
        exit;
    }
}

// 2. Serve static assets (CSS, JS, Images)
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'ico'  => 'image/x-icon',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'svg'  => 'image/svg+xml'
    ];
    
    $contentType = $mimes[$ext] ?? mime_content_type($filePath);
    header("Content-Type: $contentType");
    readfile($filePath);
    exit;
}

// 3. Fallback for root route
if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/../admin/index.html')) {
        require __DIR__ . '/../admin/index.html';
        exit;
    }
}

http_response_code(404);
echo json_encode(["error" => "Endpoint not found: " . $uri]);