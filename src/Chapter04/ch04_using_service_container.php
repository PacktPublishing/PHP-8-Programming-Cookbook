<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Container;
$container = Container::getInstance();

$container->add('add', function ($a, $b) { return $a + $b; });
$container->add('sub', function ($a, $b) { return $a - $b; });
$container->add('mul', function ($a, $b) { return $a * $b; });
$container->add('div', function ($a, $b) { return ($b === 0) ? 0 : $a / $b; });

$a = 222;
$b = 111;
echo 'Add: ' . $container->get('add')($a, $b) . PHP_EOL;
echo 'Sub: ' . $container->get('sub')($a, $b) . PHP_EOL;
echo 'Mul: ' . $container->get('mul')($a, $b) . PHP_EOL;
echo 'Div: ' . $container->get('div')($a, $b) . PHP_EOL;

// output:
/*
Add: 333
Sub: 111
Mul: 24642
Div: 2
*/

