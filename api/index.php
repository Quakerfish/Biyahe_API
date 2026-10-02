<?php
// api/index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/..' . $uri;

// If it's a real file (CSS, JS, images), serve it directly
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $mime = mime_content_type($filePath);
    if (str_ends_with($filePath, '.css')) $mime = 'text/css';
    if (str_ends_with($filePath, '.js')) $mime = 'application/javascript';
    
    header("Content-Type: $mime");
    readfile($filePath);
    exit;
}

// Route PHP requests
if (file_exists($filePath) && str_ends_with($filePath, '.php')) {
    require $filePath;
    exit;
}

// Fallback / default route
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/../admin/index.html'; // adjust to your actual login/index page
    exit;
}

http_response_code(404);
echo json_encode(["error" => "Endpoint not found: " . $uri]);