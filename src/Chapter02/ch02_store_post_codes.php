<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\PostCode;
use Cookbook\Database\PostCodeTable;

// ──────────────────────────────────────────────────────────────
// Validate command line arguments
// ──────────────────────────────────────────────────────────────

if ($argc < 2) {
    fprintf(STDERR, "Usage: %s <city_name>\n", $argv[0]);
    fprintf(STDERR, "Example: %s \"Beverly Hills\"\n", $argv[0]);
    exit(1);
}

$cityName = $argv[1];

// ──────────────────────────────────────────────────────────────
// Load configuration and establish database connection
// ──────────────────────────────────────────────────────────────

$config = require __DIR__ . '/../../config/db.config.php';
try {
    $dsn = sprintf(
        '%s:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['db_driver'],
        $config['db_host'],
        $config['db_port'] ?? 3306,
        $config['db_name'],
    );
    $pdo = new PDO($dsn, $config['db_usr'], $config['db_pwd'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    fprintf(STDERR, "Database connection failed: %s\n", $e->getMessage());
    exit(1);
}

$table = new PostCodeTable($pdo);
$results = $table->findByCity($cityName);
$storage = [];
foreach ($results as $obj) {
    echo $obj->postalCode . ' ';
    $storage[] = $obj;
}
echo PHP_EOL;
echo 'Number Found: ' . $results->getReturn() . PHP_EOL;
