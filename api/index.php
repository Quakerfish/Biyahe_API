<?php
// api/index.php

// Get the requested URL path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalize path
$file = __DIR__ . '/..' . $uri;

// If targeting a specific root file (e.g., /admin_login.php), require it directly
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    require $file;
    exit;
}

// Default response or fall back to dashboard/index
if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/../dashboard.php')) {
        require __DIR__ . '/../dashboard.php';
        exit;
    }
}

// 404 handler
http_response_code(404);
echo json_encode(["error" => "Endpoint not found: " . $uri]);