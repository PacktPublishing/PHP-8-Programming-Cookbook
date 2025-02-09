<?php
require __DIR__ . '/ch03_array_search_func_lib.php';
// build postcode => city array
$cities = \Library\buildCityArray();
foreach ($arr as $postcode => $city) echo $postcode . "\t" . $city . PHP_EOL;

// build postcode => [country, postcode, city, etc.]
$multi = \Library\buildMultiArray();
foreach ($arr as $row) echo implode("\t", $row) . PHP_EOL;

