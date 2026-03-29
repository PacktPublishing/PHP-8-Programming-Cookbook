<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json');
header('Cache-Control: no-cache');
if (!file_exists(USERS_FILE)) {
    echo '[]';
    exit;
}
echo file_get_contents(USERS_FILE);
