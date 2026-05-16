<?php
require __DIR__ . '/../../vendor/autoload.php';

use Cookbook\Iterator\Database\{PostCode};
use Cookbook\Iterator\Database\Connect;

$config = require __DIR__ . '/../../config/db.config.php';
$post   = new PostCode(Connect::getConnection($config['ch02']));
$city   = trim(strip_tags($_GET['city'] ?? $argv[1] ?? ''));
if (empty($city)) {
    echo 'USAGE: php ' . basename(__FILE__) . ' CITY' . PHP_EOL;
    exit;
}
$storage = $post->findCity($city);
foreach ($storage as $obj) {
    var_dump($obj);
}
