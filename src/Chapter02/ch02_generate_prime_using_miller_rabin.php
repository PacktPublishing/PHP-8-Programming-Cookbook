<?php
// Usaage : PHP __FILE__ [SIZE] [NUM]
//          SIZE = minimum number of digits
//          NUM  = number of primes to generate

include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Prime;

// init vars
$prime = new Prime();

// get inputs (if any)
$size = (int) ($argv[1] ?? $_GET['size'] ?? 64);
$num  = (int) ($argv[2] ?? $_GET['num'] ?? 10_000);

// generate primes
$start = microtime(TRUE);
$gen   = $prime->generate($size, $num);
echo 'Prime Number Candidates:' . PHP_EOL;
foreach ($gen as $candidate) {
    echo $candidate . ' | ';
}
echo PHP_EOL;
echo 'Time to generate: ' . (microtime(TRUE) - $start) . PHP_EOL;
