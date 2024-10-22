<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Prime;
$start  = microtime(true);
$prime  = new Prime();
$min    = $argv[1] ?? 1234567890;
$num    = $argv[2] ?? 30;
$across = 5;
$result = $prime->generate($min, $num);
foreach ($result as $num) {
    // display 6 across
    for ($x = 0; $x < $across; $x++) {
        if (!$result->valid()) break;
        echo number_format($num);
        echo "\t";
    }
    echo PHP_EOL;
}
echo 'Usage        : php ch01_prime [min value] [num primes]' . PHP_EOL;
echo 'Elapsed Time : ' . (microtime(true) - $start) . PHP_EOL;
