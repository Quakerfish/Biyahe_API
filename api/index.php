<?php
// api/index.php
// Forward all requests to root PHP files
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri !== '/' && file_exists(__DIR__ . '/..' . $uri)) {
    require __DIR__ . '/..' . $uri;
} else {
    echo "BIYAHE API is Running!";
}