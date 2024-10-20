<?php
// Usaage : PHP __FILE__ [BITS] [NUM] [ALGO]
//          BITS = bit size
//          NUM  = number of primes to generate
//          ALGO = algorithm to use

include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Chapter02\Prime\Generate;

// init vars
$generate = new Generate();

// get inputs (if any)
$bits = $argv[1] ?? $_GET['bits'] ?? 64;
$num  = $argv[2] ?? $_GET['num'] ?? 100;
$algo = $argv[3] ?? $_GET['algo'] ?? 'simple';

// generate primes
$start = microtime(TRUE);
$min   = 2**$bits;
$gen   = [];
do {
	$next = $generate->getNextPrime($min, $gen, $algo);
	echo 'Next: ' . number_format($next, 0) . PHP_EOL;
} while ($num-- > 0);
echo 'Time to generate: ' . (microtime(TRUE) - $start) . PHP_EOL;
