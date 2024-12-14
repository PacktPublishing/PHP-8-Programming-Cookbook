<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\{PostCode, Connect};
$config  = require __DIR__ . '/../../config/db.config.php';
$city    = trim(strip_tags($_GET['city'] ?? $argv[1] ?? ''));
if (empty($city)) {
    echo 'USAGE: php ' . basename(__FILE__) . ' CITY' . PHP_EOL;
    exit;
}

echo 'Normal Object Storage **********************************' . PHP_EOL;
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findCity($city);
foreach ($obj as $key => $item) {
    echo implode(':', $item->row) . PHP_EOL;
}
echo 'Unset original object:' . PHP_EOL;
unset($post);
echo 'NOTICE: objects still exist in storage:' . PHP_EOL;
reset($obj);
foreach ($obj as $key => $item) {
    echo implode(':', $item->row) . PHP_EOL;
}

$usageNormal = memory_get_peak_usage();
memory_reset_peak_usage();

echo 'Weak Reference Object Storage ****************************' . PHP_EOL;
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findCityWeakMap($city);
foreach ($obj as $item => $value) {
    echo implode(':', $item->row) . PHP_EOL;
}
echo 'Unset original object:' . PHP_EOL;
unset($post);
echo 'NOTICE: only objects still in play exist in storage:' . PHP_EOL;
reset($obj);
foreach ($obj as $item => $value) {
    echo implode(':', $item->row) . PHP_EOL;
}

$usageWeak = memory_get_peak_usage();
echo 'Normal Memory Usage   : ' . $usageNormal . PHP_EOL;
echo 'Weak Ref Memory Usage : ' . $usageWeak . PHP_EOL;
