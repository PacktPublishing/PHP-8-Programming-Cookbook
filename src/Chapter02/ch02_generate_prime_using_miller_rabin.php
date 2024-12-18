<?php
// Usaage : PHP __FILE__ [SIZE] [NUM]
//          SIZE = minimum number of digits
//          NUM  = number of primes to generate

include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Prime;

// init vars
$prime = new Prime();

// get inputs (if any)
$size = (int) ($argv[1] ?? $_GET['size'] ?? 32);
$num  = (int) ($argv[2] ?? $_GET['num'] ?? 1000);

// generate random starting number according to $size
$min   = '';
for ($x = 0; $x < $size; $x++) {
    $min .= (string) random_int(0, 9);
}

// generate primes
$start = microtime(TRUE);
$gen   = [];
$next = $prime->generate($min, $num);
echo 'Prime Number Candidates:' . PHP_EOL;
foreach ($next as $candidate) {
    echo $candidate . ' | ';
}
echo PHP_EOL;
echo 'Starting value: ' . $min . PHP_EOL;
echo 'Time to generate: ' . (microtime(TRUE) - $start) . PHP_EOL;
