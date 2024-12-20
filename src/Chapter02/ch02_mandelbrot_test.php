<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Services\Mandelbrot;
$output = (new Mandelbrot())->generate();
if (empty($_SERVER['HTTP_HOST'])) {
    echo $output;
} else {
    echo '<pre>' . $output . '</pre>';
}

// w/out OPCache: [3.410, 3.306, 3.277] / 3 = 3.331
// with OPcache: [3.392, 3.310, 3.341]) / 3 = 3.348
