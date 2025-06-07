<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\Ipsum;
$container = Container::getInstance();
$start = microtime(TRUE);
$source = [
    'war_peace' => __DIR__ . '/../../data/war_and_peace.txt',
    'shakespeare' => __DIR__ . '/../../data/shakespeare.txt'
];
foreach ($source as $key => $fn) {
    $container->add($key, new Ipsum($fn));
}
echo $container->get('shakespeare')(5);
echo 'Elapsed Time     : ' . (microtime(TRUE) - $start) . PHP_EOL;
echo 'Peak Memory Usage: ' . memory_get_peak_usage() . PHP_EOL;
