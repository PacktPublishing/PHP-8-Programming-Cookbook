<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
use Cookbook\Services\{Cache,Ipsum,IpsumWithCache};
$source = [
    'war_peace' => __DIR__ . '/../../data/war_and_peace.txt',
    'shakespeare' => __DIR__ . '/../../data/shakespeare.txt'
];
unlink(Cache::CACHE_FN);

echo 'First Run:' . PHP_EOL;
$start = microtime(TRUE);
$container = Container::getInstance();
foreach ($source as $key => $fn) {
    $container->add($key, new IpsumWithCache($fn));
}
echo $container->get('shakespeare')();
$first = microtime(TRUE) - $start;

echo 'Second Run:' . PHP_EOL;
$start = microtime(TRUE);
$container = Container::getInstance();
foreach ($source as $key => $fn) {
    $container->add($key, new IpsumWithCache($fn));
}
echo $container->get('shakespeare')();
$last = microtime(TRUE) - $start;

echo "Elapsed Time 1st Run: $first\n";
echo "Elapsed Time 2nd Run: $last\n";
