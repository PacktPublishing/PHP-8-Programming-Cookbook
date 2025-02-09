<?php
require __DIR__ . '/ch03_using_arrayiterator_func_lib.php';
$city = trim(strip_tags($_GET['city'] ?? $argv[1] ?? 'Potsdam'));
if (empty($city)) exit( 'You need to supply a city name');
$fn = __DIR__ . '/../../data/US.txt';
$city_list = \Library\fetchPostCodesByCity($city, $fn);
echo '<table border=1>' . PHP_EOL
     . \Library\htmlListUsingDoWhile($city_list)
     . '</table>' . PHP_EOL;

// sample output:
/*
US	13676	Potsdam	New York	NY	St. Lawrence	089		44.6592	-74.9681	4
US	13699	Potsdam	New York	NY	St. Lawrence	089		44.6698	-74.9813	4
US	45361	Potsdam	Ohio	OH	Miami	109			39.9635-84.4145	4
*/
