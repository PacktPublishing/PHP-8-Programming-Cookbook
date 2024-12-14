<?php
require __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Database\{PostCode, Connect};
$config = require __DIR__ . '/../../config/db.config.php';
$city   = trim(strip_tags($_GET['city'] ?? $argv[1] ?? ''));
if (empty($city)) {
    echo 'USAGE: php ' . basename(__FILE__) . ' CITY' . PHP_EOL;
    exit;
}

echo 'Normal Object Storage' . PHP_EOL;
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findOneCity($city);
$storage = new SplObjectStorage();
$storage->attach($obj);
$row = (array) $storage?->current()->row;
echo 'To Notice: object initially appears in storage:' . PHP_EOL;
echo 'City: ' . implode(':', $row) . PHP_EOL;
unset($obj, $post);
echo 'To Notice: even though unset, object still exists in storage:' . PHP_EOL;
$row = (array) $storage?->current()->row;
echo 'City: ' . implode(':', $row) . PHP_EOL;
var_dump($obj);
$usageNormal = memory_get_peak_usage();

memory_reset_peak_usage();
echo 'Weak Reference Object Storage' . PHP_EOL;
$post = new PostCode(Connect::getConnection($config['ch02']));
$obj  = $post->findOneCity($city);
$storage = new SplObjectStorage();
$storage->attach(WeakReference::create($obj));
$row = (array) $storage?->current()->get()->row;
echo 'To Notice: object initially appears in storage:' . PHP_EOL;
echo 'City: ' . implode(':', $row) . PHP_EOL;
unset($obj, $post);
echo 'To Notice: when unset, object disappears from storage:' . PHP_EOL;
$row = (array) $storage?->current()->get()->row;
echo 'City: ' . implode(':', $row) . PHP_EOL;
var_dump($obj);
$usageWeak = memory_get_peak_usage();
echo 'Normal Memory Usage   : ' . $usageNormal . PHP_EOL;
echo 'Weak Ref Memory Usage : ' . $usageWeak . PHP_EOL;
