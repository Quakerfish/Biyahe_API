<?php
// config.php
header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';

// Load .env locally, skip safely on Vercel
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Fetch variables safely from $_ENV, $_SERVER, or getenv()
$host     = $_ENV['DB_HOST']     ?? $_SERVER['DB_HOST']     ?? getenv('DB_HOST');
$port     = $_ENV['DB_PORT']     ?? $_SERVER['DB_PORT']     ?? getenv('DB_PORT');
$dbname   = $_ENV['DB_NAME']     ?? $_SERVER['DB_NAME']     ?? getenv('DB_NAME');
$user     = $_ENV['DB_USER']     ?? $_SERVER['DB_USER']     ?? getenv('DB_USER');
$password = $_ENV['DB_PASS']     ?? $_SERVER['DB_PASS']     ?? getenv('DB_PASS');

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

function sanitize_string(?string $input): string {
    if ($input === null) return '';
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}