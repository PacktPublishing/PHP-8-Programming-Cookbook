<?php
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json');
header('Cache-Control: no-cache');
echo (file_exists(USERS_FILE)) 
    ? file_get_contents(USERS_FILE)
    : '[]';
exit;
