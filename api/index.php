<?php
// api/index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Clean up URI
$path = ltrim($uri, '/');

// Target file location
$filePath = __DIR__ . '/../' . $path;

// 1. If hitting a .php endpoint (like admin_login.php)
if (str_ends_with($uri, '.php') || file_exists($filePath . '.php')) {
    $target = str_ends_with($uri, '.php') ? $filePath : $filePath . '.php';
    if (file_exists($target)) {
        require $target;
        exit;
    }
}

// 2. Serve static assets (CSS, JS, Images, Favicon)
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

// 3. Fallback for root / or missing routes
if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/../admin/index.html')) {
        require __DIR__ . '/../admin/index.html';
        exit;
    }
}

http_response_code(404);
echo json_encode(["error" => "Endpoint not found: " . $uri]);